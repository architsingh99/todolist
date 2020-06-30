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

Route::get('/', 'ToDoListController@welcome');

Route::get('/ToDoList-Save', 'ToDoListController@add')->name('todolist.save');

Route::get('/ToDoList-Complete', 'ToDoListController@complete')->name('todolist.complete');

Route::get('/ToDoList-Delete', 'ToDoListController@delete')->name('todolist.delete');

Route::get('/Show-All', 'ToDoListController@showAll')->name('show.all');
