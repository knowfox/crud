<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::resource('setting', \Knowfox\Crud\Controllers\SettingController::class);
});
