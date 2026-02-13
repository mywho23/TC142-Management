@include('layouts/template')
@section('content')

<div class="container mt-4">
    <h2>Daftar User</h2>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Foto</th>
                <th>Login Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $user)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role ? $user->role->name : '-' }}</td>
                <td>
                    @if($user->status == 'active')
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>{{ $user->last_login ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>