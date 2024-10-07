<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard'
], function () {

    Route::get('profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('profile',[ProfileController::class,'update'])->name('profile.update');


    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');



    // Route to view trashed categories
    Route::get('categories/trash', [CategoriesController::class, 'trash'])
        ->name('categories.trash');

    // Route to restore a category
    Route::put('categories/{category}/restore', [CategoriesController::class , 'restore'])
        ->name('categories.restore');

    // Route to force delete a category
    Route::delete('categories/{category}/force-delete', [CategoriesController::class , 'forceDelete'])
        ->name('categories.force-delete');

    // Resource route for categories
    Route::resource('/categories', CategoriesController::class);

     // Resource route for products
    Route::resource('/products', ProductsController::class);

});
