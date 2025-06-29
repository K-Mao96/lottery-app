<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 抽選対象者リストの一覧・作成
Route::get('/target-lists', [App\Http\Controllers\TargetListController::class, 'index'])->name('target-lists.index');
Route::get('/target-lists/create', [App\Http\Controllers\TargetListController::class, 'create'])->name('target-lists.create');
Route::post('/target-lists', [App\Http\Controllers\TargetListController::class, 'store'])->name('target-lists.store');

// 対象者の追加
Route::get('/target-lists/{targetList}/targets/create', [App\Http\Controllers\TargetController::class, 'create'])->name('targets.create');
Route::post('/target-lists/{targetList}/targets', [App\Http\Controllers\TargetController::class, 'store'])->name('targets.store');

// 抽選画面
Route::get('/lottery', [App\Http\Controllers\LotteryController::class, 'index'])->name('lottery.index');
// 抽選処理
Route::post('/lottery/draw', [App\Http\Controllers\LotteryController::class, 'draw'])->name('lottery.draw');
// 全員リセット処理
Route::post('/lottery/reset', [App\Http\Controllers\LotteryController::class, 'reset'])->name('lottery.reset');

// リスト編集
Route::get('/target-lists/{id}/edit', [App\Http\Controllers\TargetListController::class, 'edit'])->name('target_lists.edit');
// リスト更新
Route::put('/target-lists/{id}', [App\Http\Controllers\TargetListController::class, 'update'])->name('target_lists.update');
// リスト削除
Route::delete('/target-lists/{id}', [App\Http\Controllers\TargetListController::class, 'destroy'])->name('target_lists.destroy');
