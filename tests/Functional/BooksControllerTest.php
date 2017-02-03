<?php

use App\Book;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function index_status_code_should_be_200()
    {
        factory(Book::class, 2)->create();

        $this->get('/books');
        $data = json_decode($this->response->getContent(), true);
"some master code";
        $this->assertEquals($this->response->getStatusCode(), 200);
        $this->seeJson(['message' => 'Success'])
            ->assertArrayHasKey('data', $data);
        "trarararlala test";
    }

    /** @test * */
    public function show_should_return_a_valid_book()
    {
        $book = factory(Book::class)->create();

        $this->get("books/$book->id")
            ->seeStatusCode(200)
            ->seeJson([
                'title' => $book->title,
                'synopsis' => $book->description,
//                'author' => $book->author->name
            ]);
        $data = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }

    /** @test * */
    public function show_should_fail_when_the_book_id_does_not_exist()
    {
        $this->get('books/12222')
            ->seeStatusCode(404)
            ->seeJson([
                'error' => [
                    'message' => 'Book not found',
                    'status_code' => 404
                ]
            ]);
    }

    /** @test * */
    public function show_should_accept_only_numeric_values()
    {
        $this->get('books/not-numeric')
            ->seeStatusCode(404)
            ->seeJson([
                'error' => [
                    'message' => 'Book not found',
                    'status_code' => 404
                ]
            ]);
    }

    /** @test * */
    public function show_route_should_not_match_an_invalid_route()
    {
        $this->get('/books/invalid-route')->seeJsonEquals([
            'error' => [
                'message' => 'Book not found',
                'status_code' => 404
            ]
        ])->seeStatusCode(404);
    }

    /** @test * */
    public function store_should_save_new_book_in_the_database()
    {
        $book = factory(Book::class)->make(['id' => 1]);

        $this->post('/books', [
            'title' => $book->title,
            'description' => $book->description,
            'author_id' => $book->author->id,
        ]);

        $this->seeInDatabase('books', [
            'title' => $book->title,
            'description' => $book->description,
            'author_id' => $book->author->id,
        ])->seeJson(['created' => true])
            ->seeStatusCode(201)
            ->seeHeader('location', 'http://' . env('APP_DOMAIN') . "/books/$book->id");
    }

    /** @test * */
    public function update_should_only_change_fillable_fields()
    {
        $book = factory(Book::class)->create();

        $this->put("books/$book->id", [
            'title' => 'Changed title',
            'description' => 'Changed description',
            'author_id' => $book->author->id,
        ]);

        $this->seeInDatabase('books', [
            'title' => 'Changed title',
            'description' => 'Changed description',
            'author_id' => $book->author->id,
        ])->seeJson([
            'updated' => true
        ])->seeStatusCode(200);
    }

    /** @test * */
    public function update_should_fail_with_an_invalid_id()
    {
        $this->put('books/111111111')->seeStatusCode(404)->seeJson([
            'error' => [
                'message' => 'This book does not exist',
                'status_code' => 404
            ]
        ]);
    }

    /** @test * */
    public function update_should_not_match_an_invalid_route()
    {
        $this->put('/books/ivalid-uri')
            ->seeStatusCode(404);
    }

    /** @test * */
    public function destroy_should_remove_a_valid_book()
    {
        $book = factory(Book::class)->create();

        $this->delete("/books/$book->id")->notSeeInDatabase('books', [
            'id' => $book->id
        ])->seeStatusCode(204)->seeJson([
            'deleted' => true
        ]);
    }

    /** @test * */
    public function destroy_should_return_a_404_with_an_invalid_id()
    {
        $this->delete('books/11111111')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Book not found',
                    'status_code' => 404
                ]
            ]);
    }

    /** @test * */
    public function destroy_should_not_match_an_invalid_route()
    {
        $this->delete('books/invalid-book-name')->seeStatusCode(404);
    }

}
