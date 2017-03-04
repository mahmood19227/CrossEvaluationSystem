<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/login','UserController@authenticate');

Route::get('/', function () {
    return view('welcome');

});
Route::get('/register_presentation', 'PresentationController@registerPresentationForm');

Route::get('/do_register_presentation', 'PresentationController@do_register');

Route::get('/view_open_presentations','PresentationController@viewOpenPresentations');

Route::get('/evaluate_presentation','PresentationController@evaluatePresentation');

Route::get('/do_register_evaluations','PresentationController@registerEvaluations');

Route::get('/home', 'HomeController@index')->name('home');

Route::auth();

//Admin Routes

Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function()
{
    Route::get('/admin','Admin\HomeController@index');
    Route::get('/admin/presentations','Admin\PresentationController@viewPresentations');
    Route::get('/admin/open_evaluation/{id}/{period}/{offset}','Admin\PresentationController@openEvaluation');
});

//Route::get('/admin/home', 'Admin\HomeController@index');