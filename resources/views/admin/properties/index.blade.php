@extends('layouts.panel')

@section('title', 'Data Properti')
@section('page-title', 'Data Properti')
@section('page-subtitle', 'Kelola semua aset properti')

@section('topbar-actions')
    @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.properties.create') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Properti
        </a>
    @endif
@endsection

@section('content')
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header" style="background: #f8fafc; display: flex; gap: 16px; flex-wrap: wrap;">
            <form action="{{ Auth::user()->isAdmin() ? route('admin.properties.index') : route('manager.properties.index') }}" method="GET" style="display: flex; gap: 12px; flex: 1; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari properti..." style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; flex: 1; min-width: 200px; outline: none;">
                <select name="status" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="disewa" {{ request('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
                </select>
                <button type="submit" class="btn" style="background: white; border: 1px solid var(--border); padding: 8px 16px;">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.properties.index') : route('manager.properties.index') }}" class="btn" style="background: transparent; color: var(--danger); padding: 8px 16px;">Reset</a>
                @endif
            </form>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Foto</th>
                            <th>Nama Properti</th>
                            <th>Detail</th>
                            <th>Status</th>
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
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">{{ Str::limit($property->alamat, 40) }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 13px;">Total: {{ number_format($property->luas, 0, ',', '.') }} m²</div>
                                    <div style="font-size: 13px; font-weight: 600; color: {{ $property->sisa_luas > 0 ? 'var(--success)' : 'var(--danger)' }};">Sisa: {{ number_format($property->sisa_luas, 0, ',', '.') }} m²</div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Rp {{ number_format($property->harga_sewa, 0, ',', '.') }}/m²</div>
                                </td>
                                <td>
                                    @if(Auth::user()->isAdmin())
                                        <form action="{{ route('admin.properties.status', $property) }}" method="POST" id="status-form-{{ $property->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="document.getElementById('status-form-{{ $property->id }}').submit()" style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; {{ $property->status === 'tersedia' ? 'background: #ecfdf5; color: #059669;' : 'background: #fffbeb; color: #d97706;' }}">
                                                <option value="tersedia" {{ $property->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                <option value="disewa" {{ $property->status === 'disewa' ? 'selected' : '' }}>Disewa</option>
                                            </select>
                                        </form>
                                    @else
                                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; display: inline-block; {{ $property->status === 'tersedia' ? 'background: #ecfdf5; color: #059669;' : 'background: #fffbeb; color: #d97706;' }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="{{ route('properties.show', $property) }}" target="_blank" class="btn" style="padding: 6px; background: #f1f5f9; color: var(--text-secondary);" title="Lihat Publik">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('admin.properties.edit', $property) }}" class="btn" style="padding: 6px; background: #eff6ff; color: var(--info);" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                            </a>
                                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?');" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn" style="padding: 6px; background: #fef2f2; color: var(--danger); border: none;" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    Belum ada data properti yang ditambahkan.
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
