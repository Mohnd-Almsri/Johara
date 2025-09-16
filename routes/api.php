<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Blog\ArticleController;
use App\Http\Controllers\Blog\CeoController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Project\CategoryController;
use App\Http\Controllers\Project\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    Route::controller(CategoryController::class)->prefix('category')->group(function () {
        Route::get('/', 'index');
        Route::get('/{category}','show');
//        Route::post('/store', 'store');
//        Route::post('/update', 'update');
//        Route::delete('/delete', 'delete');
    });

    Route::controller(ProjectController::class)->prefix('projects')->group(function () {
        Route::get('/', 'index');
        Route::get('/{project}', 'show');
//        Route::post('/create', 'store');
//        Route::post('/update', 'update');
//        Route::delete('/delete', 'delete');
    });

Route::controller(AboutController::class)->prefix('about')->group(function () {

    Route::get('/', 'index');

    // about-us section
    Route::get('/about-us', 'about');
    Route::post('/about-us/addImage', 'addImage');
    Route::post('/about-us/create', 'aboutCreate');
    Route::post('/about-us/update', 'aboutUpdate');
    Route::delete('/about-us/delete', 'aboutDelete');

    //team section
    Route::get('/team', 'team');
    Route::get('/team/{member}', 'teamShow');
//    Route::post('/team/create', 'teamCreate');
//    Route::post('/team/update', 'teamUpdate');
//    Route::delete('/team/delete', 'teamDelete');

    // services section
    Route::get('/services', 'service');
    Route::get('/services/{service}', 'serviceShow');
//    Route::post('/service/create', 'serviceCreate');
//    Route::post('/service/update', 'serviceUpdate');
//    Route::delete('/service/delete', 'serviceDelete');


});
Route::prefix('blog')->group(function () {

//    Route::controller(CeoController::class)->prefix('ceo')->group(function () {
//        Route::get('/', 'index');
//        Route::get('/show', 'show');
//        Route::post('/addImage', 'addImage');
//        Route::post('/update', 'update');
//        Route::post('/create', 'create');
//        Route::delete('/delete', 'delete');
//    });

    Route::controller(articleController::class)->prefix('articles')->group(function () {
        Route::get('/', 'index');
        Route::get('/{article}', 'show');
//        Route::post('/update', 'updateArticle');
//        Route::post('/update-or-add-paragraph', 'createOrUpdateParagraph');
//        Route::post('/create', 'create');
//        Route::delete('/deleteArticle', 'deleteArticle');
//        Route::delete('/deleteParagraph', 'deleteParagraph');
    });


});

Route::controller(ContactController::class)->prefix('contact-us')->group(function () {
//    Route::get('/', 'index');
    Route::post('/', 'create');
//    Route::post('/mark-as-read', 'markAsRead');
//    Route::delete('/delete', 'delete');
});
