<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetUploadController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\KpiController; // ADDED: New KPI Controller
use App\Http\Controllers\AppraisalController; // ADDED: New Appraisal Controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use  MalaysiaHoliday\MalaysiaHoliday;

// Show login page
Route::get('/', function () {
    return view('welcome');
})->middleware('web')->name('welcome');

Route::get('/book', function () {
    return view('test.book'); // Your login form view
})->name('book'); // This handles the GET request

Route::get('/birthday', function () {
    return view('test.birthday'); // Your login form view
})->name('birthday'); // This handles the GET request
Route::get('/calendar-test', function () {
    return view('test');
});

// Show Forgot Password Form
Route::get('/forgot-password', function () {
    return view('forgot');
})->middleware('guest')->name('forgot');

Route::post('/forgot-password/check', [AuthController::class, 'check'])->name('forgot.check');
Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword'])->name('forgot.reset');

// Handle login form submission
Route::post('/', [AuthController::class, 'index'])->name('login.post'); // This handles the POST request

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::get('/past-employees', [EmployeeController::class, 'indexPast'])->name('past.employees');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/filter/emp', [EmployeeController::class, 'filterEmp'])->name('employees.filter');
    Route::get('/employees/filter/personal', [EmployeeController::class, 'filterPersonal'])->name('personal.filter');
    Route::get('/search', [EmployeeController::class, 'search'])->name('employees.search');

    Route::get('/employees/{lastSixDigits}/{employmentStatus}/{firstName}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/edit/{lastSixDigits}/{employmentStatus}/{firstName}', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/update/{lastSixDigits}/{employmentStatus}/{firstName}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::post('/uploadFile', [EmployeeController::class, 'uploadFile'])->name('uploadFile');

    Route::delete('/employees/unassign-asset', [EmployeeController::class, 'unassignAssetFromEmployee'])->name('employees.unassignAsset');
    Route::post('/employees/assign-asset', [EmployeeController::class, 'assignAssetToEmployee'])->name('employees.assignAsset');

    Route::get('/terminate', [EmployeeController::class, 'terminateForm'])->name('terminate.form');
    Route::post('/terminate/employee', [EmployeeController::class, 'terminate'])->name('terminate');

    Route::get('/uploademp', [UploadController::class, 'showEmp'])->name('uploademp.form');
    Route::get('/uploadfam', [UploadController::class, 'showFam'])->name('uploadfam.form');
    Route::post('/upload/employee', [UploadController::class, 'uploadEmployee'])->name('upload.emp');
    Route::post('/upload/family', [UploadController::class, 'uploadFamily'])->name('upload.family');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/family', [FamilyController::class, 'index'])->name('family.index');
    Route::post('family', [FamilyController::class, 'store'])->name('family.store');

    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{id}/edit', [JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{id}', [JobController::class, 'update'])->name('jobs.update');
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

    // Company Asset Routes
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/assets/{id}/edit', [AssetController::class, 'edit'])->name('assets.edit');
    Route::put('/assets/{id}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy'); // REQUIRED!
    Route::get('/assets/upload', [AssetUploadController::class, 'showUpload'])->name('assets.upload');
    Route::post('/assets/upload', [AssetUploadController::class, 'uploadAsset'])->name('upload.assets');
    Route::get('/assets/template/download', [AssetUploadController::class, 'downloadTemplate'])->name('assets.template.download');
    Route::resource('asset-categories', AssetCategoryController::class);
    Route::post('/assets/assign', [AssetController::class, 'assign'])->name('assets.assign');
    Route::post('/assets/unassign', [AssetController::class, 'unassign'])->name('assets.unassign');
    Route::post('/assets/unassign', [AssetController::class, 'unassign'])->name('assets.unassign');
    Route::get('/assets/search', [AssetController::class, 'search'])->name('assets.search');
    Route::get('/assets/filter', [AssetController::class, 'filter'])->name('assets.filter');

    Route::resource('company', CompanyController::class);
    Route::get('/company/{id}/employees', [CompanyController::class, 'employees'])->name('company.employees');

    // Routes for KPI System
    Route::resource('kpi', KpiController::class);
    Route::post('kpi/{kpi}/accept', [KpiController::class, 'accept'])->name('kpi.accept');
    Route::post('/kpi/{kpi}/request-revision', [KpiController::class, 'requestRevision'])->name(name: 'kpi.request-revision');
    Route::post('kpi-goals/{kpiGoal}/track', [KpiController::class, 'trackGoal'])->name('kpi.goal.track');

    // New routes for assigning KPIs
    Route::get('/kpi/assign/{kpi}', [KpiController::class, 'showAssignmentForm'])->name('kpi.assign.show');
    Route::post('/kpi/assign/{kpi}', [KpiController::class, 'assign'])->name('kpi.assign.store');

    Route::resource('appraisal', AppraisalController::class);

    // Routes for PMS Dashboard
    Route::get('/pms-dashboard', [DashboardController::class, 'pmsIndex'])->name('pms-dashboard');

    Route::get('/departments', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('/departments/store', [DepartmentController::class, 'store'])->name('department.store');
    Route::get('/departments/edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
    Route::post('/departments/update/{id}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/departments/destroy/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');
    Route::get('/departments/{id}/employees', [DepartmentController::class, 'employees'])->name('department.employees');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/test', function () {
    return redirect('/')->with('status', 'Test successful');
});
//validation
Route::post('/users/validate-field', [UserController::class, 'validateField'])->name('users.validateField');
Route::post('/users/validate-editfield', [UserController::class, 'validateEditField'])->name('users.validateEditField');
Route::post('/company/validate-field', [CompanyController::class, 'validateField'])->name('company.validateField');
Route::post('/company/validate-editfield', [CompanyController::class, 'validateEditField'])->name('company.validateEditField');
Route::post('/validate-login', [AuthController::class, 'validateLoginField'])->name('login.validate');
Route::post('/validate-field', [DepartmentController::class, 'validateField'])->name('department.validateField');
Route::post('/validate-edit-field', [DepartmentController::class, 'validateEditField'])->name('department.validateEditField');
