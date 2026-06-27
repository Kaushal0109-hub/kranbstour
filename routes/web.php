<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\MonumentController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TourController;
use App\Services\CatalogService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [PageController::class, 'search'])->name('search');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

Route::prefix('tours')->name('tours.')->group(function () {
    Route::get('/packages', [TourController::class, 'packages'])->name('packages');
    Route::get('/{category}/{packageSlug}', [TourController::class, 'package'])->name('package');

    foreach (['taj-mahal', 'jaipur', 'delhi', 'golden-triangle', 'varanasi'] as $category) {
        Route::get("/{$category}", fn () => app(TourController::class)->show($category))->name($category);
    }

    if (Schema::hasTable('tour_categories')) {
        try {
            foreach (app(CatalogService::class)->activeCategorySlugs() as $slug) {
                if (! Route::has("tours.{$slug}")) {
                    Route::get("/{$slug}", fn () => app(TourController::class)->show($slug))->name($slug);
                }
            }
        } catch (\Throwable) {
            // DB not ready during install
        }
    }

    Route::get('/destination/{slug}', [PageController::class, 'destination'])->name('destination');
});

foreach (['taxi', 'about', 'blog', 'awards', 'terms', 'privacy'] as $slug) {
    Route::get("/{$slug}", fn () => app(PageController::class)->page($slug))->name($slug);
}

Route::post('/newsletter/subscribe', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// Customer auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Customer dashboard
Route::middleware(['auth', 'customer'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('index');
    Route::get('/bookings', [CustomerDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});

// Admin auth & panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('bookings');
        Route::patch('/bookings/{booking}', [AdminDashboardController::class, 'updateBookingStatus'])->name('bookings.update');
        Route::get('/customers', [AdminDashboardController::class, 'customers'])->name('customers');
        Route::get('/messages', [AdminDashboardController::class, 'messages'])->name('messages');
        Route::patch('/messages/{message}/read', [AdminDashboardController::class, 'markMessageRead'])->name('messages.read');

        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('cities', CityController::class)->except(['show']);
            Route::resource('categories', TourCategoryController::class)->except(['show']);
            Route::resource('packages', TourPackageController::class)->except(['show']);
            Route::resource('monuments', MonumentController::class)->except(['show']);
            Route::get('/homepage', [HomepageController::class, 'index'])->name('homepage.index');
            Route::post('/homepage/stats', [HomepageController::class, 'storeStat'])->name('homepage.stats.store');
            Route::put('/homepage/stats/{stat}', [HomepageController::class, 'updateStat'])->name('homepage.stats.update');
            Route::delete('/homepage/stats/{stat}', [HomepageController::class, 'destroyStat'])->name('homepage.stats.destroy');
            Route::post('/homepage/highlights', [HomepageController::class, 'storeHighlight'])->name('homepage.highlights.store');
            Route::put('/homepage/highlights/{highlight}', [HomepageController::class, 'updateHighlight'])->name('homepage.highlights.update');
            Route::delete('/homepage/highlights/{highlight}', [HomepageController::class, 'destroyHighlight'])->name('homepage.highlights.destroy');
            Route::post('/homepage/testimonials', [HomepageController::class, 'storeTestimonial'])->name('homepage.testimonials.store');
            Route::put('/homepage/testimonials/{testimonial}', [HomepageController::class, 'updateTestimonial'])->name('homepage.testimonials.update');
            Route::delete('/homepage/testimonials/{testimonial}', [HomepageController::class, 'destroyTestimonial'])->name('homepage.testimonials.destroy');
            Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
        });
    });
});
