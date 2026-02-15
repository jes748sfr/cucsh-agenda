{{-- Esta vista no se muestra directamente — la ruta "/" redirige al login.
     Se mantiene como fallback por si se necesita una landing page en el futuro. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="0;url={{ route('login') }}">
    <title>Agenda CUCSH</title>
</head>
<body>
    <p>Redirigiendo al inicio de sesión...</p>
</body>
</html>
