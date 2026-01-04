<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\CoingeckoController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\BlockchainController;
use App\Http\Controllers\SwapController;

Route::post('/create', [AuthController::class, 'create']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('/wallet/create', [WalletController::class, 'create'])->middleware('can:wallet.create');
Route::middleware('auth:api')->get('/wallet/list', [WalletController::class, 'list'])->middleware('can:wallet.list');

Route::middleware(['auth:api'])->group(function () {
    Route::delete('/wallet/delete/{wallet_id}',[WalletController::class, 'delete'])->whereUuid('wallet_id')->middleware('can:wallet.delete,wallet_id');
    Route::post('/wallet/update/{wallet_id}',[WalletController::class, 'update'])->whereUuid('wallet_id')->middleware('can:wallet.update,wallet_id');
    Route::post('/wallet/import', [WalletController::class, 'import'])->middleware('can:general.userexists');

    Route::get('/blockchain/list', [BlockchainController::class, 'list'])->middleware('can:general.userexists');
    Route::post('/blockchain/testurl/{name}', [BlockchainController::class, 'testurl'])->middleware('can:general.userexists');

    Route::get('/wallet/balance/{wallet_address}/{chain}',[BalanceController::class, 'wallet_balance_update']);
    Route::post('balance/test',[BalanceController::class,'getBalance']);

    Route::get('/gecko/test',[CoingeckoController::class,'ping']);
    Route::post('/tokens/list',[CoingeckoController::class,'listTokens'])->middleware('can:general.userexists');

    Route::post('/swap/eth',[SwapController::class,'swapETH']);
    Route::get('/nodejs/test',[SwapController::class,'ping']);

    Route::post('/schedule/create',[ScheduleController::class, 'create'])->middleware('can:general.userexists');
    Route::get('/schedule/list',[ScheduleController::class, 'list'])->middleware('can:general.userexists');

    Route::post('/schedule/run/{schedule_id}', [ScheduleController::class, 'run_schedule'])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');
    Route::post('/schedule/stop/{schedule_id}', [ScheduleController::class, 'cancel_schedule'])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');
    Route::get('/schedule/state/{schedule_id}', [ScheduleController::class, 'get_schedule_state'])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');
    Route::get('/schedule/get/{schedule_id}',[ScheduleController::class, 'get'])->whereUuid('schedule_id')->middleware('can:schedule.ownership, schedule_id');
    Route::post('/schedule/random/{schedule_id}', [ScheduleController::class, 'generate_random' ])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');
    Route::delete('/schedule/delete/{schedule_id}', [ScheduleController::class, 'schedule_delete' ])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');
    Route::post('/schedule/rename/{schedule_id}', [ScheduleController::class, 'schedule_update' ])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');

    Route::get('/schedule/item/list/{schedule_id}',[ScheduleController::class, 'item_list'])->whereUuid('schedule_id')->middleware('can:schedule.ownership,schedule_id');
    Route::delete('/schedule/item/delete/{item_id}', [ScheduleController::class, 'item_delete'])->whereUuid('item_id');
    Route::post('/schedule/item/{item_id}', [ScheduleController::class, 'item_update'])->whereUuid('item_id');
    Route::post('/schedule/item/add/{schedule_id}', [ScheduleController::class, 'item_add'])->whereUuid('item_id')->middleware('can:schedule.ownership,schedule_id');

});