<?php


namespace App\Transformers;


use App\Book;

class BooksTransformer extends Transformer
{
    public function transform($book)
    {
        $author = Book::find($book['id'])->author->name;
        return [
            'title' => $book['title'],
            'synopsis' => $book['description'],
            'author' => $author,
            'created_at' => $book['created_at'],
            'updated_at' => $book['updated_at'],
        ];
    }
}