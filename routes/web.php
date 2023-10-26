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

Auth::routes();

Route::get('label/get-table-datas', 'LabelController@getTableDatas');
Route::post('label/new', 'LabelController@store');
Route::post('label/edit', 'LabelController@update');
Route::post('label/destroy', 'LabelController@destroy');
Route::post('label/destroy-selected', 'LabelController@destroySelected');

Route::get('board/get-labels', 'BoardController@getLabels');
Route::post('board/get-employees', 'BoardController@getEmployees');
Route::post('board/assign-yourself', 'BoardController@assignYourself');
Route::post('board/change-assignee', 'BoardController@changeAssignee');
Route::post('board/change-start-date', 'BoardController@changeStartDate');
Route::post('board/change-end-date', 'BoardController@changeEndDate');
Route::post('board/change-due-date', 'BoardController@changeDueDate');
Route::post('board/change-label', 'BoardController@changeLabel');
Route::post('board/update-hor-index', 'BoardController@updateHorIndex');
Route::post('board/update-ver-index', 'BoardController@updateVerIndex');
Route::post('board/get-module-name', 'BoardController@getModuleName');

Route::get('task/get-table-datas', 'TaskController@getTableDatas');
Route::post('task/get-init-form-datas', 'TaskController@getInitFormDatas');
Route::post('task/upload-raw', 'TaskController@uploadRaw');
Route::post('task/upload-image', 'TaskController@uploadImage');
Route::post('task/new', 'TaskController@store');
Route::post('task/edit', 'TaskController@update');
Route::post('task/destroy', 'TaskController@destroy');
Route::post('task/destroy-selected', 'TaskController@destroySelected');

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
