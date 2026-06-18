@extends('layouts.panel')

@section('title', 'Galeri Properti')
@section('page-title', 'Galeri Properti')
@section('page-subtitle', 'Pilih properti untuk mengelola foto galeri')

@section('content')
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header" style="background: #f8fafc;">
            <form action="{{ route('admin.galleries.index') }}" method="GET" style="display: flex; gap: 12px; max-width: 500px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama properti..." style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; flex: 1; outline: none;">
                <button type="submit" class="btn btn-primary" style="padding: 8px 16px;">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.galleries.index') }}" class="btn" style="background: transparent; color: var(--danger); padding: 8px 16px;">Reset</a>
                @endif
            </form>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" style="min-width: 600px;">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Foto</th>
                            <th>Nama Properti</th>
                            <th style="text-align: center;">Jumlah Foto Galeri</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties as $property)
                            <tr>
                                <td>
                                    <div style="width: 48px; height: 48px; border-radius: 8px; background: #e2e8f0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                        @if($property->thumbnail)
                                            <img src="{{ asset('storage/' . $property->thumbnail) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600;">{{ $property->nama_properti }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">{{ Str::limit($property->alamat, 50) }}</div>
                                </td>
                                <td style="text-align: center;">
                                    <span style="display: inline-flex; align-items: center; justify-content: center; background: {{ $property->galleries_count > 0 ? 'var(--primary-light)' : '#f1f5f9' }}; color: {{ $property->galleries_count > 0 ? 'var(--primary)' : 'var(--text-muted)' }}; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                        {{ $property->galleries_count }} Foto
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('admin.galleries.show', $property) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;">
                                        Kelola Galeri
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    Belum ada data properti yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($properties->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--border);">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
