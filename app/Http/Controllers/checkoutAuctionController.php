<?php

namespace App\Http\Controllers;

use App\Models\AuctionRequest;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;

class CheckoutAuctionController extends Controller
{
    public function show(AuctionRequest $auctionRequest, Request $request)
    {
        if ($auctionRequest->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('checkout/auction/Show', [
            'vehicle' => [
                'id' => $auctionRequest->id,
                'make' => ['name' => $auctionRequest->platform],
                'model' => ['name' => 'Lote ' . ($auctionRequest->lot_number ?? 'N/A')],
                'year' => 'N/A',
                'VIN' => $auctionRequest->vin,
            ]
        ]);
    }


    public function processStripe(Request $request, AuctionRequest $auctionRequest)
    {
        if ($auctionRequest->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $stripePriceId = $auctionRequest->stripe_price_id;

        return Inertia::location($request->user()->checkout(
            [$stripePriceId => 1],
            [
                'success_url' => route('checkout-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout-cancel'),
                'metadata' => [
                    'auction_request_id' => $auctionRequest->id,
                ],
            ]
        ));
    }

    public function processAlternativePayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'vehicle_id' => 'required|exists:auction_requests,id',
            'payment_method' => 'required|string',
            'accountNumber' => 'required|string',
            'amount' => 'required|numeric',
            'reference' => 'required|string',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        $auctionRequest = AuctionRequest::findOrFail($validated['vehicle_id']);

        if ($order->user_id !== $request->user()->id || $auctionRequest->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        Transaction::create([
            'order_id' => $order->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'account_number' => $validated['accountNumber'],
            'reference' => $validated['reference'],
            'status' => 'pendiente',
        ]);

        $order->update(['status' => 'verificacion']);

        return redirect()->route('checkout-success')
            ->with('success', 'Pago en proceso de verificación');
    }
}
