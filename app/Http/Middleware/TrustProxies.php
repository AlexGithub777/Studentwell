<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (DigitalOcean App Platform uses proxies).
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * Laravel 11 uses 'X-Forwarded-*' headers directly — use this string instead of a constant.
     *
     * @var string
     */
    protected $headers = 'x-forwarded-all';
}
