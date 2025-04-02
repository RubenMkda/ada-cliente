<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class CheckoutVehicleController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['photos', 'make', 'model']);

        $vehicleMake = $vehicle->make->name;
        $vehicleModel = $vehicle->model->name;

        return Inertia::render('checkout/Index', [
            'vehicle' => $vehicle,
            'vehicleMake' => $vehicleMake,
            'vehicleModel' => $vehicleModel,
        ]);
    }

    public function createOrder(Request $request, Vehicle $vehicle)
    {
        $order = Order::create([
            'user_id' => $request->user()->id,
            'vehicle_id' => $vehicle->id,
            'total_amount' => $vehicle->price,
            'status' => 'pendiente',
        ]);

        $vehicle->load(['photos', 'make', 'model']);

        return redirect()->route('vehicles.checkout.completePayment', ['order' => $order->id]);
    }

    public function checkout(Request $request, Vehicle $vehicle)
    {
        $stripePriceId = $vehicle->stripe_price_id;
        $quantity = 1;

        return Inertia::location($request->user()->checkout([$stripePriceId => $quantity], [
            'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
        ])->url);
    }

    public function sucess(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId === null) {
            return;
        }
    }

    public function completePayment(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'No tienes permiso para acceder a esta orden.');
        }

        $order->load(['vehicle.photos', 'vehicle.make', 'vehicle.model']);

        return Inertia::render('checkout/CompletePayment', [
            'order' => $order,
            'vehicle' => $order->vehicle,
        ]);
    }

    public function webhook()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_KEY');

        $payload = @file_get_contents('php://input');

        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret,
            );
        } catch (UnexpectedValueException $e) {
            return response('', 400);
        } catch (SignatureVerificationException $e) {
            return response('', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed';
                $session = $event->data->object;

            default:
                echo 'Received unknown event type ' . $event->type;
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
 
        if ($sessionId === null) {
            return;
        }

        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);

        
        if ($session->payment_status !== 'paid') {
            return;
        }

        return Inertia::render('Checkout/Success', [
            'checkoutSession' => $session,
        ]);

        return view('checkout.success');
    }

    public function cancel()
    {
        return Inertia::render('Checkout/Cancel');
    }
}
