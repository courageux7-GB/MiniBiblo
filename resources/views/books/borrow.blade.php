@extends('layout')

@section('content')
<div class="card">
    <h2>Emprunter le livre : {{ $book->title }}</h2>
    <form action="{{ route('books.borrow', $book) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="member_id">Sélectionner un membre</label>
            <select name="member_id" id="member_id" class="btn" required>
                <option value="" disabled selected>-- Choisir un membre --</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}">
                        {{ $member->name }} ({{ $member->email }})
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn">Enregistrer l'emprunt</button>
        <a href="{{ route('books.index') }}" class="btn btn-danger" style="margin-left:10px;">Annuler</a>
    </form>
</div>
@endsection
