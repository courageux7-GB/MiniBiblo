@extends('layout')

@section('content')
<div class="card">
    <h2>Emprunts en cours</h2>
    <form method="GET" action="{{ route('loans.index') }}" style="margin-bottom:1rem;">
        <label for="member_id">Filtrer par membre :</label>
        <select name="member_id" id="member_id" onchange="this.form.submit()" class="btn" style="margin-left:0.5rem;">
            <option value="">Tous les membres</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                    {{ $member->name }} ({{ $member->email }})
                </option>
            @endforeach
        </select>
    </form>

    @if($loans->isEmpty())
        <p>Aucun emprunt en cours.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Livre</th>
                    <th>Membre</th>
                    <th>Date d'emprunt</th>
                    <th>Date prévue de retour</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                    @php
                        $isOverdue = now()->gt($loan->due_date);
                    @endphp
                    <tr>
                        <td>{{ $loan->book->title }}</td>
                        <td>{{ $loan->member->name }}</td>
                        <td>{{ $loan->borrow_date->format('d/m/Y') }}</td>
                        <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                        <td class="{{ $isOverdue ? 'overdue' : '' }}">
                            {{ $isOverdue ? 'En retard' : 'À temps' }}
                        </td>
                        <td>
                            <form action="{{ route('loans.return', $loan) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Confirmer le retour du livre ?')">Retourner</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
