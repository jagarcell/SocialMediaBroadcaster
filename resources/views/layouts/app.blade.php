<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @yield('scripts')
    
        @yield('fonts')
        
         @yield('styles')

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="/public/css/app.css">
    </head>
    <body class="bodyClass">
        <div>
            <div class="menuDiv">
                <div class="homeButtonDiv"><a href="/">Home</a></div>
                <div class="phoneNumbersButtonDiv"><a href="/phonenumbers">Phone Numbers Edition</a></div>
                <div class="carriersButtonDiv"><a href="/carriers">Carriers</a></div>
                <div class="phoneNumbersVerificationButtonDiv"><a href="/verification">Phone Numbers Verification</a>
                </div>
                <div class="mailListsButtonDiv"><a href="/getMailLists">Mailing Lists</a>
           </div>
           @yield('bodymaindiv')
       </div>
    </body>
</html>
