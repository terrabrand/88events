<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredEvents = \App\Models\Event::where('status', 'published')
        ->where('is_featured', true)
        ->latest()
        ->take(5)
        ->get();
    
    $latestEvents = \App\Models\Event::where('status', 'published')
        ->latest()
        ->take(8)
        ->get();

    $categories = \App\Models\Category::all();
    $featuredItems = \App\Models\FeaturedItem::with('event')->where('is_active', true)->orderBy('sort_order')->get();

    return view('welcome', compact('featuredEvents', 'latestEvents', 'categories', 'featuredItems'));
})->name('home');

Route::get('/category/{category:slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
Route::get('/event/{event:slug}', [\App\Http\Controllers\EventController::class, 'showPublic'])->name('events.show.public');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('events', \App\Http\Controllers\EventController::class);
    
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

    // Admin Routes
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

        Route::resource('venues', \App\Http\Controllers\Admin\VenueController::class);
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
    });
});

require __DIR__.'/auth.php';
