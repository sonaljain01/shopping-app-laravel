<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderHistory;
use App\Models\PickupAddress;
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
        $pickupAddress = PickupAddress::where('user_id', auth()->user()->id)->get();
        return view('admin.orders.list', [
            'orders' => $orders,
            'pickupAddress' => $pickupAddress
        ]);
    }

    public function detail($orderId)
    {
        $order = Order::with(['billingAddress', 'shippingAddress', 'orderItems.product'])
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found');
        }

        $pickupAddresses = PickupAddress::all();
        // echo $pickupAddresses;
        return view('admin.orders.detail', [
            'order' => $order,
            'pickupAddresses' => $pickupAddresses, // Ensure this is passed
        ]);
    }

    public function changeOrderStatus(Request $request, $orderId, ShipRocketController $shipRocketController)

    {
        $request->validate([
            'status' => 'required|in:In Progress,completed,cancelled,shipped',
            'pickup' => 'required_if:status,shipped|exists:pickup_addresses,id',
        ]);

        $order = Order::with(['billingAddress', 'shippingAddress', 'orderItems.product'])->find($orderId);
        // echo  $order;
        // dd();
        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        if(in_array($order->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'Cannot change order status because it is already '.$order->status);
        }
        
        if ($request->status === 'shipped') {
            $pickupAddress = PickupAddress::find($request->pickup);

            if (!$pickupAddress || !$pickupAddress->tag) {
                return back()->with('error', 'Invalid or missing pickup address/tag.');
            }

            $shipRocketResponse = $shipRocketController->createOrder($order, $pickupAddress->tag);

            if (! $shipRocketResponse || ! method_exists($shipRocketResponse, 'status')) {
                return back()->with('error', 'Failed to create order in ShipRocket.');
            }

            if ($shipRocketResponse->status() === 200) {
                $storeResponse = $shipRocketController->storeShipment($shipRocketResponse->json());

                if (! $storeResponse['status']) {
                    return back()->with('error', $storeResponse['message']);
                }
                $order->update(['status' => "shipped"]);
            } else {
                return response()->json(['data' => $shipRocketResponse->json()]);
            }
        } else {
            $order->update(['status' => $request->status]);
        }

        OrderHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Order updated successfully.');
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

    public function updateGlobalCountry(Request $request)
    {
        session()->put('country', $request->name);
        return response()->json(['success' => true], 200);

    }
}
