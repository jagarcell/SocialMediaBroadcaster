<?php

namespace App\Http\Middleware;

use Closure;
use QuickBooksOnline\API\DataService\DataService;

class CheckQbConnect
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
        $companyId = env('QB_COMPANY_ID');
        $accessToken = env('QB_ACCESS_TOKEN');
        $qbConfig = include 'config/qbConfig.php';

        if(session()->has('code')){
            $state = session('state');
            $code = session('code');
            $realmId = session('realmId');
            return $next($request);
        }

        $dataService = DataService::Configure($qbConfig);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        // Get the Authorization URL from the SDK
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        session(['authUrl' => $authUrl]);
        return $next($request);
    }
}
