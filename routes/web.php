<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/threads', 'ThreadController@index')->name('threads');
Route::get('/threads/create', [
	'as' => 'create.thread',
	'uses' => 'ThreadController@create'
]);
Route::post('/threads', 'ThreadController@store')->middleware('isConfirmed');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::get('/threads/search', 'SearchController@show');
Route::put('/threads/{channel}/{thread}', 'ThreadController@update');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::post('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionController@subscribe')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionController@destroy')->middleware('auth');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::get('/threads/{channel}', 'ThreadController@index');
Route::post('threads/{thread}/lock', 'LockThreadController@store')->name('thread.lock')->middleware('administrator');
Route::delete('threads/{thread}/unlock', 'LockThreadController@destroy')->name('thread.open')->middleware('administrator');
Route::delete('threads/{channel}/{thread}', 'ThreadController@destroy');


Route::post('/replies/{reply}/favourite', 'FavouriteController@store');
Route::delete('/replies/{reply}/favourite', 'FavouriteController@destroy');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('reply.destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');
Route::post('replies/{reply}/best', 'BestReplyController@store')->name('best-reply.store');

Route::get('profile/{user}', 'ProfileController@show')->name('profile');
Route::delete('profile/{user}/notifications/{notificationId}', 'UserNotificationController@destroy');
Route::get('profile/{user}/notifications', 'UserNotificationController@index');

Route::get('api/users', 'Api\UserController@index');

Route::post('api/users/{user}/avatar', 'Api\AvatarController@store')
    ->middleware('auth')
    ->name('upload-avatar');

Route::get('register/confirm', 'Auth\RegisterConfirmController@confirm')
    ->name('register.confirm');

Route::get('ctwf', function() {
   factory(\App\Thread::class, 30)->create();

   return redirect(route('threads'))->with('flash', 'Threads created with seeder');
});
