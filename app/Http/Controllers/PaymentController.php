<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Transfer;

class PaymentController extends Controller
{
    public function showCheckout()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => 2000, // $20.00 USD
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            // Pass keys as props to your Inertia component
            return Inertia::render('Checkout', [
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => env('STRIPE_KEY')
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    } // <-- This closes the showCheckout method

    public function history()
    {
        // Mocking real structural database data queries
        $pendingPayments = [
            [
                'id' => 'TXN-9021',
                'date' => 'May 28, 2026',
                'description' => 'Advanced Physics Tutoring - Session #4',
                'amount' => '45.00',
                'status' => 'pending'
            ],
        ];

        $previousPayments = [
            [
                'id' => 'TXN-8841',
                'date' => 'May 15, 2026',
                'description' => 'Standard Platform Access Monthly License',
                'amount' => '20.00',
                'status' => 'paid'
            ],
            [
                'id' => 'TXN-8712',
                'date' => 'April 29, 2026',
                'description' => 'Introductory Calculus Bundle Pack',
                'amount' => '65.00',
                'status' => 'claimed'
            ],
        ];

        // Make sure 'PaymentHistory' has a capital P and capital H
return Inertia::render('PaymentHistory', [
    'pendingPayments' => $pendingPayments,
    'previousPayments' => $previousPayments,
]);

    }

    public function tutorPayouts()
    {
        // High-utility financial summary blocks
        $payoutBalances = [
            'available' => '415.00',
            'pending_clearance' => '120.00',
            'lifetime_earnings' => '3,180.00'
        ];
    
        // Explicitly structured array tracking individual completed classes
        $completedClasses = [
            [
                'id' => 'CLS-9941',
                'date' => 'May 28, 2026',
                'student' => 'Sarah Jenkins',
                'subject' => 'Advanced Physics - Quantum Mechanics',
                'amount' => '45.00',
                'can_disburse' => true,
            ],
            [
                'id' => 'CLS-9812',
                'date' => 'May 27, 2026',
                'student' => 'Michael Chang',
                'subject' => 'Introductory Calculus - Derivates',
                'amount' => '40.00',
                'can_disburse' => true,
            ],
            [
                'id' => 'CLS-9705',
                'date' => 'May 25, 2026',
                'student' => 'Emma Watson',
                'subject' => 'Organic Chemistry - Alkanes Lab',
                'amount' => '50.00',
                'can_disburse' => false, // Disbursed already
            ],
        ];
    
        return Inertia::render('TutorsPayout', [
            'balances' => $payoutBalances,
            'completedClasses' => $completedClasses,
        ]);
    }
    
    public function processClassDisbursement(Request $request)
{
    // 1. Validate the incoming payload form parameters
    $request->validate([
        'class_id' => 'required|string',
        'payout_method' => 'required|string|in:stripe,check',
        'account_name' => 'required_if:payout_method,stripe|nullable|string',
        'account_number' => 'required_if:payout_method,stripe|nullable|string',
        'mailing_address' => 'required_if:payout_method,check|nullable|string',
    ]);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    try {
        if ($request->payout_method === 'stripe') {
            // 2. Real Stripe Connect architecture relies on generating a Transfer object [2]
            // Note: In real life, 'acct_123' is the tutor's unique Stripe Connect Account ID [1, 2]
            /*
            $transfer = Transfer::create([
                'amount' => 4500, // $45.00 in cents
                'currency' => 'usd',
                'destination' => 'acct_your_tutor_connect_id', 
                'description' => 'Disbursement for Class ' . $request->class_id,
            ]);
            */
        }

        // 3. Return back to Inertia with a clean flash session success state
        return back()->with('success', 'Disbursement successfully initiated via ' . strtoupper($request->payout_method));

    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

} 

