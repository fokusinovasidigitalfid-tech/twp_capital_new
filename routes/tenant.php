<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\AccountController as Account;
use App\Http\Controllers\Tenant\LoginController as Login;
use App\Http\Controllers\Tenant\DashController as Dash;
use App\Http\Controllers\Tenant\TicketController as Ticket;
use App\Http\Controllers\Tenant\OvertimeController as Overtime;
use App\Http\Controllers\Tenant\HistoryController as History;
use App\Http\Controllers\Tenant\NewHistoryController as NewHistory;
use App\Http\Controllers\Tenant\NewsController as News;
use App\Http\Controllers\Tenant\OnlineSurveyController as OnlineSurvey;
use App\Http\Controllers\Tenant\BillingOutstandingController as BillingOutstanding;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Dimuat oleh routes/web.php di dalam Route::prefix('tenant') + middleware 'section:tenant'.
| Semua path di sini otomatis berada di bawah /tenant (contoh: '/dash' => /tenant/dash).
| Controller: App\Http\Controllers\Tenant, view: resources/views/tenant.
| Login dilakukan satu pintu di "/" (PortalLoginController).
|
*/

// /tenant -> dashboard kalau sudah login, kalau belum ke halaman login "/"
Route::get('/', [Login::class, 'index']);
Route::get('/logout', [Login::class, 'logout']);


//PrefentBack CheckAuth
Route::group(['middleware' => ['tenant-auth', 'revalidate']], function () {
	//DashboardController
	Route::get('/dash', [Dash::class, 'index']);
	Route::post('/dash/getGraph', [Dash::class, 'getGraph']);
	Route::post('/dash/getGraphMeterId', [Dash::class, 'getGraphMeterId']);
	Route::post('/dash/gen', [Dash::class, 'gen']);
	// Route::post('/dash/export', [Dash::class, 'export']);
	Route::get('/dash/export/{nm}/{lot_no}', [Dash::class, 'export']);
	Route::post('/dash/cancelOT', [Dash::class, 'cancelOT']);

	// dulu routes/api.php webtenant (tanpa login & tanpa session); dipanggil halaman tiket tenant
	Route::post('/api/ticket/save', [Ticket::class, 'update']);

	// //AccountController
	Route::view('/account/profile', 'account/profile');
	Route::get('/account/getbyemail/{email}', [Account::class, 'getbyemail']);
	Route::post('/account/updateprofile', [Account::class, 'updateprofile']);
	Route::post('/account/savepic', [Account::class, 'savepic']);
	Route::post('/account/changepass', [Account::class, 'changepass']);
	
	//TicketController
	Route::get('/ticket', [Ticket::class, 'index']);
	Route::get('/ticket/{id}/{form}', [Ticket::class, 'index']);
	Route::get('/ticket/{id}', [Ticket::class, 'getByID']);
	Route::get('/ticket/getTicket/{ent}/{prj}', [Ticket::class, 'getTicket']);
	Route::get('/ticket/getTicketNew/{ent}/{prj}', [Ticket::class, 'getTicketNew']);
	Route::get('/ticket/getTicketPrefix/{ent}/{prefix}', [Ticket::class, 'getTicketPrefix']);
	Route::post('/ticket/getCat', [Ticket::class, 'getCat']);
	Route::get('/ticket/getCatEdit/{complain_type}/{category_cd}', [Ticket::class, 'getCatEdit']);
	Route::post('/ticket/getLotNo', [Ticket::class, 'getLotNo']);
	Route::get('/ticket/getLotNoEdit/{tenant_no}/{lot_no}', [Ticket::class, 'getLotNoEdit']);
	Route::post('/ticket/savepic', [Ticket::class, 'savepic']);
	Route::post('/ticket/save', [Ticket::class, 'save']);
	Route::get('/ticketharga/getHargaItem', [Ticket::class, 'getHargaItem']);
	Route::get('/ticketharga/getHargaJasa', [Ticket::class, 'getHargaJasa']);
	
	//BillingOutstandingController
	Route::get('/oustanding', [BillingOutstanding::class, 'index']);

	//OvertimeController
	Route::get('/overtime', [Overtime::class, 'index']);
	Route::post('/overtime/getLotNo', [Overtime::class, 'getLotNo']);
	Route::view('/overtime/view', 'overtime/viewlayout');
	// Route::post('/overtime/getWorkhour', [Overtime::class, 'getWorkhour']);
	Route::GET('/overtime/workhour', [Overtime::class, 'getWorkhournew']);
	Route::post('/overtime/save', [Overtime::class, 'save']);
	Route::post('/overtime/getLayoutView', [Overtime::class, 'getLayoutView']);
	Route::post('/overtime/getsum', [Overtime::class, 'get_sum']);

	//HistoryController
	Route::view('/history/ticket', 'history/ticket_history');
	Route::view('/history/overtime', 'history/overtime_history');
	Route::view('/history/billing', 'history/billing_history');
	Route::POST('/hticketTable', [History::class, 'ticketTable']);
	Route::POST('/hovertimeTable', [History::class, 'overtimeTable']);
	Route::get('/hbillingTable', [History::class, 'billingTable']);
	Route::post('/hticketSearch', [History::class, 'ticketSearch']);
	Route::post('/hovertimeSearch', [History::class, 'overtimeSearch']);
	Route::post('/hbillingSearch', [History::class, 'billingSearch']);
	Route::post('/gethistorybillingtable', [NewHistory::class, 'getbillingtable']);
	

	//NewsController
	Route::get('/news', [News::class, 'index']);

	//OnlineSurveyController
	Route::get('/online_survey', [OnlineSurvey::class, 'index']);
	Route::post('/online_survey/save', [OnlineSurvey::class, 'save']);

});