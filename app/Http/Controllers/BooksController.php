<?php

namespace App\Http\Controllers;

use App\Book;
use App\Transformers\BooksTransformer;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    protected $transformer;

    /**
     * @param BooksTransformer $transformer
     */
    public function __construct(BooksTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return $this->transformer->transformCollection(Book::all());
    }

    /**
     * @param  $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->transformer->transform(Book::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author' => 'required'
        ], [
            'description.required' => 'Please fill out the description.'
        ]);

        $book = Book::create($request->all());

        return response()->json([
            'created' => true
        ], 201, [
            'Location' => route('books.show', ['id' => $book->id])
        ]);
    }

    /**
     * @param  Request $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => 'This book does not exist'
                ]
            ], 404);
        }

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'author' => 'required',
        ], [
            'description.required' => 'Please fill out the description.'
        ]);

        $book->update($request->all());

        return response()->json([
            'updated' => true
        ], 200);
    }

    /**
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            Book::findOrFail($id)->delete();
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }

        return response()->json([
            'deleted' => true
        ], 204);
    }
}
