<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Position;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Positions/Index', ['positions' => Position::all()->map(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "description" => $item->description,
        ])]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        $position = new Position();

        $position->name = $request->name;
        $position->description = $request->description;

        $position->save();

        return redirect()->back()->with('success', 'Position created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $position->name = $request->name;
        $position->description = $request->description;

        $position->save();

        return redirect()->back()->with('success', 'Position updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->back()->with('success', 'Position deleted successfully');
    }
}
