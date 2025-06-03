<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\ResidentDashboardController;
use App\Http\Controllers\Dashboard\CaptainDashboardController;
use App\Http\Controllers\Dashboard\OfficialDashboardController;
use App\Http\Controllers\Dashboard\SecretaryDashboardController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Secretary\ResidenceInformationController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Secretary\OfficialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\OfficialDocumentRequestController;
use App\Http\Controllers\Secretary\OfficialDocumentRequestController as SecretaryOfficialDocumentRequestController;
use App\Http\Controllers\ResidentDocumentRequestController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\Resident\StatusController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\MapLocationController;
use App\Http\Controllers\Resident\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\Admin\AdminMapController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Official\DashboardController;
use App\Http\Controllers\Official\ScheduleController as OfficialScheduleController;
use App\Http\Controllers\Official\OfficialController as OfficialOfficialController;
use App\Http\Controllers\Official\DocumentController;
use App\Http\Controllers\Official\ProjectController as OfficialProjectController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Official\InventoryController as OfficialInventoryController;
use App\Http\Controllers\Official\OfficialFeatureController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Dashboard Routes
Route::middleware(['web', 'auth'])->group(function () {
    // Resident Dashboard
    Route::get('/resident/dashboard', [ResidentDashboardController::class, 'index'])
        ->name('resident.dashboard');
    Route::get('/resident/services', [ResidentDashboardController::class, 'services'])
        ->name('resident.services');
    Route::get('/resident/documents', [ResidentDashboardController::class, 'documents'])
        ->name('resident.documents');

    // Captain Dashboard
    Route::get('/captain/dashboard', [CaptainDashboardController::class, 'index'])
        ->name('captain.dashboard');

    // Additional Captain Routes
    Route::prefix('captain')->name('captain.')->group(function () {
        Route::get('/schedule', [CaptainDashboardController::class, 'schedule'])->name('schedule');
        Route::get('/officials', [CaptainDashboardController::class, 'officials'])->name('officials.index');
        Route::patch('/officials/{official}/update-photo', [OfficialController::class, 'updatePhoto'])->name('officials.update-photo');
        Route::patch('/officials/{official}', [OfficialController::class, 'update'])->name('officials.update');
        Route::get('/residents', [CaptainDashboardController::class, 'residents'])->name('residents.index');
        Route::patch('/residents/{resident}/update-photo', [ResidenceInformationController::class, 'updatePhoto'])->name('residents.update-photo');
        Route::patch('/residents/{resident}/update-info', [ResidenceInformationController::class, 'updateInfo'])->name('residents.update-info');
        Route::patch('/residents/{resident}', [ResidenceInformationController::class, 'update'])->name('residents.update');
        Route::get('/documents', [CaptainDashboardController::class, 'documents'])->name('documents.index');
        Route::post('/documents/request', [OfficialDocumentRequestController::class, 'store'])->name('document.request.store');
        Route::put('/documents/request/status', [OfficialDocumentRequestController::class, 'updateStatus'])->name('document.request.update.status');
        Route::get('/projects', [CaptainDashboardController::class, 'projects'])->name('projects.index');
        Route::get('/map', [CaptainDashboardController::class, 'map'])->name('map');
        Route::get('/inventory', [CaptainDashboardController::class, 'inventory'])->name('inventory.index');
    });

    // Official Dashboard
    Route::get('/official/dashboard', [OfficialDashboardController::class, 'index'])
        ->name('official.dashboard');

    // Secretary Dashboard
    Route::get('/secretary/dashboard', [SecretaryDashboardController::class, 'index'])
        ->name('secretary.dashboard');
    Route::get('/secretary/map', [SecretaryDashboardController::class, 'showMap'])
        ->name('secretary.map');
    Route::get('/secretary/map/view', [SecretaryDashboardController::class, 'showMap'])
        ->name('map.view');

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Secretary Routes
    Route::get('/secretary/residence/new', [ResidenceInformationController::class, 'new_residence'])
        ->name('secretary.residence.new');
    Route::post('/secretary/residence/store', [ResidenceInformationController::class, 'store'])
        ->name('secretary.residence.store');

    // Secretary Inventory Routes
    Route::prefix('secretary/inventory')->name('secretary.inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\Secretary\InventoryController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Secretary\InventoryController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Secretary\InventoryController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\Secretary\InventoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Secretary\InventoryController::class, 'destroy'])->name('destroy');
        Route::post('/use', [App\Http\Controllers\Secretary\InventoryController::class, 'use'])->name('use');
    });

    // Inventory Routes
    Route::prefix('inventory')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/{id}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::put('/{id}', [InventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::post('/use', [InventoryController::class, 'use'])->name('inventory.use');
    });

    Route::get('/secretary/residence', [ResidenceInformationController::class, 'all_residents'])
        ->name('secretary.residence.all');
    Route::get('/secretary/user/resident', [UserController::class, 'showResidents'])
        ->name('secretary.user.resident');
    // Archive Routes
    Route::get('/secretary/residence/archived', [ResidenceInformationController::class, 'archived_residents'])
        ->name('secretary.residence.archived');
    Route::get('/secretary/residence/{id}', [ResidenceInformationController::class, 'show'])
        ->name('secretary.residence.show');
    Route::get('/secretary/residence/{id}/edit', [ResidenceInformationController::class, 'edit'])
        ->name('secretary.residence.edit');
    Route::patch('/secretary/residence/{id}/update', [ResidenceInformationController::class, 'updateInfo'])
        ->name('secretary.residence.update');
    Route::patch('/secretary/residence/{resident}/archive', [ResidenceInformationController::class, 'archive'])
        ->name('secretary.residence.archive');
    Route::patch('/secretary/residence/{resident}/restore', [ResidenceInformationController::class, 'restore'])
        ->name('secretary.residence.restore');

    // Official Routes
    Route::get('/secretary/official/new', [App\Http\Controllers\Secretary\OfficialController::class, 'create'])->name('officials.create');
    Route::get('/secretary/official', [OfficialController::class, 'index'])->name('officials.index');
    Route::get('/secretary/official/archived', [OfficialController::class, 'archived'])->name('officials.archived');
    Route::post('/secretary/official', [OfficialController::class, 'store'])->name('officials.store');
    Route::patch('/secretary/official/{official}/archive', [OfficialController::class, 'archive'])->name('officials.archive');
    Route::patch('/secretary/official/{official}/restore', [OfficialController::class, 'restore'])->name('officials.restore');
    Route::patch('/secretary/officials/{official}/update-info', [OfficialController::class, 'updateInfo'])->name('officials.update-info');
    Route::get('/secretary/officials/{id}', [App\Http\Controllers\Secretary\OfficialController::class, 'show'])->name('secretary.officials.show');
    Route::get('/secretary/officials/{id}/edit', [App\Http\Controllers\Secretary\OfficialController::class, 'edit'])->name('secretary.officials.edit');

    // profile-picture Routes
    Route::get('/profile-picture/{filename}', function ($filename) {
        $path = storage_path('app/private/profile_pictures/' . $filename);
    
        if (!file_exists($path)) {
            abort(404);
        }
    
        $mimeType = mime_content_type($path);
        return response()->file($path, ['Content-Type' => $mimeType]);
    })->name('profile.picture');
 
    // SecretaryDocument Request Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/secretary/documents', [SecretaryOfficialDocumentRequestController::class, 'index'])->name('secretary.documents.index');
        Route::post('/secretary/documents/request', [SecretaryOfficialDocumentRequestController::class, 'store'])->name('secretary.barangay.document.request.store');
        Route::put('/secretary/documents/request/status', [SecretaryOfficialDocumentRequestController::class, 'updateStatus'])->name('secretary.barangay.document.request.update.status');
    });
   
    // User Management Routes
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::put('/user/{user}/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');

    // Resident Routes
    Route::middleware(['auth'])->prefix('resident')->group(function () {
        // Dashboard
        Route::get('/dashboard', [ResidentDashboardController::class, 'index'])->name('resident.dashboard');
        Route::get('/services', [ResidentDashboardController::class, 'services'])->name('resident.services');
        Route::get('/documents', [ResidentDashboardController::class, 'documents'])->name('resident.documents');
        
        // Document Requests
        Route::get('/requestdocs', [ResidentDocumentRequestController::class, 'index'])->name('resident.requestdocs');

        // Profile
        Route::get('/profile', [ResidentDashboardController::class, 'profile'])->name('resident.profile');
    });

    Route::patch('/residents/{resident}/update-photo', [ResidenceInformationController::class, 'updatePhoto'])->name('residents.update-photo');
    Route::patch('/residents/{resident}/update-info', [ResidenceInformationController::class, 'updateInfo'])->name('residents.update-info');

    // Schedule Routes
    Route::resource('schedules', ScheduleController::class)->except(['show']);

    // Resident Document Request Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/resident/requestdocs', [ResidentDocumentRequestController::class, 'index'])->name('resident.requestdocs');
        Route::get('/resident/document-requests/{id}', [ResidentDocumentRequestController::class, 'show'])->name('resident.document.requests.show');
        Route::put('/resident/document-requests/{id}/status', [ResidentDocumentRequestController::class, 'updateStatus'])->name('resident.document.requests.update.status');
        Route::post('/resident/requestdocs', [ResidentDocumentRequestController::class, 'store'])->name('resident.requestdocs.submit');
    });

    // Complaint Routes
    Route::get('/resident/complain', [ComplaintController::class, 'create'])->name('resident.complain');
    Route::post('/resident/complain', [ComplaintController::class, 'store'])->name('resident.complain.store');

    // Resident Status Routes
    Route::get('/resident/complaints/status', [StatusController::class, 'complaintStatus'])
        ->name('resident.complaints.status');
    Route::get('/resident/documents/status', [StatusController::class, 'documentStatus'])
        ->name('resident.documents.status');

    // Walk-in Records Routes
    Route::middleware(['auth'])->prefix('secretary/records')->group(function () {
        Route::get('/', [App\Http\Controllers\Secretary\RecordController::class, 'index'])->name('secretary.records.index');
        Route::get('/create', [App\Http\Controllers\Secretary\RecordController::class, 'create'])->name('secretary.records.create');
        Route::post('/', [App\Http\Controllers\Secretary\RecordController::class, 'store'])->name('secretary.records.store');
        Route::get('/{record}', [App\Http\Controllers\Secretary\RecordController::class, 'show'])->name('secretary.records.show');
        Route::get('/{record}/edit', [App\Http\Controllers\Secretary\RecordController::class, 'edit'])->name('secretary.records.edit');
        Route::put('/{record}', [App\Http\Controllers\Secretary\RecordController::class, 'update'])->name('secretary.records.update');
        Route::delete('/{record}', [App\Http\Controllers\Secretary\RecordController::class, 'destroy'])->name('secretary.records.destroy');
        Route::get('/export', [App\Http\Controllers\Secretary\RecordController::class, 'export'])->name('secretary.records.export');
    });

    // Official Routes
    Route::prefix('official')->group(function () {
        Route::get('/inventory', [OfficialInventoryController::class, 'index'])->name('official.inventory.index');
        Route::post('/inventory', [OfficialInventoryController::class, 'store'])->name('official.inventory.store');
        Route::get('/inventory/{id}', [OfficialInventoryController::class, 'show'])->name('official.inventory.show');
        Route::put('/inventory/{id}', [OfficialInventoryController::class, 'update'])->name('official.inventory.update');
        Route::delete('/inventory/{id}', [OfficialInventoryController::class, 'destroy'])->name('official.inventory.destroy');
    });

    // Secretary Project Routes
    Route::prefix('secretary/projects')->name('secretary.projects.')->group(function () {
        Route::get('/', [App\Http\Controllers\Secretary\ProjectController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Secretary\ProjectController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Secretary\ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [App\Http\Controllers\Secretary\ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [App\Http\Controllers\Secretary\ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [App\Http\Controllers\Secretary\ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [App\Http\Controllers\Secretary\ProjectController::class, 'destroy'])->name('destroy');
    });

    // Secretary Inventory Routes
    Route::prefix('secretary/inventory')->name('secretary.inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\Secretary\InventoryController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Secretary\InventoryController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Secretary\InventoryController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\Secretary\InventoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Secretary\InventoryController::class, 'destroy'])->name('destroy');
    });
});

// Officials Routes
Route::prefix('officials')->group(function () {
    Route::get('/', [OfficialController::class, 'index'])->name('officials.index');
    Route::get('/create', [OfficialController::class, 'create'])->name('officials.create');
    Route::post('/', [OfficialController::class, 'store'])->name('officials.store');
    Route::get('/{id}/edit', [OfficialController::class, 'edit'])->name('officials.edit');
    Route::patch('/{official}', [OfficialController::class, 'update'])->name('officials.update');
    Route::patch('/{official}/archive', [OfficialController::class, 'archive'])->name('officials.archive');
    Route::patch('/{official}/restore', [OfficialController::class, 'restore'])->name('officials.restore');
    Route::get('/archived', [OfficialController::class, 'archived'])->name('officials.archived');
    Route::post('/{official}/profile-picture', [OfficialController::class, 'updatePhoto'])->name('officials.update-profile-picture');
});

Route::get('/administrators', [UserController::class, 'showAdministrators'])->name('administrators.show');
Route::get('/residents', [UserController::class, 'showResidents'])->name('residents.show');

// Project Routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
Route::get('/projects/{project}/get', [ProjectController::class, 'getProject'])->name('projects.get');
Route::get('/projects/{project}/location', [ProjectController::class, 'getLocation']);
Route::post('/projects/{project}/location', [ProjectController::class, 'updateLocation']);

// Expense Routes
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
Route::get('/expenses/{expense}', [ExpenseController::class, 'getExpense'])->name('expenses.get');
Route::patch('/expenses/{expense}/status', [ExpenseController::class, 'updateStatus'])->name('expenses.update.status');

// Budget Routes
Route::get('/budget', [BudgetController::class, 'index'])->name('budget.index');
Route::post('/budget', [BudgetController::class, 'store'])->name('budget.store');
Route::put('/budget/{budget}', [BudgetController::class, 'update'])->name('budget.update');
Route::delete('/budget/{budget}', [BudgetController::class, 'destroy'])->name('budget.destroy');
Route::get('/budget/{budget}', [BudgetController::class, 'show'])->name('budget.show');

// Official Document Request Routes
Route::middleware(['auth'])->prefix('official')->name('official.')->group(function () {
    Route::get('/documents', [OfficialDocumentRequestController::class, 'index'])->name('documents');
    Route::post('/document/request', [OfficialDocumentRequestController::class, 'store'])->name('document.request.store');
    Route::put('/document/request/status', [OfficialDocumentRequestController::class, 'updateStatus'])->name('document.request.update.status');
});


// Document printing routes
Route::middleware(['auth'])->group(function () {
    Route::get('/secretary/documents/print/clearance/{id}', [App\Http\Controllers\Secretary\DocumentController::class, 'printClearance'])
        ->name('secretary.documents.print.clearance');
    Route::get('/secretary/documents/print/residency/{id}', [App\Http\Controllers\Secretary\DocumentController::class, 'printResidency'])
        ->name('secretary.documents.print.residency');
    Route::get('/secretary/documents/print/certification/{id}', [App\Http\Controllers\Secretary\DocumentController::class, 'printCertification'])
        ->name('secretary.documents.print.certification');
    Route::get('/secretary/blotter/print', [App\Http\Controllers\Secretary\BlotterController::class, 'print'])
        ->name('secretary.blotter.print');
});

// Certificate Generation Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/secretary/certificates/clearance', [App\Http\Controllers\Secretary\CertificateController::class, 'generateClearance'])
        ->name('secretary.certificates.clearance');
    Route::post('/secretary/certificates/residency', [App\Http\Controllers\Secretary\CertificateController::class, 'generateResidency'])
        ->name('secretary.certificates.residency');
    Route::post('/secretary/certificates/certification', [App\Http\Controllers\Secretary\CertificateController::class, 'generateCertification'])
        ->name('secretary.certificates.certification');
});

// Secretary Complaint Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/secretary/complaints', [App\Http\Controllers\Secretary\ComplaintController::class, 'index'])->name('secretary.complaints.index');
    Route::post('/secretary/complaints', [App\Http\Controllers\Secretary\ComplaintController::class, 'store'])->name('secretary.complaints.store');
    Route::put('/secretary/complaints/status', [App\Http\Controllers\Secretary\ComplaintController::class, 'updateStatus'])->name('secretary.complaints.status');
    Route::get('/secretary/complaints/{id}', [App\Http\Controllers\Secretary\ComplaintController::class, 'show'])->name('secretary.complaints.show');
});

// Blotter Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/secretary/blotter', [BlotterController::class, 'index'])->name('secretary.blotter');
    Route::get('/secretary/blotter/create', [BlotterController::class, 'create'])->name('secretary.blotter.create');
    Route::post('/secretary/blotter', [BlotterController::class, 'store'])->name('secretary.blotter.store');
    Route::post('/secretary/blotter/transfer', [BlotterController::class, 'transferFromComplaint'])->name('secretary.blotter.transfer');
    Route::put('/secretary/blotter/status', [BlotterController::class, 'updateStatus'])->name('secretary.blotter.status');
});

// Summon Printing Routes
Route::get('/secretary/summon/print', [App\Http\Controllers\Secretary\SummonController::class, 'print'])->name('secretary.summon.print');

// Activity Logs Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/secretary/activity-logs', [App\Http\Controllers\Secretary\RecordController::class, 'index'])->name('secretary.activity-logs');
});

// Map Location Routes
Route::prefix('map-locations')->group(function () {
    Route::get('/', [MapLocationController::class, 'index'])->name('map-locations.index');
    Route::post('/', [MapLocationController::class, 'store'])->name('map-locations.store');
    Route::delete('/{id}', [MapLocationController::class, 'destroy'])->name('map-locations.destroy');
    Route::get('/households', [MapLocationController::class, 'getHouseholds'])->name('map-locations.households');
    Route::get('/purok-stats/{purok}', [MapLocationController::class, 'getPurokStats'])->name('map-locations.purok-stats');
    Route::put('/{id}', [MapLocationController::class, 'update']);
});

// Captain Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Captain\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule', [App\Http\Controllers\Captain\CScheduleController::class, 'index'])->name('schedule');
    Route::get('/map', [App\Http\Controllers\Captain\MapController::class, 'index'])->name('map');
    
    // Inventory Management Routes
    Route::get('/inventory', [App\Http\Controllers\Captain\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [App\Http\Controllers\Captain\InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{id}', [App\Http\Controllers\Captain\InventoryController::class, 'show'])->name('inventory.show');
    Route::put('/inventory/{id}', [App\Http\Controllers\Captain\InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [App\Http\Controllers\Captain\InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::post('/inventory/use', [App\Http\Controllers\Captain\InventoryController::class, 'use'])->name('inventory.use');
    
    // Projects Routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [App\Http\Controllers\Captain\ProjectController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Captain\ProjectController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Captain\ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [App\Http\Controllers\Captain\ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [App\Http\Controllers\Captain\ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [App\Http\Controllers\Captain\ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [App\Http\Controllers\Captain\ProjectController::class, 'destroy'])->name('destroy');
    });

    // Officials Routes
    Route::get('/officials', [App\Http\Controllers\Captain\OfficialController::class, 'index'])->name('officials.index');
    Route::post('/officials', [App\Http\Controllers\Captain\OfficialController::class, 'store'])->name('officials.store');
    Route::get('/officials/archived', [App\Http\Controllers\Captain\OfficialController::class, 'archived'])->name('officials.archived');
    Route::get('/officials/{official}', [App\Http\Controllers\Captain\OfficialController::class, 'show'])->name('officials.show');
    Route::get('/officials/{official}/edit', [App\Http\Controllers\Captain\OfficialController::class, 'edit'])->name('officials.edit');
    Route::patch('/officials/{official}', [App\Http\Controllers\Captain\OfficialController::class, 'update'])->name('officials.update');
    Route::patch('/officials/{official}/photo', [App\Http\Controllers\Captain\OfficialController::class, 'updatePhoto'])->name('officials.update-photo');
    Route::patch('/officials/{official}/archive', [App\Http\Controllers\Captain\OfficialController::class, 'archive'])->name('officials.archive');
    Route::patch('/officials/{official}/restore', [App\Http\Controllers\Captain\OfficialController::class, 'restore'])->name('officials.restore');
    
    // Residents Routes
    Route::get('/residents', [App\Http\Controllers\Captain\ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/create', [App\Http\Controllers\Captain\ResidentController::class, 'create'])->name('residents.create');
    Route::post('/residents', [App\Http\Controllers\Captain\ResidentController::class, 'store'])->name('residents.store');
    Route::get('/residents/archived', [App\Http\Controllers\Captain\ResidentController::class, 'archived'])->name('residents.archived');
    Route::get('/residents/{resident}', [App\Http\Controllers\Captain\ResidentController::class, 'show'])->name('residents.show');
    Route::get('/residents/{resident}/edit', [App\Http\Controllers\Captain\ResidentController::class, 'edit'])->name('residents.edit');
    Route::patch('/residents/{resident}', [App\Http\Controllers\Captain\ResidentController::class, 'update'])->name('residents.update');
    Route::patch('/residents/{resident}/photo', [App\Http\Controllers\Captain\ResidentController::class, 'updatePhoto'])->name('residents.update-photo');
    Route::patch('/residents/{resident}/archive', [App\Http\Controllers\Captain\ResidentController::class, 'archive'])->name('residents.archive');
    Route::patch('/residents/{resident}/restore', [App\Http\Controllers\Captain\ResidentController::class, 'restore'])->name('residents.restore');

    // Documents Routes
    Route::get('/documents', [App\Http\Controllers\Captain\CaptainDocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents/request', [App\Http\Controllers\Captain\CaptainDocumentController::class, 'store'])->name('document.request.store');
    Route::put('/documents/request/status', [App\Http\Controllers\Captain\CaptainDocumentController::class, 'updateStatus'])->name('document.request.update.status');
    Route::get('/documents/print/{type}/{id}', [App\Http\Controllers\Captain\CaptainDocumentController::class, 'printDocument'])->name('document.print');

    // Complaints Routes
    Route::get('/complaints', [App\Http\Controllers\Captain\ComplaintController::class, 'index'])->name('captain.complaints');
});

Route::get('/households/{household}/members', [HouseholdController::class, 'getMembers'])->name('households.members');

Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');

// Admin User Management Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::put('/users/{user}/change-password', [UserManagementController::class, 'changePassword'])->name('admin.users.change-password');
    Route::put('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    
    // Position Management Routes
    Route::get('/role', [PositionController::class, 'index'])->name('admin.role');
    Route::post('/role', [PositionController::class, 'store'])->name('positions.store');
    Route::put('/role/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/role/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
});

// Position Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::post('/positions', [PositionController::class, 'store'])->name('positions.store');
    Route::put('/positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('/positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
});

// Admin Routes
Route::middleware(['auth'])->group(function () {
    // Official Archive Routes
    Route::get('/admin/officials', [App\Http\Controllers\Admin\OfficialController::class, 'index'])
        ->name('admin.officials.index');
    Route::get('/admin/officials/archived', [App\Http\Controllers\Admin\OfficialController::class, 'archived'])
        ->name('admin.officials.archived');
    Route::patch('/admin/officials/{official}/restore', [App\Http\Controllers\Admin\OfficialController::class, 'restore'])
        ->name('admin.officials.restore');

    // Resident Archive Routes
    Route::get('/residence/archived', [App\Http\Controllers\Admin\ResidenceController::class, 'archived'])
        ->name('admin.residence.archived');
    Route::patch('/residence/{resident}/restore', [App\Http\Controllers\Admin\ResidenceController::class, 'restore'])
        ->name('admin.residence.restore');
});

// Backup and Restore Routes
Route::prefix('admin')->group(function () {
    Route::get('/backup', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('admin.backup.index');
    Route::post('/backup/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('admin.backup.create');
    Route::post('/backup/restore', [App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('admin.backup.restore');
    Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('admin.backup.download');
    Route::delete('/backup/delete/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('admin.backup.delete');
});

// Admin Map Routes
Route::prefix('admin')->group(function () {
    Route::get('/map', [App\Http\Controllers\Admin\AdminMapController::class, 'index'])->name('admin.map');
    Route::get('/map-locations', [App\Http\Controllers\Admin\AdminMapController::class, 'getLocations']);
    Route::post('/map-locations', [App\Http\Controllers\Admin\AdminMapController::class, 'store']);
    Route::put('/map-locations/{id}', [App\Http\Controllers\Admin\AdminMapController::class, 'update']);
    Route::delete('/map-locations/{id}', [App\Http\Controllers\Admin\AdminMapController::class, 'destroy']);
    Route::get('/map-locations/households', [App\Http\Controllers\Admin\AdminMapController::class, 'getHouseholds']);
    Route::get('/map-locations/search', [App\Http\Controllers\Admin\AdminMapController::class, 'search']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/statistics', [DashboardController::class, 'getStatistics']);
    Route::get('/dashboard/charts', [DashboardController::class, 'getCharts']);
});

// Map Routes
Route::middleware(['auth'])->group(function () {
    // Captain Map Routes
    Route::get('/captain/map', [App\Http\Controllers\Captain\MapController::class, 'index'])->name('captain.map');
    Route::get('/captain/map-locations', [App\Http\Controllers\Captain\MapController::class, 'getLocations'])->name('captain.map.locations');
    Route::get('/captain/map-locations/purok-stats/{purokName}', [App\Http\Controllers\Captain\MapController::class, 'getPurokStats'])->name('captain.map.purok.stats');
    Route::get('/captain/map-locations/households', [App\Http\Controllers\Captain\MapController::class, 'getHouseholds'])->name('captain.map.households');
    Route::get('/captain/api/search', [App\Http\Controllers\Captain\MapController::class, 'search'])->name('captain.map.search');
});

// Official Portal Routes
Route::middleware(['auth'])->prefix('official')->name('official.')->group(function () {
    // Existing official routes...
    
    // Project management routes
    Route::get('/projects', [OfficialProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [OfficialProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [OfficialProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [OfficialProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [OfficialProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [OfficialProjectController::class, 'destroy'])->name('projects.destroy');
});

// Official Schedule Routes
 Route::get('/schedule', [App\Http\Controllers\Official\ScheduleController::class, 'index'])->name('official.schedule');

// Official Inventory Routes
Route::middleware(['auth',])->prefix('official/inventory')->name('official.inventory.')->group(function () {
    Route::get('/', [App\Http\Controllers\Official\InventoryController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Official\InventoryController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Official\InventoryController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\Official\InventoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Official\InventoryController::class, 'destroy'])->name('destroy');
    Route::post('/use', [App\Http\Controllers\Official\InventoryController::class, 'use'])->name('use');
});

Route::get('/api/projects', [App\Http\Controllers\ProjectController::class, 'index']);
Route::get('/api/projects/{id}', [App\Http\Controllers\ProjectController::class, 'show']);

Route::get('/secretary/notifications', [App\Http\Controllers\Secretary\ActivityLogController::class, 'notifications'])->name('secretary.notifications');

// Official Feature Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [OfficialFeatureController::class, 'dashboard'])->name('official.dashboard');
    Route::get('/schedule', [OfficialFeatureController::class, 'schedule'])->name('official.schedule');
    Route::get('/officials', [OfficialFeatureController::class, 'officials'])->name('official.officials');
    Route::get('/residents', [OfficialFeatureController::class, 'residents'])->name('official.residents');
    Route::get('/documents', [OfficialFeatureController::class, 'documents'])->name('official.documents');
    Route::get('/projects', [OfficialFeatureController::class, 'projects'])->name('official.projects');
    Route::get('/map', [OfficialFeatureController::class, 'map'])->name('official.map');
});

// Admin Activity Logs Routes
Route::middleware(['auth',])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs');
    Route::get('/activity-logs/export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('/activity-logs/notifications', [App\Http\Controllers\Admin\ActivityLogController::class, 'notifications'])->name('activity-logs.notifications');
});
