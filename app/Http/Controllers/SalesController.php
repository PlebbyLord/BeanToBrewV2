<?php

namespace App\Http\Controllers;

use App\Mail\DeliveryConfirmation;
use App\Models\Cart;
use App\Models\Cashier;
use App\Models\Dataset;
use App\Models\Orders;
use App\Models\Purchase;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Rubix\ML\Classifiers\ClassificationTree;
use Rubix\ML\Classifiers\RandomForest;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class SalesController extends Controller
{
    public $tempArray = [], $targets = [], $resultArr=[], $l5y=[], $input = [];//this is the current year data;
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

        $sales = Dataset::get()->toArray();
        $this->readData($sales);
        
        $model = new RandomForest(new ClassificationTree(10), 300, 0.1, true);

        $dataset = new Labeled($this->tempArray, $this->targets);
        $model->train($dataset);
       

        //get the last 5 years data
        $this->l5y = $this->getLast5Years();
        // start prediction
        $this->predictingFutureYearSales($this->l5y);
        $inputDataset = new Unlabeled($this->input);
        $result = $model->predict($inputDataset); 
        
        $currentYear = (int)date('Y');
        
        $salesInPast5YEars = collect([]);
        foreach ($result as $key => $v) {
            $explodedResult = explode('-', $v);
           
            // dd($this->input[$key][3] + $explodedResult[4]);
    

            $salesPerKg5Y = round((double)$this->input[$key][3] + (double)$explodedResult[4], 2);
            $salesInPast5YEars->push([
                'id' =>$explodedResult[0],
                'year' => $currentYear++,
                'month' => $explodedResult[2],
                'day' => $explodedResult[3],
                'sales_kg' => $salesPerKg5Y,
                'coffee' =>  $explodedResult[5],
            ]);
        }

        return view('Features.stats', ['salesInPast5YEars' => $salesInPast5YEars]);
    }

    private function readData($data){
        if($data){
            foreach ($data as $key => $value) {
                $explodedDate = explode('-', $value['sales_date']);
                // Remove the original sales_date column
                unset($value['sales_date']);
                // Create an associative array with custom keys
                $customKeys = [
                    'year' => (int)$explodedDate[0],
                    'month' => $explodedDate[1] < 10 ? (int)ltrim($explodedDate[1], '0') : (int)$explodedDate[1],
                    'day' => (int)$explodedDate[2] < 10 ? (int)ltrim($explodedDate[2], '0') : (int)$explodedDate[2],
                    'sales_kg' => (double)$value['sales_kg'],
                    'price_per_kilo' => (double)$value['price_per_kilo'],
                ]; 
               array_push($this->tempArray, $customKeys);



               array_push($this->targets, $value['id'].'-'.$customKeys['year'].'-'.$customKeys['month'].'-'.$customKeys['day'].'-'.$customKeys['sales_kg'].'-'.$value['coffee_type'].' '.$value['coffee_form']);
            }

            //debuging  found on storage logs laravel.log
            // info($this->tempArray[0]);
            // info($this->targets[0]);
        }
    }

    private function predictingFutureYearSales($lastFiveYearsData){
        // Calculate sales for the next 5 years based on predictions for the last 5 years
        $growthRate = 1.1; // Assuming a growth rate of 10%

        foreach ($lastFiveYearsData as $key => $predictedSales) {
            // dd($predictedSales);
            $explodedDate = explode('-', $predictedSales->record_with_max_sales->sales_date);
            // Remove the original sales_date column
            unset($predictedSales->record_with_max_sales->sales_date);
            // Create an associative array with custom keys
            $Keys = [
                (int)$explodedDate[0],
                $explodedDate[1] < 10 ? (int)ltrim($explodedDate[1], '0') : (int)$explodedDate[1],
                $explodedDate[2] < 10 ? (int)ltrim($explodedDate[2], '0') : (int)$explodedDate[2],
                (double)$predictedSales->total_sales_kg * $growthRate,
                (double)$predictedSales->record_with_max_sales->price_per_kilo,
            ]; 

            array_push($this->input,  $Keys);
            
            // $next5YearsSales[] = $predictedSales * $growthRate;
            $growthRate += 0.1; // Increment growth rate by 10% for each subsequent year

        }
        // dd($this->input);
    }

    private function getLast5Years(){
       $lastFiveYearsData = DB::table('datasets')
        ->select(DB::raw('YEAR(sales_date) as year, SUM(sales_kg) as total_sales_kg'))
        ->whereBetween(DB::raw('YEAR(sales_date)'), [date('Y') - 6, date('Y')])
        ->groupBy(DB::raw('YEAR(sales_date)'))
        ->orderBy('year', 'desc')
        ->get()
        ->toArray();

        foreach ($lastFiveYearsData as &$data) {
            $record = DB::table('datasets')
                ->select('datasets.*')
                ->whereYear('sales_date', $data->year)
                ->orderBy('sales_kg', 'desc')
                ->first();
            
            $data->record_with_max_sales = $record;
        }
        // dd($lastFiveYearsData);
        return $lastFiveYearsData;
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
        // dd($cashiers);
        // Retrieve all purchase IDs from the filtered cashiers
        $purchaseIds = $cashiers->pluck('purchase_id');
    
        // Retrieve all item images from the purchases table using the filtered purchase IDs
        $itemImages = Purchase::whereIn('id', $purchaseIds)->pluck('item_image', 'id');
        
        // dd($cashiers);
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
