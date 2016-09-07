<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Foundation;

use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{

    public function getAvailableLocales()
    {
        $locales = $this['multilang']->getLocales();

        return array_keys($locales);
    }

    public function getDefaultLocale()
    {
        $locale = config('multilang.default_locale', 'en');

        return $locale;
    }

    public function isAdmin()
    {
        return $this->getScope() == 'admin';
    }

    public function isSite()
    {
        return !$this->isAdmin();
    }

    public function getScope()
    {
        return $this['config']->get('app.scope', 'global');
    }

}
