@extends('admin.admin_dashboard')
@section('admin') 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengaturan Warna Frontend</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan Warna</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <form action="{{ route('color.update') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $site->id }}">

                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle"></i> Ubah warna frontend sesuai keinginan Anda. Warna akan diterapkan secara dinamis ke seluruh halaman frontend.
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Warna Utama (Primary)</h6>
                                        <small class="text-muted">Digunakan untuk tombol utama, link, dll</small>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="input-group">
                                            <input type="color" name="primary_color" class="form-control form-control-color" value="{{ $site->primary_color ?? '#B56952' }}" title="Pilih warna utama">
                                            <input type="text" name="primary_color_text" class="form-control" value="{{ $site->primary_color ?? '#B56952' }}" id="primary_color_text" placeholder="#B56952">
                                        </div>
                                        <small class="text-muted">Format: #RRGGBB (contoh: #B56952)</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Warna Sekunder (Secondary)</h6>
                                        <small class="text-muted">Warna pendamping</small>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="input-group">
                                            <input type="color" name="secondary_color" class="form-control form-control-color" value="{{ $site->secondary_color ?? '#C890FF' }}" title="Pilih warna sekunder">
                                            <input type="text" name="secondary_color_text" class="form-control" value="{{ $site->secondary_color ?? '#C890FF' }}" id="secondary_color_text" placeholder="#C890FF">
                                        </div>
                                        <small class="text-muted">Format: #RRGGBB (contoh: #C890FF)</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Warna Aksen (Accent)</h6>
                                        <small class="text-muted">Warna untuk highlight/emphasis</small>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="input-group">
                                            <input type="color" name="accent_color" class="form-control form-control-color" value="{{ $site->accent_color ?? '#EE786C' }}" title="Pilih warna aksen">
                                            <input type="text" name="accent_color_text" class="form-control" value="{{ $site->accent_color ?? '#EE786C' }}" id="accent_color_text" placeholder="#EE786C">
                                        </div>
                                        <small class="text-muted">Format: #RRGGBB (contoh: #EE786C)</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Warna Teks (Text)</h6>
                                        <small class="text-muted">Warna untuk teks utama</small>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="input-group">
                                            <input type="color" name="text_color" class="form-control form-control-color" value="{{ $site->text_color ?? '#292323' }}" title="Pilih warna teks">
                                            <input type="text" name="text_color_text" class="form-control" value="{{ $site->text_color ?? '#292323' }}" id="text_color_text" placeholder="#292323">
                                        </div>
                                        <small class="text-muted">Format: #RRGGBB (contoh: #292323)</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Warna Link</h6>
                                        <small class="text-muted">Warna untuk hyperlink</small>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <div class="input-group">
                                            <input type="color" name="link_color" class="form-control form-control-color" value="{{ $site->link_color ?? '#B56952' }}" title="Pilih warna link">
                                            <input type="text" name="link_color_text" class="form-control" value="{{ $site->link_color ?? '#B56952' }}" id="link_color_text" placeholder="#B56952">
                                        </div>
                                        <small class="text-muted">Format: #RRGGBB (contoh: #B56952)</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bx bx-save"></i> Simpan Perubahan
                                        </button>
                                        <a href="{{ route('site.setting') }}" class="btn btn-secondary px-4">
                                            <i class="bx bx-arrow-back"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Preview Warna</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="p-3 rounded" style="background-color: {{ $site->primary_color ?? '#B56952' }}; color: white; text-align: center;">
                                    Warna Utama
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="p-3 rounded" style="background-color: {{ $site->secondary_color ?? '#C890FF' }}; color: white; text-align: center;">
                                    Warna Sekunder
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="p-3 rounded" style="background-color: {{ $site->accent_color ?? '#EE786C' }}; color: white; text-align: center;">
                                    Warna Aksen
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="p-3 rounded border" style="color: {{ $site->text_color ?? '#292323' }}; text-align: center;">
                                    Warna Teks
                                </div>
                            </div>
                            <div class="mb-3">
                                <a href="#" style="color: {{ $site->link_color ?? '#B56952' }}; text-decoration: underline;">
                                    Warna Link (contoh)
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sync color picker with text input
    document.addEventListener('DOMContentLoaded', function() {
        // Primary Color
        const primaryColor = document.querySelector('input[name="primary_color"]');
        const primaryColorText = document.getElementById('primary_color_text');
        primaryColor.addEventListener('input', function() {
            primaryColorText.value = this.value;
        });
        primaryColorText.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                primaryColor.value = this.value;
            }
        });

        // Secondary Color
        const secondaryColor = document.querySelector('input[name="secondary_color"]');
        const secondaryColorText = document.getElementById('secondary_color_text');
        secondaryColor.addEventListener('input', function() {
            secondaryColorText.value = this.value;
        });
        secondaryColorText.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                secondaryColor.value = this.value;
            }
        });

        // Accent Color
        const accentColor = document.querySelector('input[name="accent_color"]');
        const accentColorText = document.getElementById('accent_color_text');
        accentColor.addEventListener('input', function() {
            accentColorText.value = this.value;
        });
        accentColorText.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                accentColor.value = this.value;
            }
        });

        // Text Color
        const textColor = document.querySelector('input[name="text_color"]');
        const textColorText = document.getElementById('text_color_text');
        textColor.addEventListener('input', function() {
            textColorText.value = this.value;
        });
        textColorText.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                textColor.value = this.value;
            }
        });

        // Link Color
        const linkColor = document.querySelector('input[name="link_color"]');
        const linkColorText = document.getElementById('link_color_text');
        linkColor.addEventListener('input', function() {
            linkColorText.value = this.value;
        });
        linkColorText.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                linkColor.value = this.value;
            }
        });

        // Update form submission to sync color picker with text input
        document.querySelector('form').addEventListener('submit', function(e) {
            // Ensure color pickers have the latest values from text inputs
            if (/^#[0-9A-F]{6}$/i.test(primaryColorText.value)) {
                primaryColor.value = primaryColorText.value;
            }
            if (/^#[0-9A-F]{6}$/i.test(secondaryColorText.value)) {
                secondaryColor.value = secondaryColorText.value;
            }
            if (/^#[0-9A-F]{6}$/i.test(accentColorText.value)) {
                accentColor.value = accentColorText.value;
            }
            if (/^#[0-9A-F]{6}$/i.test(textColorText.value)) {
                textColor.value = textColorText.value;
            }
            if (/^#[0-9A-F]{6}$/i.test(linkColorText.value)) {
                linkColor.value = linkColorText.value;
            }
        });
    });
</script>

@endsection

