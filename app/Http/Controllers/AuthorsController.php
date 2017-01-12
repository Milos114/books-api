<?php

namespace App\Http\Controllers;

use App\Author;
use App\Transformers\AuthorsTransformer;

class AuthorsController extends ApiController
{
    /**
     * @var AuthorsTransformer
     */
    protected $transformer;

    /**
     * @param AuthorsTransformer $transformer
     */
    public function __construct(AuthorsTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return response()->json([
            'data' => $this->transformer->transformCollection(Author::all()->toArray()),
            'status' => [
                'message' => 'Success'
            ]
        ], 200);
    }

    public function show($id)
    {
        try {
            return response()->json([
                'data' => $this->transformer->transform(Author::findOrFail($id)->toArray()),
                'status' => [
                    'message' => 'Success'
                ]
            ], 200);
        } catch (\Exception $e) {
            return $this->respondNotFound('Author not found');
        }
    }

}
