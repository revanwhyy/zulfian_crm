@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Katalog Produk</h1>
@if($products->isEmpty())
    <div class="alert alert-info">Belum ada produk. Silakan login sebagai Manager untuk menambahkan produk.</div>
@else
<div class="row g-3">
    @foreach ($products as $product)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ $product->description }}</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Rp {{ number_format($product->subtotal, 0, ',', '.') }}</span>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buyModal{{ $product->id }}">Beli</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buy Modal -->
        <div class="modal fade" id="buyModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Beli: {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('buy') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif
@endsection
