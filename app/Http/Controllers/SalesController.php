<?php

namespace App\Http\Controllers;

use App\Mail\DeliveryConfirmation;
use App\Models\Cart;
use App\Models\Cashier;
use App\Models\Orders;
use App\Models\Purchase;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class SalesController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Orders::all();
    
        // Pass the data to the view and return it
        return view('Features.sales', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }

    public function DeliverSend(Request $request, $cartId)
    {
        // Find the cart
        $cart = Cart::findOrFail($cartId);
    
        // Check if the cart has an associated order
        if (!$cart->orders()->exists()) {
            return redirect()->back()->with('error', 'Failed to send delivery confirmation. Order not found for the cart.');
        }
    
        // Get the first order associated with the cart
        $order = $cart->orders()->first();
    
        // Send email to the user
        $user = $cart->user;
        $userEmail = $user->email;
    
        // Send email using Laravel Mail facade and pass both $cart and $order variables to the email template
        Mail::to($userEmail)->send(new DeliveryConfirmation($cart, $order)); // Pass $cart and $order variables 
        
        $cart->update(['delivery_status' => 2]);
    
        // Redirect back or to a specific page
        return redirect()->back()->with('success', 'Delivery status updated successfully and email dispatched.');
    }
    
    public function pending()
    {
        $orders = Orders::all();
    
        // Pass the data to the view and return it
        return view('Features.pending', compact('orders'));
    }

    public function stats()
    {
        return view('Features.stats');
    }

    public function online(Request $request)
    {
        // Retrieve all orders
        $orders = Orders::query()
            ->orderByDesc('created_at');
        
        // Check if filter parameters are provided in the request
        if ($request->filled('month')) {
            // Validate the request
            $request->validate([
                'month' => 'required|array', // Ensure month is an array
                'month.*' => 'in:January,February,March,April,May,June,July,August,September,October,November,December', // Validate each month
            ]);
    
            // Extract the selected months from the request
            $selectedMonths = $request->input('month');
    
            // Filter orders based on the selected months using the cart relationship
            $orders->whereHas('cart', function ($query) use ($selectedMonths) {
                // Convert month names to numeric values (e.g., January => 1, February => 2, etc.)
                $monthNumbers = collect($selectedMonths)->map(function ($month) {
                    return Carbon::parse($month)->month;
                });
    
                // Filter by month numbers
                $query->whereIn(DB::raw('MONTH(created_at)'), $monthNumbers);
            });
        }
    
        // Retrieve all filtered orders
        $orders = $orders->get();
    
        // Pass the data to the view
        return view('features.onlinesales', compact('orders'));
    }
    
    public function onsite(Request $request)
    {
        // Retrieve all cashiers
        $cashiers = Cashier::query()
            ->orderByDesc('created_at');
    
        // Check if filter parameters are provided in the request
        if ($request->filled('month')) {
            // Validate the request
            $request->validate([
                'month' => 'required|array', // Ensure month is an array
                'month.*' => 'in:January,February,March,April,May,June,July,August,September,October,November,December', // Validate each month
            ]);
    
            // Extract the selected months from the request
            $selectedMonths = $request->input('month');
    
            // Filter cashiers based on the selected months
            $cashiers->where(function ($query) use ($selectedMonths) {
                foreach ($selectedMonths as $month) {
                    $query->orWhereMonth('created_at', Carbon::parse($month)->month);
                }
            });
        }
    
        // Retrieve all filtered cashiers
        $cashiers = $cashiers->get();
    
        // Retrieve all purchase IDs from the filtered cashiers
        $purchaseIds = $cashiers->pluck('purchase_id');
    
        // Retrieve all item images from the purchases table using the filtered purchase IDs
        $itemImages = Purchase::whereIn('id', $purchaseIds)->pluck('item_image', 'id');
    
        // Pass the data to the view
        return view('Features.onsitesales', compact('cashiers', 'itemImages'));
    }

    public function exportonsite(Request $request)
    {
        // Validate request parameters (start_date and end_date)
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        // Retrieve cashier sales within the specified date range, including the end date
        $endDate = Carbon::parse($request->end_date)->addDay(); // Increment end date by 1 day
        $cashiers = Cashier::whereBetween('created_at', [$request->start_date, $endDate])->get();
    
        // Create a new Excel spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Set headers in the first row of the Excel sheet
        $sheet->setCellValue('A1', 'Cashier ID');
        $sheet->setCellValue('B1', 'Item Name');
        $sheet->setCellValue('C1', 'Item Price');
        $sheet->setCellValue('D1', 'Quantity Sold');
        $sheet->setCellValue('E1', 'Total Sales');
        $sheet->setCellValue('F1', 'Sale Date');
    
        // Populate data rows starting from the second row
        $row = 2;
        foreach ($cashiers as $cashier) {
            $sheet->setCellValue('A' . $row, $cashier->id);
            $sheet->setCellValue('B' . $row, $cashier->item_name);
            $sheet->setCellValue('C' . $row, $cashier->item_price);
            $sheet->setCellValue('D' . $row, $cashier->quantity);
            $sheet->setCellValue('E' . $row, $cashier->item_price * $cashier->quantity); // Calculate total sales
            $sheet->setCellValue('F' . $row, $cashier->created_at->format('Y-m-d'));
            $row++;
        }
    
        // Generate filename with timestamp
        $filename = 'cashier_sales_' . now()->format('Ymd_His') . '.xlsx';
    
        // Save the Excel file to the public excelfiles directory
        $filePath = 'excelfiles/' . $filename;
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $filePath));
    
        // Provide a downloadable link to the Excel file
        $downloadUrl = asset('storage/' . $filePath);
    
        // Redirect back with a success message and download link
        return redirect()->back()->with('success', 'Excel file generated successfully. <a href="' . $downloadUrl . '">Download Excel file</a>');
    }

    public function exportOnlineSales(Request $request)
{
    // Validate request parameters (start_date and end_date)
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // Retrieve online sales orders within the specified date range, including the end date
    $endDate = Carbon::parse($request->end_date)->addDay(); // Increment end date by 1 day
    $orders = Orders::whereBetween('created_at', [$request->start_date, $endDate])
                   ->whereHas('cart', function ($query) {
                       $query->where('delivery_status', 3); // Filter only delivered orders
                   })
                   ->with('cart')
                   ->get();

    // Create a new Excel spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers in the first row of the Excel sheet
    $sheet->setCellValue('A1', 'Order ID');
    $sheet->setCellValue('B1', 'Item Name');
    $sheet->setCellValue('C1', 'Quantity');
    $sheet->setCellValue('D1', 'Customer Name');
    $sheet->setCellValue('E1', 'Address');
    $sheet->setCellValue('F1', 'Contact Number');
    $sheet->setCellValue('G1', 'Shipping Option');
    $sheet->setCellValue('H1', 'Payment Option');
    $sheet->setCellValue('I1', 'Total Payment');
    $sheet->setCellValue('J1', 'Order Date');

    // Populate data rows starting from the second row
    $row = 2;
    foreach ($orders as $order) {
        $sheet->setCellValue('A' . $row, $order->id);
        $sheet->setCellValue('B' . $row, $order->cart->item_name);
        $sheet->setCellValue('C' . $row, $order->cart->quantity);
        $sheet->setCellValue('D' . $row, $order->name);
        $sheet->setCellValue('E' . $row, $order->address);
        $sheet->setCellValue('F' . $row, $order->number);
        $sheet->setCellValue('G' . $row, $order->shipping_option == 1 ? 'Standard' : 'Express');
        $sheet->setCellValue('H' . $row, $order->payment_option == 1 ? 'COD' : 'GCash');
        $sheet->setCellValue('I' . $row, $order->total_payment);
        $sheet->setCellValue('J' . $row, $order->created_at->format('Y-m-d'));
        $row++;
    }

    // Generate filename with timestamp
    $filename = 'online_sales_' . now()->format('Ymd_His') . '.xlsx';

    // Save the Excel file to the public excelfiles directory
    $filePath = 'excelfiles/' . $filename;
    $writer = new Xlsx($spreadsheet);
    $writer->save(storage_path('app/public/' . $filePath));

    // Provide a downloadable link to the Excel file
    $downloadUrl = asset('storage/' . $filePath);

    // Redirect back with a success message and download link
    return redirect()->back()->with('success', 'Excel file generated successfully. <a href="' . $downloadUrl . '">Download Excel file</a>');
}
    
}
