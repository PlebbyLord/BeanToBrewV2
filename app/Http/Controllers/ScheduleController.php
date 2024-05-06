<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\Harvest;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('Features.schedule');
    }

    public function harvest()
    {
        return view('Features.Schedules.harvest');
    }

    public function history()
    {
        return view('Features.Schedules.history');
    }

    public function history1(Request $request)
    {
        // Retrieve selected schedule types from the request
        $selectedScheduleTypes = $request->input('schedule_type');
    
        // Query schedules where progress_status is 0 or 1 and location is 'Farm 1'
        $query = Schedule::whereIn('progress_status', [0, 1])
                         ->where('location', 'Farm 1');
    
        // If specific schedule types are selected, filter by them
        if (!empty($selectedScheduleTypes) && in_array('all', $selectedScheduleTypes)) {
            // If 'All' is selected, no need to filter further
        } elseif (!empty($selectedScheduleTypes)) {
            // Filter schedules by selected schedule types
            $query->whereIn('Schedule_Type', $selectedScheduleTypes);
        }
    
        // Get the filtered schedules
        $schedules = $query->get();
        
        // Pass the data to the view
        return view('Features.Schedules.History.history1', compact('schedules'));
    }

    public function history2(Request $request)
    {
        // Retrieve selected schedule types from the request
        $selectedScheduleTypes = $request->input('schedule_type');
    
        // Query schedules where progress_status is 0 or 1 and location is 'Farm 1'
        $query = Schedule::whereIn('progress_status', [0, 1])
                         ->where('location', 'Farm 2');
    
        // If specific schedule types are selected, filter by them
        if (!empty($selectedScheduleTypes) && in_array('all', $selectedScheduleTypes)) {
            // If 'All' is selected, no need to filter further
        } elseif (!empty($selectedScheduleTypes)) {
            // Filter schedules by selected schedule types
            $query->whereIn('Schedule_Type', $selectedScheduleTypes);
        }
    
        // Get the filtered schedules
        $schedules = $query->get();
        
        // Pass the data to the view
        return view('Features.Schedules.History.history2', compact('schedules'));
    }

    public function history3(Request $request)
    {
        // Retrieve selected schedule types from the request
        $selectedScheduleTypes = $request->input('schedule_type');
    
        // Query schedules where progress_status is 0 or 1 and location is 'Farm 1'
        $query = Schedule::whereIn('progress_status', [0, 1])
                         ->where('location', 'Farm 3');
    
        // If specific schedule types are selected, filter by them
        if (!empty($selectedScheduleTypes) && in_array('all', $selectedScheduleTypes)) {
            // If 'All' is selected, no need to filter further
        } elseif (!empty($selectedScheduleTypes)) {
            // Filter schedules by selected schedule types
            $query->whereIn('Schedule_Type', $selectedScheduleTypes);
        }
    
        // Get the filtered schedules
        $schedules = $query->get();
        
        // Pass the data to the view
        return view('Features.Schedules.History.history3', compact('schedules'));
    }

    public function history4(Request $request)
    {
        // Retrieve selected schedule types from the request
        $selectedScheduleTypes = $request->input('schedule_type');
    
        // Query schedules where progress_status is 0 or 1 and location is 'Farm 1'
        $query = Schedule::whereIn('progress_status', [0, 1])
                         ->where('location', 'Farm 4');
    
        // If specific schedule types are selected, filter by them
        if (!empty($selectedScheduleTypes) && in_array('all', $selectedScheduleTypes)) {
            // If 'All' is selected, no need to filter further
        } elseif (!empty($selectedScheduleTypes)) {
            // Filter schedules by selected schedule types
            $query->whereIn('Schedule_Type', $selectedScheduleTypes);
        }
    
        // Get the filtered schedules
        $schedules = $query->get();
        
        // Pass the data to the view
        return view('Features.Schedules.History.history4', compact('schedules'));
    }
    
    public function roast()
    {
        return view('Features.Schedules.roast');
    }

    public function grind()
    {
        return view('Features.Schedules.grind');
    }

    public function pack()
    {
        return view('Features.Schedules.pack');
    }

    public function calendar()
    {
        return view('Features.Schedules.calendar');
    }

    public function calendar1()
    {
        // Fetch all events from the schedules table where location is 'Farm 1'
        $schedules = Schedule::where('location', 'Farm 1')->get();
        
        // Format events for FullCalendar
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'title' => $schedule->Schedule_Type,
                'start' => $schedule->Date_Set, // Assuming Date_Set is in the correct format
                'color' => $schedule->progress_status == 0 ? '#ff0000' : '#00ff00', // Red for 0, Green for 1
            ];
        }
        
        // Pass events data to the view
        return view('Features.Schedules.Calendar.calendar1', compact('events'));
    }

    public function calendar2()
    {
        // Fetch all events from the schedules table where location is 'Farm 1'
        $schedules = Schedule::where('location', 'Farm 2')->get();
        
        // Format events for FullCalendar
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'title' => $schedule->Schedule_Type,
                'start' => $schedule->Date_Set, // Assuming Date_Set is in the correct format
                'color' => $schedule->progress_status == 0 ? '#ff0000' : '#00ff00', // Red for 0, Green for 1
            ];
        }
        
        // Pass events data to the view
        return view('Features.Schedules.Calendar.calendar2', compact('events'));
    }

    public function calendar3()
    {
        // Fetch all events from the schedules table where location is 'Farm 1'
        $schedules = Schedule::where('location', 'Farm 3')->get();
        
        // Format events for FullCalendar
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'title' => $schedule->Schedule_Type,
                'start' => $schedule->Date_Set, // Assuming Date_Set is in the correct format
                'color' => $schedule->progress_status == 0 ? '#ff0000' : '#00ff00', // Red for 0, Green for 1
            ];
        }
        
        // Pass events data to the view
        return view('Features.Schedules.Calendar.calendar3', compact('events'));
    }

    public function calendar4()
    {
        // Fetch all events from the schedules table where location is 'Farm 1'
        $schedules = Schedule::where('location', 'Farm 4')->get();
        
        // Format events for FullCalendar
        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'title' => $schedule->Schedule_Type,
                'start' => $schedule->Date_Set, // Assuming Date_Set is in the correct format
                'color' => $schedule->progress_status == 0 ? '#ff0000' : '#00ff00', // Red for 0, Green for 1
            ];
        }
        
        // Pass events data to the view
        return view('Features.Schedules.Calendar.calendar4', compact('events'));
    }
        
    public function completed()
    {
        return view('Features.Schedules.completed');
    }

    public function completed1()
    {
        // Retrieve completed schedules (progress_status = 2) from 'Farm 1'
        $schedules = Schedule::where('progress_status', 2)
                            ->where('location', 'Farm 1')
                            ->get();
        
        // Pass the data to the view and return it
        return view('Features.Schedules.Completed.completed1', compact('schedules'));
    }

    public function completed2()
    {
        // Retrieve completed schedules (progress_status = 2) from 'Farm 1'
        $schedules = Schedule::where('progress_status', 2)
                            ->where('location', 'Farm 2')
                            ->get();
        
        // Pass the data to the view and return it
        return view('Features.Schedules.Completed.completed2', compact('schedules'));
    }

    public function completed3()
    {
        // Retrieve completed schedules (progress_status = 2) from 'Farm 1'
        $schedules = Schedule::where('progress_status', 2)
                            ->where('location', 'Farm 3')
                            ->get();
        
        // Pass the data to the view and return it
        return view('Features.Schedules.Completed.completed3', compact('schedules'));
    }

    public function completed4()
    {
        // Retrieve completed schedules (progress_status = 2) from 'Farm 1'
        $schedules = Schedule::where('progress_status', 2)
                            ->where('location', 'Farm 4')
                            ->get();
        
        // Pass the data to the view and return it
        return view('Features.Schedules.Completed.completed4', compact('schedules'));
    }

    public function updateProgress(Request $request, $id)
    {
        // Validate the request data for completing the task
        $validatedData = $request->validate([
            'kilos_harvested' => 'required|numeric|min:0', // Validate kilos_harvested
        ]);
    
        // Find the schedule by ID
        $schedule = Schedule::findOrFail($id);
    
        // Check if the schedule is in progress
        if ($schedule->progress_status == 1) {
            if ($schedule->Schedule_Type === 'Harvesting') {
                // Create a new harvest record associated with the schedule
                $harvest = new Harvest();
                $harvest->schedule_id = $schedule->id;
                $harvest->coffee_type = $schedule->coffee_species;
                $harvest->kilos_harvested = $validatedData['kilos_harvested'];
                $harvest->save();
    
                // Save record for dataset model copy
                Dataset::create([
                    'sales_date' => Carbon::parse($harvest->created_at)->format('y-m-d'),
                    'coffee_type' => $harvest->coffee_type,
                    'coffee_form' => '',
                    'sales_kg' => $harvest->kilos_harvested,
                    'price_per_kilo' => 12.77
                ]);
            }
    
            // Update progress status of the schedule to 2 (completed)
            $schedule->update(['progress_status' => 2]);
    
            return redirect()->back()->with('success', 'Task completed successfully.');
        }
    
        // Invalid operation, handle accordingly (though this part may not be needed in this scenario)
        return redirect()->back()->withErrors('Invalid operation.');
    }
    
    public function schedStart($id)
    {
        
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['progress_status' => 1]);
        return back()->with('success', 'Progress status updated successfully.');
    }


    public function saveSchedule(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'calendar' => 'required|date|after_or_equal:today',
            'location' => 'required|in:Farm 1,Farm 2,Farm 3,Farm 4', // Validate the location
        ]);
    
        // Calculate dates based on the provided calendar date
        $plantingDate = Carbon::parse($validatedData['calendar']);
    
        // Determine the batch number based on existing records for this coffeeType
        $batchNumber = Schedule::where('coffee_species', $validatedData['coffeeType'])->count() + 1;
    
        // Save Planting schedule
        $this->saveSingleSchedule($validatedData['coffeeType'], 'Planting', $plantingDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
        // Schedule watering weekly starting immediately after planting
        $wateringDate = $plantingDate->copy();
        while ($wateringDate->isBefore($plantingDate->copy()->addYears(10))) {
            if ($wateringDate->isFuture()) {
                $this->saveRecurringSchedule($validatedData['coffeeType'], 'Watering', $wateringDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
            }
            $wateringDate->addWeek(); // Increment by 1 week for next watering
        }

        // Schedule monthly checks starting immediately after planting
        $monthlyChecksDate = $plantingDate->copy();
        while ($monthlyChecksDate->isBefore($plantingDate->copy()->addYears(10))) {
            if ($monthlyChecksDate->isFuture()) {
                $this->saveRecurringSchedule($validatedData['coffeeType'], 'MonthlyChecks', $monthlyChecksDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
            }
            $monthlyChecksDate->addMonth(); // Increment by 1 month for next monthly checks
        }

        // Schedule pesticide spraying every 6 months starting immediately after planting
        $pesticideDate = $plantingDate->copy();
        while ($pesticideDate->isBefore($plantingDate->copy()->addYears(10))) {
            if ($pesticideDate->isFuture()) {
                $this->saveRecurringSchedule($validatedData['coffeeType'], 'Pesticide Spraying', $pesticideDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
            }
            $pesticideDate->addMonths(6); // Increment by 6 months for next pesticide spraying
        }

        // Schedule recurring tasks after each harvest
        $harvestDate = $plantingDate->copy()->addYears(4); // Initial harvest date (4 years after planting)
        while ($harvestDate->isBefore(Carbon::now()->addYears(10))) { // Set a long time span for recurring harvests
            // Schedule Harvesting
            $this->saveSingleSchedule($validatedData['coffeeType'], 'Harvesting', $harvestDate->format('Y-m-d'), $validatedData['location'], $batchNumber);

            // Schedule Pulping 1 week after each harvest
            $pulpingDate = $harvestDate->copy()->addWeek();
            $this->saveRecurringSchedule($validatedData['coffeeType'], 'Pulping', $pulpingDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
            // Schedule Fermenting 3 days after Pulping
            $fermentingDate = $pulpingDate->copy()->addDays(3);
            $this->saveRecurringSchedule($validatedData['coffeeType'], 'Fermenting', $fermentingDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
            // Schedule Drying 1 day after Fermenting
            $dryingDate = $fermentingDate->copy()->addDay();
            $this->saveRecurringSchedule($validatedData['coffeeType'], 'Drying', $dryingDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
            // Schedule Hulling 7 days after Drying
            $hullingDate = $dryingDate->copy()->addDays(7);
            $this->saveRecurringSchedule($validatedData['coffeeType'], 'Hulling', $hullingDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
            // Schedule Sorting 2 days after Hulling
            $sortingDate = $hullingDate->copy()->addDays(2);
            $this->saveRecurringSchedule($validatedData['coffeeType'], 'Sorting', $sortingDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
            // Schedule Pruning every 5 years after each harvest
            $pruningDate = $harvestDate->copy()->addYears(5);
            $this->saveRecurringSchedule($validatedData['coffeeType'], 'Pruning', $pruningDate->format('Y-m-d'), $validatedData['location'], $batchNumber);
    
            // Move to next harvest (1 year after current harvest)
            $harvestDate->addYear();
        }
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'All schedules saved successfully.');
    }
    
    private function saveSingleSchedule($coffeeType, $scheduleType, $dateSet, $location, $batchNumber)
    {
        // Calculate the age based on the current date and the selected date
        $selectedDate = Carbon::parse($dateSet);
        $currentDate = Carbon::now();
        $age = $currentDate->diffInYears($selectedDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $coffeeType;
        $schedule->Date_Set = $dateSet;
        $schedule->Schedule_Type = $scheduleType;
        $schedule->batch_number = $batchNumber;
        $schedule->progress_status = 0; // Set progress_status to 0 initially
        $schedule->age = $age; // Set the age
        $schedule->location = $location; // Set the location
        $schedule->user_id = Auth::id();
        $schedule->save();
    }
    
    private function saveRecurringSchedule($coffeeType, $scheduleType, $dateSet, $location, $batchNumber)
    {
        // Save the recurring schedule to the database
        $this->saveSingleSchedule($coffeeType, $scheduleType, $dateSet, $location, $batchNumber);
    }
    
    public function PackagingSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'batchNumber' => 'required|integer',
            'calendar' => 'required|date|after_or_equal:today', // Ensure the date is not in the past
            'location' => 'required|in:Farm 1,Farm 2,Farm 3,Farm 4', // Validate the location
        ], [
            'calendar.after_or_equal' => 'The selected date has already passed.',
        ]);
    
        // Check if the specified coffee species has been planted
        $coffeeSpeciesExists = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        if (!$coffeeSpeciesExists) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee species has not been planted yet.'])->withInput();
        }
    
        // Check if the entered batch number exists for the specified coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->exists();
    
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if hulling has been completed for the specified batch
        $hullingCompleted = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                    ->where('batch_number', $validatedData['batchNumber'])
                                    ->where('Schedule_Type', 'Hulling')
                                    ->where('progress_status', 2) // Check if hulling is completed
                                    ->exists();
    
        if (!$hullingCompleted) {
            return redirect()->back()->withErrors(['hulling' => 'Hulling process must be completed before scheduling packaging.'])->withInput();
        }
    
        // Proceed to save the packaging schedule
        $this->saveSingleSchedule($validatedData['coffeeType'], 'Packaging', $validatedData['calendar'], $validatedData['location'], $validatedData['batchNumber']);
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Packaging schedule saved successfully.');
    }
    
    public function RoastingSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'batchNumber' => 'required|integer',
            'calendar' => 'required|date|after_or_equal:today', // Ensure the date is not in the past
            'location' => 'required|in:Farm 1,Farm 2,Farm 3,Farm 4', // Validate the location
        ], [
            'calendar.after_or_equal' => 'The selected date has already passed.',
        ]);
    
        // Check if the specified coffee species has been planted
        $coffeeSpeciesExists = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        if (!$coffeeSpeciesExists) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee species has not been planted yet.'])->withInput();
        }
    
        // Check if the entered batch number exists for the specified coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->exists();
    
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if packaging has been completed for the specified batch
        $packagingCompleted = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                        ->where('batch_number', $validatedData['batchNumber'])
                                        ->where('Schedule_Type', 'Hulling')
                                        ->where('progress_status', 2) // Check if packaging is completed
                                        ->exists();
    
        if (!$packagingCompleted) {
            return redirect()->back()->withErrors(['hulling' => 'Hulling process must be completed before scheduling roasting.'])->withInput();
        }
    
        // Proceed to save the roasting schedule
        $this->saveSingleSchedule($validatedData['coffeeType'], 'Roasting', $validatedData['calendar'], $validatedData['location'], $validatedData['batchNumber']);
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Roasting schedule saved successfully.');
    }
    
    public function GrindingSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'batchNumber' => 'required|integer',
            'calendar' => 'required|date|after_or_equal:today', // Ensure the date is not in the past
            'location' => 'required|in:Farm 1,Farm 2,Farm 3,Farm 4', // Validate the location
        ], [
            'calendar.after_or_equal' => 'The selected date has already passed.',
        ]);
    
        // Check if the specified coffee species has been planted
        $coffeeSpeciesExists = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        if (!$coffeeSpeciesExists) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee species has not been planted yet.'])->withInput();
        }
    
        // Check if the entered batch number exists for the specified coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->exists();
    
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if roasting has been completed for the specified batch
        $roastingCompleted = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                        ->where('batch_number', $validatedData['batchNumber'])
                                        ->where('Schedule_Type', 'Roasting')
                                        ->where('progress_status', 2) // Check if roasting is completed
                                        ->exists();
    
        if (!$roastingCompleted) {
            return redirect()->back()->withErrors(['roasting' => 'Roasting process must be completed before scheduling grinding.'])->withInput();
        }
    
        // Proceed to save the grinding schedule
        $this->saveSingleSchedule($validatedData['coffeeType'], 'Grinding', $validatedData['calendar'], $validatedData['location'], $validatedData['batchNumber']);
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Grinding schedule saved successfully.');
    }
    
}
