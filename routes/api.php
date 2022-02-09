<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/testAssertion', function () {
    return response()->json([
        "status" => true,
        'message' => 'Im a tdd case',
    ], 200);
});

//This route creates the test database for the project
Route::get('create-tests-table', function () {
    return DB::statement('create database if not exists ledger_wallet_tests');
});

Route::get('lms-list', function () {
    $response = Http::get('insert url');
    $dataBag = $response['data'];
    $finalArray = collect($dataBag)->map(function ($data){
        return data_get($data, 'user_id.first_name') .' '. data_get($data, 'user_id.last_name');
    });
    // $finalArray = [];
    // foreach ($dataBag as $key => $value) {
    //     array_push($finalArray, $dataBag[$key]['user_id']);
    // }
    return $finalArray;
});