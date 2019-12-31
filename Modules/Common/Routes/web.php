<?php

Route::group([
    'middleware' => ['web'],
    'prefix' => 'common'
], function () {
    Route::name('common.')->group(function () {
        Route::get('/', function() {
            return 'Module common ok';
        });
    });
});
