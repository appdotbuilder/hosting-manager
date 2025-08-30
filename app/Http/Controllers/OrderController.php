<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['customer'])
            ->latest()
            ->paginate(10);
        
        return Inertia::render('orders/index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::active()->get();
        $serviceTypes = ServiceType::where('active', true)->get();

        return Inertia::render('orders/create', [
            'customers' => $customers,
            'serviceTypes' => $serviceTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validatedData = $request->validated();
        
        // Calculate order totals
        $subtotal = 0;
        $orderItems = [];

        foreach ($validatedData['items'] as $item) {
            $serviceType = ServiceType::find($item['service_type_id']);
            $itemTotal = $serviceType->price * $item['quantity'];
            $subtotal += $itemTotal;

            $orderItems[] = [
                'service_type_id' => $serviceType->id,
                'name' => $serviceType->name,
                'quantity' => $item['quantity'],
                'price' => $serviceType->price,
                'total' => $itemTotal,
                'domain_name' => $item['domain_name'] ?? null,
            ];
        }

        $taxAmount = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $taxAmount;

        // Create order
        $order = Order::create([
            'customer_id' => $validatedData['customer_id'],
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => 'pending',
            'items' => $orderItems,
        ]);

        // Create invoice for the order
        Invoice::create([
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'amount' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => 'pending',
            'due_date' => now()->addDays(30),
            'line_items' => array_map(function ($item) {
                return [
                    'description' => $item['name'] . ($item['domain_name'] ? ' - ' . $item['domain_name'] : ''),
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ];
            }, $orderItems),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'invoices']);

        return Inertia::render('orders/show', [
            'order' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,refunded'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        // If order is marked as paid, process services
        if ($request->status === 'paid' && $order->wasChanged('status')) {
            // Update related invoices to paid
            $order->invoices()->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            // Create services for each order item
            foreach ($order->items as $item) {
                $serviceType = ServiceType::find($item['service_type_id']);
                
                // Calculate next billing date based on billing cycle
                $nextBillingDate = match ($serviceType->billing_cycle) {
                    'monthly' => now()->addMonth(),
                    'quarterly' => now()->addMonths(3),
                    'yearly' => now()->addYear(),
                    default => now()->addMonth(),
                };

                Service::create([
                    'customer_id' => $order->customer_id,
                    'service_type_id' => $serviceType->id,
                    'domain_name' => $item['domain_name'] ?? null,
                    'status' => 'active',
                    'next_billing_date' => $nextBillingDate,
                    'expiry_date' => $nextBillingDate,
                    'provisioning_data' => [
                        'provisioned_at' => now(),
                        'server_id' => 'SRV-' . uniqid(),
                        'account_username' => 'user' . uniqid(),
                    ],
                ]);
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }


}