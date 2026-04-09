@extends('layout.app')

@section('title', 'Foto Slide | DIPANTAU')

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000
    });
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: "{{ session('error') }}",
        showConfirmButton: false,
        timer: 2000
    });
});
</script>
@endif

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Kelola Slide (Maksimal 4)
        </div>
    <div class="card-body">

    {{-- FORM TAMBAH SLIDE --}}
    @if($slides->count() < 4)
    <form action="{{ route('slides.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <input type="text" name="title" class="form-control" placeholder="Judul Slide">
        </div>
        <div class="mb-2">
            <input type="file" name="image" class="form-control" required>
        </div>
        <button class="btn btn-primary">Tambah Slide</button>
        <small id="fotoinfo">(Maksimal 2MB)</small>
    </form>
    @else
        <div class="alert alert-warning">
            Maksimal 4 slide sudah tercapai. Hapus salah satu untuk menambah.
        </div>
    @endif
    </div>
    </div>


    <hr>

    {{-- LIST SLIDE --}}
    <div class="row" id="slides-container">
        @foreach($slides as $slide)
        <div class="col-md-3 mb-4 slide-item" data-id="{{ $slide->id }}">
            <div class="card shadow-sm">
                <img src="{{ asset('storage/'.$slide->image) }}" class="card-img-top" style="height:150px; object-fit:cover;">
                <div class="card-body text-center">
                    <small>{{ $slide->title }}</small>
                    <form action="{{ route('slides.destroy', $slide->id) }}" method="POST" class="mt-2 delete-slide-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm btn-delete-slide">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Drag & Drop
    var container = document.getElementById('slides-container');
    new Sortable(container, {
        animation: 150,
        onEnd: function (evt) {
            var order = [];
            document.querySelectorAll('.slide-item').forEach((el, index) => {
                order.push({
                    id: el.dataset.id,
                    order: index + 1
                });
            });

            fetch('{{ route("slides.updateOrderAjax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({order: order})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Urutan slide diperbarui',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
            });
        }
    });

    // Konfirmasi Hapus Slide
    document.querySelectorAll('.btn-delete-slide').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Slide akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection