@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Produk</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table id="myTable" class="table table-striped mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th class="text-end no-sort">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="text-truncate" style="max-width: 400px;">{{ $product->description }}</td>
                    <td>Rp {{ number_format($product->subtotal, 0, ',', '.') }}</td>
                    <td class="text-end">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada produk.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
