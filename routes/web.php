<?php


Route::get('robots.txt', array('https', function()
{
    return url('robots.txt');
}));

Route::get('/{any}', 'SpaController@index')->where('any', '.*');
