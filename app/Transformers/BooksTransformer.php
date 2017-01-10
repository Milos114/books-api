<?php


namespace App\Transformers;


class BooksTransformer extends Transformer
{
    public function transform($book)
    {
        return [
            'data' => [
                'title' => $book->title,
                'synopsis' => $book->description,
                'author' => $book->author,
                'created_at' => $book->created_at,
                'updated_at' => $book->updated_at,
            ]
        ];
    }
}