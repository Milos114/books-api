<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            return Book::findOrFail($id);
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
        $book = Book::create($request->all());

        return response()->json(['created' => true], 201, [
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
