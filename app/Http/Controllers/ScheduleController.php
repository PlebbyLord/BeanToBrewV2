<?php

namespace App\Http\Controllers;

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
        $schedules = Schedule::whereIn('progress_status', [0, 1])->get();
    
        // Pass the data to the view and return it
        return view('Features.Schedules.history', compact('schedules'));
    }
    
    public function pesticide()
    {
        return view('Features.Schedules.pesticide');
    }

    public function prune()
    {
        return view('Features.Schedules.prune');
    }

    public function pulp()
    {
        return view('Features.Schedules.pulp');
    }

    public function ferment()
    {
        return view('Features.Schedules.ferment');
    }

    public function dry()
    {
        return view('Features.Schedules.dry');
    }

    public function hull()
    {
        return view('Features.Schedules.hull');
    }

    public function sort()
    {
        return view('Features.Schedules.sort');
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

    public function water()
    {
        return view('Features.Schedules.watering');
    }

    public function weekly()
    {
        return view('Features.Schedules.weeklychecks');
    }

    public function calendar()
    {
        // Fetch all events from the schedules table
        $schedules = Schedule::all();
        
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
        return view('Features.Schedules.calendar', compact('events'));
    }    
    

    public function completed()
    {
        $schedules = Schedule::where('progress_status', 2)->get();
    
        // Pass the data to the view and return it
        return view('Features.Schedules.completed', compact('schedules'));
    }
    

    public function updateProgress($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['progress_status' => 2]);
        return back()->with('success', 'Progress status updated successfully.');
    }
    
    public function schedStart($id)
    {
        
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['progress_status' => 1]);
        return back()->with('success', 'Progress status updated successfully.');
    }


    public function PlantingSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'calendar' => 'required|date',
            'location' => 'required|in:Farm 1,Farm 2,Farm 3,Farm 4', // Validate the location
        ]);

        // Calculate the best date for harvest (add 4 years to the selected date)
        $harvestDate = Carbon::parse($request->input('calendar'))->addYears(5)->format('Y-m-d');

        // Determine the batch number based on existing records
        $existingRecordsCount = Schedule::where('coffee_species', $validatedData['coffeeType'])->count();
        $batchNumber = $existingRecordsCount + 1;

        // Calculate the age based on the current date and the selected date
        $selectedDate = Carbon::parse($validatedData['calendar']);
        $currentDate = Carbon::now();
        $age = $currentDate->diffInYears($selectedDate);

        // Save the planting schedule to the database
        $plantingSchedule = new Schedule();
        $plantingSchedule->coffee_species = $validatedData['coffeeType'];
        $plantingSchedule->Date_Set = $validatedData['calendar'];
        $plantingSchedule->Schedule_Type = 'Planting'; // Assuming it's planting schedule
        $plantingSchedule->batch_number = $batchNumber;
        $plantingSchedule->progress_status = 0; // Set progress_status to 0 initially
        $plantingSchedule->age = $age; // Set the age
        $plantingSchedule->location = $validatedData['location']; // Set the location
        $plantingSchedule->user_id = Auth::id(); 
        $plantingSchedule->save();

        // Save the harvesting schedule to the database
        $harvestingSchedule = new Schedule();
        $harvestingSchedule->coffee_species = $validatedData['coffeeType'];
        $harvestingSchedule->Date_Set = $harvestDate; // Harvesting date is 5 years later
        $harvestingSchedule->Schedule_Type = 'Harvesting'; // Assuming it's harvesting schedule
        $harvestingSchedule->batch_number = $batchNumber;
        $harvestingSchedule->progress_status = 0; // Set progress_status to 0 initially
        $harvestingSchedule->age = $age; // Set the age
        $harvestingSchedule->location = $validatedData['location']; // Set the location
        $harvestingSchedule->user_id = Auth::id(); 
        $harvestingSchedule->save();

        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Planting and Harvesting schedules saved successfully.');
    }
    

    public function PruneSave(Request $request)
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
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Pruning';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id();
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function PesticideSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Pesticide Spraying';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function DryingSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Drying';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function FermentingSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Fermenting';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Grinding';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function HullingSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Hulling';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Packaging';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function PulpingSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Pulping';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Roasting';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function SortingSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Sorting';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

        public function WateringSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Watering';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    // Weekly Checks Process
    public function WeeklyChecksSave(Request $request)
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
    
        // Check if the entered batch number exists for the selected coffee species
        $batchExists = Schedule::where('coffee_species', $validatedData['coffeeType'])
                            ->where('batch_number', $validatedData['batchNumber'])
                            ->exists();
    
        // If batch number doesn't exist for the selected coffee species, display an error message
        if (!$batchExists) {
            return redirect()->back()->withErrors(['batchNumber' => 'Batch number does not exist for the selected coffee type.'])->withInput();
        }
    
        // Check if the coffee species has been planted yet
        $speciesPlanted = Schedule::where('coffee_species', $validatedData['coffeeType'])->exists();
    
        // If coffee species hasn't been planted yet, display an error message
        if (!$speciesPlanted) {
            return redirect()->back()->withErrors(['coffeeType' => 'Coffee bean species has not been planted yet.'])->withInput();
        }
    
        // If batch number exists, date is valid, and coffee species has been planted, proceed to save the schedule to the database
    
        // Calculate the age based on the current date and the date of planting
        $plantingDate = Schedule::where('coffee_species', $validatedData['coffeeType'])
                                ->where('batch_number', $validatedData['batchNumber'])
                                ->where('Schedule_Type', 'Planting')
                                ->value('Date_Set');
    
        // If the planting date is not found, display an error message
        if (!$plantingDate) {
            return redirect()->back()->withErrors(['batchNumber' => 'No planting record found for the specified batch number.'])->withInput();
        }
    
        // Calculate the age of the coffee plants
        $age = Carbon::parse($validatedData['calendar'])->diffInYears($plantingDate);
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Weekly Checks';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->progress_status = 0;
        $schedule->user_id = Auth::id(); 
        $schedule->age = $age; // Set the age
        $schedule->location = $validatedData['location']; // Set the location
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }
}
