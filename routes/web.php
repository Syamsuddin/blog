<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\SpamController as AdminSpamController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('posts.index');
Route::get('/blog/category/{category:slug}', [BlogController::class, 'category'])->name('posts.category');
Route::get('/blog/tag/{tag:slug}', [BlogController::class, 'tag'])->name('posts.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('posts.show');
Route::post('/blog/{post}/comments', [CommentController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('comments.store');

// Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactStore'])->name('contact.store');

Route::get('/dashboard', function () { return view('dashboard'); })
    ->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','verified'])->prefix('admin')->name('admin.')->group(function(){
    Route::resource('posts', AdminPostController::class);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('tags', AdminTagController::class)->except(['show']);
    
    // Comment management
    Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::get('comments/{comment}', [AdminCommentController::class, 'show'])->name('comments.show');
    Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/reject', [AdminCommentController::class, 'reject'])->name('comments.reject');
    Route::patch('comments/{comment}/spam', [AdminCommentController::class, 'markAsSpam'])->name('comments.spam');
    Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('comments/bulk', [AdminCommentController::class, 'bulkAction'])->name('comments.bulk');
    
    // Settings management
    Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/color-scheme', [AdminSettingsController::class, 'applyColorScheme'])->name('settings.apply-color-scheme');
    Route::post('settings/backup', [AdminSettingsController::class, 'createBackup'])->name('settings.create-backup');
    Route::get('settings/backup/download', [AdminSettingsController::class, 'downloadBackup'])->name('settings.download-backup');
    Route::delete('settings/backup/delete', [AdminSettingsController::class, 'deleteBackup'])->name('settings.delete-backup');
    
    // Spam management
    Route::prefix('spam')->name('spam.')->group(function () {
        Route::get('/', [AdminSpamController::class, 'index'])->name('index');
        Route::get('/keywords', [AdminSpamController::class, 'keywords'])->name('keywords');
        Route::post('/keywords', [AdminSpamController::class, 'storeKeyword'])->name('keywords.store');
        Route::put('/keywords/{keyword}', [AdminSpamController::class, 'updateKeyword'])->name('keywords.update');
        Route::delete('/keywords/{keyword}', [AdminSpamController::class, 'deleteKeyword'])->name('keywords.delete');
        Route::get('/flagged', [AdminSpamController::class, 'flaggedComments'])->name('flagged');
        Route::get('/spam-comments', [AdminSpamController::class, 'spamComments'])->name('spam-comments');
        Route::patch('/comments/{comment}/approve', [AdminSpamController::class, 'approveComment'])->name('comments.approve');
        Route::patch('/comments/{comment}/reject', [AdminSpamController::class, 'rejectComment'])->name('comments.reject');
        Route::patch('/comments/{comment}/spam', [AdminSpamController::class, 'markAsSpam'])->name('comments.spam');
        Route::post('/comments/bulk', [AdminSpamController::class, 'bulkAction'])->name('spam.comments.bulk');
        Route::post('/test', [AdminSpamController::class, 'testFilter'])->name('test');
        Route::get('/blocked-ips', [AdminSpamController::class, 'blockedIps'])->name('blocked-ips');
        Route::post('/block-ip', [AdminSpamController::class, 'blockIp'])->name('block-ip');
        Route::post('/unblock-ip', [AdminSpamController::class, 'unblockIp'])->name('unblock-ip');
    });
});

require __DIR__.'/auth.php';
