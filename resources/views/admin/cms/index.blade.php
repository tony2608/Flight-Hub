@extends('layouts.staff')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Manajemen Konten Beranda</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-10">
                <div class="card shadow mb-4 border-0" style="border-radius: 15px;">
                    <div class="card-header py-3 bg-white" style="border-radius: 15px 15px 0 0;">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-edit me-2"></i> Edit Slider & Promo Destinasi</h6>
                    </div>
                    <div class="card-body bg-light">
                        
                        {{-- Accordion Mulai --}}
                        <div class="accordion shadow-sm" id="cmsAccordion">
                            
                            {{-- ================= SLIDER 1 ================= --}}
                            <div class="accordion-item border-0 mb-2" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="headingSlide1">
                                    <button class="accordion-button fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSlide1" aria-expanded="true" aria-controls="collapseSlide1">
                                        <i class="fas fa-image me-2"></i> Gambar & Teks Slider 1 (Tentang Kami)
                                    </button>
                                </h2>
                                <div id="collapseSlide1" class="accordion-collapse collapse show" aria-labelledby="headingSlide1" data-bs-parent="#cmsAccordion">
                                    <div class="accordion-body bg-white">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Judul Slider</label>
                                                    <input type="text" name="content[slide_1][title]" class="form-control" value="{{ $contents['slide_1']->title ?? '' }}" placeholder="Contoh: Pilihan Utama Untuk Jelajahi Dunia">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Deskripsi</label>
                                                    <textarea name="content[slide_1][description]" class="form-control" rows="3" placeholder="Masukkan deskripsi...">{{ $contents['slide_1']->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-3">
                                                    <label class="fw-bold small text-danger">Upload Foto Baru</label>
                                                    <input type="file" name="content[slide_1][image]" class="form-control form-control-sm mb-2" accept="image/*">
                                                    <small class="text-muted d-block mb-2">*Kosongkan jika tidak ingin ganti foto</small>
                                                    
                                                    @if(isset($contents['slide_1']) && $contents['slide_1']->image)
                                                        <div class="text-center p-2 border rounded">
                                                            <small class="d-block mb-1 fw-bold">Foto Saat Ini:</small>
                                                            <img src="{{ asset('storage/'.$contents['slide_1']->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= SLIDER 2 ================= --}}
                            <div class="accordion-item border-0 mb-2" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="headingSlide2">
                                    <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSlide2" aria-expanded="false" aria-controls="collapseSlide2">
                                        <i class="fas fa-image me-2"></i> Gambar & Teks Slider 2 (Kenyamanan)
                                    </button>
                                </h2>
                                <div id="collapseSlide2" class="accordion-collapse collapse" aria-labelledby="headingSlide2" data-bs-parent="#cmsAccordion">
                                    <div class="accordion-body bg-white">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Judul Slider</label>
                                                    <input type="text" name="content[slide_2][title]" class="form-control" value="{{ $contents['slide_2']->title ?? '' }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Deskripsi</label>
                                                    <textarea name="content[slide_2][description]" class="form-control" rows="3">{{ $contents['slide_2']->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-3">
                                                    <label class="fw-bold small text-danger">Upload Foto Baru</label>
                                                    <input type="file" name="content[slide_2][image]" class="form-control form-control-sm mb-2" accept="image/*">
                                                    @if(isset($contents['slide_2']) && $contents['slide_2']->image)
                                                        <div class="text-center p-2 border rounded">
                                                            <img src="{{ asset('storage/'.$contents['slide_2']->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= SLIDER 3 ================= --}}
                            <div class="accordion-item border-0 mb-2" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="headingSlide3">
                                    <button class="accordion-button collapsed fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSlide3" aria-expanded="false" aria-controls="collapseSlide3">
                                        <i class="fas fa-image me-2"></i> Gambar & Teks Slider 3 (Layanan Pelanggan)
                                    </button>
                                </h2>
                                <div id="collapseSlide3" class="accordion-collapse collapse" aria-labelledby="headingSlide3" data-bs-parent="#cmsAccordion">
                                    <div class="accordion-body bg-white">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Judul Slider</label>
                                                    <input type="text" name="content[slide_3][title]" class="form-control" value="{{ $contents['slide_3']->title ?? '' }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Deskripsi</label>
                                                    <textarea name="content[slide_3][description]" class="form-control" rows="3">{{ $contents['slide_3']->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-3">
                                                    <label class="fw-bold small text-danger">Upload Foto Baru</label>
                                                    <input type="file" name="content[slide_3][image]" class="form-control form-control-sm mb-2" accept="image/*">
                                                    @if(isset($contents['slide_3']) && $contents['slide_3']->image)
                                                        <div class="text-center p-2 border rounded">
                                                            <img src="{{ asset('storage/'.$contents['slide_3']->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= PROMO 1 ================= --}}
                            <div class="accordion-item border-0 mb-2" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="headingPromo1">
                                    <button class="accordion-button collapsed fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePromo1" aria-expanded="false" aria-controls="collapsePromo1">
                                        <i class="fas fa-tags me-2"></i> Kotak Promo 1 (Kiri)
                                    </button>
                                </h2>
                                <div id="collapsePromo1" class="accordion-collapse collapse" aria-labelledby="headingPromo1" data-bs-parent="#cmsAccordion">
                                    <div class="accordion-body bg-white">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold small">Label Promo (Badge)</label>
                                                        <input type="text" name="content[promo_1][button_text]" class="form-control" value="{{ $contents['promo_1']->button_text ?? '' }}" placeholder="Contoh: Diskon 20%">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold small">Judul Destinasi</label>
                                                        <input type="text" name="content[promo_1][title]" class="form-control" value="{{ $contents['promo_1']->title ?? '' }}" placeholder="Contoh: Terbang ke Bali">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Deskripsi Singkat</label>
                                                    <textarea name="content[promo_1][description]" class="form-control" rows="2">{{ $contents['promo_1']->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-3">
                                                    <label class="fw-bold small text-danger">Upload Foto Destinasi</label>
                                                    <input type="file" name="content[promo_1][image]" class="form-control form-control-sm mb-2" accept="image/*">
                                                    @if(isset($contents['promo_1']) && $contents['promo_1']->image)
                                                        <div class="text-center p-2 border rounded">
                                                            <img src="{{ asset('storage/'.$contents['promo_1']->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- ================= PROMO 2 ================= --}}
                            <div class="accordion-item border-0 mb-2" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="headingPromo2">
                                    <button class="accordion-button collapsed fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePromo2" aria-expanded="false" aria-controls="collapsePromo2">
                                        <i class="fas fa-tags me-2"></i> Kotak Promo 2 (Tengah)
                                    </button>
                                </h2>
                                <div id="collapsePromo2" class="accordion-collapse collapse" aria-labelledby="headingPromo2" data-bs-parent="#cmsAccordion">
                                    <div class="accordion-body bg-white">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold small">Label Promo (Badge)</label>
                                                        <input type="text" name="content[promo_2][button_text]" class="form-control" value="{{ $contents['promo_2']->button_text ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold small">Judul Destinasi</label>
                                                        <input type="text" name="content[promo_2][title]" class="form-control" value="{{ $contents['promo_2']->title ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Deskripsi Singkat</label>
                                                    <textarea name="content[promo_2][description]" class="form-control" rows="2">{{ $contents['promo_2']->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-3">
                                                    <label class="fw-bold small text-danger">Upload Foto Destinasi</label>
                                                    <input type="file" name="content[promo_2][image]" class="form-control form-control-sm mb-2" accept="image/*">
                                                    @if(isset($contents['promo_2']) && $contents['promo_2']->image)
                                                        <div class="text-center p-2 border rounded">
                                                            <img src="{{ asset('storage/'.$contents['promo_2']->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= PROMO 3 ================= --}}
                            <div class="accordion-item border-0" style="border-radius: 10px; overflow: hidden;">
                                <h2 class="accordion-header" id="headingPromo3">
                                    <button class="accordion-button collapsed fw-bold text-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePromo3" aria-expanded="false" aria-controls="collapsePromo3">
                                        <i class="fas fa-tags me-2"></i> Kotak Promo 3 (Kanan)
                                    </button>
                                </h2>
                                <div id="collapsePromo3" class="accordion-collapse collapse" aria-labelledby="headingPromo3" data-bs-parent="#cmsAccordion">
                                    <div class="accordion-body bg-white">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold small">Label Promo (Badge)</label>
                                                        <input type="text" name="content[promo_3][button_text]" class="form-control" value="{{ $contents['promo_3']->button_text ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold small">Judul Destinasi</label>
                                                        <input type="text" name="content[promo_3][title]" class="form-control" value="{{ $contents['promo_3']->title ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="fw-bold small">Deskripsi Singkat</label>
                                                    <textarea name="content[promo_3][description]" class="form-control" rows="2">{{ $contents['promo_3']->description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-3">
                                                    <label class="fw-bold small text-danger">Upload Foto Destinasi</label>
                                                    <input type="file" name="content[promo_3][image]" class="form-control form-control-sm mb-2" accept="image/*">
                                                    @if(isset($contents['promo_3']) && $contents['promo_3']->image)
                                                        <div class="text-center p-2 border rounded">
                                                            <img src="{{ asset('storage/'.$contents['promo_3']->image) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- Accordion Selesai --}}
                        
                    </div>
                    <div class="card-footer text-end bg-white py-3" style="border-radius: 0 0 15px 15px;">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan Web
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection