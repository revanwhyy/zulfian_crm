@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Leads</h1>
<div class="card">
    <div class="table-responsive">
        <table id="myTable" class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Dibuat</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($leads as $lead)
                <tr>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->email }}</td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ $lead->address }}</td>
                    <td>{{ $lead->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data lead.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
