<?php

use App\Livewire\CreateGroup;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Chat;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return redirect()->route('chat');
})->name('home');
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get("chat", Chat::class)->name("chat");
    Route::get("groups/create", CreateGroup::class)->name("groups.create");
});

require __DIR__.'/auth.php';