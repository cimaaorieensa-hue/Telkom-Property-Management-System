@extends('layouts.panel')

@section('title', 'Tambah Penyewaan')
@section('page-title', 'Tambah Penyewaan')
@section('page-subtitle', 'Masukkan data penyewa baru')

@section('topbar-actions')
    <a href="{{ route('admin.rentals.index') }}" class="btn" style="padding: 8px 16px; font-size: 14px; background: white; border: 1px solid var(--border);">
        Kembali
    </a>
@endsection

@section('content')
    <div class="card" style="max-width: 800px;">
        <div class="card-header">
            <h2>Form Penyewaan</h2>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.rentals.store') }}" method="POST">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Nama Penyewa <span style="color: red;">*</span></label>
                        <input type="text" name="nama_penyewa" value="{{ old('nama_penyewa') }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('nama_penyewa') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('no_telepon') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Email</label>
                        <input type="email" name="email_penyewa" value="{{ old('email_penyewa') }}" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('email_penyewa') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Properti yang Disewa <span style="color: red;">*</span></label>
                        <select name="property_id" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; background: white;">
                            <option value="">-- Pilih Properti --</option>
                            @foreach($properties as $prop)
                                @if($prop->sisa_luas > 0)
                                    <option value="{{ $prop->id }}" {{ old('property_id') == $prop->id ? 'selected' : '' }}>
                                        {{ $prop->nama_properti }} (Sisa: {{ $prop->sisa_luas }} m²)
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">Pilih properti yang masih memiliki sisa area untuk disewa.</small>
                        @error('property_id') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Luas yang Disewa (m²) <span style="color: red;">*</span></label>
                        <input type="number" step="0.01" name="luas_sewa" value="{{ old('luas_sewa') }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">Masukkan luasan area yang akan disewa oleh penyewa ini. Tidak boleh melebihi sisa luas properti.</small>
                        @error('luas_sewa') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Tanggal Mulai Sewa <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('tanggal_mulai') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Tanggal Selesai Sewa <span style="color: red;">*</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('tanggal_selesai') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Status Penyewaan <span style="color: red;">*</span></label>
                        <select name="status_sewa" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; background: white;">
                            <option value="aktif" {{ old('status_sewa') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status_sewa') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status_sewa') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status_sewa') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="4" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-family: inherit;">{{ old('catatan') }}</textarea>
                        @error('catatan') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border); padding-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary">Simpan Data Penyewaan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
