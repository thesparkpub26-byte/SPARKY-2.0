<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        return response()->json(Section::withCount(['articles'])->orderBy('name')->get());
    }

    public function show(Section $section)
    {
        return response()->json($section->load(['articles']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:sections',
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:20',
        ]);

        $section = Section::create($validated);
        return response()->json($section, 201);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255|unique:sections,name,' . $section->id,
            'description' => 'nullable|string',
            'color'       => 'nullable|string|max:20',
        ]);

        $section->update($validated);
        return response()->json($section);
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return response()->json(['message' => 'Section deleted.']);
    }
}
