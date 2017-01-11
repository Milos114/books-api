<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/hello/{name}', ['middleware' => 'hello', function ($name) {
    return "Hello $name";
}]);

$app->get('/books', 'BooksController@index');
$app->post('/books', 'BooksController@store');
$app->get('/books/{id}', ['as' => 'books.show', 'uses' =>'BooksController@show']);
$app->put('/books/{id}', 'BooksController@update');
$app->delete('/books/{id}', 'BooksController@delete');

$app->get('/authors', 'AuthorsController@index');
$app->get('/authors/{id}', 'AuthorsController@show');

