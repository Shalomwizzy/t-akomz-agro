<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FarmTourController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderTrackingController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Account\DashboardController as AccountDashboardController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Account\WishlistController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\ProfileController as AccountProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\DeliveryController as AdminDeliveryController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AiController as AdminAiController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\OperationsController as AdminOperationsController;
use App\Http\Controllers\Admin\LivestockController as AdminLivestockController;
use App\Http\Controllers\Admin\FarmSupplyController as AdminFarmSupplyController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;
use App\Http\Controllers\Admin\MailBroadcastController as AdminMailBroadcastController;

// ─── PUBLIC ROUTES ──────────────────────────────────────────────────────────

// Client guide download
Route::get('/download/website-guide', [PageController::class, 'downloadGuide'])->name('download.guide');

// Sitemap
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

Route::get('/',               [HomeController::class, 'index'])->name('home');
Route::get('/about',          [AboutController::class, 'index'])->name('about');
Route::get('/farm-tour',               [FarmTourController::class, 'index'])->name('farm-tour');
Route::post('/farm-tour/book',         [FarmTourController::class, 'book'])->name('farm-tour.book');
Route::get('/farm-tour/callback',      [FarmTourController::class, 'callback'])->name('farm-tour.callback');
Route::get('/farm-tour/success',       [FarmTourController::class, 'success'])->name('farm-tour.success');
Route::get('/faq',            [PageController::class, 'faq'])->name('faq');
Route::get('/services',       [PageController::class, 'services'])->name('services');
Route::get('/gallery',        [PageController::class, 'gallery'])->name('gallery');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms',          [PageController::class, 'terms'])->name('terms');
Route::get('/refund-policy',  [PageController::class, 'refund'])->name('refund-policy');
Route::get('/shipping-policy',[PageController::class, 'shipping'])->name('shipping-policy');

// Contact
Route::get('/contact',  [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send')->middleware('throttle:5,1');

// Blog
Route::get('/blog',        [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// PWA
Route::get('/offline', [PageController::class, 'offline'])->name('offline');

// Shop
Route::get('/shop',                                        [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{category:slug}',                        [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/{category:slug}/{product:slug}',         [ShopController::class, 'product'])->name('shop.product');

// Cart
Route::get('/cart',          [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add',     [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update',  [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove',  [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',   [CartController::class, 'clear'])->name('cart.clear');
Route::post('/coupon/apply',  [CartController::class, 'applyCoupon'])->name('coupon.apply');
Route::post('/coupon/remove', [CartController::class, 'removeCoupon'])->name('coupon.remove');

// Checkout
Route::get('/checkout',                       [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout',                      [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success',               [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/paystack/callback',     [CheckoutController::class, 'paystackCallback'])->name('checkout.paystack.callback');
Route::get('/checkout/flutterwave/callback',  [CheckoutController::class, 'flutterwaveCallback'])->name('checkout.flutterwave.callback');

// Order Tracking (public — no login required)
Route::get('/orders/track', [OrderTrackingController::class, 'index'])->name('orders.track')->middleware('throttle:10,1');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:5,1');

// ─── CUSTOMER ACCOUNT ROUTES ─────────────────────────────────────────────────

Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/',                       [AccountDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders',                 [AccountOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}',         [AccountOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [AccountOrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{order}/cancel', [AccountOrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/wishlist',               [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle',       [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/addresses',              [AddressController::class, 'index'])->name('addresses');
    Route::post('/addresses',             [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}',    [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::get('/profile',                    [AccountProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile',                  [AccountProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',           [AccountProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile',                 [AccountProfileController::class, 'destroy'])->name('destroy');
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
});

// Reviews
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// ─── ADMIN ROUTES ─────────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', AdminSearchController::class)->name('search');

    // Mail broadcast
    Route::get('/mail/workers',         [AdminMailBroadcastController::class, 'workersIndex'])->name('mail.workers');
    Route::post('/mail/workers',        [AdminMailBroadcastController::class, 'sendToWorkers'])->name('mail.workers.send');
    Route::get('/mail/customers',       [AdminMailBroadcastController::class, 'usersIndex'])->name('mail.users');
    Route::post('/mail/customers',      [AdminMailBroadcastController::class, 'sendToUsers'])->name('mail.users.send');

    // Products
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::post('/products/{product}/toggle-active',          [AdminProductController::class, 'toggleActive'])->name('products.toggle-active');
    Route::post('/products/{product}/toggle-featured',        [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::delete('/products/{product}/images/{image}',       [AdminProductController::class, 'deleteImage'])->name('products.image.delete');
    Route::get('/products-export',                            [AdminProductController::class, 'export'])->name('products.export');

    // Categories
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::post('/categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');

    // Orders
    Route::get('/orders',                 [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',         [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::put('/orders/{order}/status',  [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders-export',         [AdminOrderController::class, 'export'])->name('orders.export');

    // Reviews
    Route::get('/reviews',                    [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve',  [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}',        [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Contact Messages
    Route::get('/contact-messages',                        [AdminContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{message}',              [AdminContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('/contact-messages/{message}/read',        [AdminContactMessageController::class, 'markRead'])->name('contact-messages.read');
    Route::delete('/contact-messages/{message}',           [AdminContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Customers
    Route::get('/customers',        [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Blog
    Route::resource('blog', AdminBlogController::class)->except(['show']);

    // Promotions
    Route::get('/promotions',              [AdminPromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions',             [AdminPromotionController::class, 'store'])->name('promotions.store');
    Route::put('/promotions/{coupon}',     [AdminPromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{coupon}',  [AdminPromotionController::class, 'destroy'])->name('promotions.destroy');

    // Inventory
    Route::get('/inventory',               [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::put('/inventory/{product}',     [AdminInventoryController::class, 'update'])->name('inventory.update');
    Route::post('/inventory/bulk-update',  [AdminInventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');

    // Analytics
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');

    // Deliveries
    Route::get('/deliveries', [AdminDeliveryController::class, 'index'])->name('deliveries.index');

    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    // File Upload
    Route::post('/upload', [AdminUploadController::class, 'store'])->name('upload');

    // AI Assistant (web-based so session auth works)
    Route::get('/ai',                       [AdminAiController::class, 'index'])->name('ai.index');
    Route::post('/ai/chat',                 [AdminAiController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/product-content',      [AdminAiController::class, 'productContent'])->name('ai.product-content');
    Route::post('/ai/product-description',  [AdminAiController::class, 'productDescription'])->name('ai.product-description');
    Route::post('/ai/business-plan',        [AdminAiController::class, 'businessPlan'])->name('ai.business-plan');
    Route::post('/ai/blog-post',            [AdminAiController::class, 'blogPost'])->name('ai.blog-post');
    Route::post('/ai/auto-publish',         [AdminAiController::class, 'autoPublish'])->name('ai.auto-publish');

    // User / Staff Management
    Route::get('/users',                [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create',         [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users',               [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',    [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',         [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',      [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('users.toggle');

    // Farm Operations — ERP modules
    Route::prefix('operations')->name('operations.')->group(function () {
        Route::get('/', [AdminOperationsController::class, 'dashboard'])->name('dashboard');
    });

    Route::prefix('livestock')->name('livestock.')->group(function () {
        Route::get('/',                            [AdminLivestockController::class, 'index'])->name('index');
        Route::get('/create',                      [AdminLivestockController::class, 'create'])->name('create');
        Route::post('/',                           [AdminLivestockController::class, 'store'])->name('store');
        Route::get('/{batch}',                     [AdminLivestockController::class, 'show'])->name('show');
        Route::post('/{batch}/mortality',          [AdminLivestockController::class, 'recordMortality'])->name('mortality');
        Route::get('/{batch}/expenses/create',     [AdminLivestockController::class, 'createExpense'])->name('expenses.create');
        Route::post('/{batch}/expenses',           [AdminLivestockController::class, 'storeExpense'])->name('expenses.store');
    });

    Route::prefix('farm-supplies')->name('farm-supplies.')->group(function () {
        Route::get('/',                          [AdminFarmSupplyController::class, 'index'])->name('index');
        Route::get('/create',                    [AdminFarmSupplyController::class, 'create'])->name('create');
        Route::post('/',                         [AdminFarmSupplyController::class, 'store'])->name('store');
        Route::get('/{farmSupply}',              [AdminFarmSupplyController::class, 'show'])->name('show');
        Route::post('/{farmSupply}/stock-in',    [AdminFarmSupplyController::class, 'stockIn'])->name('stock-in');
        Route::post('/{farmSupply}/stock-out',   [AdminFarmSupplyController::class, 'stockOut'])->name('stock-out');
    });

    // Gallery
    Route::get('/gallery',                     [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery',                    [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('gallery.store');
    Route::patch('/gallery/{gallery}',         [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('gallery.update');
    Route::delete('/gallery/{gallery}',        [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::post('/gallery/{gallery}/toggle',   [\App\Http\Controllers\Admin\GalleryController::class, 'toggle'])->name('gallery.toggle');

    // Team
    Route::get('/team',                        [\App\Http\Controllers\Admin\TeamController::class, 'index'])->name('team.index');
    Route::post('/team',                       [\App\Http\Controllers\Admin\TeamController::class, 'store'])->name('team.store');
    Route::patch('/team/{team}',               [\App\Http\Controllers\Admin\TeamController::class, 'update'])->name('team.update');
    Route::delete('/team/{team}',              [\App\Http\Controllers\Admin\TeamController::class, 'destroy'])->name('team.destroy');

    // Push Notifications
    Route::get('/notifications',       [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');

    // Workers (HR)
    Route::resource('workers', \App\Http\Controllers\Admin\WorkerController::class);

    // Attendance
    Route::get('/attendance',                      [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/bulk',                [\App\Http\Controllers\Admin\AttendanceController::class, 'bulkStore'])->name('attendance.bulk-store');
    Route::patch('/attendance/{attendance}',       [\App\Http\Controllers\Admin\AttendanceController::class, 'update'])->name('attendance.update');

    // Payroll
    Route::get('/payroll',                         [\App\Http\Controllers\Admin\PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/generate',               [\App\Http\Controllers\Admin\PayrollController::class, 'generate'])->name('payroll.generate');
    Route::get('/payroll/{payroll}',               [\App\Http\Controllers\Admin\PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payroll}/approve',      [\App\Http\Controllers\Admin\PayrollController::class, 'approve'])->name('payroll.approve');
    Route::post('/payroll/{payroll}/pay',          [\App\Http\Controllers\Admin\PayrollController::class, 'pay'])->name('payroll.pay');
    Route::delete('/payroll/{payroll}',            [\App\Http\Controllers\Admin\PayrollController::class, 'destroy'])->name('payroll.destroy');

    // Farm Tour Bookings
    Route::get('/tour-bookings',                                    [\App\Http\Controllers\Admin\TourBookingController::class, 'index'])->name('tour-bookings.index');
    Route::get('/tour-bookings/{tourBooking}/edit',                 [\App\Http\Controllers\Admin\TourBookingController::class, 'edit'])->name('tour-bookings.edit');
    Route::put('/tour-bookings/{tourBooking}',                      [\App\Http\Controllers\Admin\TourBookingController::class, 'update'])->name('tour-bookings.update');
    Route::post('/tour-bookings/{tourBooking}/approve',             [\App\Http\Controllers\Admin\TourBookingController::class, 'approve'])->name('tour-bookings.approve');
    Route::post('/tour-bookings/{tourBooking}/reject',              [\App\Http\Controllers\Admin\TourBookingController::class, 'reject'])->name('tour-bookings.reject');
    Route::delete('/tour-bookings/{tourBooking}',                   [\App\Http\Controllers\Admin\TourBookingController::class, 'destroy'])->name('tour-bookings.destroy');
});

// ─── FINANCE ROUTES ── accessible to ADMIN, SUPER_ADMIN, MANAGER ─────────────

Route::middleware(['auth', 'staff'])->prefix('admin/finance')->name('admin.finance.')->group(function () {
    Route::get('/',                                    [AdminFinanceController::class, 'dashboard'])->name('dashboard');
    Route::get('/fund',                                [AdminFinanceController::class, 'showFund'])->name('fund');
    Route::post('/fund',                               [AdminFinanceController::class, 'fund'])->name('fund.store');
    Route::get('/export',                              [AdminFinanceController::class, 'export'])->name('export');
    Route::get('/expenses/create',                     [AdminFinanceController::class, 'createExpense'])->name('expenses.create');
    Route::get('/expenses/direct',                     [AdminFinanceController::class, 'createDirectExpense'])->name('expenses.direct.create');
    Route::post('/expenses/direct',                    [AdminFinanceController::class, 'storeDirectExpense'])->name('expenses.direct');
    Route::post('/expenses',                           [AdminFinanceController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/expenses',                            [AdminFinanceController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/{transaction}',              [AdminFinanceController::class, 'showExpense'])->name('expenses.show');
    Route::post('/expenses/{transaction}/approve',     [AdminFinanceController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{transaction}/reject',      [AdminFinanceController::class, 'reject'])->name('expenses.reject');
    Route::post('/expenses/{transaction}/confirm',     [AdminFinanceController::class, 'confirmSpending'])->name('expenses.confirm');
    Route::get('/expenses/{transaction}/receipt',      [AdminFinanceController::class, 'serveReceipt'])->name('expenses.receipt');
});

// ─── ADMIN AUTH ───────────────────────────────────────────────────────────────

Route::get('/admin/login',  [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout',[AdminAuthController::class, 'logout'])->name('admin.logout');

// ─── AUTH ROUTES (Breeze) ───────────────────────────────────────────────────

require __DIR__ . '/auth.php';
