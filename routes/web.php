<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Mail;

/*
| Public Pages
*/
Route::get('/', fn() => view('landing'));
Route::get('/home', fn() => view('home'));
Route::get('/booked-times', [BookingController::class, 'getBookedTimes']);

/*
| Calendar
*/
Route::get('/calendar', [BookingController::class, 'calendar'])->name('calendar');
/*
| Booking
*/
Route::get('/book', [BookingController::class, 'create'])->name('booking.create');
Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
/*
| Admin Login
*/
Route::post('/admin-login', function (Request $request) {

    $username = $request->input('username');
    $password = $request->input('password');
    if ($username === 'admin' && $password === '12345') {
        // SET SESSION
        session(['is_admin' => true]);
        return redirect()->route('admin');
    }
    return back()->with('error', 'Invalid login');
})->name('admin.login');

Route::middleware('admin')->group(function () {

/*
| Admin Panel
*/
Route::get('/admin', fn() => view('admin'))->name('admin');
Route::post('/logout', function () {
    session()->forget('is_admin');
    return redirect('/home');
})->name('logout');

/*
| Calendar
*/
Route::get('/admin/calendar', [BookingController::class, 'adminCalendar'])
    ->name('admin.calendar');

/*
| Bookings
*/
Route::get('/admin/bookings', [BookingController::class, 'bookings'])
    ->name('admin.bookings');
Route::post('/admin/bookings/store', [AdminController::class, 'adminStoreBooking'])
    ->name('bookings.adminStore');
Route::delete('/admin/bookings/{booking}', [AdminController::class, 'destroy'])
    ->name('bookings.destroy');
Route::post('/bookings/{id}/approve', [BookingController::class, 'approve'])
    ->name('bookings.approve');
Route::post('/bookings/{booking}/decline', [AdminController::class, 'decline'])
    ->name('bookings.decline');
Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
    ->name('booking.cancel');

/*
| Posts CRUD
*/
Route::post('/admin/posts', [PostController::class, 'store'])
    ->name('posts.store');
Route::get('/admin/posts/{id}/edit', [PostController::class, 'edit'])
    ->name('posts.edit');
Route::put('/admin/posts/{id}', [PostController::class, 'update'])
    ->name('posts.update');
Route::delete('/admin/posts/{id}', [PostController::class, 'destroy'])
    ->name('posts.destroy');
Route::delete('/admin/posts/{id}/image/{index}', [PostController::class, 'deleteImage'])
    ->name('posts.deleteImage');

});