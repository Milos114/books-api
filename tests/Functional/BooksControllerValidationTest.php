<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BooksControllerValidationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test * */
    public function it_validates_required_fields_when_creating_a_new_book()
    {
        $this->post('/books', [], ['Accept' => 'application/json']);
    }

    /** @test * */
    public function it_validates_requied_fields_when_updating_a_book()
    {
    }
}
