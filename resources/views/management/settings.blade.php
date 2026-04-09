@extends('layout.app')

@section('title', 'Nomor WhatsApp | DIPANTAU')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Pengaturan Nomor WhatsApp Aduan
        </div>
        <div class="card-body">

            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('management.settings.update') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nomor WhatsApp (format 628xxx)</label>
                    <input type="text"
                           name="whatsapp_number"
                           value="{{ $setting->whatsapp_number }}"
                           class="form-control"
                           required>
                </div>

                <button type="submit" class="btn btn-success">
                    Simpan Perubahan
                </button>

            </form>

        </div>
    </div>
</div>

@endsection