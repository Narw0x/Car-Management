<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;


class CarController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['required_if:is_registered', 'string', 'max:17'],
            'is_registered' => ['required', 'boolean'],
        ]);

        $car = Car::create($request->all());

        return redirect()->route('car.show', $car); 
    }
}
