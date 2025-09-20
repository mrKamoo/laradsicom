<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\PrescripteurController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\FournisseurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard général - redirection vers le dashboard des commandes
Route::get('/dashboard', [CommandeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes des commandes
    Route::resource('commandes', CommandeController::class);
    Route::patch('/commandes/{commande}/status', [CommandeController::class, 'updateStatus'])->name('commandes.updateStatus');

    // Routes des devis
    Route::get('/commandes/{commande}/devis/create', [DevisController::class, 'create'])->name('devis.create');
    Route::post('/commandes/{commande}/devis', [DevisController::class, 'store'])->name('devis.store');
    Route::get('/devis/{devis}', [DevisController::class, 'show'])->name('devis.show');
    Route::get('/devis/{devis}/edit', [DevisController::class, 'edit'])->name('devis.edit');
    Route::put('/devis/{devis}', [DevisController::class, 'update'])->name('devis.update');
    Route::delete('/devis/{devis}', [DevisController::class, 'destroy'])->name('devis.destroy');
    Route::patch('/devis/{devis}/select', [DevisController::class, 'select'])->name('devis.select');
    Route::patch('/devis/{devis}/deselect', [DevisController::class, 'deselect'])->name('devis.deselect');
    Route::get('/devis/{devis}/download', [DevisController::class, 'download'])->name('devis.download');

    // Routes des prescripteurs
    Route::resource('prescripteurs', PrescripteurController::class);
    Route::patch('/prescripteurs/{prescripteur}/toggle-active', [PrescripteurController::class, 'toggleActive'])->name('prescripteurs.toggle-active');

    // Routes des communes
    Route::resource('communes', CommuneController::class);
    Route::patch('/communes/{commune}/toggle-active', [CommuneController::class, 'toggleActive'])->name('communes.toggle-active');

    // Routes des fournisseurs
    Route::resource('fournisseurs', FournisseurController::class);
    Route::patch('/fournisseurs/{fournisseur}/toggle-active', [FournisseurController::class, 'toggleActive'])->name('fournisseurs.toggle-active');
});

require __DIR__.'/auth.php';
