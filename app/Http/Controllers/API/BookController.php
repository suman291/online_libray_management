<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books["books"] = Book::all();
        return response()->json(["status"=>true,"books"=>$books],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'publication' => 'required|string|max:255',
            'year' => 'required|date|date_format:Y-m-d'
        ]);

        // Check if validation fails
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Book Store Failed',
                'errors' => $validate->errors()->all()
            ], 422);
        }

        // Retrieve the validated data and create a new book
        $validatedData = $validate->validated();
        $book=Book::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Book added successfully',
            'book' => $book
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $validate=Validator::make(["id"=>$id], [
            "id"=>"required|integer"
        ]);
        if($validate->fails()){
            return response()->json(["status"=>false,"message"=>"Invalid id"],404);
        }
        try{
               $books["book"] = Book::findOrFail($id);
                return response()->json(["status" => true, "books" => $books], 200);
        }
        catch(\Exception $e){
            return response()->json(["status"=>false, "message"=>"Book Not Found,Check Book ID "], 404);
        }
     
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($id) {
            $request->merge(["id"=>$id]);
        $validate = Validator::make($request->all(), [
            'id'=>'required|integer',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'publication' => 'required|string|max:255',
            'year' => 'required|date|date_format:Y-m-d'
        ]);
        if ($validate->fails()) {
            return response()->json(["status" => false, "message" => "Invalid request","error"=> $validate->errors()->all()], 404);
        }
        try {
            $books=Book::where(['id'=>$request->id])->update(['title' => $request->title,'author' =>$request->author,'genre' =>$request->genre,'publication' =>$request->publication,'year' =>$request->year]);
            return response()->json(["status" => true,"message" => "Books updated successfully", "books_id" => $books], 200);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Book Not Found,Check Book ID "], 404);
        }
    }else{
        return response()->json(["status" => false, "message" => "Invalid request"], 404);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if($id){
            $book=Book::findOrFail()->delete();
            return response()->json(["status" => true, "message" => "Book deleted successfully"], 200);
        }
        else{
            return response()->json(["status" => false, "message" => "Invalid request"], 404);
        }
    }
}
