@extends('layouts.panel')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . Auth::user()->name)

@section('content')
    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $totalProperti }}</h3>
                <p>Total Properti</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $propertiTersedia }}</h3>
                <p>Properti Tersedia</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $propertiDisewa }}</h3>
                <p>Sedang Disewa</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon rose">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>{{ $totalPenyewaan }}</h3>
                <p>Penyewaan Aktif</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        {{-- Recent Properties --}}
        <div class="card">
            <div class="card-header">
                <h2>Properti Terbaru</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Properti</th>
                            <th>Luas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProperties as $property)
                            <tr>
                                <td>
                                    <strong>{{ $property->nama_properti }}</strong>
                                    <br><small style="color: var(--text-muted);">{{ Str::limit($property->alamat, 30) }}</small>
                                </td>
                                <td>{{ number_format($property->luas, 0, ',', '.') }} m²</td>
                                <td>
                                    <span class="badge {{ $property->status === 'tersedia' ? 'badge-success' : 'badge-warning' }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">Belum ada data properti</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Rentals --}}
        <div class="card">
            <div class="card-header">
                <h2>Penyewaan Aktif</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th>Properti</th>
                            <th>Berakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRentals as $rental)
                            <tr>
                                <td><strong>{{ $rental->nama_penyewa }}</strong></td>
                                <td>{{ Str::limit($rental->property->nama_properti ?? '-', 20) }}</td>
                                <td>{{ $rental->tanggal_selesai->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align: center; color: var(--text-muted);">Belum ada data penyewaan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
