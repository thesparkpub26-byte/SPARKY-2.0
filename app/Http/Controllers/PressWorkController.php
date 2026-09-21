<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\PressWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PressWorkController extends Controller
{
    private const TYPES = ['Newsletter', 'Tabloid', 'Magazine', 'Litfolio'];

    public function index()
    {
        $pressWorks = PressWork::with('monitoringSheets')->latest()->get();
        
        // Group by academic year and flatten monitoring sheets
        $grouped = $pressWorks->groupBy('academic_year')->map(function ($works, $year) {
            $monitoringSheets = $works->flatMap(function ($work) {
                return $work->monitoringSheets->map(function ($sheet) use ($work) {
                    return [
                        'id' => $sheet->id,
                        'publication_type' => $sheet->publication_type,
                        'title' => $sheet->title,
                        'status' => $sheet->status,
                        'created_at' => $sheet->created_at,
                        'press_work' => [
                            'id' => $work->id,
                            'title' => $work->title,
                            'academic_year' => $work->academic_year,
                        ]
                    ];
                });
            });

            return [
                'academic_year' => $year,
                'monitoring_sheets' => $monitoringSheets
            ];
        })->values();

        return response()->json(['academic_years' => $grouped]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
        ]);

        // Check if academic year already exists
        $existingPressWork = PressWork::where('academic_year', $validated['academic_year'])->first();
        if ($existingPressWork) {
            return response()->json([
                'message' => 'An academic year with this name already exists.'
            ], 409);
        }

        $pressWork = DB::transaction(function () use ($request, $validated) {
            // Create the first issue for the academic year
            $pressWork = PressWork::create([
                'title' => 'Issue 1',
                'academic_year' => $validated['academic_year'],
                'created_by' => $request->user()->id,
            ]);

            // Create the 4 monitoring sheets
            foreach (self::TYPES as $type) {
                $pressWork->monitoringSheets()->create([
                    'publication_type' => $type,
                    'title' => $pressWork->title . ' - ' . $type,
                ]);
            }

            Activity::record($request->user(), 'Created a new academic year', $pressWork);

            try {
                $eics = \App\Models\User::where('role', 'eic')->get();
                foreach ($eics as $eic) {
                    \App\Models\Notification::create([
                        'user_id' => $eic->id,
                        'title'   => 'Academic Year Created',
                        'message' => "Academic year {$pressWork->academic_year} was set up with monitoring sheets.",
                        'type'    => 'press_work_update',
                        'data'    => ['press_work_id' => $pressWork->id],
                    ]);
                }
            } catch (\Throwable $e) {}

            return $pressWork;
        });

        return response()->json([
            'press_work' => $pressWork->load('monitoringSheets'),
            'academic_year' => $pressWork->academic_year
        ], 201);
    }

    public function destroyByYear(Request $request, $year)
    {
        $pressWork = PressWork::where('academic_year', $year)->first();
        
        if (!$pressWork) {
            return response()->json(['message' => 'Academic year not found'], 404);
        }

        DB::transaction(function () use ($pressWork, $request) {
            Activity::record($request->user(), 'Deleted academic year', $pressWork);
            $pressWork->delete();
        });

        return response()->json(['message' => 'Academic year deleted successfully']);
    }
}
