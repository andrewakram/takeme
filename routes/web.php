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

Route::get('/1', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "good";
});

Route::get('/', function () {
    return view('welcome');
});
/////////////////////////////////
Route::get('/captin-form', function () {
    return view('captin-form');
});
Route::post('/signup/form', 'SiteController@formRequest')->name('delegate-form-submit');
Route::post('/signup/send_verification_code', 'SiteController@sendVerification');
Route::post('/signup/verify_user', 'SiteController@verifyUser');
Route::get('/signup/form', function () {
    $user_id = request()->user_id;
    $phone = request()->phone;
    //$car_types = \App\CarType::get();
    $car_types = [];
    return view('signup-form', compact('user_id', 'phone', 'car_types'));
});
///////////////////////

//Auth::routes();
Route::group(['prefix' => '/admin'], function () {
    Route::get('/login', 'Admin\AuthController@login_view');
    Route::post('/login', 'Admin\AuthController@login')->name('login');
    Route::get('/logout', 'Admin\AuthController@logout')->name('logout');


    Route::group(['middleware' => 'auth:admin'], function () {

        Route::get('/home', 'HomeController@index')->name('home');

        Route::resource('/clients', 'Admin\User2Controller');
        Route::get('/clients/editStatus/{id}', 'Admin\User2Controller@editClientStatus')->name('editClientStatus');
        Route::get('/delegates', 'Admin\User2Controller@index3');
        Route::get('/delegate-orders/{delegate_id}', 'Admin\User2Controller@delegateOrders')->name('delegateOrders');
        Route::get('/delegates/editStatus/{id}', 'Admin\User2Controller@editDelegateStatus')->name('editDelegateStatus');
        Route::get('/drivers', 'Admin\User2Controller@index2');
        Route::get('/driver-trips/{driver_id}', 'Admin\User2Controller@driverTrips')->name('driverTrips');
        Route::get('/user-trips/{user_id}', 'Admin\User2Controller@userTrips')->name('userTrips');
        Route::get('/drivers/accept/{id}', 'Admin\User2Controller@accept_driver')->name('acceptDriver');
        Route::post('/drivers/editCarLevel', 'Admin\User2Controller@editCarLevel')->name('editCarLevel');
        Route::get('/admins', 'Admin\User2Controller@indexAdmin');
//
        Route::get('/finished-driver-trips/{driver_id}', 'Admin\User2Controller@finishedDriverTrips');
        Route::get('/finished-user-trips/{user_id}', 'Admin\User2Controller@finishedUserTrips');

        Route::resource('/offer_points', 'Admin\OfferPointController');
        Route::post('/offer_points/edit', 'Admin\OfferPointController@editOfferPoint')->name('editOfferPoint');
        Route::get('/user_offer_points', 'Admin\OfferPointController@user_offer_points');
        Route::get('/user_offer_points/editStatus/{id}', 'Admin\OfferPointController@editUserOfferPointStatus')
            ->name('editUserOfferPointStatus');
        Route::get('/delegate_offer_points', 'Admin\OfferPointController@delegate_offer_points');


        Route::resource('/countriess', 'Admin\CountryController');
        Route::post('/countriess/edit', 'Admin\CountryController@edit_country')->name('editCountry');
        Route::get('/countriess/editStatus/{id}', 'Admin\CountryController@editCountryStatus')->name('editCountryStatus');

        Route::resource('/cities', 'Admin\CityController');
        Route::post('/cities/edit', 'Admin\CityController@edit_city')->name('editCity');

        Route::resource('/nationals', 'Admin\NationalController');
        Route::post('/nationals/edit', 'Admin\NationalController@editNational')->name('editNational');

        Route::resource('/car_types', 'Admin\CarTypeController');
        Route::post('/car_types/edit', 'Admin\CarTypeController@editCarType')->name('editCarType');

        Route::resource('/rushhours', 'Admin\RushhourController');
        Route::post('/rushhours/edit', 'Admin\RushhourController@edit_rushhour')->name('editRushhour');
        Route::get('/rushhours/delet/{id}', 'Admin\RushhourController@delete_rushhour')->name('deleteRushhour');

        Route::resource('/reasons', 'Admin\ReasonController');
        Route::post('/reasons/edit', 'Admin\ReasonController@edit_reason')->name('editReason');
        Route::get('/reasons/delet/{id}', 'Admin\ReasonController@delete_reason')->name('deleteReason');

        Route::resource('/reasons', 'Admin\ReasonController');
        Route::post('/reasons/edit', 'Admin\ReasonController@edit_reason')->name('editReason');
        Route::get('/reasons/delet/{id}', 'Admin\ReasonController@delete_reason')->name('deleteReason');
//
        Route::resource('/issues', 'Admin\IssueController');
        Route::post('/issues/edit', 'Admin\IssueController@edit_issue')->name('editIssue');
        Route::get('/issues/delet/{id}', 'Admin\IssueController@delete_issue')->name('deleteIssue');
//
        Route::resource('/bank_account', 'Admin\BankAccountController');
        Route::post('/bank_account/edit', 'Admin\BankAccountController@edit_bank_account')->name('editBankAccount');
        Route::get('/bank_account/delet/{id}', 'Admin\BankAccountController@delete_bank_account')->name('deleteBankAccount');
//
        Route::resource('/losts', 'Admin\LostController');
        Route::post('/losts/edit', 'Admin\LostController@edit_lost')->name('editLost');
        Route::get('/losts/delet/{id}', 'Admin\LostController@delete_lost')->name('deleteLost');
//
        Route::resource('/bank_transfers', 'Admin\BankingTransferController');
//
        Route::resource('/promocodes', 'Admin\PromocodeController');
        Route::get('/promocodes/delet/{id}', 'Admin\PromocodeController@delete_promo')->name('deletePromo');

        Route::resource('/carlevelss', 'Admin\LevelController');
        Route::post('/carlevelss/edit', 'Admin\LevelController@edit_carlevels')->name('editCarlevel');

        Route::resource('/carprices', 'Admin\CarPriceController');
        Route::post('/carprices/edit', 'Admin\CarPriceController@edit_carprices')->name('editCarprice');

        Route::resource('/govs', 'Admin\GovController');
        Route::post('/govs/edit', 'Admin\GovController@edit_gov')->name('editGov');

        Route::resource('/cities', 'Admin\CityController');
        Route::post('/cities/edit', 'Admin\CityController@edit_city')->name('editCity');

        Route::resource('/cats', 'Admin\CategoryController');
        Route::post('/cats/edit', 'Admin\CategoryController@edit_cat')->name('editCat');
        Route::get('/cats/editStatus/{id}', 'Admin\CategoryController@editCatStatus')->name('editCatStatus');

        Route::resource('/trips', 'Admin\TripController');
        Route::get('/finished-trips', 'Admin\TripController@finishedTrips');
        Route::post('/trips', 'Admin\TripController@checkPaymentSrtatus')->name('checkPaymentSrtatus');
//Route::get('/offs/editStatus/{id}', 'Admin\OfferController@editOfferStatus')->name('editOfferStatus');

        Route::resource('/notifications', 'Admin\NotificationController');
        Route::get('/notifications/delet/{id}', 'Admin\NotificationController@delete_not');

        Route::resource('/reviews', 'Admin\ReviewController');
        Route::get('/reviews/delet/{id}', 'Admin\ReviewController@delete_review');

        Route::resource('/terms', 'Admin\TermController');
        Route::post('/terms/edit', 'Admin\TermController@edit_terms')->name('editTerm');

        Route::resource('/complains_suggestions', 'Admin\ComplainSuggestController');

        Route::resource('/abouts', 'Admin\AboutController');
        Route::post('/abouts/edit', 'Admin\AboutController@edit_abouts')->name('editAbout');

        Route::resource('/settings', 'Admin\SettingController');
        Route::post('/settings/edit', 'Admin\SettingController@edit_settings')->name('edit_settings');

        Route::resource('/app_explanations', 'Admin\AppExplanationController');
        Route::post('/app_explanations/edit', 'Admin\AppExplanationController@edit_explains')->name('editExplain');

        Route::resource('/categories', 'Admin\CategoryController');
        Route::post('/categories/mainCat', 'Admin\CategoryController@storeMainCat')->name('storeMainCat');
        Route::post('/categories/delet', 'Admin\CategoryController@delete_cat')->name('deleteCat');

        Route::resource('/shops', 'Admin\ShopController');
        Route::post('/shops/editStatus', 'Admin\ShopController@editShopStatus')->name('editShopStatus');
        Route::post('/shops/editVerified', 'Admin\ShopController@editShopVerified')->name('editShopVerified');
        Route::get('/shops/create', 'Admin\ShopController@createShop')->name('createShop');
        Route::get('/shops/edit/{shop_id}', 'Admin\ShopController@editShop')->name('editShop');
        Route::post('/shops/edit', 'Admin\ShopController@updateShop')->name('updateShop');
        Route::post('/shops/delet', 'Admin\ShopController@delete_shop')->name('deleteShop');
        Route::get('/active-shops', 'Admin\ShopController@activeShops');
        Route::get('/inactive-shops', 'Admin\ShopController@inactiveShops');

        Route::resource('/orders', 'Admin\OrderController');


//reports
        Route::get('reports', "Admin\ReportController@reports")->name('reports');
        Route::post('Report', "Admin\ReportController@makeReport")->name('makeReport');

        Route::post('usersReport', "Admin\ReportController@usersReport")->name('usersReport');
        Route::get('usersInvoice', "Admin\ReportController@usersInvoice")->name('usersInvoice');

        Route::post('delegatesReport', "Admin\ReportController@delegatesReport")->name('delegatesReport');
        Route::get('delegatesInvoice', "Admin\ReportController@delegatesInvoice")->name('delegatesInvoice');

        Route::post('driversReport', "Admin\ReportController@driversReport")->name('driversReport');
        Route::get('driversInvoice', "Admin\ReportController@driversInvoice")->name('driversInvoice');

        Route::post('tripsReport', "Admin\ReportController@tripsReport")->name('tripsReport');
        Route::get('tripsInvoice', "Admin\ReportController@tripsInvoice")->name('tripsInvoice');

        Route::post('ordersShopsReport', "Admin\ReportController@ordersShopsReport")->name('ordersShopsReport');
        Route::get('ordersShopsInvoice', "Admin\ReportController@ordersShopsInvoice")->name('ordersShopsInvoice');

        Route::post('ordersNormalReport', "Admin\ReportController@ordersNormalReport")->name('ordersNormalReport');
        Route::get('ordersNormalInvoice', "Admin\ReportController@ordersNormalInvoice")->name('ordersNormalInvoice');

    });
});





//////////shop
Route::group(['prefix' => '/shop'], function () {
    Route::get('/login', 'Shop\AuthController@login_view');
    Route::post('/login', 'Shop\AuthController@login')->name('login_shop');
    Route::get('/logout', 'Shop\AuthController@logout')->name('logout_shop');


    Route::group(['middleware' => 'auth:shop'], function () {

        Route::get('/edit-profile', 'Shop\ShopController@editShopProfile')->name('editShopProfile');
        Route::post('/edit-profile', 'Shop\ShopController@updateShopProfile')->name('updateShopProfile');;

        Route::get('/edit-dailyWork', 'Shop\ShopController@editDailyWork')->name('editDailyWork');
        Route::post('/edit-dailyWork', 'Shop\ShopController@updateDailyWork')->name('updateDailyWork');;

        Route::get('/home', 'ShopHomeController@index')->name('shop_home');

        Route::resource('/clients', 'Shop\User2Controller');
        Route::get('/delegates', 'Shop\User2Controller@delegates');
        Route::get('/offers', 'Shop\User2Controller@offers');
        Route::get('/admins', 'Shop\User2Controller@indexAdmin');
//
        Route::resource('/menus', 'Shop\MenuController');
        Route::post('/menus/edit', 'Shop\MenuController@editMenu')->name('editMenu');
        Route::post('/menus/delet', 'Shop\MenuController@deleteMenu')->name('deleteMenu');

        Route::resource('/products', 'Shop\ProductController');
        Route::post('/products/edit', 'Shop\ProductController@editProduct')->name('editProduct');
        Route::post('/products/delet', 'Shop\ProductController@deleteProduct')->name('deleteProduct');
        Route::post('/products/addVar', 'Shop\ProductController@addVariation')->name('addVariation');
        Route::post('/products/editVar', 'Shop\ProductController@editVariation')->name('editVariation');
        Route::post('/products/deleteVar', 'Shop\ProductController@deleteVariation')->name('deleteVariation');
        Route::post('/products/deleteOption', 'Shop\ProductController@deleteOption')->name('deleteOption');
        Route::post('/products/addOption', 'Shop\ProductController@addOption')->name('addOption');
        Route::post('/products/editOption', 'Shop\ProductController@editOption')->name('editOption');

        Route::resource('/orders', 'Shop\OrderController');
        Route::get('accept-orders', 'Shop\OrderController@acceptOrders')->name('accept-orders');
        Route::get('onway-orders', 'Shop\OrderController@onwayOrders')->name('onway-orders');
        Route::get('finished-orders', 'Shop\OrderController@finishedOrders')->name('finished-orders');
        Route::get('cancelled-orders', 'Shop\OrderController@cancelledOrders')->name('cancelled-orders');


//reports
//        Route::get('reports', "Admin\ReportController@reports")->name('reports');
//        Route::post('Report', "Admin\ReportController@makeReport")->name('makeReport');
//
//        Route::post('usersReport', "Admin\ReportController@usersReport")->name('usersReport');
//        Route::get('usersInvoice', "Admin\ReportController@usersInvoice")->name('usersInvoice');
//
//        Route::post('driversReport', "Admin\ReportController@driversReport")->name('driversReport');
//        Route::get('driversInvoice', "Admin\ReportController@driversInvoice")->name('driversInvoice');
//
//        Route::post('tripsReport', "Admin\ReportController@tripsReport")->name('tripsReport');
//        Route::get('tripsInvoice', "Admin\ReportController@tripsInvoice")->name('tripsInvoice');

    });
});

