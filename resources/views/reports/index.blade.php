@extends('layouts.panel')

@section('title', 'Laporan & Statistik')
@section('page-title', 'Laporan Pendapatan & Statistik')
@section('page-subtitle', 'Monitoring performa penyewaan tahun ' . $year)

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: flex-end;">
        <form action="{{ url()->current() }}" method="GET" style="display: flex; gap: 12px;">
            <select name="year" style="padding: 8px 16px; border: 1px solid var(--border); border-radius: 6px; outline: none;" onchange="this.form.submit()">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <div class="card" style="padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Pendapatan Lunas {{ $year }}</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--primary); margin-top: 8px;">
                Rp {{ number_format($totalRevenueYear, 0, ',', '.') }}
            </div>
            <div style="font-size: 12px; color: var(--success); margin-top: 4px;">berdasarkan payment berstatus lunas</div>
        </div>
        <div class="card" style="padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Payment Lunas</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--success); margin-top: 8px;">
                {{ $paidPaymentsCount }} <small style="font-size: 14px; font-weight: 500;">Transaksi</small>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">sepanjang tahun {{ $year }}</div>
        </div>
        <div class="card" style="padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Total Transaksi Sewa</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--text); margin-top: 8px;">
                {{ $totalRentalsYear }} <small style="font-size: 14px; font-weight: 500;">Penyewaan</small>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">sepanjang tahun {{ $year }}</div>
        </div>
        <div class="card" style="padding: 24px;">
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Total Properti Disewa</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--warning); margin-top: 8px;">
                {{ $disewa }} <small style="font-size: 14px; font-weight: 500;">dari {{ $disewa + $tersedia }} Unit</small>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">status saat ini</div>
        </div>
    </div>

    {{-- Charts --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
        
        {{-- Revenue Chart --}}
        <div class="card">
            <div class="card-header">
                <h3>Pendapatan Bulanan ({{ $year }})</h3>
            </div>
            <div class="card-body" style="padding: 24px;">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="card">
            <div class="card-header">
                <h3>Distribusi Status Properti</h3>
            </div>
            <div class="card-body" style="padding: 24px; display: flex; justify-content: center; align-items: center;">
                <div style="width: 100%; max-width: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const ctxRev = document.getElementById('revenueChart').getContext('2d');
            
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const revenueData = {!! json_encode(array_values($monthlyRevenue)) !!};
            
            // Format currency for tooltip
            const formatRupiah = (value) => {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
            };

            new Chart(ctxRev, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: revenueData,
                        backgroundColor: 'rgba(225, 29, 72, 0.8)',
                        borderColor: 'rgb(225, 29, 72)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatRupiah(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if(value >= 1000000) return 'Rp ' + (value/1000000) + ' Jt';
                                    return value;
                                }
                            }
                        }
                    }
                }
            });

            // Status Distribution Chart
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Disewa'],
                    datasets: [{
                        data: [{{ $tersedia }}, {{ $disewa }}],
                        backgroundColor: [
                            '#10b981', // green for tersedia
                            '#f59e0b'  // yellow for disewa
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, usePointStyle: true }
                        }
                    }
                }
            });
        });
    </script>
@endsection
