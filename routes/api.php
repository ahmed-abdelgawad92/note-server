<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('jwt.auth')->get('/users',[
  'uses' => 'UserController@index'
]);
Route::get('/users/{id}',function($id){
  $json = [
    [
      'id' => '1',
      'name' => 'ahmed',
      'age' => '26',
      'gender' => 'male'
    ],
    [
      'id' => '2',
      'name' => 'thuan',
      'age' => '30',
      'gender' => 'female'
    ],
    [
      'id' => '3',
      'name' => 'eva',
      'age' => '26',
      'gender' => 'female'
    ]
  ];
  return json_encode($json[$id-1]);
});
//sign up
// Route::get('getCSRF',function(){
//   return json_encode();
// });
Route::post('user/create',[
  'uses' => 'UserController@signUp'
]);
Route::post('user/login',[
  'uses' => 'UserController@signIn'
]);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
