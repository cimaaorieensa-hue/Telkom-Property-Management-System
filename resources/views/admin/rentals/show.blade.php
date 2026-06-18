@extends('layouts.panel')

@section('title', 'Detail Penyewaan')
@section('page-title', 'Detail Penyewaan')
@section('page-subtitle', 'Informasi penyewaan properti: ' . ($rental->property->nama_properti ?? 'Properti Dihapus'))

@section('topbar-actions')
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.rentals.edit', $rental) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
            Edit
        </a>
        <a href="{{ route('admin.rentals.index') }}" class="btn" style="padding: 8px 16px; font-size: 14px; background: white; border: 1px solid var(--border);">
            Kembali
        </a>
    </div>
@endsection

@section('content')
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        {{-- Kiri: Detail Sewa --}}
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 16px; margin: 0;">Informasi Sewa</h3>
                @php
                    $bg = '#f1f5f9'; $color = '#64748b'; $text = 'Tidak Diketahui';
                    if($rental->status_sewa == 'aktif') { $bg = '#ecfdf5'; $color = '#059669'; $text = 'Aktif'; }
                    if($rental->status_sewa == 'selesai') { $text = 'Selesai'; }
                    if($rental->status_sewa == 'dibatalkan') { $bg = '#fef2f2'; $color = '#e11d48'; $text = 'Dibatalkan'; }
                @endphp
                <span style="background: {{ $bg }}; color: {{ $color }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    Status: {{ $text }}
                </span>
            </div>
            <div class="card-body" style="padding: 24px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Nama Penyewa</div>
                        <div style="font-size: 15px; font-weight: 600;">{{ $rental->nama_penyewa }}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Properti Disewa</div>
                        @if($rental->property)
                            <a href="{{ route('admin.properties.edit', $rental->property) }}" style="font-size: 15px; font-weight: 600; color: var(--primary); text-decoration: underline;">
                                {{ $rental->property->nama_properti }}
                            </a>
                        @else
                            <div style="font-size: 15px; font-weight: 600; color: var(--danger);">Properti Telah Dihapus</div>
                        @endif
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Nomor Telepon</div>
                        <div style="font-size: 15px;">{{ $rental->no_telepon ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Email Penyewa</div>
                        <div style="font-size: 15px;">{{ $rental->email_penyewa ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Tanggal Mulai</div>
                        <div style="font-size: 15px; font-weight: 500;">{{ \Carbon\Carbon::parse($rental->tanggal_mulai)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Tanggal Selesai</div>
                        <div style="font-size: 15px; font-weight: 500;">{{ \Carbon\Carbon::parse($rental->tanggal_selesai)->translatedFormat('d F Y') }}</div>
                    </div>
                </div>

                <div style="border-top: 1px solid var(--border); padding-top: 24px;">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Catatan Penyewaan</div>
                    <div style="font-size: 14px; line-height: 1.6; background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                        {{ $rental->catatan ?? 'Tidak ada catatan.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Kanan: Detail Finansial / Properti Info --}}
        @if($rental->property)
        <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; margin: 0;">Pembayaran</h3>
            </div>
            <div class="card-body" style="padding: 24px;">
                @php
                    $paymentStatus = $rental->payment?->status ?? 'unpaid';
                    $paymentBg = '#f1f5f9';
                    $paymentColor = '#64748b';
                    $paymentText = 'Belum Dibuat';

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
                    <span style="font-size: 13px; color: var(--text-muted);">Status</span>
                    <span style="background: {{ $paymentBg }}; color: {{ $paymentColor }}; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700;">
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
                        <span style="font-size: 22px; font-weight: 800; color: var(--primary);">
                            Rp {{ number_format($rental->payment?->amount ?? $rental->total_tagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if($rental->payment?->status === 'paid')
                    <div style="font-size: 13px; color: var(--text-muted);">
                        Dibayar pada {{ $rental->payment->paid_at?->translatedFormat('d F Y H:i') ?? '-' }}
                    </div>
                @else
                    @if($rental->payment?->payment_url && $rental->payment->status === 'pending')
                        <a href="{{ $rental->payment->payment_url }}" class="btn btn-primary" style="width: 100%; margin-bottom: 12px;" target="_blank" rel="noopener">
                            Lanjutkan Pembayaran
                        </a>
                    @endif

                    <form action="{{ route('admin.rentals.payment.store', $rental) }}" method="POST">
                        @csrf
                        <label for="payment_method" style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 6px;">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; outline: none; background: white; margin-bottom: 12px;">
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method['paymentMethod'] }}">
                                    {{ $method['paymentName'] }}
                                    @if(!empty($method['totalFee']) && (int) $method['totalFee'] > 0)
                                        - Biaya Rp {{ number_format($method['totalFee'], 0, ',', '.') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Bayar via Duitku
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; margin: 0;">Info Properti</h3>
            </div>
            <div class="card-body" style="padding: 24px;">
                @if($rental->property->thumbnail)
                    <div style="width: 100%; height: 160px; border-radius: 8px; overflow: hidden; margin-bottom: 16px; background: #e2e8f0;">
                        <img src="{{ asset('storage/' . $rental->property->thumbnail) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif
                
                <div style="margin-bottom: 12px;">
                    <div style="font-size: 12px; color: var(--text-muted);">Alamat</div>
                    <div style="font-size: 13px;">{{ $rental->property->alamat }}</div>
                </div>
                
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                    <span style="font-size: 13px; color: var(--text-muted);">Luas Area</span>
                    <span style="font-size: 13px; font-weight: 600;">{{ number_format($rental->property->luas, 0, ',', '.') }} m²</span>
                </div>
                
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                    <span style="font-size: 13px; color: var(--text-muted);">Harga per m²</span>
                    <span style="font-size: 13px; font-weight: 600;">Rp {{ number_format($rental->property->harga_sewa, 0, ',', '.') }}/bln</span>
                </div>

                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                    <span style="font-size: 13px; color: var(--text-muted);">Biaya Sewa Pokok</span>
                    <span style="font-size: 13px; font-weight: 600;">Rp {{ number_format($rental->total_pendapatan, 0, ',', '.') }}</span>
                </div>

                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border); padding-bottom: 8px;">
                    <span style="font-size: 13px; color: var(--text-muted);">Pajak PPN (11%)</span>
                    <span style="font-size: 13px; font-weight: 600;">Rp {{ number_format($rental->pajak, 0, ',', '.') }}</span>
                </div>

                <div style="margin-top: 16px; background: var(--primary-light); padding: 16px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Total Harga Sewa</div>
                    <div style="font-size: 20px; font-weight: 800; color: var(--primary);">
                        Rp {{ number_format($rental->property->total_sewa_per_bulan, 0, ',', '.') }}<small style="font-size: 12px; font-weight: 400; color: var(--text-muted);">/bulan</small>
                    </div>
                </div>
            </div>
        </div>
        </div>
        @endif

    </div>
@endsection
