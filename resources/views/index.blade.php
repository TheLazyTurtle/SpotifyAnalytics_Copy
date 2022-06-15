<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/favicon.ico" />
    <script src="https://kit.fontawesome.com/7fe26f8076.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <meta name="description" content="Web site created using create-react-app" />
    <link rel="apple-touch-icon" href="/logo192.png" />
    <link rel="manifest" href="{{ asset('/manifest.json')}}" />
    <title>React App</title>
</head>

<body><noscript>You need to enable JavaScript to run this app.</noscript>
    <div id="root"></div>
    <script src="https://unpkg.com/react/umd/react.production.min.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom/umd/react-dom.production.min.js" crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
