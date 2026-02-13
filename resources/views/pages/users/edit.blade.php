@extends('layouts.template')
@section('content')
<div class="container mt-4">
    <h2>Ubah Data User</h2>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Ups!</strong> Form belum terisi dengan benar:
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" value="{{ old('password', $user->password)}}">
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $user->full_name)}}">
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select name="role_id" id="role_id" class="form-control">
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                    {{ $role->nama }}
                </option>

                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">Foto</label>
            <input type="file" name="img" id="img" class="form-control" accept="image/*" onchange="previewImage(event)">
        </div>
        <!-- Tempat preview -->
        <div class="mb-3">
            <img id="preview" src="#" alt="Preview Foto" style="max-width: 150px; display: none; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
        </div>
        @if ($user->img)
        <div class="mb-3">
            <label>Foto Saat Ini</label><br>
            <img src="{{ asset('template/assets/images/user/' . $user->img) }}" width="100" class="img-thumbnail mb-2">
        </div>
        @endif

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection