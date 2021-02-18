<!doctype html>
{{-- config/app.php よりロケールを取得、lang属性に設定 --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- config/app.php よりアプリケーション名を取得、titleタグに設定 --}}
    <title>{{ config('app.name') }}</title>

    {{-- scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>

    {{-- fonts --}}
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Truculenta&display=swap" rel="stylesheet">
</head>

<body>
    {{-- レンダリング部 --}}
    <div id="app"></div>
</body>

</html>
