<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EbookController;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// HOME PAGE - menggunakan HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// PUBLIC PAGES
Route::view('/tentang-kami', 'public.about')->name('about');
Route::view('/services', 'public.services')->name('services'); // service Page
Route::view('/privacy', 'public.privacy')->name('privacy');

// BOOK ROUTES
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/ebooks', [BookController::class, 'ebooks'])->name('ebooks');
    Route::get('/printed', [BookController::class, 'printedBooks'])->name('printed');
    Route::get('/search', [BookController::class, 'search'])->name('search');
    Route::get('/{id}', [BookController::class, 'show'])->name('show');
});

// Shortcut routes
Route::get('/produk', [BookController::class, 'index'])->name('produk');
Route::get('/ebooks', [BookController::class, 'ebooks'])->name('ebooks');
Route::get('/printed-books', [BookController::class, 'printedBooks'])->name('printed');
Route::get('/book/{id}', [BookController::class, 'show'])->name('book.detail');
Route::get('/book/{id}/preview', [BookController::class, 'preview'])->name('book.preview');
Route::get('/book/{id}/preview/file', [BookController::class, 'previewFile'])->name('book.preview.file');
Route::post('/book/{id}/checkout', [BookController::class, 'startCheckout'])->name('book.checkout');
Route::get('/search', [BookController::class, 'search'])->name('search');

// Disable default login/register routes
Route::get('/login', function () {
    abort(404);
});

Route::get('/register', function () {
    abort(404);
});

// Route dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->middleware(['auth'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Users Routes - protected by "USER" middleware
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
});

// CHECKOUT & PAYMENT ROUTES (Guest Checkout)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::get('/guest-form', [CheckoutController::class, 'guestForm'])->name('guest-form');
    Route::post('/process-guest', [CheckoutController::class, 'processGuest'])->name('process-guest');
    Route::get('/payment/{order}', [CheckoutController::class, 'payment'])->name('payment');
    Route::get('/payment/{order}/snap-token', [CheckoutController::class, 'snapToken'])->name('snap-token');
    Route::get('/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('confirmation');
    Route::post('/cancel/{order}', [CheckoutController::class, 'cancel'])->name('cancel');
});

// PAYMENT ROUTES
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/midtrans-callback', [PaymentController::class, 'handleCallback'])->name('midtrans-callback');
    Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
    Route::get('/status/{order}', [PaymentController::class, 'getStatus'])->name('status');
});

// EBOOK DOWNLOAD ROUTES
Route::prefix('ebook')->name('ebook.')->group(function () {
    Route::get('/download', [EbookController::class, 'download'])->name('download');
    Route::get('/link', [EbookController::class, 'getDownloadLink'])->name('link');
});

// Public blog listing & detail (cards view)
Route::get('/blog', function (Request $request) {
    $posts = BlogPost::published()
        ->with('author', 'category')
        ->orderBy('published_at', 'desc')
        ->paginate(9);

    return view('public.blog.index', [
        'posts' => $posts,
    ]);
})->name('blog.index');

Route::get('/blog/{post}', function ($id) {
    $post = BlogPost::with('author', 'category')->published()->findOrFail($id);

    return view('public.blog.show', [
        'post' => $post,
    ]);
})->name('blog.show');

// ADMIN ROUTES
require __DIR__ . '/admin.php';

require __DIR__ . '/auth.php';
