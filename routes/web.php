<?php
use App\Livewire\PostList;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/news');
})->name('home');

// removed duplicate home route (kept redirect to /news above)

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
Route::get('/news',PostList::class)->name('news.index');
Route::livewire('news/{slug}', 'pages::posts.show')->name('news.show');

Route::get('/unsubscribe/{token}', function ($token) {
    $subscriber = \App\Models\Subscriber::where('token', $token)->firstOrFail();
    if ($subscriber->deleted_at) {
        return view('unsubscribed');
    }
    abort(403);
})->name('unsubscribe');

Route::middleware('auth')->group(function(){
    Route::livewire('/posts', 'pages::posts.index')
    ->middleware('can:create posts')
    ->name('posts.index');

    Route::livewire('/posts/create', 'pages::posts.create')
    ->middleware('can:create posts')
    ->name('posts.create');

    Route::livewire('/posts/{post}/edit', 'pages::posts.edit')
    ->name('posts.edit');

    Route::livewire('/users', 'pages::users.index')
    ->middleware('can:manage users')
    ->name('users.index');

    Route::livewire('users/create','pages::users.create')
    ->middleware('can:manage users')
    ->name('users.create');

    Route::livewire('users/{user}/edit','pages::users.edit')
    ->middleware('can:manage users')
    ->name('users.edit');

    // Categories routes
    Route::livewire('/categories', 'pages::categories.index')
        ->middleware('can:manage roles')
        ->name('categories.index');
        
    Route::livewire('/categories/create', 'pages::categories.create')
        ->middleware('can:manage roles')
        ->name('categories.create');
        
    Route::livewire('/categories/{category}/edit', 'pages::categories.edit')
        ->middleware('can:manage roles')
        ->name('categories.edit');
    // Comments routes
    Route::livewire('/comments', 'pages::comments.index')
        ->middleware('can:create posts')
        ->name('comments.index');

});


require __DIR__.'/settings.php';
