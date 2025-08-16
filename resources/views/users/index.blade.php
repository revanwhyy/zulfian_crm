@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Users (Sales)</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Sales</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table id="myTable" class="table table-striped mb-0 align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Dibuat</th>
                <th class="text-end no-sort">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus sales ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data sales.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
