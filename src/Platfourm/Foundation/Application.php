<?php

namespace Longman\Platfourm\Foundation;

use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{

    public function getAvailableLocales()
    {
        //$locale = $this->getLocale();
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
