<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturedBannerController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SmptController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Livewire\Admin\Backup\Index as BackupIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:ADMIN', 'firewall.all']], function () {
    // Change language
    Route::get('/lang/{lang}', [DashboardController::class, 'changeLanguage'])->name('changelanguage');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories and Subcategories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/subcategories', [CategoryController::class, 'subcategories'])->name('subcategories');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('brands');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users');

    // Sections
    Route::get('/sections', [SectionController::class, 'index'])->name('sections');
    Route::get('/section/create', [SectionController::class, 'create'])->name('section.create');
    Route::get('/section/edit/{id}', [SectionController::class, 'edit'])->name('section.edit');

    // Featured Banners
    Route::get('/featuredBanners', [FeaturedBannerController::class, 'index'])->name('featuredBanners');

    // Pages
    Route::get('/pages', [PageController::class, 'index'])->name('pages');
    Route::get('/order-forms', [PageController::class, 'orderForms'])->name('orderforms');
    Route::get('/page/settings', [PageController::class, 'settings'])->name('page.settings');

    // Sliders
    Route::get('/sliders', [SliderController::class, 'index'])->name('sliders');

    // Blogs
    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');
    Route::get('/blog/category', [BlogCategoryController::class, 'index'])->name('blogcategories');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::get('/backup', BackupIndex::class)->name('setting.backup');
    Route::get('/shipping', [ShippingController::class, 'index'])->name('setting.shipping');
    Route::get('/popupsettings', [SettingController::class, 'popupsettings'])->name('setting.popupsettings');
    Route::get('/redirects', [SettingController::class, 'redirects'])->name('setting.redirects');

    // Reports
    Route::get('/report', [ReportController::class, 'index'])->name('report');

    // Notifications
    Route::get('/notification', [NotificationController::class, 'index'])->name('notification');

    // SMTP
    Route::get('/smpt', [SmptController::class, 'index'])->name('smpt');

    // Language
    Route::get('/language', [LanguageController::class, 'index'])->name('language');

    // Roles and Permissions
    Route::get('/roles', [RolesController::class, 'index'])->name('roles');
    Route::get('/permissions', [UsersController::class, 'permissions'])->name('permissions');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');

    // Currencies
    Route::get('/currencies', [SettingController::class, 'currencies'])->name('currencies');
});
