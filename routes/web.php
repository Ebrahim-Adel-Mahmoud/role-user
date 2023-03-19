<?php


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard',function (){
    if (auth()->guard('user')->check()){
        return Redirect::route('userdashboard');
    }elseif (auth()->guard('admin')->check()){
        return Redirect::route('admin.dashboard');
    }
})
    ->middleware('auth:user,admin')
    ->name('dashboard');
/*--- user ---*/

Route::prefix('user')
        ->name('user')
        ->middleware(['auth:user','verified'])
        ->group(function ($request){
            Route::get('dashboard',function (){
               return view('dashboard');
            })->name('dashboard');
        });

/*--- Admin ---*/

Route::prefix('admin')
    ->name('admin')
    ->middleware(['auth:admin','verified'])
    ->group(function ($request){
        Route::get('dashboard',function (){
            return view('dashboard');
        })->name('dashboard');
    });
require __DIR__.'/auth.php';
