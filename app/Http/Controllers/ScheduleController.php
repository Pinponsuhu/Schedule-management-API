<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function create(Request $request){
        $schedule = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'scheduled_date' => 'required|date',
            'from' => 'required',
            'to' => 'required'
        ]);

        $schedule = Schedule::create($schedule);

        return response()->json([
            'message' => 'Schedule Added Successfully',
            'attribute' => [
                $schedule
            ]
            ]);
    }
    public function all(){
        $schedule = Schedule::get();
        return response()->json([
            'data'=> $schedule
            ]);
    }
    public function show($schedule){
        $schedule = Schedule::find($schedule);
        return response()->json([
            'data' => [
                'id' => (string) $schedule->id,
                'attributes' => [
                    'title' => $schedule->title,
                    'description' => $schedule->description,
                    'scheduled_date' => $schedule->scheduled_date,
                    'from' => $schedule->from,
                    'to' => $schedule->to,
                    'created_at' => $schedule->created_at,
                ]
            ]
        ]);
    }

    public function destroy($schedule){
        $schedule = Schedule::find($schedule);
        $schedule->delete();

        return response()->json([
            'message' => 'Schedule Deleted Successfully'
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'scheduled_date' => 'required|date',
            'from' => 'required',
            'to' => 'required'
        ]);
        $schedule = Schedule::find($request->schedule);
        $schedule->title = $request->title;
        $schedule->description = $request->description;
        $schedule->scheduled_date = $request->scheduled_date;
        $schedule->from = $request->from;
        $schedule->to = $request->to;
        $schedule->save();

        return response()->json([
            'data' => [
                'id' => (string) $schedule->id,
                'attributes' => [
                    'title' => $schedule->title,
                    'description' => $schedule->description,
                    'scheduled_date' => $schedule->scheduled_date,
                    'from' => $schedule->from,
                    'to' => $schedule->to,
                    'created_at' => $schedule->created_at,
                ]
            ]
                ]);
    }
}
