<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Éditeur de contenu')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @stack('styles')
</head>
<body>
    <div class="editor-fullscreen">
        @yield('content')
    </div>
    
    <!-- Scripts -->
    @stack('scripts')
</body>
</html>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .editor-fullscreen {
        height: 100vh;
        width: 100vw;
        overflow: hidden;
        background: #f1f5f9;
    }
    
    body {
        overflow: hidden;
    }
</style>

<script>
    window.currentEtablissementId = {{ $etablissement->id }};
    const currentEtablissementId = {{ $etablissement->id }};
</script>