<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use Inertia\Inertia;



class CarController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'registration_number' => ['nullable', 'string', 'max:17', 'required_if:is_registered,true'],
                'is_registered' => ['required', 'boolean'],
            ]);

            if ($validated['is_registered'] && Car::where('registration_number', $validated['registration_number'])->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Car with this registration number already exists.',
                ]);
            }

            Car::create([
                'name' => $validated['name'],
                'registration_number' => $validated['registration_number'],
                'is_registered' => $validated['is_registered'],
                'searchName' => strtolower($validated['name']),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Car successfully created.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create car.',
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $car = Car::findOrFail($id);
            return Inertia::render('Cars/EditCar', [
                'car' => $car,
            ]);
        } catch (\Exception $e) {
            return Inertia::render(route('dashboard.cars'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'registration_number' => ['nullable', 'string', 'max:17', 'required_if:is_registered,true'],
                'is_registered' => ['required', 'boolean'],
            ]);

            if ($validated['is_registered'] && Car::where('registration_number', $validated['registration_number'])->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Car with this registration number already exists.',
                ]);
            }

            $car = Car::findOrFail($id);
            $car->update([
                'name' => $validated['name'],
                'registration_number' => $validated['registration_number'],
                'is_registered' => $validated['is_registered'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Car successfully updated.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update car. Please try again later.',
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $car = Car::findOrFail($id);
            $car->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Car successfully deleted.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete car. Please try again later.',
            ]);
        }
    }


    public function search($search)
    {
        try{
            $cars = Car::whereRaw('searchName LIKE ?', [strtolower($search) . '%'])->get();
            return response()->json([
                'status' => 'success',
                'cars' => $cars,
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search cars. Please try again later.',
            ]);
        }
        
    }

    public function show($id)
    {
        try {
            $car = Car::with('parts')->findOrFail($id);
            return Inertia::render('Cars/ViewCar', [
                'car' => $car,
            ]);
        } catch (\Exception $e) {
            return Inertia::render(route('dashboard.cars'));
        }
    }

    public function car($id)
    {
        try {
            $car = Car::findOrFail($id);
            return Inertia::render('Cars/ViewCar', [
                'car' => $car,
            ]);
        } catch (\Exception $e) {
            return Inertia::render(route('dashboard.cars'));
        }
    }
}
