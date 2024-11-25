<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
class Library extends Model
{
    use HasFactory;
    protected $table = 'libraies';

    protected $fillable = [
    'name',
    'location',
    'long',
    'lat',
    ];

    // public function books()
    // {
    // return $this->hasMany(Book::class);
    // }
}