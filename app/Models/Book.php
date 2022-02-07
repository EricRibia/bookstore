<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dispatchesEvents = [
        'updated' => \App\Events\BookUpdated::class,
        'created' => \App\Events\BookUpdated::class,
    ];

    public static function booksWithTheirAuthors($filters = [])
    {
        $books = self::with('author:id,first_name,last_name')->get();

        if (array_key_exists('year_of_publication', $filters)) {

            $books = $books->filter(function ($value) use ($filters) {
                $filter_date = $filters['year_of_publication'];
                $book_date = date("Y", strtotime($value->year_of_publication));
                return $book_date === $filter_date;
            });

        }

        if (array_key_exists('author', $filters)) {

            $books_by_firstname = $books->filter(function ($value) use ($filters) {
                $filter_date = $filters['author'];
                $book_author = $value->author->first_name;
                return $book_author === $filter_date;
            });

            $books_by_lastname = $books->filter(function ($value) use ($filters) {
                $filter_date = $filters['author'];
                $book_author = $value->author->last_name;
                return $book_author === $filter_date;
            });

            $books = $books_by_firstname->merge($books_by_lastname)->unique();
        }
        return $books;
    }
    public function updateBookStock($new_quantity)
    {
        return $this->update([
            'quantity' => $new_quantity,
        ]);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function validateNewQuantity($added_quantity)
    {

        $new_quantity = $this->quantity + $added_quantity;

        if ($new_quantity < 0) {
            $msg = sprintf("operation invalid, stock too low to be reduced: %s remaining", $this->quantity);
            return (object) [
                'message' => $msg,
                'failed' => true,
            ];
        } else {
            return (object) [
                'failed' => false,
                'new_quantity' => $new_quantity,
            ];
        }

    }

}
