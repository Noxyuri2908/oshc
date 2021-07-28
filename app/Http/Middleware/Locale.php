<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = \Session::get('website_language', config('app.locale'));
        // Lấy dữ liệu lưu trong Session, không có thì trả về default lấy trong config
        \App::setLocale($language);
        return $next($request);
    }
}
