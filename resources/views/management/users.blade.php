@extends('layout.app')

@section('title', 'Tambah Admin | DIPANTAU')

@section('content')

<div class="container mt-3">

    <button class="btn btn-warning fw-semibold mb-3" id="btnToggleForm">+</button>

    <div id="formTambah" style="display:none;">
        <form method="POST" action="{{ route('management.users.store') }}">
            @csrf

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="superadmin">Superadmin</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-warning fw-semibold">
                                Simpan
                            </button>
                            <button type="reset" class="btn btn-secondary fw-semibold">
                                Reset
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </div>

</div>


<!-- TABEL USER -->
<div class="container">
    <div class="card">
        <div class="card-body">

            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role == 'superadmin' ? 'bg-danger' : 'bg-primary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="text-center">

                            <form action="{{ route('management.users.destroy',$user->id) }}"
                                  method="POST"
                                  style="display:inline;"
                                  class="delete-user-form">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-danger btn-sm btn-delete-user">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script>

// Toggle Form
$('#btnToggleForm').click(function(){

    let form = $('#formTambah');
    let btn  = $(this);

    form.slideToggle(200,function(){
        btn.html(form.is(':visible') ? '-' : '+');
    });

});


// SweetAlert Success
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
@endif


// SweetAlert Error
@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: "{{ session('error') }}"
});
@endif


// Konfirmasi Hapus
$('.btn-delete-user').click(function(){

    const form = $(this).closest('form');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "User akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result)=>{
        if(result.isConfirmed){
            form.submit();
        }
    });

});

</script>

@endsection