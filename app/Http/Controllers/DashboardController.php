<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_services' => Service::where('status', 'active')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'unpaid_invoices' => Invoice::where('status', 'pending')->count(),
            'monthly_revenue' => Invoice::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('total'),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
        ];

        $recentOrders = Order::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        $recentInvoices = Invoice::with('customer')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $upcomingBilling = Service::with(['customer', 'serviceType'])
            ->where('status', 'active')
            ->whereBetween('next_billing_date', [now(), now()->addDays(7)])
            ->limit(5)
            ->get();

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentInvoices' => $recentInvoices,
            'upcomingBilling' => $upcomingBilling,
        ]);
    }
}