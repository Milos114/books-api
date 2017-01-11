<?php

use App\Book;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\Book::class, 5)->create()->each(function ($u) {
//            $u->author()->save(factory(App\Author::class)->make());
//        });
        factory(Book::class, 5)->create();
    }
}
