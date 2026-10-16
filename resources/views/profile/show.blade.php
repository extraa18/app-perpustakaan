@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <h1>Profil Saya</h1>

    <table>
        <tr>
            <th style="width: 160px; background: #f3f4f6;">Nama</th>
            <td>{{ $user['name'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Email</th>
            <td>{{ $user['email'] }}</td>
        </tr>
        <tr>
            <th style="background: #f3f4f6;">Role</th>
            <td>{{ ucfirst($user['role']) }}</td>
        </tr>
    </table>

    <h2 style="margin-top: 32px;">Ganti Password</h2>

    <form action="{{ route('profile.password') }}" method="POST">
        @csrf
        @method('PUT')

        <label for="password_lama">Password Lama</label>
        <input type="password" name="password_lama" id="password_lama">
        @error('password_lama')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_baru">Password Baru</label>
        <input type="password" name="password_baru" id="password_baru">
        @error('password_baru')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
        <input type="password" name="password_baru_confirmation" id="password_baru_confirmation">

        <button type="submit" class="btn">Ganti Password</button>
    </form>
@endsection