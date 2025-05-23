<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class Locale
{
    public function handle($request, Closure $next)
    {
        if (Schema::hasTable('languages')) {
            $language_default = Language::query()
                ->where('is_default', Language::IS_DEFAULT)
                ->first('code');
        }

        $language_code = Session::get('language_code');

        if ($language_code) {
            App::setLocale($language_code);
        } elseif (!empty($language_default['code'])) {
            App::setLocale($language_default['code']);
        } else {
            App::setLocale(config('app.locale')); // fallback
        }

        return $next($request);
    }
}
