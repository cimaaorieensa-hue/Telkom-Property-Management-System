@extends('layouts.public')
@section('title', $property->nama_properti)
@section('meta-description', Str::limit($property->deskripsi, 160))

@section('content')
    <div class="detail-wrapper">
        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <a href="{{ route('properties.index') }}">Daftar Properti</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            <span>{{ Str::limit($property->nama_properti, 30) }}</span>
        </div>

        <div class="detail-grid">
            {{-- Left: Gallery --}}
            <div>
                <div class="detail-gallery" id="mainGallery">
                    @if($property->thumbnail)
                        <img src="{{ asset('storage/' . $property->thumbnail) }}" alt="{{ $property->nama_properti }}" id="mainImage">
                    @elseif($property->galleries->count() > 0)
                        <img src="{{ asset('storage/' . $property->galleries->first()->image_path) }}" alt="{{ $property->nama_properti }}" id="mainImage">
                    @else
                        <div class="placeholder-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                        </div>
                    @endif
                </div>

                @if($property->galleries->count() > 0)
                    <div class="gallery-thumbs">
                        @foreach($property->galleries as $i => $gallery)
                            <div class="gallery-thumb {{ $i === 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ asset('storage/' . $gallery->image_path) }}')">
                                <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->caption ?? 'Foto ' . ($i+1) }}">
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Description --}}
                <div class="detail-desc" style="margin-top:24px">
                    <h3>Deskripsi Properti</h3>
                    <p style="text-align: justify; white-space: pre-wrap; line-height: 1.8; color: var(--text-secondary);">{{ $property->deskripsi ?? 'Deskripsi belum tersedia.' }}</p>
                </div>

                {{-- Google Maps --}}
                @if($property->link_google_maps)
                    <div class="detail-desc">
                        <h3>Lokasi</h3>
                    </div>
                    @php
                        preg_match('/[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)/', $property->link_google_maps, $coords);
                        $lat = $coords[0] ?? null;
                    @endphp
                    @if($lat)
                        <div class="detail-map">
                            <iframe src="https://maps.google.com/maps?q={{ $lat }}&output=embed&z=15" allowfullscreen loading="lazy"></iframe>
                        </div>
                    @endif
                    <a href="{{ $property->link_google_maps }}" target="_blank" class="btn btn-outline" style="color:var(--text);border-color:var(--border);width:100%;margin-bottom:24px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        Buka di Google Maps
                    </a>
                @endif
            </div>

            {{-- Right: Info --}}
            <div>
                <span class="property-badge {{ $property->status }}" style="margin-bottom:12px;display:inline-block">
                    {{ $property->status === 'tersedia' ? '✓ Tersedia' : '⏳ Sedang Disewa' }}
                </span>
                <h1 class="detail-info" style="font-size:26px;font-weight:700;margin-bottom:8px">{{ $property->nama_properti }}</h1>
                <div class="detail-address">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    {{ $property->alamat }}
                </div>

                {{-- Interactive Calculator Box --}}
                <div class="detail-price-box" style="background: var(--surface-card); border: 1px solid var(--border); padding: 24px; border-radius: 12px; margin-bottom: 24px; box-shadow: var(--shadow-sm);">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="var(--primary)"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm3-4.5h.008v.008H11.25V11.25Zm0 2.25h.008v.008H11.25V13.5Zm0 2.25h.008v.008H11.25v-.008Zm3-4.5h.008v.008H14.25V11.25Zm0 2.25h.008v.008H14.25V13.5Zm0 2.25h.008v.008H14.25v-.008ZM7.5 18h9a2.25 2.25 0 0 0 2.25-2.25V5.25A2.25 2.25 0 0 0 16.5 3h-9A2.25 2.25 0 0 0 5.25 5.25v10.5A2.25 2.25 0 0 0 7.5 18Z" /></svg>
                        Kalkulator Harga Sewa
                    </h3>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Luas yang dibutuhkan (m²)</label>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="number" id="calcLuas" value="{{ $property->sisa_luas }}" min="1" max="{{ $property->sisa_luas }}" style="width: 100px; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px; font-weight: 600; outline: none;" {{ $property->sisa_luas <= 0 ? 'disabled' : '' }}>
                            <span style="font-size: 13px; color: var(--text-muted);">Sisa Area: <strong style="color: {{ $property->sisa_luas > 0 ? 'var(--success)' : 'var(--danger)' }}">{{ number_format($property->sisa_luas, 0, ',', '.') }} m²</strong> (Total: {{ number_format($property->luas, 0, ',', '.') }} m²)</span>
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-secondary); margin-bottom: 8px;">Periode Sewa</label>
                        <div style="display: flex; gap: 8px; background: #f1f5f9; padding: 4px; border-radius: 8px;">
                            <button type="button" class="period-btn active" data-period="harian" style="flex: 1; padding: 8px; border: none; background: white; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); color: var(--text-primary); transition: 0.2s;">Harian</button>
                            <button type="button" class="period-btn" data-period="bulanan" style="flex: 1; padding: 8px; border: none; background: transparent; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; color: var(--text-secondary); transition: 0.2s;">Bulanan</button>
                            <button type="button" class="period-btn" data-period="tahunan" style="flex: 1; padding: 8px; border: none; background: transparent; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; color: var(--text-secondary); transition: 0.2s;">Tahunan</button>
                        </div>
                    </div>

                    <div style="padding-top: 16px; border-top: 1px dashed var(--border); display: flex; flex-direction: column; gap: 6px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-secondary);">
                            <span>Harga Sewa (Sebelum Pajak):</span>
                            <span id="calcBasePrice" style="font-weight: 600;">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; color: var(--text-secondary);">
                            <span>Pajak PPN (11%):</span>
                            <span id="calcTaxPrice" style="font-weight: 600;">Rp 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; color: var(--text-primary); font-weight: 700; border-top: 1px solid var(--border); padding-top: 8px; margin-top: 4px;">
                            <span>Total Estimasi:</span>
                            <span id="calcTotalPrice" style="color: var(--primary); font-size: 18px;">Rp 0</span>
                        </div>
                        <div id="calcDetail" style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">...</div>
                    </div>
                </div>

                {{-- Specs --}}
                <div class="detail-specs" style="margin-bottom: 24px;">
                    <div class="detail-spec">
                        <div class="val" style="color: {{ $property->sisa_luas > 0 ? 'var(--success)' : 'var(--danger)' }}">{{ number_format($property->sisa_luas, 0, ',', '.') }} m²</div>
                        <div class="lbl">Sisa Luas Tersedia</div>
                    </div>
                    <div class="detail-spec">
                        <div class="val">{{ number_format($property->luas, 0, ',', '.') }} m²</div>
                        <div class="lbl">Total Luas Bangunan</div>
                    </div>
                    <div class="detail-spec">
                        <div class="val" style="font-size: 14px;">Rp {{ number_format($property->harga_sewa, 0, ',', '.') }}</div>
                        <div class="lbl">Harga Dasar per m²/bulan</div>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="detail-cta">
                    @if($property->sisa_luas > 0)
                        @guest
                            <a href="{{ route('user.orders.create', $property) }}?luas={{ $property->sisa_luas }}&period=bulanan" class="btn btn-primary btn-order">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                                Login untuk Order
                            </a>
                        @elseif(Auth::user()->isUser())
                            <a href="{{ route('user.orders.create', $property) }}?luas={{ $property->sisa_luas }}&period=bulanan" class="btn btn-primary btn-order">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 4.556-3.861 8.25-8.625 8.25a9.17 9.17 0 0 1-2.536-.354L3 21l1.427-4.28A7.927 7.927 0 0 1 3.75 12c0-4.556 3.861-8.25 8.625-8.25S21 7.444 21 12Z"/></svg>
                                Order Sekarang
                            </a>
                        @else
                            <button type="button" class="btn btn-primary" disabled style="opacity: .55; cursor: not-allowed;">
                                Order Khusus User
                            </button>
                        @endguest
                    @endif
                    @if($setting->whatsapp_link)
                        <a href="{{ $setting->whatsapp_link }}?text={{ urlencode('Halo, saya tertarik dengan properti: ' . $property->nama_properti) }}" target="_blank" class="btn btn-wa">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                    @if($setting->no_telepon)
                        <a href="tel:{{ $setting->no_telepon }}" class="btn btn-phone">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            Telepon
                        </a>
                    @endif
                </div>

                {{-- Related --}}
                @if($relatedProperties->count() > 0)
                    <div style="margin-top:32px">
                        <h3 style="font-size:16px;font-weight:600;margin-bottom:12px">Properti Lainnya</h3>
                        @foreach($relatedProperties as $related)
                            <a href="{{ route('properties.show', $related) }}" style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--border);border-radius:8px;margin-bottom:8px;transition:.15s;background:var(--surface)">
                                <div style="width:60px;height:45px;background:#e2e8f0;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;overflow:hidden">
                                    @if($related->thumbnail)
                                        <img src="{{ asset('storage/' . $related->thumbnail) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#94a3b8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg>
                                    @endif
                                </div>
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $related->nama_properti }}</div>
                                    <div style="font-size:12px;color:var(--primary);font-weight:600">Rp {{ number_format($related->total_sewa_per_bulan, 0, ',', '.') }}/bln</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function changeImage(thumb, src) {
            const main = document.getElementById('mainImage');
            if (main) main.src = src;
            document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        // Kalkulator Sewa Logic
        document.addEventListener('DOMContentLoaded', function() {
            const basePricePerMonth = {{ $property->harga_sewa }}; // Harga per m2 per bulan
            const maxLuas = {{ $property->sisa_luas }};
            
            const inputLuas = document.getElementById('calcLuas');
            const periodBtns = document.querySelectorAll('.period-btn');
            const calcTotalPrice = document.getElementById('calcTotalPrice');
            const calcDetail = document.getElementById('calcDetail');

            let currentPeriod = 'bulanan'; // Default period

            // Set default active button
            periodBtns.forEach(btn => {
                if(btn.dataset.period === 'bulanan') {
                    btn.style.background = 'white';
                    btn.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
                    btn.style.color = 'var(--text-primary)';
                } else {
                    btn.style.background = 'transparent';
                    btn.style.boxShadow = 'none';
                    btn.style.color = 'var(--text-secondary)';
                }
            });

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(number);
            }

            function updateCalculator() {
                let luas = parseFloat(inputLuas.value);
                if (isNaN(luas) || luas < 1) luas = 1;
                if (luas > maxLuas) {
                    luas = maxLuas;
                    inputLuas.value = maxLuas;
                }

                let pricePerMeter;
                let periodText;
                
                if (currentPeriod === 'harian') {
                    pricePerMeter = basePricePerMonth / 30;
                    periodText = 'hari';
                } else if (currentPeriod === 'bulanan') {
                    pricePerMeter = basePricePerMonth;
                    periodText = 'bulan';
                } else if (currentPeriod === 'tahunan') {
                    pricePerMeter = basePricePerMonth * 12;
                    periodText = 'tahun';
                }

                const basePrice = pricePerMeter * luas;
                const taxPrice = basePrice * 0.11;
                const totalPrice = basePrice + taxPrice;
                
                document.getElementById('calcBasePrice').textContent = `Rp ${formatRupiah(Math.ceil(basePrice))}`;
                document.getElementById('calcTaxPrice').textContent = `Rp ${formatRupiah(Math.ceil(taxPrice))}`;
                calcTotalPrice.innerHTML = `Rp ${formatRupiah(Math.ceil(totalPrice))}<span style="font-size: 14px; font-weight: normal; color: var(--text-muted);">/${periodText}</span>`;
                calcDetail.innerHTML = `(Rp ${formatRupiah(Math.ceil(pricePerMeter))} per m² × ${luas} m² + PPN 11%)`;

                // Update WA link dynamically
                const waBtn = document.querySelector('.btn-wa');
                if(waBtn) {
                    const waBase = waBtn.href.split('?text=')[0];
                    const msg = `Halo, saya tertarik dengan properti: {{ $property->nama_properti }}.\n\nSaya ingin menyewa seluas ${luas} m² untuk periode ${currentPeriod}.`;
                    waBtn.href = `${waBase}?text=${encodeURIComponent(msg)}`;
                }

                const orderBtn = document.querySelector('.btn-order');
                if(orderBtn) {
                    const orderBase = `{{ route('user.orders.create', $property) }}`;
                    orderBtn.href = `${orderBase}?luas=${encodeURIComponent(luas)}&period=${encodeURIComponent(currentPeriod)}`;
                }
            }

            inputLuas.addEventListener('input', updateCalculator);

            periodBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    periodBtns.forEach(b => {
                        b.style.background = 'transparent';
                        b.style.boxShadow = 'none';
                        b.style.color = 'var(--text-secondary)';
                    });
                    this.style.background = 'white';
                    this.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)';
                    this.style.color = 'var(--text-primary)';
                    
                    currentPeriod = this.dataset.period;
                    updateCalculator();
                });
            });

            // Initialize
            updateCalculator();
        });
    </script>
@endsection
