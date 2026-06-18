@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="hero-inner">
            <h1>Solusi Properti Terpercaya <span>Wilayah Sumbar & Jambi</span></h1>
            <p>{{ $setting->tentang ?? 'Telkom Property menyediakan solusi properti komersial di lokasi strategis di seluruh Indonesia.' }}</p>
            <div class="hero-actions">
                <a href="{{ route('properties.index') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    Lihat Semua Properti
                </a>
                @if($setting->whatsapp_link)
                    <a href="{{ $setting->whatsapp_link }}" target="_blank" class="btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                        Hubungi Kami
                    </a>
                @endif
            </div>
            <div class="hero-stats">
                <div class="hero-stat"><h3>{{ $totalProperti }}</h3><p>Total Properti</p></div>
                <div class="hero-stat"><h3>{{ $totalTersedia }}</h3><p>Tersedia</p></div>
                <div class="hero-stat"><h3>{{ $totalDisewa }}</h3><p>Disewa</p></div>
            </div>
        </div>
    </section>

    {{-- Featured Properties --}}
    <section class="section">
        <div class="section-header">
            <h2>Properti Unggulan</h2>
            <p>Pilihan properti terbaik yang siap untuk disewa</p>
        </div>

        <div class="property-grid">
            @forelse($featuredProperties as $property)
                <a href="{{ route('properties.show', $property) }}" class="property-card">
                    <div class="property-card-img">
                        @if($property->thumbnail)
                            <img src="{{ asset('storage/' . $property->thumbnail) }}" alt="{{ $property->nama_properti }}">
                        @else
                            <div class="placeholder-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                            </div>
                        @endif
                        <span class="property-badge {{ $property->status }}">{{ ucfirst($property->status) }}</span>
                    </div>
                    <div class="property-card-body">
                        <h3>{{ $property->nama_properti }}</h3>
                        <div class="address">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            {{ Str::limit($property->alamat, 40) }}
                        </div>
                        <div class="property-card-meta">
                            <div class="price">
                                Rp {{ number_format($property->total_sewa_per_bulan, 0, ',', '.') }}
                                <small>/bulan</small>
                            </div>
                            <div class="area">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                                <span style="color: {{ $property->sisa_luas > 0 ? 'var(--success)' : 'var(--text-muted)' }}; font-weight: 600;">Sisa: {{ number_format($property->sisa_luas, 0, ',', '.') }} m²</span>
                                <span style="font-size: 11px; margin-left: 4px;">/ {{ number_format($property->luas, 0, ',', '.') }} m²</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18"/></svg>
                    <h3>Belum ada properti</h3>
                    <p>Properti akan segera tersedia.</p>
                </div>
            @endforelse
        </div>

        @if($featuredProperties->count() > 0)
            <div style="text-align:center;margin-top:32px">
                <a href="{{ route('properties.index') }}" class="btn btn-primary">Lihat Semua Properti</a>
            </div>
        @endif
    </section>
@endsection
