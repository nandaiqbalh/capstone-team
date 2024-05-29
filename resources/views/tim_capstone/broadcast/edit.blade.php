@extends('tim_capstone.base.app')

@section('title')
    Pengumuman
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Pengumuman</h5>
        <!-- notification -->
        @include('template.notification')

        <!-- Bordered Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ubah Pengumuman</h5>
                <small class="text-muted float-end">
                    <a href="{{ url('/tim-capstone/broadcast') }}" class="btn btn-danger btn-sm float-right"><i
                            class="fas fa-chevron-left fa-sm"></i> Kembali</a>
                </small>
            </div>
            <form action="{{ url('/tim-capstone/broadcast/edit-process') }}" method="post" autocomplete="off"
                enctype="multipart/form-data">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $broadcast->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Nama Event<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_event"
                                    value="{{ $broadcast->nama_event }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Link Pendukung</label>
                                <input type="text" class="form-control" name="link_pendukung"
                                    value="{{ $broadcast->link_pendukung }}" placeholder="Contoh: google.com">
                                <small class="text-muted">Masukkan URL tanpa https://</small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tanggal Mulai<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tgl_mulai"
                                    value="{{ $broadcast->tgl_mulai }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Tanggal Selesai<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tgl_selesai"
                                    value="{{ $broadcast->tgl_selesai }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>Keterangan<span class="text-danger"></span></label>
                                <textarea class="form-control" id="editor" name="keterangan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    {{-- <script>
                        $(document).ready(function() {
                            ClassicEditor
                                .create(document.querySelector('#editor'))
                                .then(editor => {
                                    editor.setData(`{!! old('keterangan') !!}`); // Set nilai awal editor CKEditor
                                })
                                .catch(error => {
                                    console.error(error);
                                });
                        });
                    </script> --}}
                    <script>
                        ClassicEditor
                            .create(document.querySelector('#editor'), {
                                toolbar: {
                                    items: [
                                        'heading',
                                        '|',
                                        'bold',
                                        'italic',
                                        'link',
                                        'bulletedList',
                                        'numberedList',
                                        'alignment',
                                        '|',
                                        'undo',
                                        'redo'
                                    ]
                                },
                                language: 'en',
                                image: {
                                    toolbar: ['imageTextAlternative']
                                }
                            })
                            .then(editor => {
                                editor.setData(`{!! old('keterangan') !!}`);
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    </script>
                    
                </div>
                <div class="card-footer float-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
