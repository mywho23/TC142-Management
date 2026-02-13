@extends('layouts.template') {{-- kalau lu punya layout utama --}}

@section('content')
<div class="container mt-4">
    <div class="table-responsive">
        <h2>Daftar User</h2>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <table id="usersTable" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Foto</th>
                    <th>Last Login</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ str_repeat('*', 10) }}</td>
                    <td>{{ $u->full_name }}</td>
                    <td>{{ $u->role->nama ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $u->status == 'active' ? 'bg-primary' : 'bg-danger' }}">
                            {{ ucfirst($u->status) }}
                        </span>
                    </td>
                    <td>
                        <img src="{{ asset('template/assets/images/user/' . $u->img) }}" width="50" height="50" class="img-thumbnail">
                    </td>
                    <td>{{ $u->last_login }}</td>
                    <td>
                        {{-- Tombol Edit --}}
                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-warning btn-sm me-1">
                            <i class="fa fa-pencil-square-o"></i>
                        </a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau hapus user ini?')">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data user</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection