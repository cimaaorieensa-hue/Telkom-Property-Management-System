<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta-description', 'Telkom Property - Solusi properti komersial terbaik di berbagai lokasi strategis Indonesia')">
    <title>@yield('title', 'Telkom Property') - Sistem Informasi Properti</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{
            --primary:#e11d48;--primary-dark:#be123c;--primary-light:#fff1f2;
            --dark:#0f172a;--dark-light:#1e293b;
            --text:#0f172a;--text-sec:#475569;--text-muted:#94a3b8;
            --border:#e2e8f0;--bg:#f8fafc;--surface:#ffffff;
            --success:#10b981;--warning:#f59e0b;
            --radius:12px;
            --shadow:0 1px 3px rgba(0,0,0,.1),0 1px 2px rgba(0,0,0,.06);
            --shadow-lg:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1);
            --shadow-xl:0 20px 25px -5px rgba(0,0,0,.1),0 8px 10px -6px rgba(0,0,0,.1);
        }
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}

        /* === NAVBAR === */
        .navbar{background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;backdrop-filter:blur(12px);background:rgba(255,255,255,.9)}
        .navbar-inner{max-width:1200px;margin:0 auto;padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between}
        .navbar-brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:18px;color:var(--dark)}
        .navbar-brand-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--primary),#f43f5e);border-radius:10px;display:flex;align-items:center;justify-content:center}
        .navbar-brand-icon svg{width:18px;height:18px;color:#fff}
        .navbar-links{display:flex;align-items:center;gap:8px}
        .navbar-links a{padding:8px 16px;border-radius:8px;font-size:14px;font-weight:500;color:var(--text-sec);transition:.15s}
        .navbar-links a:hover,.navbar-links a.active{color:var(--primary);background:var(--primary-light)}
        .navbar-cta{padding:8px 20px;background:var(--primary);color:#fff;border-radius:8px;font-size:14px;font-weight:600;transition:.2s}
        .navbar-cta:hover{background:var(--primary-dark);transform:translateY(-1px)}
        .mobile-toggle{display:none;background:none;border:none;cursor:pointer;padding:8px;color:var(--text)}
        .mobile-toggle svg{width:24px;height:24px}

        /* === FOOTER === */
        .footer{background:var(--dark);color:#94a3b8;padding:48px 24px 24px}
        .footer-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr;gap:40px}
        .footer-brand h3{color:#f8fafc;font-size:18px;margin-bottom:8px}
        .footer-brand p{font-size:14px;line-height:1.7;max-width:320px}
        .footer-col h4{color:#e2e8f0;font-size:14px;font-weight:600;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em}
        .footer-col a{display:block;font-size:14px;color:#94a3b8;padding:4px 0;transition:.15s}
        .footer-col a:hover{color:#f8fafc}
        .footer-bottom{max-width:1200px;margin:24px auto 0;padding-top:24px;border-top:1px solid rgba(255,255,255,.08);text-align:center;font-size:13px}
        .footer-contact{display:flex;flex-direction:column;gap:8px}
        .footer-contact-item{display:flex;align-items:center;gap:8px;font-size:14px}
        .footer-contact-item svg{width:16px;height:16px;flex-shrink:0;color:var(--primary)}

        /* === HERO === */
        .hero{background:linear-gradient(135deg,var(--dark) 0%,var(--dark-light) 100%);padding:80px 24px;position:relative;overflow:hidden}
        .hero::before{content:'';position:absolute;top:-40%;right:-20%;width:600px;height:600px;background:radial-gradient(circle,rgba(225,29,72,.12),transparent 70%);border-radius:50%}
        .hero-inner{max-width:1200px;margin:0 auto;position:relative;z-index:1}
        .hero h1{font-size:42px;font-weight:800;color:#f8fafc;line-height:1.15;max-width:600px;letter-spacing:-.02em}
        .hero h1 span{background:linear-gradient(135deg,#fb7185,#f43f5e);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        .hero p{color:#94a3b8;font-size:18px;margin:16px 0 32px;max-width:520px;line-height:1.7}
        .hero-stats{display:flex;gap:40px;margin-top:40px}
        .hero-stat h3{font-size:32px;font-weight:800;color:#f8fafc}
        .hero-stat p{font-size:13px;color:#64748b;margin-top:2px}
        .hero-actions{display:flex;gap:12px;flex-wrap:wrap}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 24px;border:none;border-radius:10px;font-family:inherit;font-size:15px;font-weight:600;cursor:pointer;transition:.2s;text-decoration:none}
        .btn-primary{background:linear-gradient(135deg,var(--primary),#f43f5e);color:#fff;box-shadow:0 4px 16px rgba(225,29,72,.3)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(225,29,72,.4)}
        .btn-outline{background:transparent;color:#e2e8f0;border:1px solid rgba(255,255,255,.15)}
        .btn-outline:hover{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.3)}
        .btn svg{width:18px;height:18px}

        /* === SECTION === */
        .section{padding:64px 24px}
        .section-header{text-align:center;margin-bottom:40px}
        .section-header h2{font-size:28px;font-weight:700;color:var(--text)}
        .section-header p{color:var(--text-sec);font-size:16px;margin-top:8px;max-width:500px;margin-left:auto;margin-right:auto}

        /* === PROPERTY CARDS === */
        .property-grid{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
        .property-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:.3s;box-shadow:var(--shadow)}
        .property-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-xl)}
        .property-card-img{height:200px;background:#e2e8f0;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center}
        .property-card-img img{width:100%;height:100%;object-fit:cover}
        .property-card-img .placeholder-icon{color:#94a3b8}
        .property-card-img .placeholder-icon svg{width:48px;height:48px}
        .property-badge{position:absolute;top:12px;left:12px;padding:4px 12px;border-radius:6px;font-size:12px;font-weight:600}
        .property-badge.tersedia{background:rgba(16,185,129,.9);color:#fff}
        .property-badge.disewa{background:rgba(245,158,11,.9);color:#fff}
        .property-card-body{padding:20px}
        .property-card-body h3{font-size:16px;font-weight:600;color:var(--text);margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
        .property-card-body .address{font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:4px;margin-bottom:12px}
        .property-card-body .address svg{width:14px;height:14px;flex-shrink:0}
        .property-card-meta{display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid #f1f5f9}
        .property-card-meta .price{font-size:16px;font-weight:700;color:var(--primary)}
        .property-card-meta .price small{font-size:12px;font-weight:400;color:var(--text-muted)}
        .property-card-meta .area{font-size:13px;color:var(--text-sec);display:flex;align-items:center;gap:4px}
        .property-card-meta .area svg{width:14px;height:14px}

        /* === SEARCH BAR === */
        .search-section{background:var(--surface);border-bottom:1px solid var(--border);padding:20px 24px}
        .search-inner{max-width:1200px;margin:0 auto;display:flex;gap:12px;align-items:center;flex-wrap:wrap}
        .search-input{flex:1;min-width:200px;padding:10px 16px;padding-left:40px;border:1px solid var(--border);border-radius:8px;font-family:inherit;font-size:14px;outline:none;transition:.2s;background:var(--bg) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%2394a3b8' stroke-width='2' viewBox='0 0 24 24'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.3-4.3'/%3E%3C/svg%3E") 12px center no-repeat}
        .search-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(225,29,72,.08)}
        .search-select{padding:10px 16px;border:1px solid var(--border);border-radius:8px;font-family:inherit;font-size:14px;outline:none;background:var(--bg);cursor:pointer;min-width:140px;color:var(--text)}
        .search-select:focus{border-color:var(--primary)}
        .search-btn{padding:10px 20px;background:var(--primary);color:#fff;border:none;border-radius:8px;font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;transition:.15s}
        .search-btn:hover{background:var(--primary-dark)}

        /* === DETAIL PAGE === */
        .detail-wrapper{max-width:1200px;margin:0 auto;padding:32px 24px}
        .detail-grid{display:grid;grid-template-columns:1.5fr 1fr;gap:32px}
        .detail-gallery{border-radius:var(--radius);overflow:hidden;background:#e2e8f0;min-height:400px;display:flex;align-items:center;justify-content:center;position:relative}
        .detail-gallery img{width:100%;height:100%;object-fit:cover}
        .detail-gallery .placeholder-icon{color:#94a3b8}
        .detail-gallery .placeholder-icon svg{width:64px;height:64px}
        .gallery-thumbs{display:flex;gap:8px;margin-top:12px}
        .gallery-thumb{width:80px;height:60px;border-radius:8px;overflow:hidden;cursor:pointer;border:2px solid transparent;transition:.15s;background:#e2e8f0}
        .gallery-thumb:hover,.gallery-thumb.active{border-color:var(--primary)}
        .gallery-thumb img{width:100%;height:100%;object-fit:cover}
        .detail-info h1{font-size:26px;font-weight:700;margin-bottom:8px}
        .detail-address{display:flex;align-items:center;gap:6px;color:var(--text-sec);font-size:14px;margin-bottom:20px}
        .detail-address svg{width:16px;height:16px;color:var(--primary)}
        .detail-price-box{background:var(--primary-light);border:1px solid #fecdd3;border-radius:var(--radius);padding:20px;margin-bottom:20px}
        .detail-price-box .label{font-size:13px;color:var(--text-sec);margin-bottom:4px}
        .detail-price-box .price{font-size:28px;font-weight:800;color:var(--primary)}
        .detail-price-box .price small{font-size:14px;font-weight:400;color:var(--text-sec)}
        .detail-price-box .total{font-size:14px;color:var(--text-sec);margin-top:4px}
        .detail-specs{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px}
        .detail-spec{background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:14px;text-align:center}
        .detail-spec .val{font-size:18px;font-weight:700;color:var(--text)}
        .detail-spec .lbl{font-size:12px;color:var(--text-muted);margin-top:2px}
        .detail-desc{margin-bottom:24px}
        .detail-desc h3{font-size:16px;font-weight:600;margin-bottom:8px}
        .detail-desc p{color:var(--text-sec);font-size:14px;line-height:1.8}
        .detail-map{border-radius:var(--radius);overflow:hidden;height:300px;border:1px solid var(--border);margin-bottom:24px}
        .detail-map iframe{width:100%;height:100%;border:none}
        .detail-cta{display:flex;gap:12px}
        .detail-cta .btn{flex:1;padding:14px}
        .btn-wa{background:#25d366;color:#fff}
        .btn-wa:hover{background:#1da851}
        .btn-phone{background:var(--dark);color:#fff}
        .btn-phone:hover{background:#1e293b}
        .breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);margin-bottom:24px}
        .breadcrumb a{color:var(--text-sec);transition:.15s}
        .breadcrumb a:hover{color:var(--primary)}
        .breadcrumb svg{width:12px;height:12px}

        /* === PAGINATION === */
        .pagination-wrap{display:flex;justify-content:center;padding:32px 0}
        .pagination-wrap nav{display:flex;gap:4px;align-items:center}
        .pagination-wrap nav a,.pagination-wrap nav span{padding:8px 14px;border-radius:8px;font-size:14px;font-weight:500;border:1px solid var(--border);color:var(--text-sec);transition:.15s}
        .pagination-wrap nav a:hover{background:var(--primary-light);color:var(--primary);border-color:var(--primary)}
        .pagination-wrap nav span.current{background:var(--primary);color:#fff;border-color:var(--primary)}
        .pagination-wrap nav span.disabled{opacity:.4;cursor:default}

        /* === EMPTY STATE === */
        .empty-state{text-align:center;padding:64px 24px}
        .empty-state svg{width:64px;height:64px;color:#cbd5e1;margin-bottom:16px}
        .empty-state h3{font-size:18px;font-weight:600;margin-bottom:8px}
        .empty-state p{color:var(--text-muted);font-size:14px}

        /* === RESPONSIVE === */
        @media(max-width:768px){
            .navbar-links{display:none;position:absolute;top:64px;left:0;right:0;background:var(--surface);border-bottom:1px solid var(--border);flex-direction:column;padding:12px;gap:4px}
            .navbar-links.open{display:flex}
            .mobile-toggle{display:block}
            .hero h1{font-size:28px}
            .hero p{font-size:15px}
            .hero-stats{gap:24px}
            .hero-stat h3{font-size:24px}
            .property-grid{grid-template-columns:1fr}
            .detail-grid{grid-template-columns:1fr}
            .footer-inner{grid-template-columns:1fr;gap:24px}
            .search-inner{flex-direction:column}
            .search-input{width:100%}
            .detail-cta{flex-direction:column}
        }
        @media(min-width:769px) and (max-width:1024px){
            .property-grid{grid-template-columns:repeat(2,1fr)}
        }
    </style>
</head>
<body>
    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="navbar-brand">
                @if($setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" style="height: 36px; border-radius: 6px;">
                @else
                    <div class="navbar-brand-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3H21m-3.75 3H21"/></svg>
                    </div>
                @endif
                {{ $setting->nama_perusahaan ?? 'Telkom Property' }}
            </a>

            <button class="mobile-toggle" onclick="document.querySelector('.navbar-links').classList.toggle('open')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            <div class="navbar-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('properties.index') }}" class="{{ request()->routeIs('properties.*') ? 'active' : '' }}">Daftar Properti</a>
                @auth
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isManager() ? route('manager.dashboard') : route('user.dashboard')) }}" style="color: var(--primary); font-weight: 600;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
                @if($setting->no_whatsapp)
                    <a href="{{ $setting->whatsapp_link }}" target="_blank" class="navbar-cta">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display:inline;vertical-align:middle;margin-right:4px"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                        Hubungi Kami
                    </a>
                @endif
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <h3>{{ $setting->nama_perusahaan ?? 'Telkom Property' }}</h3>
                <p>{{ $setting->tentang ?? 'Solusi properti komersial terbaik di Indonesia.' }}</p>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('properties.index') }}">Daftar Properti</a>
                <a href="{{ route('login') }}">Login Pengelola</a>
            </div>
            <div class="footer-col">
                <h4>Kontak</h4>
                <div class="footer-contact">
                    @if($setting->no_telepon)
                        <div class="footer-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            {{ $setting->no_telepon }}
                        </div>
                    @endif
                    @if($setting->no_whatsapp)
                        <div class="footer-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                            {{ $setting->no_whatsapp }}
                        </div>
                    @endif
                    @if($setting->email_perusahaan)
                        <div class="footer-contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            {{ $setting->email_perusahaan }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} {{ $setting->nama_perusahaan ?? 'Telkom Property' }}. All rights reserved.
        </div>
    </footer>
</body>
</html>
