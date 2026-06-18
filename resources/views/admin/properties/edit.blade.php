@extends('layouts.panel')

@section('title', 'Edit Properti')
@section('page-title', 'Edit Properti')
@section('page-subtitle', 'Ubah data properti')

@section('topbar-actions')
    <a href="{{ route('admin.properties.index') }}" class="btn" style="padding: 8px 16px; font-size: 14px; background: white; border: 1px solid var(--border);">
        Kembali
    </a>
@endsection

@section('content')
    <div class="card" style="max-width: 800px;">
        <div class="card-header">
            <h2>Form Edit Properti</h2>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Nama Properti <span style="color: red;">*</span></label>
                        <input type="text" name="nama_properti" value="{{ old('nama_properti', $property->nama_properti) }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('nama_properti') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Alamat Lengkap <span style="color: red;">*</span></label>
                        <textarea name="alamat" rows="3" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-family: inherit;">{{ old('alamat', $property->alamat) }}</textarea>
                        @error('alamat') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Luas (m²) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="luas" value="{{ old('luas', (float)$property->luas) }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('luas') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Harga Sewa per m² (Rp) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="harga_sewa" value="{{ old('harga_sewa', (float)$property->harga_sewa) }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('harga_sewa') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Status <span style="color: red;">*</span></label>
                        <select name="status" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; background: white;">
                            <option value="tersedia" {{ old('status', $property->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="disewa" {{ old('status', $property->status) == 'disewa' ? 'selected' : '' }}>Disewa</option>
                        </select>
                        @error('status') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Ganti Foto Utama (Opsional)</label>
                        <input type="file" name="thumbnail" accept="image/*" style="width: 100%; padding: 7px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-size: 14px;">
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        @error('thumbnail') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Link Google Maps</label>
                        <input type="url" name="link_google_maps" value="{{ old('link_google_maps', $property->link_google_maps) }}" placeholder="https://maps.google.com/..." style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('link_google_maps') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Deskripsi Properti</label>
                        <textarea name="deskripsi" rows="6" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-family: inherit;">{{ old('deskripsi', $property->deskripsi) }}</textarea>
                        @error('deskripsi') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border); padding-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                    @if($property->thumbnail)
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ asset('storage/' . $property->thumbnail) }}" alt="" style="width: 60px; height: 60px; border-radius: 6px; object-fit: cover;">
                            <span style="font-size: 12px; color: var(--text-muted);">Foto saat ini</span>
                        </div>
                    @else
                        <div></div>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
