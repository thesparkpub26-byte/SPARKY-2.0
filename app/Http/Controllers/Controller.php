<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Adds a ->where() for every ?param that is present. A malformed value (an array, or text where an id
     * belongs) matches nothing instead of crashing or being ignored, and values are always bound as query
     * parameters, never spliced into SQL.
     *
     * @param array<string, string> $textFilters  ?param => column
     * @param array<string, string> $idFilters    ?param => column (whole numbers only)
     */
    protected function filterBy($query, Request $request, array $textFilters, array $idFilters = []): void
    {
        foreach ($textFilters as $param => $column) {
            if (!$request->has($param)) continue;

            $value = $request->input($param);
            is_string($value) ? $query->where($column, $value) : $query->whereRaw('0 = 1');
        }

        foreach ($idFilters as $param => $column) {
            if (!$request->has($param)) continue;

            $value = $request->input($param);
            (is_string($value) || is_int($value)) && ctype_digit((string) $value)
                ? $query->where($column, (int) $value)
                : $query->whereRaw('0 = 1');
        }
    }
}
