<?php

namespace Leantime\Plugins\audittrail\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Leantime\Core\Environment;
use Leantime\Core\IncomingRequest;
use Symfony\Component\HttpFoundation\Response;
use Leantime\Core\Language;

class GetLanguageAssets
{
    public function __construct(
        private Language $language,
        private Environment $config,
    ) {}

    /**
     * Install the custom fields plugin DB if necessary.
     *
     * @param \Closure(IncomingRequest): Response $next
     * @throws BindingResolutionException
     **/
    public function handle(IncomingRequest $request, Closure $next): Response
    {
        $languageArray = Cache::get('audittrail.languageArray', []);

        if (! empty($languageArray)) {
            $this->language->ini_array = array_merge($this->language->ini_array, $languageArray);
            return $next($request);
        }

        if (! Cache::store('installation')->has('audittrail.language.en-US')) {
            $languageArray += parse_ini_file(__DIR__ . '/../Language/en-US.ini', true);
        }

        if (($language = $_SESSION["usersettings.language"] ?? $this->config->language) !== 'en-US') {
            if (! Cache::store('installation')->has('audittrail.language.' . $language)) {
                Cache::store('installation')->put(
                    'audittrail.language.' . $language,
                    parse_ini_file(__DIR__ . '/../Language/' . $language . '.ini', true)
                );
            }

            $languageArray = array_merge($languageArray, Cache::store('installation')->get('audittrail.language.' . $language));
        }

        Cache::put('audittrail.languageArray', $languageArray);

        $this->language->ini_array = array_merge($this->language->ini_array, $languageArray);
        return $next($request);
    }
}

