<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use App\Models\Library;

class BookLibary extends Model
{
        protected $table = 'book_library';
        protected $fillable = ['book_id', 'libraies_id', 'available_copies'];
        public function book()
        {
            return $this->belongsTo(Book::class, 'book_id');
        }
        public function library()
        {
            return $this->belongsTo(Library::class, 'libraies_id');
        }
}