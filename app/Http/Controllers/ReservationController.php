<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Reservation as ModelsReservation;
use App\Models\BookLibary;
use App\Models\Library;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->role == 'admin'):
            $getreservations=ModelsReservation::all();
            $books=Book::where('availability', 'yes')->get();
            return view('reservations.index', compact('getreservations', 'books'));
        else:
            $userid = Auth::user()->id;
            $getreservations = ModelsReservation::where('user_id', $userid)->get();
            $books = Book::where('availability', 'yes')->get();
            return view('reservations.index', compact('getreservations', 'books'));
        endif;
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
        $reservationCheck = ModelsReservation::where([
            ["user_id" , $request->user_id],
            ['book_id', $request->book_id],
            ['libraies_id', $request->libraies_id],
            ['status', 'reserved']
        ])->exists();
        if(!$reservationCheck):
        // Ensure book_id exists
        if ($request->has('book_id')) {
            // Fetch book and check availability
            $getBook= Book::find($request->book_id);

            if ($getBook && $getBook->availability === "yes") {
                // Validate incoming request data
                $validate = Validator::make($request->all(), [
                    "user_id" => "required",
                    "libraies_id" => "required",
                    "book_id" => "required",
                ]);

                if ($validate->fails()) {
                    return response()->json([
                        "status" => false,
                        "message" => "Validation Error",
                        "errors" => $validate->errors()->all(),
                    ], 422);
                }

                // Fetch the BookLibrary record
                $bookLibrary = BookLibary::where([
                    ['book_id', $request->book_id],
                    ['libraies_id', $request->libraies_id],
                ])->first();

                if (!$bookLibrary || $bookLibrary->available_copies <= 0) {
                    return response()->json([
                        "status" => false,
                        "message" => "The book is not available for reservation.",
                    ], 400);
                }

                // Decrement the available copies atomically
                $bookLibrary->decrement('available_copies');
                if ($bookLibrary->available_copies == 0) {
                   $getBook->update(['availability' => 'no']);
                }
                // Create the reservation
                ModelsReservation::create([
                    "user_id" => $request->user_id,
                    "libraies_id" => $request->libraies_id,
                    "book_id" => $request->book_id,
                ]);

                return response()->json([
                    "status" => true,
                    "message" => "Reservation created successfully.",
                ], 200);
            }

            return response()->json([
                "status" => false,
                "message" => "The book is not available for reservation.",
            ], 400);
        }

        return response()->json([
            "status" => false,
            "message" => "Book ID is required.",
        ], 400);
    else:
            return response()->json([
                "status" => false,
                "message" => "Book already reserved by you.",
            ], 400);
    endif;
    }



    /**
     * Display the specified resource.
     */
    public function show(ModelsReservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModelsReservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required",
            "libraies_id" => "required", // Corrected spelling
            "book_id" => "required",
            "status" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validator->errors()->all(),
            ], 422);
        }

        // Fetch book and check availability
        $getBook = Book::find($request->book_id);

        if (!$getBook) {
            return response()->json([
                "status" => false,
                "message" => "Book not found.",
            ], 404);
        }

        // Fetch the BookLibrary record
        $bookLibrary = BookLibary::where([
            ['book_id', $request->book_id],
            ['libraies_id', $request->libraies_id],
        ])->first();

        if (!$bookLibrary) {
            return response()->json([
                "status" => false,
                "message" => "BookLibrary record not found.",
            ], 404);
        }

        // Update availability and increment copies based on the book's status
        if ($getBook->availability === "no") {
            $getBook->update(['availability' => 'yes']);
        }

        // Increment available copies
        $bookLibrary->increment('available_copies');

        // Update the reservation
        ModelsReservation::where("id", $id)->update([
            "user_id" => $request->user_id,
            "libraies_id" => $request->libraies_id,
            "book_id" => $request->book_id,
            "status" => "returned",
        ]);

        return response()->json([
            "status" => true,
            "message" => "Reservation updated successfully.",
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelsReservation $reservation)
    {
        //
    }
}
