@extends('layout')

@section('content')
<div class="card">
    <h2>Liste des membres</h2>
    <a href="{{ route('members.create') }}" class="btn">Ajouter un membre</a>
    @if($members->isEmpty())
        <p>Aucun membre enregistré.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <a href="{{ route('members.edit', $member) }}" class="btn">Modifier</a>
                            <form action="{{ route('members.destroy', $member) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce membre ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
