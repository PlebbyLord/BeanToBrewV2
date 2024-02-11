<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $schedules = Schedule::all();
    
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

    public function water()
    {
        return view('Features.Schedules.watering');
    }
    
    public function PlantingSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'calendar' => 'required|date',
        ]);
    
        // Calculate the best date for harvest (add 4 years to the selected date)
        $bestDateForHarvest = Carbon::parse($request->input('calendar'))->addYears(4)->format('Y-m-d');
    
        // Determine the batch number based on existing records
        $existingRecordsCount = Schedule::where('coffee_species', $validatedData['coffeeType'])->count();
        $batchNumber = $existingRecordsCount + 1;
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Harvest_Date = $bestDateForHarvest;
        $schedule->Schedule_Type = 'Planting'; // Assuming it's planting schedule
        $schedule->batch_number = $batchNumber;
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }

    public function WaterSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'batchNumber' => 'required|integer',
            'calendar' => 'required|date|after_or_equal:today', // Ensure the date is not in the past
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
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Watering';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    }
    
    public function PruneSave(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'coffeeType' => 'required|in:arabica,excelsa,liberica,robusta',
            'batchNumber' => 'required|integer',
            'calendar' => 'required|date|after_or_equal:today', // Ensure the date is not in the past
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
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Pruning';
        $schedule->batch_number = $validatedData['batchNumber'];
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
    
        // Save the schedule to the database
        $schedule = new Schedule();
        $schedule->coffee_species = $validatedData['coffeeType'];
        $schedule->Date_Set = $validatedData['calendar'];
        $schedule->Schedule_Type = 'Pesticide Spraying';
        $schedule->batch_number = $validatedData['batchNumber'];
        $schedule->save();
    
        // Redirect or respond accordingly
        return redirect()->back()->with('success', 'Schedule saved successfully.');
    } 
}
