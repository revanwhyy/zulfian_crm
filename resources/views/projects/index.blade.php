@extends('layouts.app')

@section('content')
<h1 class="h4 mb-3">Projects</h1>
<div class="card">
    <div class="table-responsive">
        <table id="myTable" class="table table-striped mb-0 align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>Lead</th>
                <th>Produk</th>
                <th>Sales</th>
                <th>Status</th>
                <th class="text-end no-sort">Aksi</th>
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
                    <td>
                        @php($badge = match($project->status){
                            'waiting' => 'secondary',
                            'in_progress' => 'info',
                            'waiting_for_approval' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'secondary'
                        })
                        <span class="badge bg-{{ $badge }} text-uppercase">{{ str_replace('_',' ', $project->status) }}</span>
                    </td>
                    <td class="text-end">
                        @auth
                            @if(auth()->user()->isManager())
                                @if($project->status === \App\Models\Project::STATUS_WAITING)
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $project->id }}">Assign</button>
                                @endif
                                @if($project->status === \App\Models\Project::STATUS_WAITING_FOR_APPROVAL)
                                    <form method="POST" action="{{ route('projects.updateStatus', $project) }}" class="d-inline" onsubmit="return confirm('Setujui project ini?')">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('projects.updateStatus', $project) }}" class="d-inline" onsubmit="return confirm('Tolak project ini?')">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @endif
                            @elseif(auth()->user()->isSales())
                                @if($project->status === \App\Models\Project::STATUS_IN_PROGRESS && $project->user_id === auth()->id())
                                    <form method="POST" action="{{ route('projects.updateStatus', $project) }}" class="d-inline" onsubmit="return confirm('Ubah status ke menunggu persetujuan?')">
                                        @csrf
                                        <input type="hidden" name="status" value="waiting_for_approval">
                                        <button class="btn btn-sm btn-warning">Ajukan Persetujuan</button>
                                    </form>
                                @endif
                            @endif
                        @endauth
                    </td>
                </tr>

                @if(auth()->check() && auth()->user()->isManager())
                @push('modals')
                <div class="modal fade" id="assignModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Sales - Project #{{ $project->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('projects.assign', $project) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Pilih Sales</label>
                                        <select name="user_id" class="form-select" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach($sales as $s)
                                                <option value="{{ $s->id }}" @selected($project->user_id === $s->id)>{{ $s->name }} ({{ $s->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endpush
                @endif
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada project.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
