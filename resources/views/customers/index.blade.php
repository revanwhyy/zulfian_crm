@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Customers (Approved Projects)</h1>
<div class="card">
    <div class="table-responsive">
        <table id="myTable" class="table table-striped mb-0 align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>Lead</th>
                <th>Produk</th>
                <th>Sales</th>
                <th>Disetujui Pada</th>
            </tr>
            </thead>
            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>{{ $project->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $project->lead->name }}</div>
                        <div class="small text-muted">{{ $project->lead->email }} | {{ $project->lead->phone }}</div>
                        <div class="small text-muted">{{ $project->lead->address }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $project->product->name }}</div>
                        <div class="small text-muted">Rp {{ number_format($project->product->subtotal, 0, ',', '.') }}</div>
                    </td>
                    <td>{{ $project->user?->name ?? '-' }}</td>
                    <td>{{ $project->updated_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada pelanggan (approved).</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
