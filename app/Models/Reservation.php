<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use App\Models\User;
use App\Models\Library;
class Reservation extends Model
{
    protected $table= "reservations";
    protected $fillable = ['user_id', 'book_id', 'libraies_id', 'reserved_at', 'status'];  
    public function bookName(){
        return $this->belongsTo(Book::class,'book_id');
    }
    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function libraryDetails()
    {
        return $this->belongsTo(Library::class, 'libraies_id');
    }
}
