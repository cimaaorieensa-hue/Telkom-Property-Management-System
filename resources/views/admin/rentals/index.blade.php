@extends('layouts.panel')

@section('title', 'Data Penyewaan')
@section('page-title', 'Data Penyewaan')
@section('page-subtitle', 'Kelola data penyewa dan masa sewa')

@section('topbar-actions')
    <a href="{{ route('admin.rentals.create') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Penyewaan
    </a>
@endsection

@section('content')
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header" style="background: #f8fafc; display: flex; gap: 16px;">
            <form action="{{ route('admin.rentals.index') }}" method="GET" style="display: flex; gap: 12px; flex: 1;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penyewa, no telp, properti..." style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; flex: 1; outline: none;">
                <select name="status_sewa" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; outline: none;">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status_sewa') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status_sewa') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status_sewa') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="btn" style="background: white; border: 1px solid var(--border); padding: 8px 16px;">Filter</button>
                @if(request()->anyFilled(['search', 'status_sewa']))
                    <a href="{{ route('admin.rentals.index') }}" class="btn" style="background: transparent; color: var(--danger); padding: 8px 16px;">Reset</a>
                @endif
            </form>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" style="min-width: 900px;">
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th>Properti</th>
                            <th>Masa Sewa</th>
                            <th>Status Sewa</th>
                            <th>Status Payment</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $rental)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $rental->nama_penyewa }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                        @if($rental->no_telepon) {{ $rental->no_telepon }} @endif
                                        @if($rental->email_penyewa) • {{ $rental->email_penyewa }} @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500; font-size: 14px;">{{ $rental->property->nama_properti ?? 'Properti Dihapus' }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Disewa: {{ $rental->luas_sewa }} m²</div>
                                </td>
                                <td>
                                    <div style="font-size: 13px;">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->format('d M Y') }} -</div>
                                    <div style="font-size: 13px; font-weight: 600; color: var(--primary);">{{ \Carbon\Carbon::parse($rental->tanggal_selesai)->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <form action="{{ route('admin.rentals.status', $rental) }}" method="POST" id="status-form-{{ $rental->id }}">
                                        @csrf
                                        @method('PATCH')
                                        @php
                                            $bg = '#f1f5f9'; $color = '#64748b';
                                            if($rental->status_sewa == 'aktif') { $bg = '#ecfdf5'; $color = '#059669'; }
                                            if($rental->status_sewa == 'dibatalkan') { $bg = '#fef2f2'; $color = '#e11d48'; }
                                        @endphp
                                        <select name="status_sewa" onchange="document.getElementById('status-form-{{ $rental->id }}').submit()" style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; background: {{ $bg }}; color: {{ $color }};">
                                            <option value="aktif" {{ $rental->status_sewa === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="selesai" {{ $rental->status_sewa === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="dibatalkan" {{ $rental->status_sewa === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    @php
                                        $paymentStatus = $rental->payment?->status ?? 'unpaid';
                                        $paymentBg = '#f1f5f9';
                                        $paymentColor = '#64748b';
                                        $paymentText = 'Belum Dibuat';

                                        if ($paymentStatus === 'pending') {
                                            $paymentBg = '#fffbeb';
                                            $paymentColor = '#d97706';
                                            $paymentText = 'Pending';
                                        }

                                        if ($paymentStatus === 'paid') {
                                            $paymentBg = '#ecfdf5';
                                            $paymentColor = '#059669';
                                            $paymentText = 'Lunas';
                                        }

                                        if (in_array($paymentStatus, ['cancelled', 'failed'])) {
                                            $paymentBg = '#fef2f2';
                                            $paymentColor = '#e11d48';
                                            $paymentText = $paymentStatus === 'cancelled' ? 'Dibatalkan' : 'Gagal';
                                        }
                                    @endphp
                                    <span style="display: inline-flex; align-items: center; padding: 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 700; background: {{ $paymentBg }}; color: {{ $paymentColor }};">
                                        {{ $paymentText }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <a href="{{ route('admin.rentals.show', $rental) }}" class="btn" style="padding: 6px; background: #f1f5f9; color: var(--text-secondary);" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.rentals.edit', $rental) }}" class="btn" style="padding: 6px; background: #eff6ff; color: var(--info);" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </a>
                                        <form action="{{ route('admin.rentals.destroy', $rental) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sewa ini?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" style="padding: 6px; background: #fef2f2; color: var(--danger); border: none;" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                    Belum ada data penyewaan yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rentals->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--border);">
                    {{ $rentals->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
