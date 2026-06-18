@extends('layouts.panel')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('page-subtitle', 'Kelola informasi publik dan kontak perusahaan')

@section('content')
    <div class="card" style="max-width: 800px;">
        <div class="card-header">
            <h2>Informasi Perusahaan</h2>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    
                    {{-- Logo Upload --}}
                    <div style="grid-column: 1 / -1; margin-bottom: 12px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Logo Perusahaan</label>
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 100px; height: 100px; border-radius: 8px; border: 1px solid var(--border); background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 8px;">
                                @if($setting->logo)
                                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <span style="font-size: 12px; color: var(--text-muted); text-align: center;">Belum ada logo</span>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <input type="file" name="logo" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; outline: none; margin-bottom: 8px;">
                                <div style="font-size: 12px; color: var(--text-muted);">Format: JPG, PNG, WEBP. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah logo.</div>
                                @error('logo') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Nama Perusahaan <span style="color: red;">*</span></label>
                        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $setting->nama_perusahaan) }}" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('nama_perusahaan') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Nomor WhatsApp</label>
                        <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $setting->no_whatsapp) }}" placeholder="Contoh: 628123456789" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        <small style="color: var(--text-muted); display: block; margin-top: 4px;">Gunakan kode negara (62).</small>
                        @error('no_whatsapp') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Nomor Telepon Kantor</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $setting->no_telepon) }}" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('no_telepon') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Email Perusahaan</label>
                        <input type="email" name="email_perusahaan" value="{{ old('email_perusahaan', $setting->email_perusahaan) }}" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                        @error('email_perusahaan') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Alamat Lengkap</label>
                        <textarea name="alamat_perusahaan" rows="3" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-family: inherit;">{{ old('alamat_perusahaan', $setting->alamat_perusahaan) }}</textarea>
                        @error('alamat_perusahaan') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Tentang / Deskripsi Singkat</label>
                        <textarea name="tentang" rows="4" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; font-family: inherit;" placeholder="Tuliskan deskripsi singkat mengenai perusahaan yang akan tampil di halaman publik...">{{ old('tentang', $setting->tentang) }}</textarea>
                        @error('tentang') <span style="color: red; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div style="border-top: 1px solid var(--border); padding-top: 20px; text-align: right;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
