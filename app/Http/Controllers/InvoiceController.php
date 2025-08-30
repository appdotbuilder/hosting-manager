<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'order'])
            ->latest()
            ->paginate(10);
        
        return Inertia::render('invoices/index', [
            'invoices' => $invoices
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'order']);

        return Inertia::render('invoices/show', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,overdue,cancelled'
        ]);

        $updateData = [
            'status' => $request->status
        ];

        // If marking as paid, set paid_at timestamp
        if ($request->status === 'paid' && $invoice->status !== 'paid') {
            $updateData['paid_at'] = now();
            
            // If invoice has an associated order, mark it as paid too
            if ($invoice->order) {
                $invoice->order->update(['status' => 'paid']);
            }
        }

        $invoice->update($updateData);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}