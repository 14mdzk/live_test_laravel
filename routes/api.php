<?php

use App\Models\Repository;
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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/repositories', function () {
    $response = Http::get('https://api.github.com/repositories', [
        'since' => Repository::latest('id')->first()?->id,
    ]);

    $data = array_map(function ($item) {
        return [
            'id' => $item['id'],
            'full_name' => $item['full_name'],
            'owner_login' => $item['owner']['login'],
        ];
    }, $response->json());

    return DB::table('repositories')->insert($data);
});
