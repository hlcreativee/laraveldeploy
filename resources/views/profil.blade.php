@extends('layouts.app')

@section('content')

<div class="container-profil">

    <div class="header">
        <h2>Profil Pengguna</h2>

        <div>
            <button class="btn" onclick="openModal()">Edit Profil</button>

            <a href="{{ route('profil.delete') }}"
               onclick="return confirm('Yakin hapus data?')"
               style="background:red; color:white; padding:8px 12px; border-radius:8px; margin-left:10px;">
               Hapus
            </a>
        </div>
    </div>

    {{-- NOTIF --}}
    @if(session('success'))
        <div style="background:#d1fae5; padding:10px; border-radius:8px; margin-top:10px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- PROFILE CARD --}}
    <div class="profile-card">
        <img 
             src="{{ $user && $user->foto ? asset('uploads/'.$user->foto) : 'https://via.placeholder.com/100' }}"
             style="width:100px; height:100px; object-fit:cover; border-radius:50%;"
>
        <div>
            <h2 style="margin:0;">
                {{ $user->nama ?? 'USER' }}
            </h2>

            <p style="margin:5px 0;">
                {{ $user->email ?? '-' }}
            </p>

            <small>
                {{ $user->no_hp ?? '-' }}
            </small>
        </div>
    </div>

    {{-- INFO CARD --}}
    <div class="cards">

        <div class="card">
            <h4>Email</h4>
            <p>{{ $user->email ?? '-' }}</p>
        </div>

        <div class="card">
            <h4>No HP</h4>
            <p>{{ $user->no_hp ?? '-' }}</p>
        </div>

    </div>

</div>

{{-- MODAL --}}
<div id="modalEdit" class="modal">
    <div class="modal-content">

        <h3>Edit Profil</h3>

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="{{ $user->nama ?? '' }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email ?? '' }}">
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" value="{{ $user->no_hp ?? '' }}">
            </div>

            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="foto">
            </div>

            <div class="form-action">
                <button type="submit" class="btn-save">Simpan</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
            </div>

        </form>

    </div>
</div>

<script>
function openModal() {
    document.getElementById("modalEdit").style.display = "flex";
}

function closeModal() {
    document.getElementById("modalEdit").style.display = "none";
}
</script>

@endsection