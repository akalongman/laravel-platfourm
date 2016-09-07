<?php
/*
 * This file is part of the Laravel Platfourm package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\Platfourm\Http\Middleware;

use Closure;

class XssSecurity
{
    /**
     * Handle the given request and get the response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Set security headers
        $uri = $request->getUri();

        // checking for debugger
        //if (strpos($uri, '/itdc/debug') === false) {
        // http://blogs.msdn.com/b/ieinternals/archive/2010/03/30/combating-clickjacking-with-x-frame-options.aspx
        $response->headers->set('X-Frame-Options', 'DENY', false);
        //}

        // http://msdn.microsoft.com/en-us/library/ie/gg622941(v=vs.85).aspx
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);

        // http://msdn.microsoft.com/en-us/library/dd565647(v=vs.85).aspx
        $response->headers->set('X-XSS-Protection', '1; mode=block', false);

        return $response;
    }
}
