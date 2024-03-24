<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Counter;

class InvoiceController extends Controller
{
    public function getAllInvoices()
    {
        $invoices = Invoice::with('customer')->orderBy('id', 'desc')->get();
        return response()->json([
            'invoices' => $invoices
        ], 200);
    }

    public function getInvoice($id)
    {
        $invoice = Invoice::with('customer', 'invoice_items.product')->find($id);
        return response()->json([
          'invoice' => $invoice
        ], 200);
    }

    public function searchInvoice( Request $request )
    {
        $search = $request->get('s');
        if( $search != null ){

            $invoices = Invoice::with('customer')
                ->where('id', 'LIKE', "%$search%")
                ->get();

            return response()->json([
                'invoices' => $invoices         
            ], 200);
        } else {
            return $this->getAllInvoices();
        }
    }

    public function createInvoice( Request $request )
    {
        $counter = Counter::where('key', 'invoice')->first();
        $ramdom = Counter::where('key', 'invoice')->first();

        $invoice = Invoice::orderBy('id', 'DESC')->first();
        if($invoice){
            $invoice = $invoice->id+1;
            $counters = $counter->value + $invoice;
        } else{
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default Terms and Conditions',
            'items' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quantity' => 1
            ]
        ];

        return response()->json( $formData, 200);
    }

    public function addInvoice( Request $request )
    {
        $invoiceItems = $request->input('invoice_items');
        $invoiceData['sub_total'] = $request->input("subtotal");
        $invoiceData['total'] = $request->input("total");
        $invoiceData['customer_id'] = $request->input("customer_id");
        $invoiceData['number'] = $request->input("number");
        $invoiceData['date'] = $request->input("date");
        $invoiceData['due_date'] = $request->input("due_date");
        $invoiceData['discount'] = $request->input("discount");
        $invoiceData['reference'] = $request->input("reference");
        $invoiceData['terms_and_conditions'] = $request->input("terms_and_conditions");
        // dd($invoiceData);

        $invoice = Invoice::create($invoiceData);

        foreach(json_decode($invoiceItems) as $item){
            $invoiceItem['product_id'] = $item->id;
            $invoiceItem['invoice_id'] = $invoice->id;
            $invoiceItem['quantity'] = $item->quantity;
            $invoiceItem['unit_price'] = $item->unit_price;

            InvoiceItem::create($invoiceItem);
        }
    }
}


// formData.append('customer_id', customerId.value);
// formData.append('date', form.value.date);
// formData.append('due_date', form.value.due_date);
// formData.append('number', form.value.number);
// formData.append('reference', form.value.reference);
// formData.append('discount', form.value.discount);
// formData.append('subtotal', form.value.subtotal);
// formData.append('total', form.value.total);
// formData.append('terms_and_conditions', form.value.terms_and_conditions);