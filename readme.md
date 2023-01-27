First of thank you so much for purchasing MultiEgg Premium! Hope you enjoy this BETA addon. This is a simple install as most of it you just have to upload to your /var/www/pterodactyl directory. If you need any support contact us at our discord server: https://discord.gg



1. In routes/admin.php

Under all file contents add:

/*
|--------------------------------------------------------------------------
| MultiEgg Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /admin/multiegg
|
*/
Route::group(['prefix' => 'multiegg'], function () {
    Route::get('/', [Admin\MultiEggController::class, 'index'])->name('admin.multiegg.index');
    Route::get('/lite', [Admin\MultiEggController::class, 'lite'])->name('admin.multiegg.lite');
    Route::get('/plus', [Admin\MultiEggController::class, 'plus'])->name('admin.multiegg.plus');
    Route::get('/pro', [Admin\MultiEggController::class, 'pro'])->name('admin.multiegg.pro');
    Route::get('/support', [Admin\MultiEggController::class, 'support'])->name('admin.multiegg.support');
    Route::get('/api/clearcache', [Admin\MultiEggController::class, 'clearCache']);

    Route::post('/edit', [Admin\MultiEggController::class, 'update'])->name('admin.multiegg.edit');
});


2. In resources/views/layouts/admin.blade.php

Above:

<li class="{{ ! starts_with(Route::currentRouteName(), 'admin.nests') ?: 'active' }}">

Add:

<li class="{{ ! starts_with(Route::currentRouteName(), 'admin.multiegg') ?: 'active' }}">
                            <a href="{{ route('admin.multiegg.index') }}">
                                <i class="fa fa-gears"></i> <span>MultiEgg</span>
                            </a>
                        </li>

3. Run `composer require laravelcollective/html` in `/var/www/pterodactyl`
4. In config/app.php

Below: 

Illuminate\View\ViewServiceProvider::class,

Add:

Collective\Html\HtmlServiceProvider::class,

5. In config/app.php

Below:

'Theme' => Pterodactyl\Extensions\Facades\Theme::class,

Add:

'Form' => Collective\Html\FormFacade::class,
'Html' => Collective\Html\HtmlFacade::class,

6. In composer.json

Below:

"webmozart/assert": "~1.11"

Add: 

"laravelcollective/html":"~6.3.0"

7. Run `composer update` in `/var/www/pterodactyl`
8. Run `composer install` in `/var/www/pterodactyl`



There your done, make sure you have uploaded the files into your /var/www/pterodactyl directory.

Next run these commands:

php artisan migrate --force
yarn build:production
php artisan route:clear
