<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

 // PRUEBAS
 Route::get('/pruebas', [App\Http\Controllers\PruebasController::class,'dolar_del_dia']);

// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// storage links y path servidor produccion
// ------------------------------------------------------------------

Route::get('path', function(){
    echo ('PATH REAL:  '.realpath(base_path('/../html/sistema')));
    echo('<br>');echo('<br>');
    echo('PUBLIC PATH: '.public_path());
    echo('<br>');echo('<br>');
    echo('BASE PATH: '.base_path());
    echo('<br>');echo('<br>');
    echo('STORAGE PATH: '.storage_path());
 });
           /// este es el que funciona: Realiza el storage:link//
Route::get('symlink', function(){
    $storageFolder = storage_path().'/app/public';
    $linkFolder = public_path().'/storage';
    symlink($storageFolder,$linkFolder);
    echo 'se complento el storage:link utilizando el Symlink';
});

 // Localization
 Route::get('locale/{lang}', [App\Http\Controllers\LocaleController::class,'setLocale']);

// Frontend routes
Route::prefix('front')->as('front.')->group(function () {
    // Nothing here yet
});

// Backend routes
Route::prefix('back')->as('back.')->group(function () {
    // USERS
    Route::middleware('auth')->group(function () {
        /* ------------------------------------------------------------------------ */
        // General
        Route::controller(App\Http\Controllers\Back\GeneralController::class)->group(function () {
            Route::post('/general/setValueDB', 'setValueDB')->name('general.setValueDB');
            Route::post('/general/setValueSession', 'setValueSession')->name('general.setValueSession');
            Route::get('/general/getDatatablesHelp', 'getDatatablesHelp')->name('general.getDatatablesHelp');
        });
        /* ---------------------------------------- */
        // Customers
        Route::controller(App\Http\Controllers\Back\CustomerController::class)->group(function () {
            Route::delete('/customers/massDestroy', 'massDestroy')->name('customers.massDestroy');
            Route::get('/customers/getAlikes', 'getAlikes')->name('customers.getAlikes');

            Route::resource('/customers', App\Http\Controllers\Back\CustomerController::class)->except(['destroy']);
        });
        /* ------------------------------------------------------------------------ */
    });

    // DEVELOPER
    Route::middleware('auth', 'can:propietario')->group(function () {
        /* ------------------------------------------------------------------------ */
        // Developer
        Route::controller(App\Http\Controllers\Back\DeveloperController::class)->group(function () {
            Route::get('/developer/hashGenerator', 'hashGenerator')->name('developer.hashGenerator');
            Route::get('/developer/impressum', 'impressum')->name('developer.impressum');
            Route::get('/developer/session', 'session')->name('developer.session');
            Route::get('/developer/test', 'test')->name('developer.test');
        });
        /* ---------------------------------------- */
        // Backups
        Route::controller(App\Http\Controllers\Back\BackupController::class)->group(function () {
            Route::get('/backups', 'index')->name('backups.index');
            Route::get('/backups/create', 'create')->name('backups.create');
            Route::get('/backups/download/{file_name}', 'download')->name('backups.download');
            Route::get('/backups/delete/{file_name}', 'delete')->name('backups.delete');
        });
        /* ------------------------------------------------------------------------ */
        // Users
        Route::controller(App\Http\Controllers\Back\UserController::class)->group(function () {
            Route::get('/users/getUserlogs', 'getUserlogs')->name('users.getUserlogs');
            Route::delete('/users/massDestroy', 'massDestroy')->name('users.massDestroy');
            Route::get('/users/modal', 'showModal')->name('users.modal');
            Route::get('/users/notification/{message} ', 'showMessage')->name('users.message');

            Route::resource('/users', App\Http\Controllers\Back\UserController::class)->except(['show', 'destroy']);
        });

        // Users log
        Route::controller(App\Http\Controllers\Back\UserlogController::class)->group(function () {
            Route::get('/userslog/index', 'index')->name('userslog.index');
            Route::get('/userslog/statsCountry', 'statsCountry')->name('userslog.statsCountry');
            Route::get('/userslog/statsCountryMap', 'statsCountryMap')->name('userslog.statsCountryMap');
            Route::get('/userslog/statsPeriod', 'statsPeriod')->name('userslog.statsPeriod');
        });
        /* ------------------------------------------------------------------------ */
        // Usuarios
        Route::controller(App\Http\Controllers\Back\UsuarioController::class)->group(function () {
                    Route::get('/usuarios/getUserlogs', 'getUserlogs')->name('usuarios.getUserlogs');
                    Route::resource('/usuarios', App\Http\Controllers\Back\UsuarioController::class)->except(['show', 'destroy']);
        });
        /* ------------------------------------------------------------------------ */
        // Divisas
        Route::controller(App\Http\Controllers\Back\DivisaController::class)->group(function () {
            Route::get('/divisas/refresh', 'refresh_data')->name('divisas.refresh');
            Route::delete('/divisas/massDestroy', 'massDestroy')->name('divisas.massDestroy');
            Route::get('/divisas/notification/{message} ', 'showMessage')->name('divisas.message');
            Route::resource('/divisas', App\Http\Controllers\Back\DivisaController::class)->except(['show', 'destroy']);
        });     
        /* ------------------------------------------------------------------------ */
        // Perfil
            Route::controller(App\Http\Controllers\Back\ProfileController::class)->group(function () {
            Route::get('/profile', 'show')->name('profile.show');
            Route::put('/profile/update/{user}', 'update')->name('profile.update');
            Route::get('/profile/update-password', 'showPassword')->name('profile.showPassword');
            Route::put('/profile/update-password/{user}', 'updatePassword')->name('profile.updatePassword');

        });

        /* ------------------------------------------------------------------------ */
        // Programas y Menciones estan los dos en el mismo controller
        Route::controller(App\Http\Controllers\Back\ProgramaController::class)->group(function () {
            Route::get('/tablas_base', 'index')->name('tablas.index');
            Route::get('/programas', 'programas')->name('programas.datos');
            Route::get('/programas/create', 'programa_create')->name('programas.create');
            Route::post('/programas/store', 'programa_store')->name('programas.store');
            Route::get('/programas/{programa}/edit', 'programa_edit')->name('programas.edit'); 
            Route::put('/programas/{programa}', 'programa_update')->name('programas.update');  
            Route::delete('/programas/massDestroy', 'programaDestroy')->name('programas.Destroy');                      
            Route::get('/menciones', 'menciones')->name('menciones.datos');
            Route::get('/menciones/create', 'mencion_create')->name('menciones.create');
            Route::post('/menciones/store', 'mencion_store')->name('menciones.store');
            Route::get('/menciones/{mencion}/edit', 'mencion_edit')->name('menciones.edit'); 
            Route::put('/menciones/{mencion}', 'mencion_update')->name('menciones.update');  
            Route::delete('/menciones/massDestroy', 'mencionDestroy')->name('menciones.Destroy'); 
            Route::get('/cohortes', 'cohortes')->name('cohortes.datos');  
            Route::get('/cohortes/create', 'cohorte_create')->name('cohortes.create');
            Route::post('/cohortes/store', 'cohorte_store')->name('cohortes.store');
            Route::get('/cohortes/{cohorte}/edit', 'cohorte_edit')->name('cohortes.edit'); 
            Route::put('/cohortes/{cohorte}', 'cohorte_update')->name('cohortes.update');  
            Route::delete('/cohortes/massDestroy', 'cohorteDestroy')->name('cohortes.Destroy'); 

        });

        // Aranceles
            Route::controller(App\Http\Controllers\Back\ArancelController::class)->group(function () {
            Route::resource('/aranceles', App\Http\Controllers\Back\ArancelController::class)->except(['show', 'destroy']);
            Route::delete('/aranceles/massDestroy', 'arancelesDestroy')->name('aranceles.Destroy');  
            Route::get('/matriculas', 'matriculas_index')->name('matriculas.index');
            Route::get('/matriculas/create', 'matricula_create')->name('matriculas.create');
            Route::post('/matriculas/store', 'matricula_store')->name('matriculas.store');
            Route::get('/matriculas/{matricula}/edit', 'matricula_edit')->name('matriculas.edit'); 
            Route::put('/matriculas/{matricula}', 'matricula_update')->name('matriculas.update');
            Route::delete('/matriculas/massDestroy', 'matriculasDestroy')->name('matriculas.Destroy');  
        });     
        /* ------------------------------------------------------------------------ */

          // Estudiantes
            Route::controller(App\Http\Controllers\Back\EstudianteController::class)->group(function () {
            Route::resource('/estudiantes', App\Http\Controllers\Back\EstudianteController::class)->except(['show', 'destroy']);
            Route::delete('/estudiantes/massDestroy', 'estudiantesDestroy')->name('estudiantes.Destroy');
            Route::post('/estudiantes/dropdown', 'dropdown')->name('estudiantes.dropdown');
            Route::get('/estudiantes/notification/{message} ', 'showMessage')->name('estudiantes.message');
            Route::get('/estudiantes/recibos/{estudiante} ', 'showRecibos')->name('estudiantes.recibos');

        });     
        /* ------------------------------------------------------------------------ */
          // Recibos
            Route::controller(App\Http\Controllers\Back\ReciboController::class)->group(function () {
                Route::get('/recibos', 'index')->name('recibos.index');
                Route::get('/recibos/create/{estudiante}','create')->name('recibos.create');
                Route::post('/recibos/store/','store')->name('recibos.store');                
                Route::get('/recibos/pdf/{recibo}', 'printRecibo')->name('recibos.Pdf');
                Route::get('/recibos/validaEstudiante','validaEstudiante')->name('recibos.validaEstudiante');
                Route::post('/recibos/store/','store')->name('recibos.store');
                Route::get('/recibos/validaRecibo','validaRecibo')->name('recibos.validaRecibo');  
                Route::get('/recibos/verificado/{tmp_recibo}','showVerificado')->name('recibos.showVerificado');  
                Route::get('/recibos/goBack/{tmp_recibo}','goBack')->name('recibos.goBack');
                Route::get('/recibos/sugerencias','sugerencias')->name('recibos.sugerencias');
                Route::post('/recibos/petitorio','petitorio')->name('recibos.petitorio');
                Route::get('/recibos/validaPetitorio','validaPetitorio')->name('recibos.validaPetitorio');
                Route::delete('/recibos/massDestroy', 'recibosDestroy')->name('recibos.Destroy');
    
        });    
        /* ------------------------------------------------------------------------ */
      
        // Petitorios
        Route::controller(App\Http\Controllers\Back\PetitorioController::class)->group(function () {
            Route::get('/petitorios/index', 'index')->name('petitorios.index');
            Route::post('/petitorios/accion', 'accion')->name('petitorios.accion');
            Route::get('/petitorios/donativos', 'donativos')->name('petitorios.donativos');
        });
      /* ------------------------------------------------------------------------ */
      
        // Conciliación Bancaria
        Route::controller(App\Http\Controllers\Back\BancoController::class)->group(function () {
            Route::get('/bancos', 'index')->name('bancos.index');
            Route::post('/bancos/import', 'importFile')->name('bancos.import');
            Route::get('/bancos/upload', 'upload')->name('bancos.upload');
            Route::get('/bancos/conciliacion', 'conciliacion')->name('bancos.conciliacion');
            Route::get('/bancos/ajax_cierre', 'ajax_cierre')->name('bancos.ajax_cierre');
            Route::get('/bancos/ajax_diario', 'ajax_diario')->name('bancos.ajax_diario');
            Route::get('/bancos/diario', 'index_diario')->name('bancos.index_diario');
            Route::get('/bancos/uploadDiario', 'uploadDiario')->name('bancos.uploadDiario');
            Route::post('/bancos/importDiario', 'importDiario')->name('bancos.importDiario');

        });


    });


   

});


