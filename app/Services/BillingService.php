<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use Carbon\Carbon;

class BillingService
{
    /**
     * Generate recurring invoices for all active services.
     */
    public function generateRecurringInvoices(): array
    {
        $results = [
            'generated' => 0,
            'errors' => []
        ];

        $servicesToBill = Service::with(['customer', 'serviceType'])
            ->where('status', 'active')
            ->where('next_billing_date', '<=', now())
            ->get();

        foreach ($servicesToBill as $service) {
            try {
                $this->createRecurringInvoice($service);
                $results['generated']++;
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'service_id' => $service->id,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    /**
     * Create a recurring invoice for a service.
     */
    public function createRecurringInvoice(Service $service): Invoice
    {
        $serviceType = $service->serviceType;
        $amount = $serviceType->price;
        $taxAmount = $amount * 0.1; // 10% tax
        $total = $amount + $taxAmount;

        // Calculate next billing date
        $nextBillingDate = match ($serviceType->billing_cycle) {
            'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'yearly' => now()->addYear(),
            default => now()->addMonth(),
        };

        $invoice = Invoice::create([
            'customer_id' => $service->customer_id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => 'pending',
            'due_date' => now()->addDays(30),
            'line_items' => [
                [
                    'description' => $serviceType->name . 
                        ($service->domain_name ? ' - ' . $service->domain_name : '') .
                        ' (Recurring)',
                    'quantity' => 1,
                    'price' => $amount,
                    'total' => $amount,
                ],
            ],
            'is_recurring' => true,
        ]);

        // Update service next billing date
        $service->update([
            'next_billing_date' => $nextBillingDate,
        ]);

        return $invoice;
    }

    /**
     * Mark overdue invoices.
     */
    public function markOverdueInvoices(): int
    {
        return Invoice::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }

    /**
     * Get billing statistics.
     */
    public function getBillingStats(): array
    {
        return [
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'monthly_revenue' => Invoice::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('total'),
            'total_outstanding' => Invoice::whereIn('status', ['pending', 'overdue'])
                ->sum('total'),
            'services_due_billing' => Service::where('status', 'active')
                ->where('next_billing_date', '<=', now()->addDays(7))
                ->count(),
        ];
    }
}