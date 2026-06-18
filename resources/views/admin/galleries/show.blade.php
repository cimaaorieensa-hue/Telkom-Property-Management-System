@extends('layouts.panel')

@section('title', 'Kelola Galeri: ' . $property->nama_properti)
@section('page-title', 'Kelola Galeri Properti')
@section('page-subtitle', $property->nama_properti)

@section('topbar-actions')
    <a href="{{ route('admin.galleries.index') }}" class="btn" style="padding: 8px 16px; font-size: 14px; background: white; border: 1px solid var(--border);">
        Kembali ke Daftar
    </a>
@endsection

@section('content')
    @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div style="background: #dcfce3; border: 1px solid #86efac; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Upload Section --}}
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h3>Upload Foto Baru</h3>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.galleries.store', $property) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div style="border: 2px dashed var(--border); border-radius: 8px; padding: 40px 24px; text-align: center; background: #f8fafc; margin-bottom: 16px; position: relative;" id="dropzone">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="var(--text-muted)" style="margin-bottom: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                    </svg>
                    <h4 style="font-size: 16px; margin-bottom: 8px;">Pilih atau Tarik Foto Kesini</h4>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Mendukung format JPG, PNG, WEBP (Max 10MB/foto). Anda dapat memilih lebih dari 1 foto sekaligus.</p>
                    
                    <input type="file" name="images[]" id="fileInput" multiple accept="image/*" required style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    
                    <div id="filePreview" style="font-size: 14px; font-weight: 600; color: var(--primary);"></div>
                </div>
                
                @error('images') <div style="color: red; font-size: 13px; margin-bottom: 16px;">{{ $message }}</div> @enderror
                @error('images.*') <div style="color: red; font-size: 13px; margin-bottom: 16px;">{{ $message }}</div> @enderror

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Upload Foto</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Gallery Grid --}}
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3>Foto Galeri ({{ $property->galleries->count() }})</h3>
        </div>
        <div class="card-body" style="padding: 24px;">
            @if($property->galleries->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                    @foreach($property->galleries as $gallery)
                        <div style="border-radius: 8px; overflow: hidden; border: 1px solid var(--border); position: relative; group;">
                            <div style="height: 150px; background: #e2e8f0;">
                                <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            
                            {{-- Delete Button Overlay --}}
                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');" style="position: absolute; top: 8px; right: 8px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(225, 29, 72, 0.9); color: white; border: none; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; backdrop-filter: blur(4px); transition: 0.2s;" title="Hapus Foto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: var(--text-muted); background: #f8fafc; border-radius: 8px; border: 1px dashed var(--border);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" style="margin-bottom: 16px; opacity: 0.5;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <h4>Belum ada foto galeri</h4>
                    <p style="font-size: 14px; margin-top: 4px;">Silakan upload foto baru di form atas.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const files = e.target.files;
            const preview = document.getElementById('filePreview');
            const btn = document.getElementById('submitBtn');
            const dropzone = document.getElementById('dropzone');
            
            if (files.length > 0) {
                preview.innerHTML = files.length + " file terpilih. Siap diupload!";
                btn.disabled = false;
                dropzone.style.borderColor = 'var(--primary)';
                dropzone.style.background = 'var(--primary-light)';
            } else {
                preview.innerHTML = "";
                btn.disabled = true;
                dropzone.style.borderColor = 'var(--border)';
                dropzone.style.background = '#f8fafc';
            }
        });
    </script>
@endsection
