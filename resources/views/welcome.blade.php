<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Task Manager</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

        <!-- Styles / Scripts -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-BPWzf21J.css') }}">
        <script type="module" src="{{ asset('build/assets/app-J41D_F52.js') }}"></script>
    </head>

<body>
    <div id="app">
        <task-manager></task-manager>
        </div>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
