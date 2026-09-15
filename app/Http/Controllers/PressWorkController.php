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
        return response()->json(PressWork::with('monitoringSheets')->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
        ]);

        $pressWork = DB::transaction(function () use ($request, $validated) {
            $pressWork = PressWork::create([
                ...$validated,
                'created_by' => $request->user()->id,
            ]);

            foreach (self::TYPES as $type) {
                $pressWork->monitoringSheets()->create([
                    'publication_type' => $type,
                    'title' => $pressWork->title . ' - ' . $type,
                ]);
            }

            Activity::record($request->user(), 'Created a press work', $pressWork);
            return $pressWork;
        });

        return response()->json($pressWork->load('monitoringSheets'), 201);
    }
}
