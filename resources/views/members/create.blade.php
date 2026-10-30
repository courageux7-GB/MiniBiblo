@extends('layout')

@section('content')
<div class="card">
    <h2>Ajouter un membre</h2>
    <form action="{{ route('members.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" class="btn" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="btn" required>
        </div>
        <button type="submit" class="btn">Enregistrer</button>
        <a href="{{ route('members.index') }}" class="btn btn-danger" style="margin-left:10px;">Annuler</a>
    </form>
</div>
@endsection
