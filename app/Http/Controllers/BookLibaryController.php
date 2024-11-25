<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookLibary;
use App\Models\Library;
use App\Models\Book;
class BookLibaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookLibraries = BookLibary::all();
        $libraries = Library::all();
        $books=Book::all(); 
        return view('booklibray.index', compact('bookLibraries', 'libraries', 'books')); 
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
            'book_id' => 'required|exists:books,id',
            'libraies_id' => 'required|exists:libraies,id', 
            'available_copies' => 'required|integer|min:1|max:255',
        ]);
        try{
                    BookLibary::create($validatedData);
                    return response()->json(['message' => 'Data added successfully']);
        }
        catch(\Exception $e){
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
  
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch all libraries associated with the given book ID
        $bookLibraries = BookLibary::where([['book_id', '=', $id],['available_copies','>',0]])->get();

        // Check if any results were found
        if ($bookLibraries->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No libraries found for the given book ID'], 404);
        }

        // Optionally include related library data for each result (if relationship exists)
        $libraries = $bookLibraries->map(function ($bookLibrary) {
            return $bookLibrary->library; // Assuming the 'library' relationship exists
        });

        return response()->json(['status' => true, 'libraries' => $libraries], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookLibary $bookLibrary) 
    {
        //
    }
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'libraies_id' => 'required|exists:libraies,id', // Fixed table name
            'book_id' => 'required|exists:books,id',
            'available_copies' => 'required|integer|min:1', // Changed 'number' to 'integer' and added a reasonable 'min' value
        ]);

        $bookLibrary = BookLibary::findOrFail($id);
        $bookLibrary->update($validatedData);
        return response()->json(['message' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        BookLibary::findOrFail($id)->delete();

        return response()->json(['message' => 'Data deleted successfully.']);
    }
    
}
