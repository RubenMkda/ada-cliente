<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
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
        $orderId = $request->input('order_id'); 

        return Inertia::location($request->user()->checkout([$stripePriceId => $quantity], [
            'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
            'metadata' => [
                'order_id' => $orderId, 
            ]
        ])->url);
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
            return redirect()->route('checkout-cancel');
        }
    
        $session = Cashier::stripe()->checkout->sessions->retrieve($sessionId);
        
        if ($session->payment_status !== 'paid') {
            return redirect()->route('checkout-cancel');
        }
    
        // Obtener el order_id del metadata de la sesión de Stripe
        $orderId = $session->metadata->order_id ?? null;
        
        if ($orderId) {
            $order = Order::find($orderId);
            
            if ($order) {
                $transaction = Transaction::create([
                    'order_id' => $order->id,
                    'amount' => $session->amount_total / 100, 
                    'type' => 'deposit', 
                    'status' => 'completed',
                    'payment_method' => $session->payment_method_types[0] ?? 'card',
                    'transaction_date' => now(),
                ]);
                $order->update(['status' => 'completado']);
            }
        }
    
        return Inertia::render('checkout/Success', [
            'checkoutSession' => $session,
        ]);
    }

    public function cancel()
    {
        return Inertia::render('checkout/Cancel');
    }
}
