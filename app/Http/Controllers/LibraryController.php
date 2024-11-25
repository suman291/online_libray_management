<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $libraries = Library::all();
        return view('Libray.index',compact('libraries'));
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'long' => 'required|numeric',
            'lat' => 'required|numeric',
        ]);

        // Create a new library record
        $library = Library::create([
            'name' => $validatedData['name'],
            'location' => $validatedData['location'],
            'long' => $validatedData['long'],
            'lat' => $validatedData['lat'],
        ]);

        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Library added successfully!',
            'library' => $library,  // You can return the new library object if you need to display it
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $library = Library::findOrFail($id);
        $library->update($request->only(['name', 'location', 'long', 'lat']));

        return response()->json(['message' => 'Library updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Library::findOrFail($id)->delete();

        return response()->json(['message' => 'Library deleted successfully.']);
    }
    public function getLibraries()
    {
        $libraries = Library::all();
        return response()->json($libraries);
    }

   
}
