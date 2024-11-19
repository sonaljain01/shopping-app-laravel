<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderHistory;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.username', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');

        if ($request->get('keyword') != "") {
            $orders = $orders->where('users.username', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('users.email', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('orders.id', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orWhere('orders.phone', 'like', '%' . $request->keyword . '%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.list', [
            'orders' => $orders
        ]);
    }

    public function detail($orderId)
    {
        $order = Order::select('orders.*', 'billing_addresses.*')
            ->where('orders.id', $orderId)
            ->leftJoin('billing_addresses', 'orders.billing_address_id', '=', 'billing_addresses.id')
            ->first();

        $orderItems = OrderItem::select('order_items.*', 'products.*')
            ->where('order_id', $orderId)
            ->rightJoin('products', 'order_items.product_id', '=', 'products.id')
            ->get();


        // Check if order exists
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function changeOrderStatus(Request $request, $orderId)
    {

        $order = Order::findOrFail($orderId);
        if ($order->status === 'cancelled' || $order->status === 'completed') {
            // If cancelled, return a response indicating the order cannot be updated
            session()->flash('error', 'You cannot update the order status because the order is ' . $order->status . '.');
            return redirect()->route('orders.detail', $orderId);
        }
        $previousStatus = $order->status;

        // Update the order status
        $order->status = $request->status;
        $order->save();

        // Log the status update in the order_histories table
        OrderHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'changed_at' => now(), // Stores the current timestamp
        ]);

        session()->flash('success', 'Order status updated successfully');
        return redirect()->route('orders.detail', $orderId)->with('success', 'Order status updated successfully');
    }

    public function viewInvoice($orderId)
    {
        // Retrieve the order with its billing address and items
        $order = Order::with('billingAddress')
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        // Retrieve order items with product details
        $orderItems = OrderItem::with('product')
            ->where('order_id', $orderId)
            ->get();

        // Generate the PDF
        $pdf = Pdf::loadView('invoices.invoice', [
            'order' => $order,
            'orderItems' => $orderItems,
        ]);

        // $pdfContent = $pdf->output();

        // if (request()->has('print')) {
        //     // Optionally send the PDF to PrintNode for printing
        //     $printResult = $this->sendToPrintNode(base64_encode($pdfContent), 'Invoice-' . $order->id);

        //     if ($printResult['success']) {
        //         return response($pdfContent, 200, [
        //             'Content-Type' => 'application/pdf',
        //             'Content-Disposition' => 'inline; filename="invoice-' . $order->id . '.pdf"',
        //         ]);
        //     }

        //     return redirect()->route('orders.index')->with(
        //         'error',
        //         'Failed to send invoice to printer: ' . $printResult['error']
        //     );
        // }

        // Stream the PDF for inline viewing
        return $pdf->stream('invoice-' . $order->id . '.pdf');
    }

    private function sendToPrintNode($pdfBase64, $title)
    {
        $apiKey = env('PRINTNODE_API_KEY');
        $desktopId = env('PRINTNODE_DESKTOP_ID'); // Allow desktop ID to be configurable

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post('https://api.printnode.com/printjobs', [
                'auth' => [$apiKey, env('PRINTNODE_API_SECRET')],
                'json' => [
                    'printerId' => $desktopId,
                    'title' => $title,
                    'contentType' => 'pdf_base64',
                    'content' => $pdfBase64,
                ],
            ]);

            if ($response->getStatusCode() === 201) {
                return ['success' => true];
            }

            return ['success' => false, 'error' => 'Unexpected response from PrintNode'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }


    public function getPrinters()
    {
        $apiKey = env('PRINTNODE_API_KEY');
        $response = \Http::withBasicAuth($apiKey, env('PRINTNODE_API_SECRET'))
            ->get('https://api.printnode.com/printers');

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Failed to fetch printers'], $response->status());
        }
    }


    // public function generateInvoicePDF($orderId)
    // {
    //     $order = Order::select('orders.*', 'billing_addresses.*')
    //         ->where('orders.id', $orderId)
    //         ->leftJoin('billing_addresses', 'orders.billing_address_id', '=', 'billing_addresses.id')
    //         ->first();

    //     $orderItems = OrderItem::select('order_items.*', 'products.*')
    //         ->where('order_id', $orderId)
    //         ->rightJoin('products', 'order_items.product_id', '=', 'products.id')
    //         ->get();

    //     if (!$order) {
    //         return response()->json(['error' => 'Order not found'], 404);
    //     }

    //     $pdf = Pdf::loadView('invoices.invoice', [
    //         'order' => $order,
    //         'orderItems' => $orderItems
    //     ]);

    //     $pdfContent = base64_encode($pdf->output());

    //     return response()->json(['content' => $pdfContent]);
    // }

    public function printInvoice($orderId)
    {
        // Retrieve the order with its billing address
        $order = Order::with('billingAddress')->find($orderId);

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        // Retrieve the order items with product details
        $orderItems = OrderItem::with('product')->where('order_id', $orderId)->get();

        // Generate the PDF
        $pdf = $this->generateInvoicePdf($order, $orderItems);

        // Convert PDF content to Base64
        $pdfContent = $pdf->output();
        $pdfBase64 = base64_encode($pdfContent);

        // Send the PDF to PrintNode
        $printResult = $this->sendToPrintNode($pdfBase64, 'Invoice-' . $order->id);

        if ($printResult['success']) {
            return redirect()->route('orders.index')->with('success', 'Invoice sent to printer successfully!');
        }

        return redirect()->route('orders.index')->with(
            'error',
            'Failed to send invoice to printer: ' . $printResult['error']
        );
    }

    private function generateInvoicePdf($order, $orderItems)
    {
        return Pdf::loadView('invoices.invoice', [
            'order' => $order,
            'orderItems' => $orderItems,
        ])
            ->setPaper('A4') // Standard paper size
            ->setOption('dpi', 72) // Low resolution to reduce file size
            ->setOption('enable_font_subsetting', true); // Optimize fonts
    }



}
