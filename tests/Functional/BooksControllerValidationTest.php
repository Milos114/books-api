<?php

use App\Book;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test * */
    public function it_validates_required_fields_when_creating_a_new_book()
    {
        $this->post('/books', [], ['Accept' => 'application/json']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('title', $body);
        $this->assertArrayHasKey('description', $body);

        $this->assertEquals(["The title field is required."], $body['title']);
        $this->assertEquals(["Please fill out the description."], $body['description']);

    }

    /** @test * */
    public function it_validates_requied_fields_when_updating_a_book()
    {
        $book = factory(Book::class)->create();
        $this->put("/books/$book->id", [], ['Accept' => 'application/json']);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('title', $body);
        $this->assertArrayHasKey('description', $body);

        $this->assertEquals(["The title field is required."], $body['title']);
        $this->assertEquals(["Please fill out the description."], $body['description']);
    }

    /** @test * */
    public function it_validates_fields_length_when_creating_a_book()
    {
        $book = factory(Book::class)->make([
            'title' => str_repeat('a', 256),
            'description' => 'some description',
        ]);

        $this->post("/books/", [
            'title' => $book->title,
            'description' => $book->description,
        ], ['Accept' => 'application/json']);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
        $this->notSeeInDatabase('books', ['id' => $book->id]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertEquals(["The title may not be greater than 255 characters."], $body['title']);
    }

    /** @test * */
    public function title_passes_create_validation_when_exactly_max()
    {
        $book = factory(Book::class)->make([
            'id' => 1,
            'title' => str_repeat('a', 255),
            'description' => 'some description',
        ]);

        $this->post("/books/", [
            'title' => $book->title,
            'description' => $book->description,
            'author_id' => $book->author->id
        ], ['Accept' => 'application/json']);

        $this->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['created' => true])
            ->seeInDatabase('books', ['title' => $book->title]);
    }

    /** @test * */
    public function title_fail_create_validation_when_author_id_is_not_int()
    {
        $book = factory(Book::class)->make([
            'id' => 1,
            'title' => 'title',
            'description' => 'some description',
        ]);

        $this->post("/books/", [
            'title' => $book->title,
            'description' => $book->description,
            'author_id' => 'string'
        ], ['Accept' => 'application/json']);

        $body = json_decode($this->response->getContent(), true);

        $this->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->notSeeInDatabase('books', ['title' => $book->title])
            ->assertEquals(["The author id must be an integer."], $body['author_id']);
    }

    /** @test * */
    public function title_passes_update_validation_when_exactly_max()
    {
        $book = factory(Book::class)->create([
            'title' => str_repeat('a', 255)
        ]);

        $this->put("/books/$book->id", [
            'title' => $book->title,
            'description' => 'Some description',
            'author_id' => $book->author->id
        ], ['Accept' => 'application/json']);

        $this->seeStatusCode(Response::HTTP_OK)
            ->seeJson(['updated' => true])
            ->seeInDatabase('books', ['title' => $book->title]);
    }

    /** @test * */
    public function it_fails_update_validation_when_author_id_is_not_int()
    {
        $book = factory(Book::class)->create();

        $this->put("/books/$book->id", [
            'title' => 'title',
            'description' => 'description',
            'author_id' => 'string'
        ], ['Accept' => 'application/json']);

        $body = json_decode($this->response->getContent(), true);

        $this->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->notSeeInDatabase('books', ['title' => 'title', 'description' => 'description'])
            ->assertEquals(["The author id must be an integer."], $body['author_id']);
    }
}
