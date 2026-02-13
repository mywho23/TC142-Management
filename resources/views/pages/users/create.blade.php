@extends('layouts.template')
@section('content')
<div class="container mt-4">
    <h2>Tambah User Baru</h2>

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

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="full_name" name="full_name" class="form-control" value="{{ old('full_name') }}">
        </div>

        <div class="mb-3">
            <label for="role_id" class="form-label">Role</label>
            <select name="role_id" class="form-control">
                <option value="">-- Pilih Role --</option>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="" disabled selected>-- Pilih Status --</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
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

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection