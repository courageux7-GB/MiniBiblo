@extends('layout')

@section('content')
<div class="card">
    <h2>Ajouter un livre</h2>
    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" class="btn" required>
        </div>
        <div class="form-group">
            <label for="author">Auteur</label>
            <input type="text" id="author" name="author" class="btn" required>
        </div>
        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn" class="btn" required>
        </div>
        <div class="form-group">
            <label for="total_copies">Nombre total d'exemplaires</label>
            <input type="number" id="total_copies" name="total_copies" class="btn" min="1" required>
        </div>
        <button type="submit" class="btn">Enregistrer</button>
        <a href="{{ route('books.index') }}" class="btn btn-danger" style="margin-left:10px;">Annuler</a>
    </form>
</div>
@endsection
