<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Revan CRM') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <style>
        /* Add padding around tables within cards for better spacing */
        .card > .table-responsive,
        .card .table-responsive {
            padding: 1rem;
        }
        /* Increase cell padding for DataTables to match Bootstrap spacing */
        table.dataTable > thead > tr > th,
        table.dataTable > tbody > tr > td,
        table.dataTable > tfoot > tr > th {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Revan CRM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('projects.index') }}">Projects</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('leads.index') }}">Leads</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customers.index') }}">Customers</a></li>
                    @if(auth()->user()->isManager())
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item d-flex align-items-center text-white me-2">Hello, {{ auth()->user()->name }}</li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</div>
@stack('modals')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.querySelector('#myTable');
        if (el) {
            let table = new DataTable('#myTable');
        }
    });
</script>
@yield('scripts')
</body>
</html>
