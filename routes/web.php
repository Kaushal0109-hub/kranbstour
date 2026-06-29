<?php

use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\AuthSettingController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\MonumentController;
use App\Http\Controllers\Admin\MapsSettingController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingCheckoutController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SocialAuthController;
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
    Route::get('/{category}/{packageSlug}/book', [BookingCheckoutController::class, 'create'])->name('book');
    Route::get('/{category}/monument/{monumentSlug}', [TourController::class, 'monument'])->name('monument');
    Route::get('/{category}/{packageSlug}', [TourController::class, 'package'])->name('package');

    $categorySlugs = ['taj-mahal', 'jaipur', 'delhi', 'golden-triangle', 'varanasi'];
    if (Schema::hasTable('tour_categories')) {
        try {
            $categorySlugs = app(CatalogService::class)->activeCategorySlugs();
        } catch (\Throwable) {
            // DB not ready during install
        }
    }

    foreach ($categorySlugs as $category) {
        Route::get("/{$category}", fn () => app(TourController::class)->show($category))->name($category);
    }

    Route::get('/destination/{slug}', [PageController::class, 'destination'])->name('destination');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

$cmsSlugs = ['taxi', 'about', 'awards', 'terms', 'privacy'];
if (Schema::hasTable('cms_pages')) {
    try {
        $cmsSlugs = \App\Models\CmsPage::query()->active()->pluck('slug')->all();
    } catch (\Throwable) {
        //
    }
}
foreach (array_values(array_filter($cmsSlugs, fn (string $slug) => $slug !== 'blog')) as $slug) {
    Route::get("/{$slug}", fn () => app(PageController::class)->page($slug))->name($slug);
}

Route::post('/newsletter/subscribe', [PageController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

Route::post('/bookings/checkout', [BookingCheckoutController::class, 'store'])->name('bookings.checkout');
Route::post('/bookings/{booking}/payment/confirm', [BookingCheckoutController::class, 'confirmPayment'])->name('bookings.payment.confirm');
Route::get('/bookings/success/{booking}', [BookingCheckoutController::class, 'success'])->name('bookings.success');

// Customer auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login/otp/send', [AuthController::class, 'sendLoginOtp'])->name('login.otp.send')->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register/otp/send', [AuthController::class, 'sendRegisterOtp'])->name('register.otp.send')->middleware('throttle:5,1');
    Route::get('/auth/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('auth.verify-otp');
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp.submit')->middleware('throttle:10,1');
    Route::post('/auth/otp/resend', [AuthController::class, 'resendOtp'])->name('auth.otp.resend')->middleware('throttle:3,1');
    Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
});

Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

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
            Route::resource('blog-posts', BlogPostController::class)->except(['show']);
            Route::resource('cms-pages', CmsPageController::class)->except(['show']);
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
            Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment-gateways.index');
            Route::get('/payment-gateways/{paymentGateway}/edit', [PaymentGatewayController::class, 'edit'])->name('payment-gateways.edit');
            Route::put('/payment-gateways/{paymentGateway}', [PaymentGatewayController::class, 'update'])->name('payment-gateways.update');
            Route::get('/maps-settings', [MapsSettingController::class, 'edit'])->name('maps-settings.edit');
            Route::put('/maps-settings', [MapsSettingController::class, 'update'])->name('maps-settings.update');
            Route::get('/auth-settings', [AuthSettingController::class, 'edit'])->name('auth-settings.edit');
            Route::put('/auth-settings', [AuthSettingController::class, 'update'])->name('auth-settings.update');
        });
    });
});
