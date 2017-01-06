<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 6.1.17.
 * Time: 16.26
 */

namespace App\Transformers;


class BooksTransformer extends Transformer
{
    public function transform($book)
    {
        return [
            'title' => $book->title,
            'synopsis' => $book->description,
            'author' => $book->author,
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,
        ];
    }
}