<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Part;
use Inertia\Inertia;

class PartController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'serial_number' => ['required', 'string', 'max:255'],
                'car_id' => ['required', 'integer'],
            ]);

            Part::create([
                'name' => $validated['name'],
                'serial_number' => $validated['serial_number'],
                'car_id' => $validated['car_id'],
                'searchName' => strtolower($validated['name']),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Part successfully created.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create part.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $part = Part::findOrFail($id);
            return Inertia::render('Parts/EditPart', [
                'part' => $part,
            ]);
        } catch (\Exception $e) {
            return Inertia::render(route('dashboard.parts'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $part = Part::findOrFail($id);
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'serial_number' => ['required', 'string', 'max:255'],
                'car_id' => ['required', 'integer'],
            ]);

            $part->update([
                'name' => $validated['name'],
                'serial_number' => $validated['serial_number'],
                'car_id' => $validated['car_id'],
                'searchName' => strtolower($validated['name']),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Part successfully updated.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update part.',
            ]);
        }
    }


    public function destroy($id)
    {
        try {
            $part = Part::findOrFail($id);
            $part->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Part successfully deleted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete part.',
            ]);
        }
    }

    public function search($search)
    {
        try{
            $parts = Part::whereRaw('searchName LIKE ?', [strtolower($search) . '%'])->get();
            return response()->json([
                'status' => 'success',
                'parts' => $parts,
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search parts. Please try again later.',
            ]);
        }
        
    }

    public function show($id)
    {
        try {
            $part = Part::findOrFail($id);
            return Inertia::render('Parts/ShowPart', [
                'part' => $part,
            ]);
        } catch (\Exception $e) {
            return Inertia::render(route('dashboard.parts'));
        }
    }
}
