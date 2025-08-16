@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Edit Produk</h1>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="subtotal" class="form-control" value="{{ old('subtotal', $product->subtotal) }}" min="0" step="0.01" required>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
