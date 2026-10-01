<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\PressWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PressWorkController extends Controller
{
    private const TYPES = ['Newsletter', 'Tabloid', 'Magazine', 'Litfolio'];

    /**
     * Lists the academic years with their monitoring sheets (Newsletter, Tabloid, Magazine, Litfolio), newest
     * first.
     */
    public function index()
    {
        $user = auth()->user();
        $pressWorks = PressWork::with('monitoringSheets')->latest()->get();

        // For staff writers, staff artists, staff broadcasters, and section editors:
        // Show all press works (both current and newly created ones)
        // Since they only get access to press works that are actively created/maintained,
        // we don't need to filter by historical academic years
        if ($user && in_array($user->role, ['staff_writer', 'staff_artist', 'staff_broadcaster', 'section_editor'])) {
            // All press works are accessible to active staff members
            // (The EIC only creates new academic years when they need active staff to contribute)
        }

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

    /**
     * Creates an academic year with its four monitoring sheets and notifies the Editors-in-Chief; a year can
     * exist only once.
     */
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
                    'title' => $type,
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

    /** Deletes an academic year together with its monitoring sheets and entries. */
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
