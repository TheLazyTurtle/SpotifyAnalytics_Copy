<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/favicon.ico" />
    <script src="https://kit.fontawesome.com/7fe26f8076.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <meta name="description" content="A website to view your spotify listening activity" />
    <link rel="apple-touch-icon" href="/logo192.png" />
    <link rel="manifest" href="{{ asset('/manifest.json')}}" />
    <title>Spotify Analytics</title>
</head>

<body>
    <noscript>You need to enable JavaScript to run this app.</noscript>
    <div id="root"></div>
    <script src="{{ mix('/js/index.js') }}"></script>
</body>

</html>
