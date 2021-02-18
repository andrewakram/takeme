<?php

use App\Models\Country;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
/*Route::get('/1', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "good";
});*/
Route::post('/addContry', function (Request $request) {
    //dd($request[0]);
    for ($i = 0; $i < sizeof($request->all()); $i++) {
        Country::create([
            'name' => $request[$i]['name'],
            'name_en' => $request[$i]['english_name'],
            'code' => $request[$i]['phone_code'],
            'code_name' => $request[$i]['name_code'],
        ]);
    }
});
//Route::group(['middleware' => 'setTimeZone'], function () {


    Route::group(['prefix' => '/app', 'namespace' => 'Api\App'], function () {
        Route::get('/get-fee-percent', 'AppController@getFeePercent');
        Route::get('/app-explanation/{type}', 'AppController@appExplanation');
        Route::get('/terms', 'AppController@termCondition');
        Route::get('/about-us', 'AppController@aboutUs');
        Route::post('/contact-us', 'AppController@contactUs');
        Route::get('/countries', 'AppController@countries');
        Route::get('/cars-nationals', 'AppController@cars_nationals');
        Route::get('/countries-codes', 'AppController@countriesCodes');
        Route::get('/cities', 'AppController@cities');
        Route::get('/get-notifications', 'AppController@getNotifications');

    });

    Route::group(['prefix' => '/app-uber', 'namespace' => 'Api\AppUber'], function () {
//Start apis app
        Route::post('/complains_suggestions', 'AppController@complainSuggest');
        Route::get('/about_us', 'AppController@aboutUs');
        Route::get('/terms', 'AppController@termCondition');
        Route::get('/issues', 'AppController@issues');
        Route::get('/losts', 'AppController@losts');
        Route::get('/get_criedit_cards', 'AppController@getCrieditCards');
        Route::post('/add_criedit_card', 'AppController@addCrieditCard');
        Route::post('/criedit_card/activate', 'AppController@activateCrieditCard');
        Route::post('/wallet/change_status', 'AppController@walletchangeStatus');
        Route::get('/notifications', 'AppController@notifications');
//End apis app
    });


    Route::group(['prefix' => '/user', 'namespace' => 'Api\User'], function () {
        Route::post('/phone-check', 'AuthController@phoneCheck');
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
        Route::post('/code-send', 'AuthController@codeSend');
        Route::post('/code-check', 'AuthController@codeCheck');
        Route::post('/update-profile', 'AuthController@updateProfile');
        //Route::post('/forget-password', 'AuthController@forgetPassword');

        Route::get('/categories-shops', 'HomeController@categoriesShops');
        Route::get('/shops-by-category', 'HomeController@shopsByCategory');
        Route::get('/shop-details', 'HomeController@shopDetails');
        Route::get('/shop-rates', 'HomeController@shopRates');
        Route::get('/product-details', 'HomeController@productDetails');
        Route::post('/rate-shop', 'HomeController@rateShop');
//    Route::get('/get-rates', 'HomeController@getRates');
        //Route::post('/add-to-cart', 'OrderController@addToCart');
        Route::get('/home', 'HomeController@homeThirdDepartment');
        //
        Route::post('/make-order', 'HomeController@makeOrder');
        Route::post('/re-order', 'HomeController@reOrder');

        Route::post('/accept-order', 'HomeController@acceptOffer');
        Route::post('/cancel-order', 'HomeController@cancelOrder');
        Route::post('/accept-confirm-request', 'HomeController@acceptConfirmRequest');

        Route::get('/get-orders-offers', 'HomeController@getOrdersOffers');
        Route::get('/get-offers', 'HomeController@getOffers');
        Route::get('/get-delegate-orders-rates', 'HomeController@getDelegateOrdersRates');
        Route::get('/get-lower-offer', 'HomeController@getLowerOffer');
        Route::post('/check-promo', 'HomeController@checkPromo');
        Route::get('/saved-locations', 'HomeController@savedLocations');

        Route::get('/get-replaced-points', 'HomeController@getReplacedPoints');
        Route::get('/get-offer-points', 'HomeController@getofferPoints');
        Route::post('/replace-points', 'HomeController@replacePoints');
        Route::get('/get-user-wallet-recharges', 'HomeController@getUserWalletRecharges');
        Route::post('/raise-user-wallet', 'HomeController@raiseUserWallet');

        Route::get('/get-user-admin-messages', 'HomeController@getUserAdminMessages');
        Route::post('/chat-with-admin', 'HomeController@chatWithAdmin');

        Route::get('/get-user-messages', 'HomeController@getUserMessages');
        Route::post('/send-message', 'HomeController@sendMessage');

        Route::get('/get-history-orders', 'HomeController@getHistoryOrders');

        Route::get('/shop-sliders', 'HomeController@shopSliders');
        Route::get('/search-shop', 'HomeController@searchShops');
        Route::get('/get-order-status', 'HomeController@getOrderStatus');
        Route::get('/change-my-delegate', 'HomeController@changeMyDelegate');

        Route::post('/rate-order', 'HomeController@rateOrder');
    });

    Route::group(['prefix' => '/delegate', 'namespace' => 'Api\Delegate'], function () {
        Route::post('/phone-check', 'AuthController@phoneCheck');
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
        Route::post('/code-send', 'AuthController@codeSend');
        Route::post('/code-check', 'AuthController@codeCheck');
        Route::post('/update-profile', 'AuthController@updateProfile');
        Route::get('/delegate-documents', 'AuthController@delegateDocuments');
        Route::get('/profile-data', 'AuthController@profileData');
        Route::Post('/replace-points', 'AuthController@replacePoints');
        Route::get('/rates-of-orders', 'DelegateController@ratesOfOrders');
        Route::get('/get-order-offers', 'DelegateController@getOrderOffers');
        Route::post('/add-order-offer', 'DelegateController@addOrderOffer');
        //
        Route::get('/get-shops', 'DelegateController@getShops');
        Route::get('/subscribe-as-delegate', 'DelegateController@subscribeAsDelegate');
        Route::get('/waiting-orders', 'DelegateController@waitingOrders');
        Route::get('/all-waiting-orders', 'DelegateController@allWaitingOrders');
        Route::get('/my-orders', 'DelegateController@myOrders');
        Route::get('/order-details', 'DelegateController@orderDetails');
        Route::get('/subscribed-shops', 'DelegateController@subscribedShops');

        //
        Route::post('/order-change-status', 'DelegateController@changeStatus');
        Route::post('/send-confirm-request', 'DelegateController@sendConfirmRequest');

        Route::get('/get-delegate-messages', 'DelegateController@getDelegateMessages');
        Route::post('/send-message', 'DelegateController@sendMessage');

        Route::get('/get-replaced-points', 'DelegateController@getReplacedPoints');
    });

//Route::group(['middleware' => 'auth:user'], function () {
//});
//Route::group(['middleware' => 'auth:delegate'], function () {
//});
//Route::group(['middleware' => 'auth:driver'], function () {
//});


    Route::group(['prefix' => '/user-uber', 'namespace' => 'Api\UserUber'], function () {
        //Start apis authentication
        Route::post('/code_send', 'AuthController@codeSend');
        Route::post('/code_check', 'AuthController@codeCheck');

        Route::post('/register', 'AuthController@register');

        Route::post('/login', 'AuthController@login');
        Route::post('/forget_password', 'AuthController@forgetPassword');
        Route::post('/update_profile', 'AuthController@updateProfile');
        Route::get('/get_point_offers', 'AuthController@getPointOffers');
        Route::post('/convert_points', 'AuthController@convertPoints');
        //End apis authentication

        //home
        Route::get('/home', 'HomeController@home');
        Route::get('/bank_accounts', 'HomeController@bankAccounts');
        Route::get('/countries', 'HomeController@countries');
        Route::post('/check_promo_code', 'HomeController@checkPromoCode');
        //trip
        Route::post('/clculate_trip_price', 'TripController@calculateTripPrices');
        Route::post('/add_location', 'TripController@addLocation');
        Route::get('/get_locations', 'TripController@getLocations');
        Route::get('/cancelling_reasons', 'TripController@cancellingReasons');

        Route::post('/create_trip', 'TripController@createTrip');
        //
        Route::post('/delete_trip', 'TripController@deleteTrip');
        Route::post('/cancel_trip', 'TripController@cancelTrip');
        Route::get('/trip_details', 'TripController@tripDetails');
        Route::get('/trip_history', 'TripController@tripHistory');
        Route::post('/rate_trip', 'TripController@rateTrip');

        Route::post('/change_status', 'TripController@changeStatus');


        Route::get('/scheduled_trip', 'TripController@createScheduledTrip');
        Route::post('/trip_history', 'TripController@tripHistory');
        //nnnnnnn

        Route::get('/chat_history', 'TripController@chatHistory');
        Route::post('/add_message', 'TripController@addMessage');

        //cron job link
        Route::get('/scheduled_trip', 'TripController@scheduledTrip');

    });
    Route::group(['prefix' => '/captin'], function () {
        Route::post('/update_location', 'Api\UserUber\AuthController@updateLocation');
    });
    Route::group(['prefix' => '/captin', 'namespace' => 'Api\Captin'], function () {

        Route::post('/phone-check', 'AuthController@phoneCheck');
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
        Route::post('/code-send', 'AuthController@codeSend');
        Route::post('/code-check', 'AuthController@codeCheck');
        Route::post('/update-profile', 'AuthController@updateProfile');
        Route::get('/driver-documents', 'AuthController@driverDocuments');
        //Start apis authentication
        Route::post('/register_complete', 'Api\User\AuthController@captinCompleteRegister');


        //End apis authentication
    });

    Route::group(['prefix' => '/captin', 'namespace' => 'Api\Captin'], function () {
        // 1=waiting_captin , 2=trip_started , 3=trip_finished , 4=trip_cancelled"
        Route::post('/change_status', 'TripController@changeStatus');
        //
        Route::post('/collect_money', 'TripController@collectMoney');
        //
        Route::post('/calculate_trip_cost', 'TripController@calculateTripCost');
        Route::get('/trip_history/{type}/{key}', 'TripController@tripHistory');
        Route::post('/rate_trip', 'TripController@rateTrip');
        Route::post('/update_status', 'TripController@updateStatus');
        //
        Route::post('/add_bank_transfer', 'AuthController@addBankTransfer');
        Route::get('/banking_transfers', 'AuthController@bankingTransfers');


        Route::get('/get-credits', 'TripController@getCredits');
        Route::get('/get-my-car-levels', 'AuthController@getMyCarLevels');
        Route::post('/update-my-car-levels', 'AuthController@updateMyCarLevels');


    });


//});