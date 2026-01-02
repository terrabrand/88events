<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

Route::get('/category/{category:slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
Route::get('/category/{category:slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('events', \App\Http\Controllers\EventController::class)->except(['show']);
    
    // Ticket Management (Organizer)
    Route::post('events/{event}/ticket-types', [\App\Http\Controllers\TicketTypeController::class, 'store'])->name('ticket-types.store');
    Route::delete('ticket-types/{ticketType}', [\App\Http\Controllers\TicketTypeController::class, 'destroy'])->name('ticket-types.destroy');
    Route::get('events/{event}/export', [\App\Http\Controllers\EventController::class, 'exportAttendees'])->name('events.export');
    
    // Coupons (Organizer)
    Route::post('events/{event}/coupons', [\App\Http\Controllers\CouponController::class, 'store'])->name('coupons.store');
    Route::delete('coupons/{coupon}', [\App\Http\Controllers\CouponController::class, 'destroy'])->name('coupons.destroy');

    // Reviews & Reports (Attendee)
    Route::post('events/{event}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('events/{event}/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');

    // Ticket Purchasing & Viewing (Attendee)
    // Route::post('events/{event}/tickets/purchase', [\App\Http\Controllers\TicketController::class, 'purchase'])->name('tickets.purchase');
    Route::post('events/{event}/checkout', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('tickets.purchase'); // Reusing name for view compatibility
    Route::get('payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');

    Route::get('my-tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}/download', [\App\Http\Controllers\TicketController::class, 'download'])->name('tickets.download');

    Route::middleware(['role:organizer|scanner|admin'])->group(function () {
        Route::get('/scanner', function (\Illuminate\Http\Request $request) {
            $event = null;
            if ($request->has('event_id')) {
                $event = \App\Models\Event::find($request->input('event_id'));
            }
            return view('scanner', compact('event'));
        })->name('scanner');
        
        Route::post('/scanner/check', [\App\Http\Controllers\Api\CheckInController::class, 'store'])->name('scanner.check');
    });

    // User Support Tickets
    Route::resource('support', \App\Http\Controllers\SupportTicketController::class);
    Route::post('support/{ticket}/reply', [\App\Http\Controllers\SupportTicketController::class, 'reply'])->name('support.reply');

    // Admin Routes
    // Support Ticket System
    // The following routes are now covered by the resource route above, except for the reply route.
    // Route::get('support', [\App\Http\Controllers\SupportTicketController::class, 'index'])->name('support.index');
    // Route::get('support/create', [\App\Http\Controllers\SupportTicketController::class, 'create'])->name('support.create');
    // Route::post('support', [\App\Http\Controllers\SupportTicketController::class, 'store'])->name('support.store');
    // Route::get('support/{ticket}', [\App\Http\Controllers\SupportTicketController::class, 'show'])->name('support.show');
    // Route::post('support/{ticket}/reply', [\App\Http\Controllers\SupportTicketController::class, 'reply'])->name('support.reply');

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/featured', [\App\Http\Controllers\Admin\FeaturedItemController::class, 'index'])->name('featured.index');
        Route::post('/featured', [\App\Http\Controllers\Admin\FeaturedItemController::class, 'store'])->name('featured.store');
        Route::delete('/featured/{featuredItem}', [\App\Http\Controllers\Admin\FeaturedItemController::class, 'destroy'])->name('featured.destroy');
        Route::post('/featured/update-order', [\App\Http\Controllers\Admin\FeaturedItemController::class, 'updateOrder'])->name('featured.update-order');

        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'payment'])->name('settings.payment');
        Route::put('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'updatePayment'])->name('settings.payment.update');

        // Packages Management
        Route::get('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'index'])->name('packages.index');
        Route::post('/packages', [\App\Http\Controllers\Admin\PackageController::class, 'store'])->name('packages.store');
        Route::put('/packages/{package}', [\App\Http\Controllers\Admin\PackageController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [\App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('packages.destroy');

        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::put('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'update'])->name('reports.update');

        Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleApproval'])->name('reviews.toggle');
        Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/support', [\App\Http\Controllers\Admin\SupportTicketController::class, 'index'])->name('support.index');
        Route::get('/support/{ticket}', [\App\Http\Controllers\Admin\SupportTicketController::class, 'show'])->name('support.show');
        Route::patch('/support/{ticket}/status', [\App\Http\Controllers\Admin\SupportTicketController::class, 'updateStatus'])->name('support.status');
        Route::post('/support/{ticket}/reply', [\App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])->name('admin.support.reply');

        Route::resource('venues', \App\Http\Controllers\Admin\VenueController::class);

        // Ad Management
        Route::resource('ad-packages', \App\Http\Controllers\Admin\AdPackageController::class)->except(['show', 'create', 'edit']);
        Route::get('promotions', [\App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('promotions.index');
        Route::patch('promotions/{promotion}/status', [\App\Http\Controllers\Admin\PromotionController::class, 'updateStatus'])->name('promotions.status');
        
        // Manual Credits
        Route::get('credits/add', [\App\Http\Controllers\Admin\CreditController::class, 'create'])->name('credits.create');
        Route::post('credits/add', [\App\Http\Controllers\Admin\CreditController::class, 'store'])->name('credits.store');
    });

    // Organizer Routes
    Route::middleware(['role:organizer|admin'])->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/coupons', [\App\Http\Controllers\Organizer\CouponController::class, 'index'])->name('coupons.index');
        
        // SMS Marketing
        Route::get('/sms', [\App\Http\Controllers\Organizer\SmsMarketingController::class, 'index'])->name('sms.index');
        Route::get('/events/{event}/sms', [\App\Http\Controllers\Organizer\SmsMarketingController::class, 'create'])->name('sms.create');
        Route::post('/events/{event}/sms', [\App\Http\Controllers\Organizer\SmsMarketingController::class, 'store'])->name('sms.store');

        // Email Marketing
        Route::get('/email', [\App\Http\Controllers\Organizer\EmailMarketingController::class, 'index'])->name('email.index');
        Route::get('/events/{event}/email', [\App\Http\Controllers\Organizer\EmailMarketingController::class, 'create'])->name('email.create');
        Route::post('/events/{event}/email', [\App\Http\Controllers\Organizer\EmailMarketingController::class, 'store'])->name('email.store');

        // Guestlist Management
        Route::get('/guests', [\App\Http\Controllers\Organizer\GuestlistController::class, 'index'])->name('guests.index');
        Route::post('/guests', [\App\Http\Controllers\Organizer\GuestlistController::class, 'store'])->name('guests.store');
        Route::post('/guests/{guest}/invite', [\App\Http\Controllers\Organizer\GuestlistController::class, 'addToEvents'])->name('guests.invite');
        Route::delete('/guests/{guest}', [\App\Http\Controllers\Organizer\GuestlistController::class, 'destroy'])->name('guests.destroy');
        Route::get('/events/{event}/guests', [\App\Http\Controllers\Organizer\GuestlistController::class, 'eventGuestlist'])->name('guests.event');
        Route::patch('/events/{event}/guests/{guest}/status', [\App\Http\Controllers\Organizer\GuestlistController::class, 'toggleStatus'])->name('guests.event.status');
        Route::post('/events/{event}/guests/import', [\App\Http\Controllers\Organizer\GuestlistController::class, 'importFromPastEvents'])->name('guests.import');
        Route::delete('/events/{event}/guests/{guest}', [\App\Http\Controllers\Organizer\GuestlistController::class, 'removeFromEvent'])->name('guests.event.remove');

        Route::resource('venues', \App\Http\Controllers\Organizer\VenueController::class);
        Route::post('/venues/{venue}/pull', [\App\Http\Controllers\Organizer\VenueController::class, 'pull'])->name('venues.pull');

        // Promotion & Credits
        Route::resource('promotions', \App\Http\Controllers\Organizer\PromotionController::class);
        Route::get('credits', [\App\Http\Controllers\Organizer\CreditController::class, 'index'])->name('credits.index');
        Route::post('credits/deposit', [\App\Http\Controllers\Organizer\CreditController::class, 'deposit'])->name('credits.deposit');
    });

    // Admin Blog Management
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('posts/models', [\App\Http\Controllers\Admin\PostController::class, 'models'])->name('posts.models');
        Route::post('posts/generate', [\App\Http\Controllers\Admin\PostController::class, 'generate'])->name('posts.generate');
        Route::post('posts/generate-image', [\App\Http\Controllers\Admin\PostController::class, 'generateImage'])->name('posts.generate-image');
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
    });
});

require __DIR__.'/auth.php';

// Social Login
Route::get('auth/{provider}/redirect', [App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('auth/{provider}/callback', [App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback'])->name('social.callback');

// Search Route

Route::get('/event/{event:slug}', [\App\Http\Controllers\EventController::class, 'showPublic'])->name('events.show.public');
Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'showPublic'])->name('events.show');

// Organizer Profile & Follow System
Route::get('/organizers/{organizer}', [\App\Http\Controllers\OrganizerProfileController::class, 'show'])->name('organizers.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/organizers/{organizer}/follow', [\App\Http\Controllers\FollowController::class, 'store'])->name('organizers.follow');
    Route::delete('/organizers/{organizer}/follow', [\App\Http\Controllers\FollowController::class, 'destroy'])->name('organizers.unfollow');
    
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Static Pages
Route::get('/pricing', [\App\Http\Controllers\PageController::class, 'pricing'])->name('pages.pricing');
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('pages.about');
Route::get('/careers', [\App\Http\Controllers\PageController::class, 'careers'])->name('pages.careers');
Route::get('/press', [\App\Http\Controllers\PageController::class, 'press'])->name('pages.press');
Route::get('/security', [\App\Http\Controllers\PageController::class, 'security'])->name('pages.security');
Route::get('/developers', [\App\Http\Controllers\PageController::class, 'developers'])->name('pages.developers');
Route::get('/terms', [\App\Http\Controllers\PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [\App\Http\Controllers\PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/cookies', [\App\Http\Controllers\PageController::class, 'cookies'])->name('pages.cookies');

// Blog Routes
Route::get('/blog', [\App\Http\Controllers\PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('blog.show');
