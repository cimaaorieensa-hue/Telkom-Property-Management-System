@extends('layouts.public')
@section('title', 'Daftar Properti')
@section('meta-description', 'Daftar lengkap properti komersial Telkom Property yang tersedia untuk disewa.')

@section('content')
    {{-- Page Header --}}
    <div style="background:linear-gradient(135deg,var(--dark),var(--dark-light));padding:40px 24px;">
        <div style="max-width:1200px;margin:0 auto">
            <h1 style="font-size:28px;font-weight:700;color:#f8fafc;margin-bottom:4px">Daftar Properti</h1>
            <p style="color:#94a3b8;font-size:15px">Temukan properti yang sesuai dengan kebutuhan bisnis Anda</p>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="search-section">
        <form class="search-inner" method="GET" action="{{ route('properties.index') }}">
            <input type="text" name="search" class="search-input" placeholder="Cari nama, alamat, atau deskripsi properti..." value="{{ request('search') }}">
            <select name="status" class="search-select">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="disewa" {{ request('status') == 'disewa' ? 'selected' : '' }}>Disewa</option>
            </select>
            <select name="sort" class="search-select">
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="harga_rendah" {{ request('sort') == 'harga_rendah' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="harga_tinggi" {{ request('sort') == 'harga_tinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="luas_terbesar" {{ request('sort') == 'luas_terbesar' ? 'selected' : '' }}>Luas Terbesar</option>
            </select>
            <button type="submit" class="search-btn">Cari</button>
        </form>
    </div>

    {{-- Property List --}}
    <section class="section">
        @if(request('search'))
            <p style="max-width:1200px;margin:0 auto 20px;font-size:14px;color:var(--text-sec)">
                Menampilkan hasil untuk "<strong>{{ request('search') }}</strong>"
                — {{ $properties->total() }} properti ditemukan
                <a href="{{ route('properties.index') }}" style="color:var(--primary);margin-left:8px">Reset</a>
            </p>
        @endif

        <div class="property-grid">
            @forelse($properties as $property)
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
                <div class="empty-state" style="grid-column:1/-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    <h3>Properti tidak ditemukan</h3>
                    <p>Coba ubah kata kunci pencarian atau filter Anda.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($properties->hasPages())
            <div class="pagination-wrap">
                {{ $properties->links() }}
            </div>
        @endif
    </section>
@endsection
