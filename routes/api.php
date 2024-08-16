<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TransportationCompanyController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile'])->middleware('auth');
    Route::post('/updateWallet/{id}', [AuthController::class, 'updateWallet'])->middleware('auth');
    Route::post('email_verification', [EmailVerificationController::class, 'email_verification']);
    Route::get('email_verification', [EmailVerificationController::class, 'sendEmailVerification']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'country'
], function ($router) {
    Route::post('addCountry', [CountryController::class, 'addCountry'])->name('addCountry')->middleware('auth','check');
    Route::post('updateCountry/{id}', [CountryController::class, 'updateCountry'])->name('updateCountry')->middleware('auth','check');
    Route::post('deleteCountry/{id}', [CountryController::class, 'deleteCountry'])->name('deleteCountry')->middleware('auth','check');
    Route::post('searchCountry/{name}', [CountryController::class, 'searchCountry'])->middleware('auth');
    Route::post('getCountry/{id}', [CountryController::class, 'getCountry'])->middleware('auth');
    Route::get('getAllCountries', [CountryController::class, 'getAllCountries'])->middleware('auth');
    Route::post('updatePhoto/{id}', [CountryController::class, 'updatePhoto'])->name('country_updatePhoto')->middleware('auth','check');

});
Route::group([
    'middleware' => 'api',
    'prefix' => 'city'
], function ($router) {
    Route::post('addCity', [CityController::class, 'addCity'])->name('addCity')->middleware('auth','check');
    Route::post('updateCity/{id}', [CityController::class, 'updateCity'])->name('updateCity')->middleware('auth','check');
    Route::post('deleteCity/{id}', [CityController::class, 'deleteCity'])->name('deleteCity')->middleware('auth','check');
    Route::post('searchCity/{name}', [CityController::class, 'searchCity'])->middleware('auth');
    Route::post('getCity/{id}', [CityController::class, 'getCity'])->middleware('auth');
    Route::get('getAllCities', [CityController::class, 'getAllCities'])->middleware('auth');
    Route::post('updatePhoto/{id}', [CityController::class, 'updatePhoto'])->name('city_updatePhoto')->middleware('auth','check');

});
Route::group([
    'middleware' => 'api',
    'prefix' => 'facility'
], function ($router) {
    Route::post('addFacility', [FacilityController::class, 'addFacility'])->name('addFacility')->middleware('auth','check');
    Route::post('updateFacility/{id}', [FacilityController::class, 'updateFacility'])->name('updateFacility')->middleware('auth','check');
    Route::post('deleteFacility/{id}', [FacilityController::class, 'deleteFacility'])->name('deleteFacility')->middleware('auth','check');
    Route::post('searchFacility/{name}', [FacilityController::class, 'searchFacility'])->middleware('auth');
    Route::post('getFacility/{id}', [FacilityController::class, 'getFacility']);
    Route::get('getAllFacility', [FacilityController::class, 'getAllFacility']);
    Route::post('getFacilityByCity/{id}', [FacilityController::class, 'getFacilityByCity'])->middleware('auth');
    Route::post('updatePhoto/{id}', [FacilityController::class, 'updatePhoto'])->name('facility_updatePhoto')->middleware('auth','check');
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'hotel'
], function ($router) {
    Route::post('addHotel', [HotelController::class, 'addHotel'])->name('addHotel')->middleware('auth','check');
    Route::post('updateHotel/{id}', [HotelController::class, 'updateHotel'])->name('updateHotel')->middleware('auth','check');
    Route::post('deleteHotel/{id}', [HotelController::class, 'deleteHotel'])->name('deleteHotel')->middleware('auth','check');
    Route::post('searchHotel/{name}', [HotelController::class, 'searchHotel'])->middleware('auth');
    Route::post('getHotel/{id}', [HotelController::class, 'getHotel'])->middleware('auth');
    Route::get('getAllHotel', [HotelController::class, 'getAllHotel'])->middleware('auth');
    Route::post('getHotelByCity/{id}', [HotelController::class, 'getHotelByCity'])->middleware('auth');
    Route::post('updatePhoto/{id}', [HotelController::class, 'updatePhoto'])->name('hotel_updatePhoto')->middleware('auth','check');


});
Route::group([
    'middleware' => 'api',
    'prefix' => 'restaurant'
], function ($router) {
    Route::post('addRestaurant', [RestaurantController::class, 'addRestaurant'])->name('addRestaurant')->middleware('auth','check');
    Route::post('updateRestaurant/{id}', [RestaurantController::class, 'updateRestaurant'])->name('updateRestaurant')->middleware('auth','check');
    Route::post('deleteRestaurant/{id}', [RestaurantController::class, 'deleteRestaurant'])->name('deleteRestaurant')->middleware('auth','check');
    Route::post('searchRestaurant/{name}', [RestaurantController::class, 'searchRestaurant'])->middleware('auth');
    Route::post('getRestaurant/{id}', [RestaurantController::class, 'getRestaurant'])->middleware('auth');
    Route::get('getAllRestaurant', [RestaurantController::class, 'getAllRestaurant'])->middleware('auth');
    Route::post('getRestaurantByCity/{id}', [RestaurantController::class, 'getRestaurantByCity'])->middleware('auth');
    Route::post('updatePhoto/{id}', [RestaurantController::class, 'updatePhoto'])->name('restaurant_updatePhoto')->middleware('auth','check');

});
Route::group([
    'middleware' => 'api',
    'prefix' => 'company'
], function ($router) {
    Route::post('addCompany', [TransportationCompanyController::class, 'addCompany'])->name('addCompany')->middleware('auth','check');
    Route::post('updateCompany/{id}', [TransportationCompanyController::class, 'updateCompany'])->name('updateCompany')->middleware('auth','check');
    Route::post('deleteCompany/{id}', [TransportationCompanyController::class, 'deleteCompany'])->name('deleteCompany')->middleware('auth','check');
    Route::post('searchCompany/{name}', [TransportationCompanyController::class, 'searchCompany'])->middleware('auth');
    Route::post('getCompany/{id}', [TransportationCompanyController::class, 'getCompany'])->middleware('auth');
    Route::get('getAllCompany', [TransportationCompanyController::class, 'getAllCompany'])->middleware('auth');
    Route::post('updatePhoto/{id}', [TransportationCompanyController::class, 'updatePhoto'])->name('company_updatePhoto')->middleware('auth','check');

});
Route::group([
    'middleware' => 'api',
    'prefix' => 'trip'
], function ($router) {
    Route::post('addTrip', [TripController::class, 'addTrip'])->middleware('auth');
    Route::post('updateTrip/{id}', [TripController::class, 'updateTrip'])->middleware('auth');
    Route::post('deleteTrip/{id}', [TripController::class, 'deleteTrip'])->middleware('auth');
    Route::post('getTrip/{id}', [TripController::class, 'getTrip'])->middleware('auth');
    Route::get('getAllTrips', [TripController::class, 'getAllTrips'])->middleware('auth');
    Route::post('updatePhoto/{id}', [TripController::class, 'updatePhoto'])->middleware('auth');
    Route::get('getAdminTrips', [TripController::class, 'getAdminTrips'])->middleware('auth');
    Route::get('getUserTrips', [TripController::class, 'getUserTrips'])->middleware('auth');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'activity'
], function ($router) {
    Route::post('addActivity', [ActivityController::class, 'addActivity'])->middleware('auth');
    Route::post('updateActivity/{id}', [ActivityController::class, 'updateActivity'])->middleware('auth');
    Route::post('deleteActivity/{id}', [ActivityController::class, 'deleteActivity'])->middleware('auth');
    Route::post('getActivity/{id}', [ActivityController::class, 'getActivity'])->middleware('auth');
    Route::post('getTripActivity/{id}', [ActivityController::class, 'getTripActivity'])->middleware('auth');

});

Route::group([
    'middleware' => 'api',
    'prefix' => 'booking'
], function ($router) {
    Route::post('addBook', [BookingController::class, 'addBook'])->middleware('auth');
    Route::post('updateBook/{id}', [BookingController::class, 'updateBook'])->middleware('auth');
    Route::post('getUserBook/{id}', [BookingController::class, 'getUserBook'])->middleware('auth');
    Route::get('getAllUserBookings', [BookingController::class, 'getAllUserBookings'])->middleware('auth');
    Route::get('getAllBookings', [BookingController::class, 'getAllBookings'])->middleware('auth');
    Route::get('getUserFinance', [BookingController::class, 'getUserFinance'])->middleware('auth');
    Route::post('deleteBook/{id}', [BookingController::class, 'deleteBook'])->middleware('auth');
    Route::post('viewUserFinance/{id}', [BookingController::class, 'viewUserFinance'])->middleware('auth');
    Route::post('getTripBook/{id}', [BookingController::class, 'getTripBook'])->name('getTripBook')->middleware('auth','check');


});

Route::group([
    'middleware' => 'api',
    'prefix' => 'favourite'
], function ($router) {
    Route::post('addFav', [\App\Http\Controllers\FavouriteController::class, 'addfav'])->middleware('auth');
    Route::post('deleteFav/{id}', [\App\Http\Controllers\FavouriteController::class, 'deletefav'])->middleware('auth');
    Route::get('getFav', [\App\Http\Controllers\FavouriteController::class, 'getfav'])->middleware('auth');


});

Route::group([
    'middleware' => 'api',
    'prefix' => 'rate'
], function ($router) {
    Route::post('updateRate', [\App\Http\Controllers\RatingController::class, 'updaterate'])->middleware('auth');
    Route::post('getRating/{id}', [\App\Http\Controllers\RatingController::class, 'getrating'])->middleware('auth');

});
