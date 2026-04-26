<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flea Market</title>

    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <img class="header_logo" src="{{ asset('img/coachtech_header_logo.png') }}" alt="coachtech">
            @yield('nav')
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>

