<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    protected $fillable = [
        'title',
        'author',
        'genre',
        'publication',
        'year',
        'availability',
    ];
    // public function libraries()
    // {
    //     return $this->belongsTo(Library::class, 'library_id_fk');
    // }
}
