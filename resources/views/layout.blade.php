<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Bibliothèque</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="header">
        <h1>Gestion de Bibliothèque</h1>
        <nav class="nav">
            <a href="{{ route('books.index') }}">Livres</a>
            <a href="{{ route('members.index') }}">Membres</a>
            <a href="{{ route('loans.index') }}">Emprunts</a>
        </nav>
    </header>
    <main class="container">
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="footer">
        <p>&copy; {{ date('Y') }} Bibliothèque</p>
    </footer>
</body>
</html>
