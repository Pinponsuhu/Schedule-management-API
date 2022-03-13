<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        $perPage = 2;
        $page = 1;
        $schedule = Schedule::with('creator')->orderBy('created_at', 'desc')
            ->paginate($perPage, array('*'), 'page', $page);
        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function store(Request $request)
    {
        $eventExists = Schedule::where([
            'title' => $request->title,
            'from' => $request->from,
            'to' => $request->to,
        ])->exists();

        if ($eventExists) {
            return response()->json([
                'success' => false,
                'message' => 'Event already exists.',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'added_by' => 'required|numeric|exists:users,id',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $schedule = Schedule::create($request->all());
        $schedule->load('creator');

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function show($schedule)
    {
        $schedule = Schedule::with('creator')->find($schedule);
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $schedule = Schedule::find($schedule->id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule does not exist.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ], 422);
        }

        $schedule->update($request->all());
        $schedule->load('creator');

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule = Schedule::find($schedule->id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule does not exist.',
            ], 404);
        }

        if ($schedule->delete()) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule deleted.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }

    }
}
