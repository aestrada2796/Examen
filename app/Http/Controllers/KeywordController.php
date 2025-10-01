<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $keywords = Keyword::all();
        return response()->json($keywords);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:keywords,name'
        ]);

        $keyword = Keyword::create($request->only('name'));
        return response()->json($keyword, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $keyword = Keyword::findOrFail($id);
        $keyword->delete();
        
        return response()->json(['message' => 'Keyword deleted successfully']);
    }
}
