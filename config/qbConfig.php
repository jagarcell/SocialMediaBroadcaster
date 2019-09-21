<?php
	return[
	'auth_mode' => 'oauth2',
    'ClientID' => env('CLIENT_ID'),
    'ClientSecret' =>  env('CLIENT_SECRET'),
    'RedirectURI' => env('OAUTH_REDIRECT_URI'),
    'scope' => env('OAUTH_SCOPE'),
    'baseUrl' => "development",
	];
