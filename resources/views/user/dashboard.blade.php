@extends('layouts.panel')

@section('title', 'Area Pelanggan')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . $user->name)

@section('content')
    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                </svg>
            </div>
            <div class="stat-info" style="min-width: 0;">
                <h3 style="font-size: 15px; font-weight: 600; word-break: break-all; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 170px;" title="{{ $user->email }}">{{ $user->email }}</h3>
                <p>Email Akun</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $rentals->count() }}</h3>
                <p>Total Order</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $availableProperties->count() }}</h3>
                <p>Properti Tersedia</p>
            </div>
        </div>
    </div>

    {{-- Main Sections --}}
    <div style="display: grid; grid-template-columns: 1.25fr 0.75fr; gap: 24px; margin-top: 24px;">
        {{-- Recent Order History --}}
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px;">
                <h2 style="font-size: 16px; font-weight: 600; color: var(--text-primary);">Riwayat Order Terbaru</h2>
                @if($rentals->count() > 0)
                    <a href="{{ route('user.rentals.index') }}" style="font-size: 13px; color: var(--primary); font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                        Lihat Semua
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @endif
            </div>
            <div class="card-body">
                @forelse($rentals as $rental)
                    @php
                        $paymentStatus = $rental->payment?->status ?? 'unpaid';
                        $badgeClass = match ($paymentStatus) {
                            'paid' => 'badge-success',
                            'pending' => 'badge-warning',
                            'cancelled' => 'badge-danger',
                            'failed' => 'badge-danger',
                            default => 'badge-danger',
                        };
                        $paymentText = match ($paymentStatus) {
                            'paid' => 'Lunas',
                            'pending' => 'Menunggu Pembayaran',
                            'cancelled' => 'Dibatalkan',
                            'failed' => 'Gagal',
                            default => 'Belum Dibayar',
                        };
                    @endphp
                    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; gap: 16px; transition: background .15s;" class="rental-list-item">
                        <div>
                            <div style="font-weight: 700; color: var(--text-primary); font-size: 15px;">
                                <a href="{{ route('user.rentals.show', $rental) }}" style="color: inherit; text-decoration: none;" class="rental-title-link">
                                    {{ $rental->property->nama_properti ?? 'Properti Dihapus' }}
                                </a>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted); margin-top: 6px; display: flex; align-items: center; gap: 6px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>
                                {{ $rental->tanggal_mulai->format('d M Y') }} - {{ $rental->tanggal_selesai->format('d M Y') }}
                            </div>
                            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">
                                Luas: <strong>{{ number_format($rental->luas_sewa, 0, ',', '.') }} m²</strong>
                                @if($rental->payment?->amount)
                                    · Tagihan: <strong style="color: var(--primary);">Rp {{ number_format($rental->payment->amount, 0, ',', '.') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
                            <span class="badge {{ $badgeClass }}">
                                {{ $paymentText }}
                            </span>
                            @if($rental->payment?->payment_url && $rental->payment->status === 'pending')
                                <a href="{{ $rental->payment->payment_url }}" target="_blank" rel="noopener" class="btn" style="background: var(--primary); color: white; border-radius: 8px; font-size: 12px; padding: 6px 12px; font-weight: 600; text-decoration: none; transition: .2s; box-shadow: 0 2px 6px rgba(225, 29, 72, 0.2)">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 48px 24px; color: var(--text-muted);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: var(--text-muted); opacity: .4; margin-bottom: 12px; display: inline-block;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        <p>Belum ada order yang terhubung dengan akun email Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Available Properties --}}
        <div class="card">
            <div class="card-header" style="padding: 20px 24px;">
                <h2 style="font-size: 16px; font-weight: 600; color: var(--text-primary);">Rekomendasi Properti</h2>
            </div>
            <div class="card-body" style="padding: 8px 24px 20px;">
                @forelse($availableProperties as $property)
                    <a href="{{ route('properties.show', $property) }}" style="display: block; padding: 14px 0; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit; transition: .15s;" class="property-item">
                        <div style="font-weight: 700; color: var(--text-primary); font-size: 14px; transition: color .15s;" class="prop-title">{{ $property->nama_properti }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $property->alamat }}
                        </div>
                        <div style="font-size: 13px; color: var(--primary); font-weight: 700; margin-top: 8px; display: flex; align-items: center; justify-content: space-between;">
                            <span>Rp {{ number_format($property->harga_sewa, 0, ',', '.') }}/m²/bulan</span>
                            <span style="font-size: 11px; background: #ecfdf5; color: #059669; padding: 2px 6px; border-radius: 4px; font-weight: 600;">Tersedia</span>
                        </div>
                    </a>
                @empty
                    <div style="text-align: center; padding: 32px 12px; color: var(--text-muted);">
                        Belum ada properti yang tersedia saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .rental-list-item:last-child {
            border-bottom: none !important;
        }
        .rental-list-item:hover {
            background: #fafafa;
        }
        .rental-title-link:hover {
            color: var(--primary) !important;
            text-decoration: underline !important;
        }
        .property-item:last-child {
            border-bottom: none !important;
        }
        .property-item:hover .prop-title {
            color: var(--primary);
        }
        .property-item:hover {
            transform: translateX(4px);
        }
        @media (max-width: 992px) {
            div[style*="grid-template-columns: 1.25fr 0.75fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
