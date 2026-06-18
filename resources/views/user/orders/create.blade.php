@extends('layouts.app')

@section('title', 'Order Sewa')

@section('body')
<div style="min-height: 100vh; background: #f8fafc;">
    <header style="background: white; border-bottom: 1px solid var(--border);">
        <div style="max-width: 980px; margin: 0 auto; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
            <div>
                <div style="font-size: 13px; color: var(--text-muted); font-weight: 700;">Form Order Sewa</div>
                <h1 style="font-size: 22px; font-weight: 800; margin: 2px 0 0;">{{ $property->nama_properti }}</h1>
            </div>
            <a href="{{ route('properties.show', $property) }}" class="btn" style="background: #f1f5f9; color: var(--text-secondary); padding: 10px 14px;">Kembali</a>
        </div>
    </header>

    <main style="max-width: 980px; margin: 0 auto; padding: 28px 24px 48px;">
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('user.orders.store', $property) }}" method="POST" style="display: grid; grid-template-columns: 1.1fr .9fr; gap: 24px;">
            @csrf

            <section class="card">
                <div class="card-header">
                    <h3 style="font-size: 16px; margin: 0;">Data Sewa</h3>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div class="form-group">
                        <label for="luas_sewa" class="form-label" style="color: var(--text-secondary);">Luas Sewa (m2)</label>
                        <input id="luas_sewa" type="number" name="luas_sewa" min="1" max="{{ $property->sisa_luas }}" step="0.01" value="{{ old('luas_sewa', $luas) }}" class="form-input" style="background: white; color: var(--text-primary); border-color: var(--border);" required>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px;">Sisa luas tersedia: {{ number_format($property->sisa_luas, 0, ',', '.') }} m2</div>
                    </div>

                    <div class="form-group">
                        <label for="period" class="form-label" style="color: var(--text-secondary);">Periode Sewa</label>
                        <select id="period" name="period" class="form-input" style="background: white; color: var(--text-primary); border-color: var(--border);" required>
                            <option value="harian" {{ old('period', $period) === 'harian' ? 'selected' : '' }}>Harian</option>
                            <option value="bulanan" {{ old('period', $period) === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ old('period', $period) === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_mulai" class="form-label" style="color: var(--text-secondary);">Tanggal Mulai</label>
                        <input id="tanggal_mulai" type="date" name="tanggal_mulai" min="{{ now()->toDateString() }}" value="{{ old('tanggal_mulai', now()->toDateString()) }}" class="form-input" style="background: white; color: var(--text-primary); border-color: var(--border);" required>
                    </div>

                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; border-radius: 8px; padding: 14px; font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
                        Metode pembayaran akan dipilih di halaman Midtrans Snap setelah Anda menekan tombol pembayaran.
                    </div>

                    <div class="form-group">
                        <label for="catatan" class="form-label" style="color: var(--text-secondary);">Catatan Tambahan</label>
                        <textarea id="catatan" name="catatan" rows="4" class="form-input" style="background: white; color: var(--text-primary); border-color: var(--border); resize: vertical;" placeholder="Opsional">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </section>

            <aside class="card">
                <div class="card-header">
                    <h3 style="font-size: 16px; margin: 0;">Persetujuan</h3>
                </div>
                <div class="card-body" style="padding: 24px;">
                    <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; padding: 16px; margin-bottom: 18px; display: flex; flex-direction: column; gap: 8px;">
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Harga Sewa</div>
                            <div id="baseAmountPreview" style="font-size: 16px; font-weight: 700; color: var(--text-primary);">
                                Rp {{ number_format($baseAmount, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Pajak PPN (11%)</div>
                            <div id="taxPreview" style="font-size: 16px; font-weight: 700; color: var(--text-primary);">
                                Rp {{ number_format($tax, 0, ',', '.') }}
                            </div>
                        </div>
                        <div style="border-top: 1px dashed #fecdd3; padding-top: 8px; margin-top: 4px;">
                            <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Tagihan</div>
                            <div id="amountPreview" style="font-size: 26px; font-weight: 900; color: var(--primary); margin-top: 2px;">
                                Rp {{ number_format($amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; gap: 10px; font-size: 13px; color: var(--text-secondary); margin-bottom: 18px;">
                        <div><strong>Properti:</strong> {{ $property->nama_properti }}</div>
                        <div><strong>Alamat:</strong> {{ $property->alamat }}</div>
                        <div><strong>Harga dasar:</strong> Rp {{ number_format($property->harga_sewa, 0, ',', '.') }}/m2/bulan</div>
                    </div>

                    <div style="border-top: 1px solid var(--border); padding-top: 16px; font-size: 13px; color: var(--text-secondary); line-height: 1.7;">
                        Dengan mencentang persetujuan, saya menyatakan data order sudah benar, bersedia mengikuti ketentuan sewa, dan memahami pembayaran akan diproses melalui Midtrans Sandbox.
                    </div>

                    <label style="display: flex; gap: 10px; align-items: flex-start; margin: 18px 0; font-size: 13px; color: var(--text-primary); cursor: pointer;">
                        <input type="checkbox" name="agreement" value="1" required style="margin-top: 3px; accent-color: var(--primary);">
                        <span>Saya menyetujui ketentuan sewa dan ingin melanjutkan ke pembayaran.</span>
                    </label>

                    <button type="submit" class="btn btn-primary">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </aside>
        </form>
    </main>
</div>

<script>
    const pricePerMonth = {{ (float) $property->harga_sewa }};
    const luasInput = document.getElementById('luas_sewa');
    const periodSelect = document.getElementById('period');
    const amountPreview = document.getElementById('amountPreview');
    const baseAmountPreview = document.getElementById('baseAmountPreview');
    const taxPreview = document.getElementById('taxPreview');

    function rupiah(value) {
        return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(Math.ceil(value));
    }

    function updateAmount() {
        const luas = Number(luasInput.value || 0);
        const period = periodSelect.value;
        let multiplier = 1;

        if (period === 'harian') multiplier = 1 / 30;
        if (period === 'tahunan') multiplier = 12;

        const base = pricePerMonth * multiplier * luas;
        const tax = base * 0.11;
        const total = base + tax;

        baseAmountPreview.textContent = `Rp ${rupiah(base)}`;
        taxPreview.textContent = `Rp ${rupiah(tax)}`;
        amountPreview.textContent = `Rp ${rupiah(total)}`;
    }

    luasInput.addEventListener('input', updateAmount);
    periodSelect.addEventListener('change', updateAmount);
</script>
@endsection
