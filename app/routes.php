<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
 
Route::get('/', function()
{
    return View::make('search.index');
});
Route::post('/menuCategories/{restaurantId}', function($restaurantId)
{
    $t = new RestaurantController(new api(Config::get('API.host'),Config::get('API.headers')));
    $t->setRestaurantId($restaurantId);
    echo $t->getDefaultMenuProducts();
});
Route::post('restaurants',function()
{
    $t = new RestaurantController(new api(Config::get('API.host'),Config::get('API.headers')));
    $t->setAreacode($_POST['areacode']);
    echo $t->areaSearch();
});
