@extends('layouts.panel')

@section('title', 'Detail Penyewaan')
@section('page-title', 'Detail Penyewaan')
@section('page-subtitle', 'Informasi penyewaan properti: ' . ($rental->property->nama_properti ?? 'Properti Dihapus'))

@section('topbar-actions')
    <div style="display: flex; gap: 8px;" class="no-print">
        <button onclick="window.print()" class="btn" style="padding: 8px 16px; font-size: 14px; background: white; border: 1px solid var(--border); display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.821V21m0 0H18m-11.28 0h-.008v-.008h.008v.008Zm11.28 0h.008v-.008h-.008v.008ZM18 21V13.82m-12.72-5.41H18M6.72 8.41a3 3 0 0 0-3 3v4.24a3 3 0 0 0 3 3h10.56a3 3 0 0 0 3-3V11.41a3 3 0 0 0-3-3M6.72 8.41V3a1 1 0 0 1 1-1h8.56a1 1 0 0 1 1 1v5.41" />
            </svg>
            Cetak Bukti Sewa
        </button>
        <a href="{{ route('user.rentals.index') }}" class="btn" style="padding: 8px 16px; font-size: 14px; background: white; border: 1px solid var(--border); text-decoration: none; color: inherit;">
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;" class="rental-detail-grid">
        
        {{-- Kiri: Detail Sewa --}}
        <div style="display: flex; flex-direction: column; gap: 24px;" id="print-invoice">
            {{-- Print Logo & Title (hidden by default, visible during print) --}}
            <div class="print-only-header" style="display: none; border-bottom: 2px solid #e11d48; padding-bottom: 16px; margin-bottom: 24px; align-items: center; justify-content: space-between;">
                <div>
                    <h1 style="color: #e11d48; font-size: 24px; font-weight: 800; letter-spacing: -0.02em;">{{ $setting->nama_perusahaan }}</h1>
                    <p style="font-size: 12px; color: #64748b; margin-top: 4px;">{{ $setting->alamat_perusahaan ?? 'Pengelolaan Aset Properti Terintegrasi' }}</p>
                </div>
                <div style="text-align: right;">
                    <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0;">BUKTI SEWA PROPERTI</h2>
                    <p style="font-size: 12px; color: #64748b; margin-top: 4px;">ID Sewa: #RENTAL-{{ $rental->id }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px;">
                    <h3 style="font-size: 16px; margin: 0; font-weight: 600;">Informasi Penyewaan</h3>
                    @php
                        $bg = '#f1f5f9'; $color = '#64748b'; $text = 'Tidak Diketahui';
                        if($rental->status_sewa == 'aktif') { $bg = '#ecfdf5'; $color = '#059669'; $text = 'Aktif'; }
                        if($rental->status_sewa == 'selesai') { $text = 'Selesai'; }
                        if($rental->status_sewa == 'dibatalkan') { $bg = '#fef2f2'; $color = '#e11d48'; $text = 'Dibatalkan'; }
                    @endphp
                    <span class="badge" style="background: {{ $bg }}; color: {{ $color }}; font-weight: 600;">
                        Sewa: {{ $text }}
                    </span>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Nama Penyewa</div>
                            <div style="font-size: 15px; font-weight: 600; color: var(--text-primary);">{{ $rental->nama_penyewa }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Properti Disewa</div>
                            @if($rental->property)
                                <div style="font-size: 15px; font-weight: 600; color: var(--text-primary);">
                                    {{ $rental->property->nama_properti }}
                                </div>
                            @else
                                <div style="font-size: 15px; font-weight: 600; color: var(--danger);">Properti Telah Dihapus</div>
                            @endif
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Nomor Telepon</div>
                            <div style="font-size: 15px; color: var(--text-primary);">{{ $rental->no_telepon ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Email Penyewa</div>
                            <div style="font-size: 15px; color: var(--text-primary);">{{ $rental->email_penyewa ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Tanggal Mulai</div>
                            <div style="font-size: 15px; font-weight: 500; color: var(--text-primary);">{{ $rental->tanggal_mulai->translatedFormat('d F Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Tanggal Selesai</div>
                            <div style="font-size: 15px; font-weight: 500; color: var(--text-primary);">{{ $rental->tanggal_selesai->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 20px; margin-bottom: 20px;">
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Rincian Biaya Sewa</div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                            <div>
                                <div style="font-size: 11px; color: var(--text-muted);">Biaya Sewa Pokok</div>
                                <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->total_pendapatan, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: var(--text-muted);">Pajak PPN (11%)</div>
                                <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->pajak, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; color: var(--primary);">Total Pembayaran</div>
                                <div style="font-size: 14px; font-weight: 700; color: var(--primary);">Rp {{ number_format($rental->payment?->amount ?? $rental->total_tagihan, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 24px;">
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Catatan Sewa</div>
                        <div style="font-size: 14px; line-height: 1.6; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border); color: var(--text-secondary); white-space: pre-line;">
                            {{ $rental->catatan ?? 'Tidak ada catatan khusus.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Detail Finansial / Info Pembayaran --}}
        <div style="display: flex; flex-direction: column; gap: 24px;" class="no-print">
            <div class="card">
                <div class="card-header" style="padding: 20px 24px;">
                    <h3 style="font-size: 16px; margin: 0; font-weight: 600;">Status Pembayaran</h3>
                </div>
                <div class="card-body" style="padding: 24px;">
                    @php
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

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <span style="font-size: 13px; color: var(--text-muted);">Status Tagihan</span>
                        <span class="badge" style="background: {{ $paymentBg }}; color: {{ $paymentColor }}; font-weight: 700;">
                            {{ $paymentText }}
                        </span>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px;">
                            <span style="color: var(--text-muted);">Biaya Sewa</span>
                            <span style="font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->total_pendapatan, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px;">
                            <span style="color: var(--text-muted);">Pajak PPN (11%)</span>
                            <span style="font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->pajak, 0, ',', '.') }}</span>
                        </div>
                        <div style="border-top: 1px dashed var(--border); padding-top: 8px; margin-top: 4px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Total Bayar</span>
                            <span style="font-size: 20px; font-weight: 800; color: var(--primary);">
                                Rp {{ number_format($rental->payment?->amount ?? $rental->total_tagihan, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($paymentStatus === 'paid')
                        <div style="background: #ecfdf5; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px; padding: 12px; font-size: 13px; display: flex; align-items: center; gap: 8px; margin-bottom: 12px; font-weight: 500;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <span>Transaksi Lunas. Terima kasih.</span>
                        </div>
                        @if($rental->payment->paid_at)
                            <div style="font-size: 12px; color: var(--text-muted); text-align: center;">
                                Dibayar pada: {{ $rental->payment->paid_at->translatedFormat('d M Y, H:i') }} WIB
                            </div>
                        @endif
                    @elseif($paymentStatus === 'pending' && $rental->payment?->payment_url)
                        <div style="background: #fffbeb; border: 1px solid #fde68a; color: #92400e; border-radius: 8px; padding: 12px; font-size: 13px; display: flex; align-items: flex-start; gap: 8px; margin-bottom: 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="flex-shrink: 0; margin-top: 1px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                            <span>Pembayaran Anda via Midtrans Snap aman sedang menunggu transfer/proses pembayaran.</span>
                        </div>
                        <a href="{{ $rental->payment->payment_url }}" class="btn btn-primary" style="width: 100%; border-radius: 8px; text-decoration: none;" target="_blank" rel="noopener">
                            Lanjutkan Pembayaran Online
                        </a>
                    @else
                        <div style="font-size: 13px; color: var(--text-secondary); text-align: center; line-height: 1.5;">
                            Pembayaran belum dapat diselesaikan secara online. Silakan hubungi admin pengelola di kontak bawah ini.
                        </div>
                    @endif
                </div>
            </div>

            @if($rental->property)
                <div class="card">
                    <div class="card-header" style="padding: 20px 24px;">
                        <h3 style="font-size: 16px; margin: 0; font-weight: 600;">Detail Properti</h3>
                    </div>
                    <div class="card-body" style="padding: 24px;">
                        @if($rental->property->thumbnail)
                            <div style="width: 100%; height: 160px; border-radius: 8px; overflow: hidden; margin-bottom: 16px; background: #e2e8f0;">
                                <img src="{{ asset('storage/' . $rental->property->thumbnail) }}" alt="{{ $rental->property->nama_properti }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif

                        <div style="margin-bottom: 12px;">
                            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 2px;">Alamat</div>
                            <div style="font-size: 13px; color: var(--text-primary); font-weight: 500;">{{ $rental->property->alamat }}</div>
                        </div>

                        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                            <span style="font-size: 13px; color: var(--text-muted);">Luas Disewa</span>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-primary);">{{ number_format($rental->luas_sewa, 0, ',', '.') }} m²</span>
                        </div>

                        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                            <span style="font-size: 13px; color: var(--text-muted);">Harga Dasar</span>
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->property->harga_sewa, 0, ',', '.') }}/m²/bulan</span>
                        </div>

                        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                            <span style="font-size: 13px; color: var(--text-muted);">Biaya Sewa Dasar</span>
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->total_pendapatan, 0, ',', '.') }}</span>
                        </div>

                        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                            <span style="font-size: 13px; color: var(--text-muted);">Pajak PPN (11%)</span>
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);">Rp {{ number_format($rental->pajak, 0, ',', '.') }}</span>
                        </div>

                        @if($rental->property->link_google_maps)
                            <a href="{{ $rental->property->link_google_maps }}" target="_blank" rel="noopener" class="btn" style="width: 100%; background: #f1f5f9; color: var(--text-secondary); border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 16px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                Buka di Google Maps
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Kontak Pengelola --}}
            <div class="card">
                <div class="card-header" style="padding: 20px 24px;">
                    <h3 style="font-size: 16px; margin: 0; font-weight: 600;">Kontak Pengelola</h3>
                </div>
                <div class="card-body" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 10px;">
                    @if($setting->whatsapp_link)
                        <a href="{{ $setting->whatsapp_link }}?text={{ urlencode('Halo, saya penyewa dengan ID sewa #RENTAL-' . $rental->id . ' ingin menanyakan tentang properti: ' . ($rental->property->nama_properti ?? '')) }}" target="_blank" rel="noopener" class="btn" style="background: #25d366; color: white; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px; transition: opacity .15s;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.446L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.97C16.379 1.966 13.903.94 11.285.94c-5.44 0-9.865 4.37-9.87 9.802 0 1.693.457 3.345 1.323 4.793L1.753 22.24l6.894-1.802-.008-.284z"/>
                            </svg>
                            Hubungi via WhatsApp
                        </a>
                    @endif

                    @if($setting->no_telepon)
                        <a href="tel:{{ $setting->no_telepon }}" class="btn" style="background: #f1f5f9; color: var(--text-primary); border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            Hubungi via Telepon
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .sidebar, .topbar, .no-print, .sidebar-overlay {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
            .content-area {
                padding: 0 !important;
            }
            .rental-detail-grid {
                grid-template-columns: 1fr !important;
                gap: 0 !important;
            }
            #print-invoice {
                width: 100% !important;
                box-shadow: none !important;
                border: none !important;
            }
            .print-only-header {
                display: flex !important;
            }
            .card {
                box-shadow: none !important;
                border: none !important;
            }
            .card-header {
                border-bottom: 1px solid #e2e8f0 !important;
                padding: 10px 0 !important;
            }
            .card-body {
                padding: 15px 0 !important;
            }
        }
        @media (max-width: 992px) {
            .rental-detail-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
