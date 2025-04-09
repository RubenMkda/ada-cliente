<?php

namespace App\Http\Controllers;

use App\Models\AuctionRequest;
use App\Models\ServiceFee;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;

class AuctionRequestController extends Controller
{
    public function index()
    {
        $auctionRequests = AuctionRequest::where('user_id', auth()->user()->id)->get();

        return Inertia::render('auctionRequest/Index', [
            'auctionRequests' => $auctionRequests
        ]);
    }

    public function create()
    {
        return Inertia::render('auctionRequest/Create', [
            'platforms' => AuctionRequest::getPlatforms(),
            'serviceFeeRules' => ServiceFee::select('min_amount', 'max_amount', 'fee')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:Copart,IAAI,Manheim,ACV Auctions',
            'vin' => 'required|string|size:17',
            'lot_number' => 'nullable|string|max:50',
            'vehicle_location' => 'required|string|max:255',
            'max_bid' => 'required|numeric|min:100',
            'auction_url' => 'nullable|url',
            'transport_quote' => 'boolean',
            'carfax_quote' => 'boolean',
            'comments' => 'nullable|string',
            'promo_code' => 'nullable|string|max:50',
        ]);
    
        $validated['user_id'] = auth()->id();
        $validated['service_fee'] = $this->getServiceFeeFromBid($validated['max_bid'], $validated['promo_code'] ?? null);
        $validated['status'] = 'pending';
    
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        $product = Product::create([
            'name' => "Subasta: {$validated['platform']} - VIN {$validated['vin']}",
            'metadata' => [
                'user_id' => $validated['user_id'],
                'vin' => $validated['vin'],
            ],
        ]);
    
        $price = Price::create([
            'unit_amount' => intval($validated['service_fee'] * 100), 
            'currency' => 'usd',
            'product' => $product->id,
        ]);
    
        $validated['stripe_product_id'] = $product->id;
        $validated['stripe_price_id'] = $price->id;
    
        $auctionRequest = AuctionRequest::create($validated);
    
        return redirect()->route('home')
            ->with('success', 'Solicitud de subasta creada correctamente');
    }
    

    private function getServiceFeeFromBid($maxBid, $promoCode = null)
    {
        return ServiceFee::query()
            ->when($promoCode, function ($query) use ($promoCode) {
                $query->where('promo_code', $promoCode)
                    ->where(function ($q) {
                        $q->whereNull('valid_until')
                            ->orWhere('valid_until', '>=', now());
                    });
            }, function ($query) {
                $query->whereNull('promo_code');
            })
            ->where('min_amount', '<=', $maxBid)
            ->where(function ($query) use ($maxBid) {
                $query->where('max_amount', '>=', $maxBid)
                    ->orWhereNull('max_amount');
            })
            ->orderBy('min_amount', 'desc')
            ->value('fee') ?? config('auction.service_fee', 99.99);
    }
}