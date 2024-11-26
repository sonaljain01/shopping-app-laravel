<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderHistory;
use App\Http\Controllers\admin\ShipRocketController;

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
        $order = Order::with(['billingAddress', 'shippingAddress', 'orderItems.product'])
            ->where('id', $orderId)
            ->first();

        // Check if the order exists
        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found');
        }

        return view('admin.orders.detail', [
            'order' => $order,
        ]);

    }

    public function changeOrderStatus(Request $request, $orderId, ShipRocketController $shipRocketController)
    {
        $request->validate([
            'status' => 'required|in:In Progress,completed,cancelled,shipped',
        ]);

        // Retrieve the order
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        // Prevent updating if the order is already completed or cancelled
        if (in_array($order->status, ['cancelled', 'completed'])) {
            return response()->json(['success' => false, 'message' => 'Cannot update the status of an order that is ' . $order->status . '.'], 400);
        }

        // Handle "shipped" status with ShipRocket integration
        if ($request->status === 'shipped') {
            $shipRocketResponse = $shipRocketController->createOrder($order);

            // Verify ShipRocket response
            if (!$shipRocketResponse || !isset($shipRocketResponse['status'])) {
                return response()->json(['success' => false, 'message' => 'Failed to create order in ShipRocket.'], 500);
            }

            if ($shipRocketResponse['status'] === 200) {
                $shipRocketOrderData = $shipRocketResponse['data'] ?? [];

                // Update the order with ShipRocket details
                $order->update([
                    'shiprocket_order_id' => $shipRocketOrderData['order_id'] ?? null,
                    'shiprocket_tracking_number' => $shipRocketOrderData['tracking_number'] ?? null,
                    'shiprocket_label_url' => $shipRocketOrderData['label_url'] ?? null,
                    'status' => 'shipped',
                ]);

                // Store additional shipment details if needed
                $storeResponse = $shipRocketController->storeShipment($shipRocketOrderData);
                if (!$storeResponse['status']) {
                    return response()->json(['success' => false, 'message' => $storeResponse['message']], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing order in ShipRocket: ' . ($shipRocketResponse['message'] ?? 'Unknown error.'),
                ], 500);
            }
        } else {
            // Update order status for other statuses
            $order->status = $request->status;
            $order->save();
        }

        // Log the order status change
        OrderHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'changed_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
    }


    public function viewInvoice($orderId)
    {
        // Retrieve the order with its related models: billingAddress, shippingAddress, and orderItems with products
        $order = Order::with(['billingAddress', 'shippingAddress', 'orderItems.product'])
            ->where('id', $orderId)
            ->first();

        $orderItems = $order->orderItems; // This is already loaded via eager loading

        // Check if the order exists
        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found');
        }

        // Load the PDF view
        $pdf = Pdf::loadView('invoices.invoice', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);

        return $pdf->stream('invoice-' . $order->id . '.pdf');
    }


    public function printInvoice($orderId)
    {
        // Retrieve the order with its related models: billingAddress, shippingAddress, and orderItems with products
        $order = Order::with(['billingAddress', 'shippingAddress', 'orderItems.product'])
            ->where('id', $orderId)
            ->first();

        // Check if the order exists
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        // Retrieve the order items (already eager-loaded in $order)
        $orderItems = $order->orderItems;

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

}
