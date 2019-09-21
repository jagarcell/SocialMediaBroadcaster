<?php

namespace App;

use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class test extends Model
{
    //
    public function quickBooks(Request $request)
    {
    	# code...

    	$companyId = env('QB_COMPANY_ID');
    	$accessToken = env('QB_ACCESS_TOKEN');
        $qbConfig = include 'config/qbConfig.php';

        if(session()->has('code')){
            $state = session('state');
            $code = session('code');
            $realmId = session('realmId');
            return ['state' => $state, 'code' => $code, 'realmId' => $realmId];
        }

		$dataService = DataService::Configure($qbConfig);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

        // Get the Authorization URL from the SDK
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        return ['authUrl' => $authUrl, 'status' => 'signing',];

    }

    public function qbCallBack(Request $request)
    {
    	# code...
        $qbConfig = config('qbConfig');

        $state = $request['state'];
        $code = $request['code'];
        $realmId = $request['realmId'];

        $dataService = DataService::Configure($qbConfig);
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);
        $dataService->updateOAuth2Token($accessToken);

        session(['state' => $state, 'code' => $code, 'realmId' => $realmId, 'accessToken' => $accessToken]);
        session()->forget('authUrl');

        $connInfo = ['state' => $state, 'code' => $code, 'realmId' => $realmId];
        if(session()->has('qbapi')){
            $qbapi = session('qbapi');
            session()->forget('qbapi');
            switch ($qbapi) {
                case 'companyInfo':
                    # code...
                    $companyInfo = $this->companyInfo($request);
                    return view('/test', ['connInfo' => $connInfo, 'companyInfo' => $companyInfo,]);
                    break;
                case 'inventorySummary':
                    $inventorySummary = $this->inventorySummary($request);
                    return view('/test', ['connInfo' => $connInfo, 'companyInfo' => $inventorySummary,]);
                    break;
                default:
                    # code...
                    return view('/test', ['connInfo' => $connInfo,]);
                    break;
            }
        }
        return view('/test', ['connInfo' => $connInfo,]);
    }

    public function companyInfo(Request $request)
    {
        # code...
        $session = session();
        if(session()->has('authUrl')){
            $authUrl = session('authUrl');
            session()->forget('authUrl');
            session(['qbapi' => 'companyInfo']);
            return ['authUrl' => $authUrl];
        }

        $qbConfig = include 'config/qbConfig.php';

        $dataService = DataService::Configure($qbConfig);
        $accessToken = session('accessToken');
        $dataService->updateOAuth2Token($accessToken);
        $companyInfo = $dataService->getCompanyInfo();
        return json_encode($companyInfo);
    }

    public function inventorySummary(Request $request)
    {
        # code...
        $session = session();
        if(session()->has('authUrl')){
            $authUrl = session('authUrl');
            session()->forget('authUrl');
            session(['qbapi' => 'inventorySummary']);
            return ['authUrl' => $authUrl];
        }

        $qbConfig = include 'config/qbConfig.php';

        $dataService = DataService::Configure($qbConfig);
        $accessToken = session('accessToken');
        $dataService->updateOAuth2Token($accessToken);

        $data = $dataService->Query('SELECT * FROM Item ORDER BY Id');
        return json_encode($data);
    }
}
