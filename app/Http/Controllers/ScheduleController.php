<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function getSchedules(Request $request)
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

    public function addOrEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'creator_id' => 'required|numeric|exists:users,id',
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

        $schedule = Schedule::updateOrCreate(["id" => $request->id], $request->except(['created_at', 'updated_at']));
        $schedule->load('creator');

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function getASchedule($id)
    {
        $schedule = Schedule::with('creator_id')->find($id);
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

    public function deleteASchedule($id)
    {
        $schedule = Schedule::find($id);

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
