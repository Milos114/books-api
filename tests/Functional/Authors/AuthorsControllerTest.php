<?php


use App\Author;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorsControllerTest extends \TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function index_responds_with_200_status_code()
    {
        $this->get('/authors')->seeStatusCode(Response::HTTP_OK);
    }

    /** @test */
    public function index_should_return_a_collection_of_records()
    {
        factory(Author::class, 2)->create();

        $this->get('/authors');
        $body = json_decode($this->response->content(), true);

        foreach ($body['data'] as $author) {
            $this->seeJson([
                'name' => $author['name'],
                'gender' => $author['gender'],
                'biography' => $author['biography'],
                'created' => $author['created'],
                'updated' => $author['updated']
            ]);
        }

        $this->assertArrayHasKey('data', $body);
        $this->assertArrayHasKey('status', $body);
        $this->assertArrayHasKey('message', $body['status']);
    }

    /** @test */
    public function show_should_return_a_valid_author()
    {
        $author = factory(Author::class)->create();

        $this->get("/authors/$author->id");

        $this->seeJson([
            'name' => $author->name,
            'gender' => $author->gender,
            'biography' => $author->biography,
            'created_at' => $author->created_at->toDateTimeString(),
            'updated_at' => $author->updated_at->toDateTimeString(),
        ])->seeStatusCode(200);
    }
}