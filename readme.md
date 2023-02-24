<p align="center">
<img alt="MultiEgg Plugin + Addon Reinstaller"
    src="https://cdn.discordapp.com/icons/1065406608605192312/7ff555c04132449c28e2d178445818f6.png?size=256">
</p>

<h1 align="center">MultiEgg Plugin + Addon Reinstaller</h1>

<p align="center">
 <b>
      The MultiEgg Installer thats been forked to support Addon Reinstaller
    </b>
    <b>
      | The Modified Script was made by VanillaChan#6571
  </b>
</p>

<p align="center">
    <a href="https://discord.gg/z4ZFaXUZMa">
        <img alt="Discord" src="https://img.shields.io/discord/1065406608605192312?color=7289DA&label=Discord&logo=discord&logoColor=7289DA">
    </a>
</p>

## Table of Contents 

*   [Forked Notice](#forked-notice)
*   [Introduction](#introduction)
*   [Part 1 Installation](#Installation-Part-1)
*   [Base Installation](#Installation-Part-2a)
*   [Base Installation + Addon Reinstaller](#Installation-Part-2b)
*   [Addon Support List](#Addon-Supported)

## Forked Notice
THIS IS A FORKED PROJECT!!
I do not and will not take any responsiblity for any corruption for database/sql stuff that MultiEgg may change!
I will try my attempts to update the project early but you been warned as it may or may not work.

## Introduction
First of thank you so much for purchasing MultiEgg Premium! Hope you enjoy this EGG addon. 
This is a simple install as most of it you just have to upload to your /var/www/pterodactyl directory. 
If you need any support contact us at our discord server: https://discord.gg/z4ZFaXUZMa

## Installation Part 1
1. In routes/admin.php

At the very bottom, insert the following:
```
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
    Route::get('/support', [Admin\MultiEggController::class, 'support'])->name('admin.multiegg.support');
    Route::get('/api/clearcache', [Admin\MultiEggController::class, 'clearCache']);  
    Route::post('/edit', [Admin\MultiEggController::class, 'update'])->name('admin.multiegg.edit');
});
```

2. In resources/views/layouts/admin.blade.php

Above:

```
<li class="{{ ! starts_with(Route::currentRouteName(), 'admin.nests') ?: 'active' }}">
```
Add:

```
<li class="{{ ! starts_with(Route::currentRouteName(), 'admin.multiegg') ?: 'active' }}">
                            <a href="{{ route('admin.multiegg.index') }}">
                                <i class="fa fa-gears"></i> <span>MultiEgg</span>
                            </a>
                        </li>
```
## Installation Part 2a
Easy right? now finish it off by running MultiEgg base script:

BASE INSTALL with no Addon Re-Installer:
bash <(curl -s https://api.multiegg.xyz/addon/install.sh) Always Latest Version

## Installation Part 2b
Or you can run Vanilla's Fork Script:
```Current Bash Version 1.1```
```bash <curl -s https://cdn.mcneko.net/MultiEgg-Plugin%2BAddon-Reinstaller/MutiEgg-Plugin%2BAddon-1.1-Reinstaller-install.sh>```

All Download Verions
| Version | Download |
| --- | -------------------- |
| 1.1 | **[Current Version - 1.1](https://cdn.mcneko.net/MultiEgg-Plugin%2BAddon-Reinstaller/MutiEgg-Plugin%2BAddon-1.1-Reinstaller-install.sh)** |
| 1.0 | **[1.0 Download](https://cdn.mcneko.net/MultiEgg-Plugin%2BAddon-Reinstaller/MutiEgg-Plugin%2BAddon-Reinstaller-install.sh)** |

## Addon Supported
Addon Support List
| | Addon's Currently Supported | Installer Version Added | Addon Link |
| ------------------ | -------- | ----- |--------- |
| **Wemx/Billing** | :heavy_check_mark: | 1.0 | **[Webpage](https://wemx.net/marketplace)** |

Legend Key:
Installer Versison Added: This indicates on when it was added. Example Wemx/Billing was added on 1.0
