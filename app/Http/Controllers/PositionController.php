<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::latest()->get();
        return view('admin.role', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position_name' => 'required|string|max:255|unique:positions',
            'description' => 'nullable|string'
        ]);

        Position::create($request->all());

        return redirect()->back()->with('success', 'Position created successfully.');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'position_name' => 'required|string|max:255|unique:positions,position_name,' . $position->id,
            'description' => 'nullable|string'
        ]);

        $position->update($request->all());

        return redirect()->back()->with('success', 'Position updated successfully.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return response()->json(['success' => 'Position deleted successfully.']);
    }
} 