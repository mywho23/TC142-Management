<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Models\Device;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MmiController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\CeklisController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\QtgController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\LogbookTimerController;
use App\Http\Controllers\TeknisiLogbookController;
use App\Http\Controllers\RekapJamController;
use Faker\Guesser\Name;
use Psy\CodeCleaner\FunctionContextPass;

//Route::get('/welcom', function () { return view('welcome'); });
Route::get('/testview', function () {
    return view('testview');
});

Route::prefix('auth')->name('auth.')->group(function () {

    // Halaman login
    Route::get('/login', [AuthController::class, 'loginPage'])
        ->name('login');

    // Proses login
    Route::post('/login', [AuthController::class, 'loginProcess'])
        ->name('login.process');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware('auth.custom')->group(function () {

    //Dashboard
    Route::get('/', function () {

        $user = Auth::user();

        if (hasRole(['Administrator', 'Engineer'])) {
            $devices = Device::all();
            $totalDevices = Device::count();
            return view('pages.dashboard', compact('devices', 'totalDevices'));
        }

        if (hasRole(['Operation'])) {
            return redirect()->route('operation.home');
        }

        if (hasRole(['SQM'])) {
            return redirect()->route('sqm.home');
        }

        // future: customer
        if (hasRole(['customer'])) {
            return redirect()->route('customer.home');
        }

        abort(404);
    })->name('dashboard');

    Route::prefix('operation')->name('operation.')->group(function () {

        Route::get('/home', function () {
            $devices = Device::all();
            return view('pages.operasi.home', compact('devices'));
        })->name('home');
    });

    Route::prefix('sqm')->name('sqm.')->group(function () {

        Route::get('/home', function () {
            $devices = Device::all();
            return view('pages.sqm.home', compact('devices'));
        })->name('home');
    });

    Route::middleware(['auth.custom', 'role:Administrator'])->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create1', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware(['auth.custom', 'role:Administrator,SQM,Operation,Engineer'])->group(function () {
        Route::prefix('device')->name('device.')->group(function () {
            Route::get('/create0', [DeviceController::class, 'create'])->name('create');
            Route::post('/', [DeviceController::class, 'save'])->name('save');
            Route::put('/{id}', [DeviceController::class, 'update'])->name('update');
        });

        Route::prefix('jadwal')->name('jadwal.')->group(function () {

            Route::get('/rekap-jam', [RekapJamController::class, 'index'])->name('rekap');
            Route::get('/rekap-jam/{device}/show', [RekapJamController::class, 'show'])->name('rekap.device');
            Route::get('/device/{device}/print', [RekapJamController::class, 'print'])->name('print');
        });

        Route::prefix('jadwal/{device_id}/menu')->name('jadwal.')->group(function () {
            Route::get('/', [JadwalController::class, 'index'])->name('index');
            Route::get('/create2', [JadwalController::class, 'create'])->name('create');
            Route::post('/', [JadwalController::class, 'store'])->name('store');
            Route::put('/{id}', [JadwalController::class, 'update'])->name('update');
            Route::delete('/{id}', [JadwalController::class, 'hapus'])->name('hapus');
        });

        Route::prefix('mmi/{device_id}/index')->name('mmi.')->group(function () {
            Route::get('/', [MmiController::class, 'index'])->name('index');
            Route::get('/create3', [MmiController::class, 'create'])->name('create');
            Route::post('/', [MmiController::class, 'store'])->name('store');
            Route::put('/{id}', [MmiController::class, 'update'])->name('update');
            Route::delete('/{id}', [MmiController::class, 'hapus'])->name('hapus');
            Route::get('/print', [MmiController::class, 'print'])->name('print');
        });
    });

    Route::middleware(['auth.custom', 'role:Administrator,Engineer'])->group(function () {
        Route::prefix('record/{device_id}/maintenance')->name('record.')->group(function () {
            Route::get('/', [RecordController::class, 'menu'])->name('menu');
            Route::get('/create4', [RecordController::class, 'create'])->name('create');
            Route::post('/', [RecordController::class, 'save'])->name('save');
            Route::put('/edit/{id}', [RecordController::class, 'update'])->name('update');
            Route::get('/{id}/print', [RecordController::class, 'print'])->name('print');
            Route::delete('/remove/{id}', [RecordController::class, 'delete'])->name('delete');
        });

        Route::prefix('ceklis')->name('ceklis.')->group(function () {

            Route::get('/', [CeklisController::class, 'index'])->name('index');

            // Step 1: pilih device & tipe
            Route::get('/create5', [CeklisController::class, 'create'])->name('create');
            Route::post('/next', [CeklisController::class, 'nextStep'])->name('next');

            // Step 2: tampilkan item checklist
            Route::get('/{device}/{tipe}/items', [CeklisController::class, 'showItems'])->name('items');

            // Submit hasil checklist
            Route::post('/{device}/{tipe}/store', [CeklisController::class, 'store'])->name('store');

            // Ajax update status (kalau tetap pakai)
            Route::post('/update-status', [CeklisController::class, 'updateStatus'])->name('updateStatus');
            Route::get('/{id}/print', [CeklisController::class, 'print'])->name('print');
            Route::delete('/{device_id}/{id}', [CeklisController::class, 'delete'])->name('delete');
        });

        Route::prefix('qtg/{device_id}/chapter')->name('qtg.chapter.')->group(function () {
            //Route::get('/', [QtgController::class, 'index'])->name('index');
            Route::get('/{year?}', [QtgController::class, 'index'])->name('index');
            Route::post('/pilih-tahun', [QtgController::class, 'tahun'])->name('tahun');
            Route::post('/add-year', [QtgController::class, 'addYear'])->name('addyear');
            Route::post('/upload', [QtgController::class, 'storeUpload'])->name('upload');
            Route::delete('/upload/{id}/delete', [QtgController::class, 'deleteUpload'])->name('delete');
            Route::get('/{id}/pdf', [QtgController::class, 'streamPdf'])->name('pdf');
            Route::get('/{year}/print', [QtgController::class, 'print'])->name('print');
        });

        Route::prefix('logbook/{device_id}/customer')->name('logbook.')->group(function () {
            Route::get('/', [LogbookController::class, 'indexCustomer'])->name('indexCustomer');
            Route::get('/{logbook_id}/edit', [LogbookController::class, 'editCustomer'])->name('editCustomer');
            Route::put('/{logbook_id}', [LogbookController::class, 'updateCustomer'])->name('updateCustomer');
            Route::get('/{logbook}/print', [LogbookController::class, 'print'])->name('print');
        });

        Route::prefix('timer')->name('timer.')->group(function () {
            Route::get('/create/{logbook}/', [LogbookTimerController::class, 'create'])->name('create');
            Route::get('/device/{device}/timer', [LogbookTimerController::class, 'select'])->name('select');
            Route::post('/start/{logbook}', [LogbookTimerController::class, 'start'])->name('start');
            Route::post('/pause/{logbook}', [LogbookTimerController::class, 'pause'])->name('pause');
            Route::post('/resume/{logbook}', [LogbookTimerController::class, 'resume'])->name('resume');
            //Route::post('/tick/{timerlog}', [LogbookTimerController::class, 'tick'])->name('tick'); // autosave 10 detik
            Route::post('/stop/{logbook}', [LogbookTimerController::class, 'stop'])->name('stop');
            Route::post('/store/{logbook}', [LogbookTimerController::class, 'store'])->name('store');
            Route::delete('/delete/{timerlog}', [LogbookTimerController::class, 'delete'])->name('delete');
        });

        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/index', [InventoryController::class, 'index'])->name('index');
            Route::get('/create/penyimpanan', [InventoryController::class, 'create'])->name('create');
            Route::post('/store', [InventoryController::class, 'store'])->name('store');
            Route::delete('/delete/{id}/', [InventoryController::class, 'delete'])->name('delete');
            Route::get('/edit/{id}/inventory/', [InventoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InventoryController::class, 'update'])->name('update');
        });
    });

    Route::middleware(['auth.custom', 'role:Engineer'])->group(function () {
        Route::prefix('teknisi')->name('teknisi.')->group(function () {
            Route::get('/panel', [TeknisiLogbookController::class, 'dashboard'])->name('panel');
            Route::get('/menulogbook/{device_id}/panel', [TeknisiLogbookController::class, 'menulogbook'])->name('menulogbook');
            Route::get('/device/{id}/panel', [TeknisiLogbookController::class, 'devicePanel'])->name('devicepanel');
            Route::post('/device/{device_id}/release', [TeknisiLogbookController::class, 'release'])->name('release');
            Route::post('/device/{device_id}/accept', [TeknisiLogbookController::class, 'accept'])->name('accept');
            Route::get('/logbook/{id}/detail', [LogbookController::class, 'detail'])->name('logbook.detail');
            Route::get('/logbook/{id}/timer', [LogbookTimerController::class, 'timer'])->name('logbook.timer');
            Route::delete('/logbook/{id}/delete', [TeknisiLogbookController::class, 'deleteLogbook'])->name('logbook.delete');
            Route::get('/device/{device_id}/diskrepansi/pending', [TeknisiLogbookController::class, 'komplainPending'])->name('diskrepansi.pending');
            Route::get('/diskrepansi/{logbook_id}/jawab', [TeknisiLogbookController::class, 'jawabForm'])->name('diskrepansi.jawab.form');
            Route::post('/diskrepansi/{logbook_id}/jawab', [TeknisiLogbookController::class, 'jawabStore'])->name('diskrepansi.jawab.store');
        });
    });
});
