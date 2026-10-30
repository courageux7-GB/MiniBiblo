@extends('layout')

@section('content')
<div class="card">
    <h2>Liste des livres</h2>
    <a href="{{ route('books.create') }}" class="btn">Ajouter un livre</a>
    @if($books->isEmpty())
        <p>Aucun livre disponible.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>ISBN</th>
                    <th>Exemplaires totaux</th>
                    <th>Disponibles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->total_copies }}</td>
                        <td>{{ $book->available_copies }}</td>
                        <td>
                            <a href="{{ route('books.edit', $book) }}" class="btn">Modifier</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce livre ?')">Supprimer</button>
                            </form>
                            @if($book->available_copies > 0)
                                <a href="{{ route('books.borrowForm', $book) }}" class="btn">Emprunter</a>
                            @else
                                <span class="btn" style="opacity:0.5; cursor:not-allowed;">Emprunter</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
