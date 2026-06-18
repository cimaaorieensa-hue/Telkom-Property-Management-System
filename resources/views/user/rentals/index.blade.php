@extends('layouts.panel')

@section('title', 'Penyewaan Saya')
@section('page-title', 'Penyewaan Saya')
@section('page-subtitle', 'Riwayat seluruh sewa properti dan status pembayaran Anda')

@section('topbar-actions')
    <a href="{{ route('properties.index') }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="margin-right: 4px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
        </svg>
        Cari Properti Lain
    </a>
@endsection

@section('content')
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header" style="background: #f8fafc; display: flex; gap: 16px; padding: 16px 24px;">
            <form action="{{ route('user.rentals.index') }}" method="GET" style="display: flex; gap: 12px; flex: 1; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama properti..." style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 8px; flex: 1; min-width: 200px; outline: none; font-size: 14px; font-family: inherit;">
                <select name="status_sewa" style="padding: 8px 14px; border: 1px solid var(--border); border-radius: 8px; outline: none; font-size: 14px; font-family: inherit; background: white;">
                    <option value="">Semua Status Sewa</option>
                    <option value="aktif" {{ request('status_sewa') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status_sewa') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status_sewa') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="btn" style="background: white; border: 1px solid var(--border); padding: 8px 18px; font-size: 14px; color: var(--text-primary); border-radius: 8px;">Filter</button>
                @if(request()->anyFilled(['search', 'status_sewa']))
                    <a href="{{ route('user.rentals.index') }}" class="btn" style="background: transparent; color: var(--danger); padding: 8px 14px; font-size: 14px; text-decoration: none;">Reset</a>
                @endif
            </form>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table" style="min-width: 900px; width: 100%;">
                    <thead>
                        <tr>
                            <th>Properti</th>
                            <th>Masa Sewa</th>
                            <th>Luas Sewa</th>
                            <th>Status Sewa</th>
                            <th>Status Pembayaran</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $rental)
                            @php
                                $statusSewaBg = '#f1f5f9'; $statusSewaColor = '#64748b';
                                if($rental->status_sewa == 'aktif') { $statusSewaBg = '#ecfdf5'; $statusSewaColor = '#059669'; }
                                if($rental->status_sewa == 'dibatalkan') { $statusSewaBg = '#fef2f2'; $statusSewaColor = '#e11d48'; }

                                $paymentStatus = $rental->payment?->status ?? 'unpaid';
                                $paymentBg = '#f1f5f9';
                                $paymentColor = '#64748b';
                                $paymentText = 'Belum Dibayar';

                                if ($paymentStatus === 'pending') {
                                    $paymentBg = '#fffbeb';
                                    $paymentColor = '#d97706';
                                    $paymentText = 'Menunggu Pembayaran';
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
                            <tr class="rental-row">
                                <td>
                                    <div style="font-weight: 700; color: var(--text-primary); font-size: 14px;">
                                        <a href="{{ route('user.rentals.show', $rental) }}" style="color: inherit; text-decoration: none;" class="rental-link">
                                            {{ $rental->property->nama_properti ?? 'Properti Dihapus' }}
                                        </a>
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $rental->property->alamat ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 13px; color: var(--text-primary);">
                                        {{ $rental->tanggal_mulai->format('d M Y') }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                        s/d {{ $rental->tanggal_selesai->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; font-size: 14px;">{{ number_format($rental->luas_sewa, 0, ',', '.') }} m²</div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">Estimasi {{ $rental->durasi_sewa }} Bulan</div>
                                </td>
                                <td>
                                    <span class="badge" style="background: {{ $statusSewaBg }}; color: {{ $statusSewaColor }};">
                                        {{ ucfirst($rental->status_sewa) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
                                        <span class="badge" style="background: {{ $paymentBg }}; color: {{ $paymentColor }};">
                                            {{ $paymentText }}
                                        </span>
                                        @if($rental->payment?->amount)
                                            <div style="font-size: 12px; font-weight: 700; color: var(--text-primary); margin-left: 2px;">
                                                Rp {{ number_format($rental->payment->amount, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 8px; align-items: center; justify-content: flex-end;">
                                        <a href="{{ route('user.rentals.show', $rental) }}" class="btn" style="padding: 8px 12px; background: #f1f5f9; color: var(--text-secondary); border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none;" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: inline-block; vertical-align: middle; margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                            Detail
                                        </a>
                                        @if($paymentStatus === 'pending' && $rental->payment?->payment_url)
                                            <a href="{{ $rental->payment->payment_url }}" target="_blank" rel="noopener" class="btn" style="padding: 8px 14px; background: var(--primary); color: white; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; box-shadow: 0 2px 4px rgba(225, 29, 72, 0.2);" title="Bayar Sekarang">
                                                Bayar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 48px; color: var(--text-muted);">
                                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: var(--text-muted); opacity: .4; margin-bottom: 12px; display: inline-block;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                    </svg>
                                    <p style="margin: 0;">Tidak menemukan riwayat sewa properti yang sesuai.</p>
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

    <style>
        .rental-row:hover {
            background: #fafafa;
        }
        .rental-link:hover {
            color: var(--primary) !important;
            text-decoration: underline !important;
        }
    </style>
@endsection
