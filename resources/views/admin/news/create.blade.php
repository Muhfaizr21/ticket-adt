@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
/* ===== Neutral, compact, professional ===== */
:root{
  --accent:#2563eb; /* soft blue */
  --muted:#6b7280;
  --bg:#f8fafc;
  --card:#ffffff;
}

.container-centered{ max-width:760px; margin:32px auto; }

.card-clean{
  background:var(--card);
  border-radius:12px;
  box-shadow:0 6px 18px rgba(15,23,42,0.06);
  overflow:hidden;
}

.card-head{
  background:transparent;
  padding:20px 28px;
  border-bottom:1px solid rgba(15,23,42,0.04);
}
.card-body{
  padding:22px 28px;
  background:var(--bg);
}

/* floating inputs but compact */
.form-floating > .form-control,
.form-floating > textarea {
  height: calc(2.4rem + 0.5rem);
  padding:0.6rem 0.75rem;
  border-radius:8px;
  background: #fff;
  border:1px solid rgba(15,23,42,0.06);
  transition:box-shadow .18s, border-color .18s;
}
.form-floating > textarea { height:120px; padding-top:0.9rem; padding-bottom:0.9rem; }
.form-floating > .form-control:focus,
.form-floating > textarea:focus{
  border-color:var(--accent);
  box-shadow:0 6px 18px rgba(37,99,235,0.08);
  background:#fff;
}
label { font-size:0.92rem; color:var(--muted); }

/* small icon inside input (subtle) */
.input-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:0.95rem; }
.with-icon .form-control { padding-left:38px; }

/* file drop zone simple */
.file-zone{
  border:1px dashed rgba(15,23,42,0.06);
  background:#fff;
  border-radius:10px;
  padding:14px;
  text-align:center;
  cursor:pointer;
}
.file-zone p{ margin:0; color:var(--muted); font-size:0.95rem; }

/* preview */
#imagePreview { max-height:220px; border-radius:8px; object-fit:cover; box-shadow:0 6px 18px rgba(15,23,42,0.06); }

/* buttons */
.btn-round{ border-radius:10px; padding:8px 18px; }
.btn-primary-subtle{
  background:var(--accent);
  border:none;
  color:#fff;
}
.btn-secondary-subtle{ background:#fff; border:1px solid rgba(15,23,42,0.06); color:var(--muted); }

/* small screens */
@media (max-width:576px){
  .container-centered{ padding:0 12px; margin:18px auto; }
  .card-head, .card-body{ padding:16px; }
}
</style>
@endpush

@section('content')
<div class="container-centered">
  <div class="card-clean">
    <div class="card-head d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-0 fw-semibold">Tambah Berita</h5>
        <small class="text-muted">Buat berita singkat dan jelas.</small>
      </div>
      <a href="{{ route('admin.news.index') }}" class="btn btn-secondary-subtle btn-round">
        <i class="bi bi-arrow-left me-1"></i> Kembali
      </a>
    </div>

    <div class="card-body">
      <form id="newsForm" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Title --}}
        <div class="form-floating mb-3 position-relative with-icon">
          <i class="bi bi-type input-icon"></i>
          <input name="title" id="title" type="text" class="form-control" placeholder="Judul Berita" required value="{{ old('title') }}">
          <label for="title">Judul Berita</label>
          @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Content --}}
        <div class="form-floating mb-3 position-relative with-icon">
          <i class="bi bi-chat-left-text input-icon" style="top:38px"></i>
          <textarea name="content" id="content" class="form-control" placeholder="Isi berita" required>{{ old('content') }}</textarea>
          <label for="content">Konten</label>
          @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        {{-- Image --}}
        <div class="mb-3">
          <label class="form-label small text-muted d-block mb-2">Gambar (opsional)</label>

          <div class="file-zone" id="fileZone" onclick="document.getElementById('image').click()">
            <input id="image" name="image" type="file" accept="image/*" class="d-none" onchange="previewImage(event)">
            <p class="mb-0">Klik untuk pilih gambar — atau seret ke sini (opsional)</p>
          </div>

          <div id="previewWrap" class="mt-3 d-none text-center">
            <img id="imagePreview" src="#" alt="preview" />
            <div class="mt-2">
              <button type="button" class="btn btn-secondary-subtle btn-round me-2" onclick="removePreview()">Hapus</button>
            </div>
          </div>

          @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mt-4">
          <button type="button" id="btnSubmit" class="btn btn-primary-subtle btn-round">
            <i class="bi bi-send me-1"></i> Simpan
          </button>
          <a href="{{ route('admin.news.index') }}" class="btn btn-secondary-subtle btn-round">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
/* preview image */
function previewImage(e){
  const file = e.target.files && e.target.files[0];
  if(!file) return removePreview();
  const url = URL.createObjectURL(file);
  document.getElementById('imagePreview').src = url;
  document.getElementById('previewWrap').classList.remove('d-none');
}

/* remove preview */
function removePreview(){
  const input = document.getElementById('image');
  input.value = '';
  document.getElementById('imagePreview').src = '#';
  document.getElementById('previewWrap').classList.add('d-none');
}

/* drop support (simple) */
const zone = document.getElementById('fileZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.opacity=0.85; });
zone.addEventListener('dragleave', e => { zone.style.opacity=1; });
zone.addEventListener('drop', e => {
  e.preventDefault();
  zone.style.opacity=1;
  const f = e.dataTransfer.files && e.dataTransfer.files[0];
  if(f && f.type.startsWith('image/')) {
    document.getElementById('image').files = e.dataTransfer.files;
    previewImage({ target: { files: e.dataTransfer.files } });
  }
});

/* submit with sweetalert confirm */
document.getElementById('btnSubmit').addEventListener('click', () => {
  Swal.fire({
    title: 'Simpan berita?',
    text: 'Data akan disimpan ke sistem.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, simpan',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#2563eb'
  }).then(res => {
    if(res.isConfirmed) document.getElementById('newsForm').submit();
  });
});
</script>
@endpush
