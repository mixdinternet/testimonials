<?php

Route::group(['middleware' => ['web'], 'prefix' => config('admin.url'), 'as' => 'admin.testimonials'], function () {
    Route::group(['middleware' => ['auth.admin', 'auth.rules']], function () {
        Route::get('testimonials/trash', ['uses' => 'TestimonialsAdminController@index', 'as' => '.trash']);
        Route::post('testimonials/restore/{id}', ['uses' => 'TestimonialsAdminController@restore', 'as' => '.restore']);
        Route::resource('testimonials', 'TestimonialsAdminController', [
            'names' => [
                'index' => '.index',
                'create' => '.create',
                'store' => '.store',
                'edit' => '.edit',
                'update' => '.update',
                'show' => '.show',
            ], 'except' => ['destroy']]);
        Route::delete('testimonials/destroy', ['uses' => 'TestimonialsAdminController@destroy', 'as' => '.destroy']);
    });
});

Route::group(['prefix' => 'api', 'as' => 'api.testimonials'], function () {
    Route::get('testimonials', ['uses' => 'TestimonialsController@index', 'as' => '.index']);
    Route::get('testimonials/{slug}', ['uses' => 'TestimonialsController@show', 'as' => '.show']);
});