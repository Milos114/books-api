<?php

namespace App\Http\Controllers;

use App\Author;
use App\Transformers\AuthorsTransformer;

class AuthorsController extends Controller
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
//        dd($this->transformer->transform(Author::findOrFail($id)));
        try {
            return response()->json([
                'data' => $this->transformer->transform(Author::findOrFail($id)->toArray()),
                'status' => 'Success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => 'Author not found'
                ]
            ]);
        }
    }

}
