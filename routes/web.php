<?php

use App\Http\Controllers\PortalLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Satu aplikasi, satu halaman login ("/") untuk Admin dan Tenant.
| Setelah login, masing-masing masuk ke bagiannya:
|   /admin/...   -> routes/admin.php   (App\Http\Controllers\Admin,  views/admin)
|   /tenant/...  -> routes/tenant.php  (App\Http\Controllers\Tenant, views/tenant)
| Middleware 'section' (App\Http\Middleware\Section) membuat url()/view()/DB default
| di tiap bagian bekerja sama seperti di webadmin / webtenant lama.
|
*/

Route::get('/', [PortalLoginController::class, 'index']);
// redirect tanpa closure supaya `php artisan route:cache` bisa dipakai
Route::redirect('/login', '/');
// batasi percobaan login / cek email per IP (anti brute force & enumerasi email)
Route::post('/login', [PortalLoginController::class, 'login'])->middleware('throttle:10,1');
Route::get('/login/businesses', [PortalLoginController::class, 'businesses'])->middleware('throttle:30,1');
Route::get('/logout', [PortalLoginController::class, 'logout']);

Route::prefix('admin')->middleware('section:admin')->group(base_path('routes/admin.php'));
Route::prefix('tenant')->middleware('section:tenant')->group(base_path('routes/tenant.php'));

// URL project lama (/webadmin/..., /webtenant/...) -> halaman login satu pintu
Route::redirect('/webadmin/{any?}', '/')->where('any', '.*');
Route::redirect('/webtenant/{any?}', '/')->where('any', '.*');
