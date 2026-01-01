<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Transaction;
use App\Services\Payment\MockGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $gateway;

    public function __construct()
    {
        // simplistic dependency injection for now. 
        // In real app, bind interface in AppServiceProvider.
        $this->gateway = new MockGateway();
    }

    public function checkout(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1|max:10',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'payment_method' => 'required|string',
            'seat_number' => 'nullable|string',
        ]);

        // Check if gateway is enabled
        if (\App\Models\Setting::get($validated['payment_method'] . '_enabled') !== '1') {
            return back()->with('error', 'Selected payment method is currently unavailable.');
        }

        $ticketType = TicketType::findOrFail($request->ticket_type_id);

        // Seat Mapping Validation
        if ($event->has_seat_mapping) {
            if (empty($validated['seat_number'])) {
                return back()->with('error', 'Please select a seat number.');
            }
            // Double check if seat is still available
            $isOccupied = $event->tickets()
                ->where('status', '!=', 'cancelled')
                ->where('seat_number', $validated['seat_number'])
                ->exists();
            if ($isOccupied) {
                return back()->with('error', 'Sorry, seat ' . $validated['seat_number'] . ' is already taken.');
            }
        }

        $quantity = $validated['quantity'];
        $subtotal = $ticketType->price * $quantity;
        
        // Handle Coupon logic (omitted for brevity, keeping existing)
        $discountAmount = 0;
        $couponId = null;
        if (!empty($validated['coupon_code'])) {
            $coupon = $event->coupons()->where('code', $validated['coupon_code'])->first();
            if ($coupon && $coupon->isValid()) {
                if ($coupon->type === 'fixed') {
                    $discountAmount = min($subtotal, $coupon->amount);
                } else {
                    $discountAmount = $subtotal * ($coupon->amount / 100);
                }
                $coupon->increment('used_count');
                $couponId = $coupon->code; 
            }
        }
        $discountedTotal = max(0, $subtotal - $discountAmount);

        // Tax Logic
        $taxAmount = 0;
        if ($event->tax_type === 'exclusive') {
            $taxAmount = $discountedTotal * ($event->tax_rate / 100);
        } elseif ($event->tax_type === 'inclusive') {
            $taxRate = $event->tax_rate / 100;
            $basePrice = $discountedTotal / (1 + $taxRate);
            $taxAmount = $discountedTotal - $basePrice;
        }

        $totalCharge = $event->tax_type === 'exclusive' ? ($discountedTotal + $taxAmount) : $discountedTotal;

        // Commission Logic (Referral)
        $promoterId = null;
        $commissionAmount = 0;
        if ($event->allow_promoters) {
            $referralCode = $request->cookie('referral_code');
            if ($referralCode) {
                $promoter = \App\Models\User::where('referral_code', $referralCode)->first();
                if ($promoter && $promoter->id !== Auth::id()) {
                    $promoterId = $promoter->id;
                    if ($event->commission_type === 'percentage') {
                        $commissionAmount = ($totalCharge * ($event->commission_rate / 100));
                    } else {
                        $commissionAmount = $event->commission_rate;
                    }
                }
            }
        }

        if ($totalCharge <= 0) {
           return $this->processFreeTickets($event, $ticketType, $validated['quantity']);
        }

        $ref = 'TXN-' . strtoupper(Str::random(12));
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'amount' => $totalCharge,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'coupon_code' => $couponId,
            'currency' => 'USD',
            'payment_method' => $validated['payment_method'],
            'transaction_ref' => $ref,
            'status' => 'pending',
            'meta_data' => [
                'quantity' => $validated['quantity'],
                'seat_number' => $validated['seat_number'] ?? null,
            ],
            'promoter_id' => $promoterId,
            'commission_amount' => $commissionAmount,
        ]);

        // Gateway Specific Logic
        switch ($validated['payment_method']) {
            case 'bank_transfer':
                return redirect()->route('tickets.index')->with('success', 'Order placed! Please follow the bank transfer instructions: ' . \App\Models\Setting::get('bank_account_details'));
            
            case 'cash_on_hand':
                return redirect()->route('tickets.index')->with('success', 'Order placed! Please pay cash: ' . \App\Models\Setting::get('cash_instructions'));

            default:
                // For Online Gateways (PayPal, Stripe, etc.)
                // In a real app, this is where you'd call their SDKs.
                // For this kit, we simulate a successful redirect.
                $result = $this->gateway->charge([
                    'amount' => $totalCharge,
                    'currency' => 'USD',
                    'email' => Auth::user()->email,
                    'ref' => $ref,
                    'method' => $validated['payment_method']
                ]);

                if ($result['success']) {
                    return redirect($result['redirect_url']);
                }
                return back()->with('error', 'Payment initiation failed: ' . $result['message']);
        }
    }



    public function success(Request $request)
    {
        $ref = $request->query('ref');
        $transaction = Transaction::where('transaction_ref', $ref)->firstOrFail();

        if ($transaction->status === 'completed') {
            return redirect()->route('tickets.index')->with('success', 'Payment already processed.');
        }

        // Verify with gateway (Mock just assumes success if here)
        $callbackData = $this->gateway->handleCallback(['ref' => $ref]);

        if ($callbackData['status'] === 'completed') {
            $transaction->update([
                'status' => 'completed',
                'external_ref' => $callbackData['external_ref']
            ]);

            // GENERATE TICKETS
            $quantity = $transaction->meta_data['quantity'];
            $seatNumber = $transaction->meta_data['seat_number'] ?? null;
            $this->generateTickets($transaction->event, $transaction->ticketType, $transaction->user, $quantity, $seatNumber);

            return redirect()->route('tickets.index')->with('success', 'Payment successful! Tickets generated.');
        }

        return redirect()->route('events.show', $transaction->event)->with('error', 'Payment verification failed.');
    }

    private function processFreeTickets(Event $event, TicketType $ticketType, int $quantity, ?string $seatNumber = null)
    {
         $this->generateTickets($event, $ticketType, Auth::user(), $quantity, $seatNumber);
         return redirect()->route('tickets.index')->with('success', 'Free tickets claiming successfully!');
    }

    private function generateTickets(Event $event, TicketType $ticketType, $user, int $quantity, ?string $seatNumber = null)
    {
        if ($ticketType->available_quantity < $quantity) {
             // In real world, handle refund here if stock ran out during payment
             throw new \Exception("Stock ran out!"); 
        }

        for ($i = 0; $i < $quantity; $i++) {
            Ticket::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'ticket_type_id' => $ticketType->id,
                'ticket_code' => strtoupper(Str::random(10)),
                'seat_number' => $i === 0 ? $seatNumber : null, // Assign seat to the first ticket if mapping exists
                'status' => 'valid',
            ]);
        }

        $ticketType->increment('quantity_sold', $quantity);
    }
}
