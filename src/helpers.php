<?php

use Illuminate\Contracts\Routing\UrlGenerator;

if (!function_exists('assetver')) {
    /**
     * Get the path to a versioned Elixir file.
     *
     * @param  string $file
     * @return string
     *
     * @throws \RuntimeException
     * @throws \ErrorException
     */
    function assetver($file)
    {
        if (!app()->environment('production')) {
            return asset($file);
        }

        $file = preg_replace('#^build\/#', '', $file);

        static $manifest = null;

        if ($manifest === null) {
            try {
                $content = file_get_contents(public_path('build/rev/rev-manifest.json'));
            } catch (ErrorException $e) {
                throw new ErrorException('rev-manifest.json file not found!');
            }

            $manifest = json_decode($content, true);
        }

        if (isset($manifest[$file])) {
            return asset('build/rev/' . $manifest[$file]);
        }

        throw new RuntimeException("File {$file} not defined in asset manifest.");
    }
}

if (!function_exists('site_url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string $path
     * @param  mixed  $parameters
     * @param  bool   $secure
     * @return Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function site_url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        return lang_url($path, $parameters, $secure);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string $path
     * @param  mixed  $parameters
     * @param  bool   $secure
     * @return Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function admin_url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        $prefix = config('platfourm.admin_prefix', 'admin');
        if ($prefix) {
            $path = $prefix . '/' . $path;
        }

        return site_url($path, $parameters, $secure);
    }
}

if (!function_exists('p')) {
    /**
     * Generate a url for the application.
     *
     * @param  mixed $value
     */
    function p($value)
    {
        $debugbar = app('debugbar');
        foreach (func_get_args() as $value) {
            $debugbar->addMessage($value, 'debug');
        }
    }
}

if (!function_exists('array_to_subarray')) {
    /**
     * Generate a url for the application.
     *
     * @param  array $array
     * @return  array
     */
    function array_to_subarray(array $array)
    {
        $return = [];
        foreach ($array as $k => $v) {
            $return[] = [
                'id'   => $k,
                'name' => $v,
            ];
        }

        return $return;
    }
}

if (!function_exists('j')) {
    function j($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value);
    }
}

if (!function_exists('a')) {
    function a($value)
    {
        return e(j($value));
    }
}
