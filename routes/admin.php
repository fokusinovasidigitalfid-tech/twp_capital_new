<?php

use App\Http\Controllers\Admin\AccountController as Account;
use App\Http\Controllers\Admin\GroupController as Group;
use App\Http\Controllers\Admin\DashController as Dash;
use App\Http\Controllers\Admin\HistoryController as History;
use App\Http\Controllers\Admin\LoginController as Login;
use App\Http\Controllers\Admin\MenuController as Menu;
use App\Http\Controllers\Admin\NewsPromoController as NewsPromo;
use App\Http\Controllers\Admin\OvertimeController as Overtime;
use App\Http\Controllers\Admin\ProjectsController as Project;
use App\Http\Controllers\Admin\SurveyPublishController as SurveyPublish;
use App\Http\Controllers\Admin\SurveyResultController as SurveyResult;
use App\Http\Controllers\Admin\SurveyTemplateController as SurveyTemplate;
use App\Http\Controllers\Admin\SysSpecController as SysSpec;
use App\Http\Controllers\Admin\ManagementController as Management;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Dimuat oleh routes/web.php di dalam Route::prefix('admin') + middleware 'section:admin'.
| Semua path di sini otomatis berada di bawah /admin (contoh: '/dash' => /admin/dash).
| Controller: App\Http\Controllers\Admin, view: resources/views/admin.
| Login dilakukan satu pintu di "/" (PortalLoginController).
|
*/
// /admin -> dashboard kalau sudah login, kalau belum ke halaman login "/"
Route::get('/', [Login::class, 'index']);
Route::get('/logout', [Login::class, 'logout']);


Route::group(['middleware' => ['admin-auth', 'revalidate']], function () {
    // dulu terbuka tanpa login (daftar akun termasuk hash password) -> sekarang hanya admin
    Route::view('/account/forgot_password', 'account.index');
    Route::get('/account/data', [Account::class, 'getTable']);

    //DashboardController
    Route::get('/dash', [Dash::class, 'index']);
    Route::post('/dash/data/overtime', [Dash::class, 'getTableOT']);
    Route::post('/dash/dlpdf', [Dash::class, 'generatepdf']);
    Route::post('/dash/data/ticket', [Dash::class, 'getTableTicket']);
    Route::get('/dash/export/{nm?}', [Dash::class, 'export']);
    Route::get('/dash/electric-usage', [Dash::class, 'getElectricUsage']);

    //ManagementController
    Route::get('/management', [Management::class, 'index']);
    Route::get('/management/ap-aging', [Management::class, 'getApAging']);
    Route::get('/management/ar-aging', [Management::class, 'getArAging']);
    Route::get('/management/revenue-data', [Management::class, 'getRevenueData']);
    Route::get('/management/expense-data', [Management::class, 'getExpenseData']);
    Route::get('/management/profit-data', [Management::class, 'getProfitData']);
    Route::get('management/bank-balance', [Management::class, 'getBankBalance']);

    // //AccountController
    Route::view('/account/profile', 'account/profile');
    Route::get('/account/getbyemail/{email}', [Account::class, 'getbyemail']);
    Route::post('/account/updateprofile', [Account::class, 'updateprofile']);
    Route::post('/account/savepic', [Account::class, 'savepic']);
    Route::post('/account/changepass', [Account::class, 'changepass']);
    Route::view('/account/reset', 'account/index');
    Route::post('/account/data/', [Account::class, 'getTable']);
    Route::post('/account/resetpass', [Account::class, 'resetpass']);
    
    //Sys Spec Controller
    Route::get('/systemspec', [SysSpec::class, 'index']);
    Route::get('/systemspec/id/{id}', [SysSpec::class, 'getByID']);
    Route::post('/systemspec/saveimage', [SysSpec::class, 'imglogin']);
    Route::post('/systemspec/delete', [SysSpec::class, 'delete']);

      //OvertimeController
     Route::get('/overtime/approval', [Overtime::class, 'index_app']);
     Route::post('/overtime/data/new', [Overtime::class, 'getTableNewOT']);
     Route::post('/overtime/data/app', [Overtime::class, 'getTableAppOT']);
     Route::post('/overtime/data/cancel', [Overtime::class, 'getTableCancelOT']);
     Route::post('/overtime/approve', [Overtime::class, 'approveOT']);
     Route::post('/overtime/cancel', [Overtime::class, 'cancelOT']);
    Route::get('/overtime/posting', [Overtime::class, 'index_post']);
    Route::post('/overtime/posting/all', [Overtime::class, 'getTable']);
    Route::post('/overtime/posting/save', [Overtime::class, 'save']);
    Route::post('/overtime/posting/approve', [Overtime::class, 'approve_ot']);
    Route::post('/overtime/posting/cancel', [Overtime::class, 'cancel_ot']);


    //NewsPromoController
    Route::view('/news', 'news.index');
    Route::post('/news/all', [NewsPromo::class, 'getTable']);
    Route::get('/news/form/{type}/{id?}', [NewsPromo::class, 'addform']);
    Route::get('/news/id/{id}', [NewsPromo::class, 'getByID']);
    Route::post('/news/save', [NewsPromo::class, 'save']);
    Route::post('/news/savepic', [NewsPromo::class, 'savepic']);
    Route::post('/news/delete', [NewsPromo::class, 'delete']);

    //HistoryController
    Route::get('/history/ticket', [History::class, 'ticket']);
    Route::post('/history/data/ticket', [History::class, 'getTableTicket']);
    Route::get('/history/overtime', [History::class, 'overtime']);
    Route::post('/history/data/overtime', [History::class, 'getTableOT']);
    Route::view('/history/users', 'history.users');
    Route::post('/history/data/users', [History::class, 'getTableLog']);
    Route::post('/history/dlpdf', [History::class, 'dlpdf']);
    Route::get('/history/export/{type}', [History::class, 'export']);

    //SurveyController
    //SurveyTemplateController
    Route::view('/survey/questions', 'survey.questions.index');
    Route::post('/survey/questions/all', [SurveyTemplate::class, 'getTable']);
    Route::get('/survey/questions/id/{id}', [SurveyTemplate::class, 'getByID']);
    Route::view('/survey/questions/form', 'survey.questions.form');
    Route::post('/survey/questions/save', [SurveyTemplate::class, 'save']);
    Route::post('/survey/questions/delete', [SurveyTemplate::class, 'delete']);

    //SurveyPublishController
    Route::view('/survey/publish', 'survey.publish.index');
    Route::post('/survey/publish/all', [SurveyPublish::class, 'getTable']);
    Route::post('/survey/publish/allpublished', [SurveyPublish::class, 'getTable_publish']);
    Route::get('/survey/publish/id/{id}', [SurveyPublish::class, 'getByID']);
    Route::get('/survey/publish/form', [SurveyPublish::class, 'form']);
    Route::view('/survey/publish/add', 'survey.publish.publish');
    Route::post('/survey/publish/save', [SurveyPublish::class, 'save']);
    Route::post('/survey/publish/savepublish', [SurveyPublish::class, 'savepublish']);
    Route::post('/survey/publish/delete', [SurveyPublish::class, 'delete']);

    //SurveyResultController
    Route::view('/survey/result', 'survey.result.index');
    Route::post('/survey/result/all', [SurveyResult::class, 'getTable']);
    Route::get('/survey/result/see/{id}', [SurveyResult::class, 'viewresult']);
    Route::get('/survey/result/id/{id}', [SurveyResult::class, 'getByID']);
    // Route::post('/survey/result/dlpdf', [SurveyResult::class, 'generatepdf']);
    Route::get('/survey/result/export/{id}', [SurveyResult::class, 'generatepdf']);

    // Route::get('/account/forgot_password', [SurveyResult::class, 'generatepdf']);
    
});
