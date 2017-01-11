<?php


namespace App\Transformers;


class AuthorsTransformer extends Transformer
{
    public function transform($author)
    {
        return [
            'name' => $author['name'],
            'gender' => $author['gender'],
            'biography' => $author['biography'],
            'created_at' => $author['created_at']->toDateTimeString(),
            'updated_at' => $author['updated_at']->toDateTimeString(),
        ];
    }
}