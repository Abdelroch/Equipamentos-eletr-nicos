<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::group(
    [
        'prefix' => ''
    ],
    function () {
        Route::get('/', ['uses' => 'Visitor\MainController@index', 'as' => 'index']);

        Route::group(
            [
                'prefix' => 'produto',
                'as'=> 'visitor.',
            ],
            function () {

                Route::get('/{product_slug}/negociar', ['uses' => 'Visitor\MainController@product_details', 'as' => 'negotiate']);

            }
        );

        Route::group(
            [
                'prefix' => 'produtos',
                'as' => 'customer.',
                'middleware'=>'auth'
            ],
            function () {

                Route::group(['prefix'=>'configuracoes/minha-conta', 'as'=>'settings.my_accout.'], function(){
                    Route::get('', ['uses' => 'Customer\MainController@profile', 'as' => 'profile']);
                });

                Route::get('solicitacoes', ['uses' => 'Customer\MainController@order_requests', 'as' => 'order_requests']);
                Route::post('encomenda/negociar', ['uses' => 'Customer\MainController@store_order_negotiation', 'as' => 'store_order_negotiation']);

            }
        );

        Route::get('loja', ['uses' => 'Visitor\MainController@store', 'as' => 'store']);

        Route::post('criar-uma-conta', ['uses' => 'Visitor\MainController@customer_create_account', 'as' => 'customer.create_account']);
    }



);



require __DIR__.'/auth.php';
require base_path('routes/admin.php');

