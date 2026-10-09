
@extends('layouts.profil.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-page {
        max-width: 420px;
        margin: 20px auto;
        padding: 0 12px;
    }

    .profile-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px 16px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
    }

    .profile-photo-wrapper {
        position: relative;
        width: 88px;
        height: 88px;
        margin: 0 auto;
    }

    .profile-photo {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #198754;
    }

    .profile-photo-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #198754;
        color: white;
        font-weight: bold;
        font-size: 22px;
    }

    .profile-plus {
        position: absolute;
        right: -4px;
        bottom: -2px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid white;
        background: #198754;
        color: white;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-plus:hover {
        background: #157347;
    }

    .profile-name {
        font-size: 18px;
        font-weight: 700;
        margin-top: 14px;
        margin-bottom: 12px;
    }

    .profile-subtitle {
        color: #64748b;
        font-size: 14px;
    }

    .profile-divider {
        border-color: #dce3eb;
        margin: 22px 0;
    }

    .profile-info {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 20px;
    }

    .profile-info i {
        color: #198754;
        font-size: 18px;
        width: 18px;
        margin-top: 13px;
    }

    .profile-info-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .profile-info-value {
        color: #343a40;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .btn-edit-profile {
        width: 100%;
        padding: 12px;
        border-radius: 9px;
        font-weight: 600;
        background: #198754;
        color: #fff;
        border: none;
    }

    .btn-edit-profile:hover {
        background: #157347;
        color: #fff;
    }
</style>

<div class="profile-page">
    <div class="profile-card">

        {{-- FOTO PROFIL DAN TOMBOL PLUS --}}
        <div class="text-center">
            <div class="profile-photo-wrapper">

                @if (auth()->user()->photo)
                    <img
                        src="{{ asset('storage/' . auth()->user()->photo) }}"
                        alt="Foto Profil"
                        class="profile-photo">
                @else
                    <div class="profile-photo profile-photo-fallback">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                @endif

                <button
                    type="button"
                    class="profile-plus"
                    title="Ganti foto profil"
                    aria-label="Ganti foto profil"
                    onclick="document.getElementById('profile-photo-input').click()">
                    +
                </button>

                <form
                    action="{{ route('profile.photo') }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <input
                        type="file"
                        name="photo"
                        id="profile-photo-input"
                        accept=".jpg,.jpeg,.png,.webp"
                        hidden
                        onchange="if (this.files.length) this.form.submit()">
                </form>

            </div>

            <h5 class="profile-name">
                {{ auth()->user()->name }}
            </h5>

            <div class="profile-subtitle">Informasi akun</div>
        </div>

        <hr class="profile-divider">

        {{-- NAMA --}}
        <div class="profile-info">
            <i class="bi bi-person"></i>
            <div>
                <div class="profile-info-label">Nama</div>
                <div class="profile-info-value">
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        {{-- EMAIL --}}
        <div class="profile-info">
            <i class="bi bi-envelope"></i>
            <div>
                <div class="profile-info-label">Email</div>
                <div class="profile-info-value">
                    {{ auth()->user()->email }}
                </div>
            </div>
        </div>

        {{-- TELEPON --}}
        <div class="profile-info">
            <i class="bi bi-telephone"></i>
            <div>
                <div class="profile-info-label">Telephone</div>
                <div class="profile-info-value">
                    {{ auth()->user()->phone ?? '-' }}
                </div>
            </div>
        </div>

        {{-- ALAMAT --}}
        <div class="profile-info">
            <i class="bi bi-geo-alt"></i>
            <div>
                <div class="profile-info-label">Alamat</div>
                <div class="profile-info-value">
                    {{ auth()->user()->address ?? '-' }}
                </div>
            </div>
        </div>

        {{-- EDIT PROFIL --}}
        <a href="{{ route('profile.edit') }}"
           class="btn btn-edit-profile">
            Edit Profil
        </a>

    </div>
</div>
@endsection
