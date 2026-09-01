<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Charge remaining balance off-session using the saved card.
 * Returns \Stripe\PaymentIntent on success, or null if we fallback to invoice.
 */
if (!function_exists('charge_balance_now')) {
    function charge_balance_now($customer_id, $payment_method_id, $amount_cents, $currency = 'aud', $landing_page_id = 0)
    {
        if (!class_exists('\Stripe\StripeClient')) {
            throw new \Exception('Stripe SDK not loaded (composer autoload?).');
        }

        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

        try {
            $pi = $stripe->paymentIntents->create([
                'amount'         => (int) $amount_cents,
                'currency'       => strtolower($currency),
                'customer'       => $customer_id,
                'payment_method' => $payment_method_id,
                'off_session'    => true,
                'confirm'        => true,
                'description'    => 'Remaining balance — Destiny Duo Certification',
                'metadata'       => [
                    'landing_page_id' => (string) $landing_page_id,
                    'type'            => 'balance',
                ],
            ]);
            return $pi;
        } catch (\Stripe\Exception\CardException $e) {
            // 3DS or other auth required → fallback to invoice so the user can complete auth in browser
            create_balance_invoice($customer_id, (int) $amount_cents, strtolower($currency), (int) $landing_page_id);
            return null;
        }
    }
}

/**
 * Create & email a Stripe invoice for the remaining balance.
 * Returns \Stripe\Invoice.
 */
if (!function_exists('create_balance_invoice')) {
    function create_balance_invoice($customer_id, $amount_cents, $currency = 'aud', $landing_page_id = 0, $days_until_due = 14)
    {
        if (!class_exists('\Stripe\StripeClient')) {
            throw new \Exception('Stripe SDK not loaded (composer autoload?).');
        }

        $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

        // Add the line item for the balance
        $stripe->invoiceItems->create([
            'customer'    => $customer_id,
            'currency'    => strtolower($currency),
            'amount'      => (int) $amount_cents,
            'description' => 'Remaining balance — Destiny Duo Certification',
            'metadata'    => ['landing_page_id' => (string) $landing_page_id],
        ]);

        // Create the invoice to send
        $invoice = $stripe->invoices->create([
            'customer'          => $customer_id,
            'collection_method' => 'send_invoice',        // user will pay via hosted invoice page
            'days_until_due'    => (int) $days_until_due, // or compute from your stored due date
            'metadata'          => ['landing_page_id' => (string) $landing_page_id],
            'auto_advance'      => true,                  // auto-finalize
        ]);

        // Email it to the customer
        $stripe->invoices->sendInvoice($invoice->id);

        return $invoice;
    }
}
