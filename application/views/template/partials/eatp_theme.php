<?php
/**
 * Lapisan tema E-ATP v2 (modern, merah, responsif).
 *
 * Dimuat PALING AKHIR di <head> (langsung oleh template, atau otomatis oleh core/MY_Output.php)
 * dan hanya menimpa TAMPILAN lewat selector berawalan ".eatp" (class di <body>).
 * Tidak mengubah id, class, atau urutan elemen, sehingga script lama (menu.js, load.js,
 * EasyUI, penghitung approve/reject di halaman detail, dll.) tetap berjalan.
 */
?>
<!-- eatp-theme-layer -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ================================================================
   1. Design tokens
   ================================================================ */
:root {
	--eatp-red-50:  #fff1f1;
	--eatp-red-100: #fde2e2;
	--eatp-red-200: #fbc5c5;
	--eatp-red-400: #fa3131;
	--eatp-red-500: #e02424;
	--eatp-red-600: #c00505;   /* merah utama dashboard */
	--eatp-red-700: #a00404;
	--eatp-red-800: #8e0404;
	--eatp-red-900: #5d0202;

	--eatp-ink:     #171b26;
	--eatp-ink-2:   #394050;
	--eatp-muted:   #6b7280;
	--eatp-subtle:  #9aa0ad;
	--eatp-line:    #e8eaef;
	--eatp-line-2:  #d7dae1;
	--eatp-bg:      #f4f5f8;
	--eatp-surface: #ffffff;
	--eatp-soft:    #f8f9fb;
	--eatp-soft-2:  #f1f3f6;

	--eatp-green:   #16a34a;  --eatp-green-bg:  #e8f7ee;  --eatp-green-ink: #11703a;
	--eatp-blue:    #0284c7;  --eatp-blue-bg:   #e6f4fb;  --eatp-blue-ink:  #075985;
	--eatp-amber:   #d97706;  --eatp-amber-bg:  #fef4e6;  --eatp-amber-ink: #92400e;

	--eatp-radius:    16px;
	--eatp-radius-md: 12px;
	--eatp-radius-sm: 10px;
	--eatp-shadow-xs: 0 1px 2px rgba(16, 24, 40, .05);
	--eatp-shadow-sm: 0 1px 2px rgba(16, 24, 40, .06), 0 1px 3px rgba(16, 24, 40, .04);
	--eatp-shadow:    0 1px 2px rgba(16, 24, 40, .04), 0 12px 32px -16px rgba(16, 24, 40, .18);
	--eatp-shadow-lg: 0 30px 80px -28px rgba(16, 24, 40, .5);
	--eatp-focus:     0 0 0 4px rgba(192, 5, 5, .14);
	--eatp-ease:      cubic-bezier(.2, .8, .2, 1);
	--eatp-font:      "Plus Jakarta Sans", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
	--eatp-nav-h:     60px;
	--eatp-ctl-h:     38px;    /* tinggi standar input & tombol */
}

/* ================================================================
   2. Dasar & tipografi
   ================================================================ */
body.eatp {
	background: var(--eatp-bg);
	color: var(--eatp-ink);
	font-family: var(--eatp-font);
	font-size: 13px;
	line-height: 1.55;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	text-rendering: optimizeLegibility;
}
.eatp .container-fluid { background: var(--eatp-bg); }
.eatp h1, .eatp h2, .eatp h3, .eatp h4, .eatp h5, .eatp h6,
.eatp input, .eatp button, .eatp select, .eatp textarea, .eatp .btn { font-family: var(--eatp-font); }
.eatp h1, .eatp h2, .eatp h3, .eatp h4 { color: var(--eatp-ink); letter-spacing: -.015em; }
.eatp a { color: var(--eatp-red-600); transition: color .15s ease; }
.eatp a:hover { color: var(--eatp-red-800); }
.eatp ::selection { background: rgba(192, 5, 5, .16); }
.eatp input[type="checkbox"], .eatp input[type="radio"] { accent-color: var(--eatp-red-600); }
.eatp hr { border-top-color: var(--eatp-line); border-bottom: 0; }
.eatp .muted { color: var(--eatp-muted); }
.eatp .text-error { color: var(--eatp-red-600); }
.eatp .text-success { color: var(--eatp-green); }
.eatp .text-warning { color: var(--eatp-amber); }
.eatp .text-info { color: var(--eatp-blue); }

.eatp ::-webkit-scrollbar { width: 10px; height: 10px; }
.eatp ::-webkit-scrollbar-track { background: transparent; }
.eatp ::-webkit-scrollbar-thumb { background: #cfd3db; border-radius: 10px; border: 2px solid transparent; background-clip: content-box; }
.eatp ::-webkit-scrollbar-thumb:hover { background-color: #b3b8c3; }

/* ================================================================
   3. Top bar
   ================================================================ */
.eatp .navbar.main {
	position: -webkit-sticky;
	position: sticky;
	top: 0;
	z-index: 1030;
	height: var(--eatp-nav-h);
	margin: 0;
	border: 0;
	background: var(--eatp-red-600);
	background-image:
		radial-gradient(120% 180% at 100% 0%, rgba(250, 49, 49, .55) 0%, rgba(250, 49, 49, 0) 55%),
		linear-gradient(115deg, var(--eatp-red-900) 0%, var(--eatp-red-700) 40%, var(--eatp-red-600) 72%, var(--eatp-red-500) 100%);
	box-shadow: 0 8px 24px -14px rgba(93, 2, 2, .8);
}
.eatp .navbar.main .appbrand,
.eatp .navbar.main .appbrand > span {
	height: var(--eatp-nav-h);
	line-height: var(--eatp-nav-h);
	background: transparent;
	border-right: 1px solid rgba(255, 255, 255, .12);
}
.eatp .navbar.main .appbrand h3 {
	margin: 0;
	line-height: var(--eatp-nav-h);
	font-family: var(--eatp-font) !important;
	font-size: 19px;
	font-weight: 800;
	letter-spacing: .06em;
	white-space: nowrap;
	color: #fff !important;
	text-shadow: 0 2px 12px rgba(0, 0, 0, .25);
}
.eatp .navbar.main .btn-navbar {
	height: var(--eatp-nav-h);
	line-height: var(--eatp-nav-h);
	padding: 0 20px;
	border: 0;
	border-radius: 0;
	background: rgba(255, 255, 255, .08);
	box-shadow: none;
	transition: background-color .2s ease;
}
.eatp .navbar.main .btn-navbar:hover { background: rgba(255, 255, 255, .18); transform: none; }
.eatp .navbar.main .btn-navbar .icon-bar { width: 20px; background: #fff; box-shadow: none; border-radius: 2px; }
.eatp .navbar.main .topnav, .eatp .navbar.main .topnav > li { border: 0; }
.eatp .navbar.main .topnav > li { height: var(--eatp-nav-h); line-height: var(--eatp-nav-h); }
.eatp .navbar.main .topnav > li > a {
	height: var(--eatp-nav-h);
	line-height: var(--eatp-nav-h);
	color: rgba(255, 255, 255, .92);
	font-weight: 600;
	transition: background-color .2s ease, color .2s ease;
}
.eatp .navbar.main .topnav > li > a:hover { color: #fff; background: rgba(255, 255, 255, .12); }
.eatp .navbar.main .topnav > li > a.glyphicons i:before { top: 21px; color: rgba(255, 255, 255, .9); }
.eatp .navbar.main .topnav > li.open > a { height: var(--eatp-nav-h); line-height: var(--eatp-nav-h); border: 0; background: rgba(255, 255, 255, .16); color: #fff; }
.eatp .navbar.main .topnav > li.open > a i:before { top: 21px; color: #fff; }
.eatp .navbar.main .topnav > li.open > a .caret { border-top-color: #fff; }
.eatp .navbar.main .topnav > li.open .dropdown-menu {
	top: 100%;
	margin-top: 8px;
	padding: 6px;
	width: 270px;
	border: 1px solid var(--eatp-line);
	border-radius: 14px;
	background: var(--eatp-surface);
	box-shadow: var(--eatp-shadow-lg);
}
.eatp .navbar.main .topnav > li.open .dropdown-menu li { border: 0; }
.eatp .navbar.main .topnav > li.open .dropdown-menu li > a {
	height: 40px; line-height: 40px; padding: 0 12px;
	border: 0; border-radius: 9px; background: transparent;
	color: var(--eatp-ink-2); font-weight: 600;
}
.eatp .navbar.main .topnav > li.open .dropdown-menu li > a:hover { background: var(--eatp-red-50); color: var(--eatp-red-600); }
.eatp .navbar.main .topnav > li.open .dropdown-menu li.highlight,
.eatp .navbar.main .topnav > li.open .dropdown-menu li.highlight > a { background: transparent; }
.eatp .navbar.main .topnav > li.open .dropdown-menu li.highlight.profile { margin-top: 4px; padding: 10px 12px; border-top: 1px solid var(--eatp-line); line-height: 1.5; }

/* ================================================================
   4. Sidebar (struktur tidak diubah; menu.js bergantung padanya)
   ================================================================ */
.eatp #menu { background: var(--eatp-surface); background-image: none; border-right: 1px solid var(--eatp-line); box-shadow: none; }
.eatp #menu .profile { display: block; padding: 18px 0 8px; }
.eatp #menu .profile img { transition: transform .4s var(--eatp-ease); }
.eatp #menu .profile a:hover img { transform: scale(1.05) rotate(-2deg); }
.eatp #menu > ul { padding: 6px 0 28px; }
.eatp #menu > ul > li,
.eatp #menu > ul > li:first-child { height: 44px; line-height: 44px; margin: 3px 12px; border: 0; border-radius: 12px; background: transparent; }
.eatp #menu > ul > li a {
	height: 44px; line-height: 44px; border: 0; border-radius: 12px;
	color: var(--eatp-ink-2); font-size: 13px; font-weight: 600;
	transition: background-color .2s ease, color .2s ease, box-shadow .2s ease, padding-left .2s var(--eatp-ease);
}
.eatp #menu > ul > li a.glyphicons i:before,
.eatp #menu > ul > li.glyphicons a i:before { top: 13px; color: var(--eatp-subtle); transition: color .2s ease; }
.eatp #menu > ul > li > a:hover { background: var(--eatp-red-50); color: var(--eatp-red-600); }
.eatp #menu > ul > li > a:hover i:before { color: var(--eatp-red-600); }
.eatp #menu > ul > li.active > a {
	border: 0;
	background: var(--eatp-red-600);
	background-image: linear-gradient(120deg, var(--eatp-red-700), var(--eatp-red-500));
	color: #fff;
	box-shadow: 0 10px 20px -12px rgba(192, 5, 5, .8);
}
.eatp #menu > ul > li.active > a i:before, .eatp #menu > ul > li.active.glyphicons i:before { color: #fff; }
.eatp #menu > ul > li.hasSubmenu.active { height: auto; padding: 0 0 6px; border: 0; background: transparent; }
.eatp #menu > ul > li .count {
	top: 13px; right: 10px; padding: 2px 8px; border-radius: 999px;
	background: var(--eatp-soft-2); color: var(--eatp-muted); font-size: 10.5px; box-shadow: none;
}
.eatp #menu > ul > li.active .count { background: rgba(255, 255, 255, .22); color: #fff; }
.eatp #menu > ul ul { position: relative; margin: 6px 0 0 22px; padding-left: 8px; border-left: 2px solid var(--eatp-line); }
.eatp #menu > ul ul li { margin: 1px 0; }
.eatp #menu > ul ul li a {
	position: relative; height: 34px; line-height: 34px; padding-left: 14px;
	border-radius: 9px; color: #4b5160; font-weight: 500;
}
.eatp #menu > ul ul li a:hover { background: var(--eatp-soft-2); color: var(--eatp-red-600); padding-left: 18px; }
.eatp #menu > ul ul li.active a, .eatp #menu > ul ul li.active-menu a { background: var(--eatp-red-50); color: var(--eatp-red-600); font-weight: 700; }
.eatp #menu > ul ul li.active > a:before, .eatp #menu > ul ul li.active-menu > a:before {
	content: ""; position: absolute; left: -11px; top: 9px; bottom: 9px; width: 3px; border-radius: 3px; background: var(--eatp-red-600);
}
.eatp #menu > ul ul li.hasSubmenu > a { color: var(--eatp-ink-2); font-weight: 600; }
.eatp #menu > ul ul .count { top: 8px; }

/* Sidebar tetap di tempat saat konten panjang di-scroll (tablet & desktop).
   Layout lama memakai float + trik padding 99999px di dalam wrapper overflow:hidden,
   sehingga sidebar ikut ter-scroll; di sini dijadikan fixed dengan scroll sendiri. */
@media (min-width: 768px) {
	.eatp #menu {
		position: fixed;
		top: var(--eatp-nav-h);
		bottom: 0;
		left: 0;
		z-index: 1000;
		width: 250px;
		min-height: 0;
		margin: 0;
		padding-bottom: 24px;
		overflow-x: hidden;
		overflow-y: auto;
		-ms-overflow-style: -ms-autohiding-scrollbar;
		overscroll-behavior: contain;
		scrollbar-width: thin;
	}
	.eatp #menu::-webkit-scrollbar { width: 6px; }
	.eatp #content { margin-left: 251px; }
	.eatp .menu-hidden #content { margin-left: 0; }
}

/* ================================================================
   5. Konten, breadcrumb, header halaman
   ================================================================ */
.eatp #content { background: var(--eatp-bg); padding-bottom: 32px; }
.eatp .innerLR { padding: 0 24px; }
.eatp .separator.bottom { margin-bottom: 16px; }
.eatp .breadcrumb {
	height: auto; min-height: 48px; line-height: 48px; margin: 0 0 20px; padding: 0 24px;
	border: 0; border-bottom: 1px solid var(--eatp-line); border-radius: 0;
	background: rgba(255, 255, 255, .85);
	-webkit-backdrop-filter: saturate(180%) blur(8px);
	backdrop-filter: saturate(180%) blur(8px);
	box-shadow: none; font-size: 13px; color: var(--eatp-muted);
}
.eatp .breadcrumb a { color: var(--eatp-ink-2); font-weight: 600; }
.eatp .breadcrumb a:hover { color: var(--eatp-red-600); }
.eatp .breadcrumb > li:last-child { color: var(--eatp-red-600); font-weight: 700; }
.eatp .breadcrumb .glyphicons i:before { color: var(--eatp-red-600); }
.eatp .breadcrumb .divider { color: #c3c7d0; padding: 0 6px; }

.eatp #content h3, .eatp #content > h2, .eatp .innerLR > h2 { color: var(--eatp-ink); font-weight: 800; letter-spacing: -.02em; }
.eatp #content h3 { font-size: 21px; line-height: 1.3; margin-top: 4px; }
.eatp h3.glyphicons i:before, .eatp h2.glyphicons i:before { color: var(--eatp-red-600); }
.eatp h4.glyphicons i:before { color: var(--eatp-red-600); }

/* Header halaman dengan tombol aksi */
.eatp .heading-buttons {
	margin: 0 0 18px; padding: 16px 20px;
	border: 1px solid var(--eatp-line); border-radius: var(--eatp-radius);
	background: var(--eatp-surface); box-shadow: var(--eatp-shadow-sm);
}
.eatp .heading-buttons h1, .eatp .heading-buttons h2, .eatp .heading-buttons h3,
.eatp .heading-buttons h4 { margin: 0; font-size: 19px; font-weight: 800; line-height: 38px; }
.eatp .heading-buttons .buttons { margin: 0; }

/* ================================================================
   6. Kartu (widget), accordion, well
   ================================================================ */
.eatp .widget {
	margin-bottom: 20px;
	border: 1px solid var(--eatp-line);
	border-radius: var(--eatp-radius);
	background: var(--eatp-surface);
	box-shadow: var(--eatp-shadow);
	transition: box-shadow .3s ease, transform .3s var(--eatp-ease);
}
.eatp .widget .widget { box-shadow: none; border-radius: var(--eatp-radius-md); }
.eatp .widget .widget-head {
	border: 0; border-bottom: 1px solid var(--eatp-line);
	border-radius: var(--eatp-radius) var(--eatp-radius) 0 0;
	background: var(--eatp-surface); background-image: none; box-shadow: none;
}
.eatp .widget .widget-head:not(.progress) { height: auto; min-height: 50px; }
.eatp .widget .widget-head .heading {
	height: 50px; line-height: 50px; padding: 0 20px;
	color: var(--eatp-ink); font-size: 14.5px; font-weight: 700; letter-spacing: -.01em;
}
.eatp .widget .widget-head .heading.glyphicons { padding-left: 50px; }
.eatp .widget .widget-head .heading.glyphicons i:before {
	top: 50%; left: 16px; width: 26px; height: 26px; margin-top: -13px; padding: 0;
	border-radius: 8px; background: var(--eatp-red-50);
	color: var(--eatp-red-600); font-size: 14px; line-height: 26px; text-align: center;
}
.eatp .widget .widget-head > .btn, .eatp .widget .widget-head .pull-right { margin-top: 6px; margin-right: 10px; }
.eatp .widget .widget-body { padding: 18px 20px; }
.eatp .widget .widget-footer { border-top: 1px solid var(--eatp-line); background: var(--eatp-soft); border-radius: 0 0 var(--eatp-radius) var(--eatp-radius); }

.eatp .accordion-group {
	margin-bottom: 12px;
	border: 1px solid var(--eatp-line);
	border-radius: var(--eatp-radius-md);
	background: var(--eatp-surface);
	box-shadow: var(--eatp-shadow-xs);
	overflow: hidden;
	transition: box-shadow .25s ease, border-color .25s ease;
}
.eatp .accordion-group:hover { border-color: var(--eatp-line-2); box-shadow: var(--eatp-shadow-sm); }
.eatp .accordion-heading .accordion-toggle {
	position: relative;
	padding: 14px 44px 14px 18px;
	background: var(--eatp-surface);
	color: var(--eatp-ink);
	font-size: 13.5px;
	font-weight: 700;
	transition: background-color .2s ease, color .2s ease;
}
.eatp .accordion-heading .accordion-toggle:after {
	content: ""; position: absolute; right: 18px; top: 50%;
	width: 8px; height: 8px; margin-top: -6px;
	border-right: 2px solid var(--eatp-subtle); border-bottom: 2px solid var(--eatp-subtle);
	transform: rotate(45deg); transition: transform .25s var(--eatp-ease), border-color .2s ease;
}
.eatp .accordion-heading .accordion-toggle.collapsed:after { transform: rotate(-45deg); margin-top: -4px; }
.eatp .accordion-heading .accordion-toggle:hover { background: var(--eatp-red-50); color: var(--eatp-red-600); text-decoration: none; }
.eatp .accordion-heading .accordion-toggle:hover:after { border-color: var(--eatp-red-600); }
.eatp .accordion-inner { padding: 14px 18px; border-top: 1px solid var(--eatp-line); overflow-x: auto; -webkit-overflow-scrolling: touch; }
.eatp .accordion-group .accordion-group { margin: 8px 0; box-shadow: none; background: var(--eatp-soft); }
.eatp .accordion-group .accordion-group .accordion-toggle { background: var(--eatp-soft); font-size: 13px; }

.eatp .well { border: 1px solid var(--eatp-line); border-radius: var(--eatp-radius-md); background: var(--eatp-soft); box-shadow: none; }
.eatp .form-actions {
	margin-top: 18px; padding: 16px 20px;
	border-top: 1px solid var(--eatp-line); border-radius: 0 0 var(--eatp-radius-md) var(--eatp-radius-md);
	background: var(--eatp-soft);
}

/* ================================================================
   7. Tabel
   ================================================================ */
.eatp .table { color: var(--eatp-ink); border-collapse: separate; border-spacing: 0; }
.eatp .table th, .eatp .table td { padding: 11px 14px; border-top-color: var(--eatp-line); vertical-align: middle; line-height: 1.5; }
.eatp .table-condensed th, .eatp .table-condensed td { padding: 8px 12px; }
.eatp .table thead th, .eatp .table > tbody:first-child > tr:first-child > th {
	background: var(--eatp-soft);
	color: var(--eatp-muted);
	font-size: 11px;
	font-weight: 700;
	letter-spacing: .06em;
	text-transform: uppercase;
	border-bottom: 1px solid var(--eatp-line);
}
.eatp .table tbody tr > td { transition: background-color .15s ease; }
.eatp .table tbody tr:hover > td { background-color: #fff6f6; }
.eatp .table-striped tbody > tr:nth-child(odd) > td { background-color: #fbfbfd; }
.eatp .table-striped tbody > tr:nth-child(odd):hover > td { background-color: #fff6f6; }
.eatp .table-bordered { border: 1px solid var(--eatp-line); border-left: 1px solid var(--eatp-line); border-radius: var(--eatp-radius-md); overflow: hidden; }
.eatp .table-bordered th, .eatp .table-bordered td { border-left: 1px solid #f0f1f4; }
.eatp .table-bordered th:first-child, .eatp .table-bordered td:first-child { border-left: 0; }
/* Sel label (mis. info site di halaman detail memakai background #f5f5f5 inline) */
.eatp .table td[style*="#f5f5f5"], .eatp .table td[style*="#F5F5F5"] {
	background: var(--eatp-soft) !important;
	color: var(--eatp-muted);
	font-size: 12px;
	font-weight: 600;
	white-space: nowrap;
}
.eatp .table-primary { border: 1px solid var(--eatp-line); }
.eatp .table-primary thead th {
	border-color: var(--eatp-line);
	background: var(--eatp-red-50);
	color: var(--eatp-red-800);
	font-size: 11px;
	letter-spacing: .06em;
	text-transform: uppercase;
}
.eatp .table-primary tbody td { background: var(--eatp-surface); border-color: #f0f1f4; }
.eatp .table-primary tbody tr:nth-child(odd) td { background: #fbfbfd; }
.eatp .table-primary tbody tr.selected td, .eatp .table-primary tbody tr.selectable:hover td { background: #fff6f6; box-shadow: none; }
.eatp .table-primary tbody td.important { color: var(--eatp-red-600); }
.eatp .table caption { color: var(--eatp-muted); font-weight: 600; }

/* ================================================================
   8. Tombol
   ================================================================ */
.eatp .btn {
	padding: 8px 15px;
	border: 1px solid var(--eatp-line-2);
	border-radius: var(--eatp-radius-sm);
	background: var(--eatp-surface);
	background-image: none;
	color: var(--eatp-ink-2);
	font-size: 13px;
	font-weight: 600;
	line-height: 20px;
	text-shadow: none;
	box-shadow: var(--eatp-shadow-xs);
	filter: none;
	transition: background-color .15s ease, border-color .15s ease, color .15s ease, box-shadow .2s ease, transform .15s var(--eatp-ease);
}
.eatp .btn:hover { background: var(--eatp-soft); color: var(--eatp-ink); border-color: #c9cdd6; transform: translateY(-1px); box-shadow: 0 8px 18px -10px rgba(16, 24, 40, .35); }
.eatp .btn:active, .eatp .btn.active { transform: translateY(0); box-shadow: inset 0 2px 4px rgba(16, 24, 40, .1); }
.eatp .btn:focus { outline: 0; box-shadow: var(--eatp-focus); }
.eatp .btn.disabled, .eatp .btn[disabled] { transform: none; opacity: .5; box-shadow: none; cursor: not-allowed; }
.eatp .btn-small { padding: 5px 11px; font-size: 12px; line-height: 18px; border-radius: 8px; }
.eatp .btn-mini { padding: 2px 8px; font-size: 11px; line-height: 16px; border-radius: 7px; }
.eatp .btn-large { padding: 11px 20px; font-size: 15px; border-radius: 12px; }

.eatp .btn-primary, .eatp .btn-primary:hover, .eatp .btn-primary.disabled, .eatp .btn-primary[disabled] {
	border-color: var(--eatp-red-700);
	background: var(--eatp-red-600);
	background-image: linear-gradient(180deg, var(--eatp-red-500), var(--eatp-red-600));
	color: #fff;
}
.eatp .btn-primary:hover { background-image: linear-gradient(180deg, var(--eatp-red-600), var(--eatp-red-700)); box-shadow: 0 10px 20px -10px rgba(192, 5, 5, .7); }
.eatp .btn-success, .eatp .btn-success:hover, .eatp .btn-sucess { border-color: #15803d; background: var(--eatp-green); background-image: linear-gradient(180deg, #22b457, var(--eatp-green)); color: #fff; }
.eatp .btn-info, .eatp .btn-info:hover { border-color: #0369a1; background: var(--eatp-blue); background-image: linear-gradient(180deg, #0ea5e9, var(--eatp-blue)); color: #fff; }
.eatp .btn-warning, .eatp .btn-warning:hover { border-color: #b45309; background: var(--eatp-amber); background-image: linear-gradient(180deg, #f59e0b, var(--eatp-amber)); color: #fff; }
.eatp .btn-danger, .eatp .btn-danger:hover { border-color: #b91c1c; background: #dc2626; background-image: linear-gradient(180deg, #ef4444, #dc2626); color: #fff; }
.eatp .btn-inverse, .eatp .btn-inverse:hover { border-color: #0b0e15; background: var(--eatp-ink); color: #fff; }
.eatp .btn-success:hover, .eatp .btn-info:hover, .eatp .btn-warning:hover, .eatp .btn-danger:hover, .eatp .btn-inverse:hover { filter: brightness(.95); }
.eatp .btn-link, .eatp .btn-link:hover { border-color: transparent; background: transparent; box-shadow: none; color: var(--eatp-red-600); }
.eatp .btn-group > .btn { transform: none; }
.eatp .btn-group > .btn:first-child { border-radius: var(--eatp-radius-sm) 0 0 var(--eatp-radius-sm); }
.eatp .btn-group > .btn:last-child { border-radius: 0 var(--eatp-radius-sm) var(--eatp-radius-sm) 0; }
.eatp .btn-group > .btn:only-child { border-radius: var(--eatp-radius-sm); }

/* Tombol ber-ikon glyphicons: ikon selalu di tengah vertikal apa pun tinggi tombolnya */
.eatp .glyphicons.btn-icon { padding: 8px 15px 8px 36px; }
.eatp .glyphicons.btn-small { padding: 5px 11px 5px 32px; }
.eatp .glyphicons.btn-mini { padding: 2px 8px 2px 27px; }
.eatp .glyphicons.btn-icon i:before, .eatp .glyphicons.btn-small i:before, .eatp .glyphicons.btn-mini i:before {
	top: 50%; bottom: auto; height: auto; padding: 0; margin: 0;
	line-height: 1; background: none; border-radius: 0; text-shadow: none;
	-webkit-transform: translateY(-50%); transform: translateY(-50%);
}
.eatp .glyphicons.btn-icon i:before { left: 4px; width: 32px; font-size: 13px; }
.eatp .glyphicons.btn-small i:before { left: 3px; width: 28px; font-size: 12px; }
.eatp .glyphicons.btn-mini i:before { left: 2px; width: 24px; font-size: 11px; }
.eatp .btn-primary.glyphicons i:before, .eatp .btn-success.glyphicons i:before, .eatp .btn-info.glyphicons i:before,
.eatp .btn-warning.glyphicons i:before, .eatp .btn-danger.glyphicons i:before, .eatp .btn-inverse.glyphicons i:before { color: rgba(255, 255, 255, .92); }
.eatp .btn-default.glyphicons i:before, .eatp .btn.glyphicons:not([class*="btn-"]) i:before { color: var(--eatp-muted); }
.eatp .btn-heading { border-radius: 8px !important; }

/* Tombol ikon kecil di grid (ukuran tetap 25x25 agar kolom tidak bergeser) */
.eatp .btn-action {
	border-radius: 8px; box-shadow: none;
	transition: transform .15s var(--eatp-ease), background-color .15s ease, box-shadow .15s ease, border-color .15s ease;
}
.eatp .btn-action:hover { transform: translateY(-1px) scale(1.08); box-shadow: 0 6px 14px -6px rgba(16, 24, 40, .35); }
.eatp .btn-action i:before { text-shadow: none; }
.eatp .btn-action.btn-success { background: var(--eatp-green-bg); border-color: #c7ecd6; }
.eatp .btn-action.btn-success i:before { color: var(--eatp-green); }
.eatp .btn-action.btn-info { background: var(--eatp-blue-bg); border-color: #c4e5f5; }
.eatp .btn-action.btn-info i:before { color: var(--eatp-blue); }
.eatp .btn-action.btn-danger { background: var(--eatp-red-50); border-color: var(--eatp-red-100); }
.eatp .btn-action.btn-danger i:before { color: var(--eatp-red-600); }
.eatp .btn-action.btn-warning { background: var(--eatp-amber-bg); border-color: #fbe0bb; }
.eatp .btn-action.btn-warning i:before { color: var(--eatp-amber); }
.eatp .btn-action.btn-default, .eatp .btn-action.btn-primary { background: var(--eatp-soft-2); border-color: var(--eatp-line); background-image: none; }
.eatp .btn-action.btn-default i:before { color: var(--eatp-muted); }
.eatp .btn-action.btn-primary i:before { color: var(--eatp-red-600); }

/* Approve / Reject (halaman detail) - class tidak diubah, penghitung JS tetap bekerja */
.eatp .btn.approve, .eatp .btn.reject { min-width: 86px; background: var(--eatp-surface); background-image: none; color: var(--eatp-ink-2); border-color: var(--eatp-line-2); }
.eatp .btn.approve:hover { background: var(--eatp-green-bg); border-color: #9fdcb6; color: var(--eatp-green-ink); }
.eatp .btn.reject:hover { background: var(--eatp-red-50); border-color: var(--eatp-red-200); color: var(--eatp-red-700); }
.eatp .btn.approve.active { background: var(--eatp-green); background-image: linear-gradient(180deg, #22b457, var(--eatp-green)); border-color: #15803d; color: #fff; box-shadow: 0 8px 16px -10px rgba(22, 163, 74, .8); }
.eatp .btn.reject.active { background: #dc2626; background-image: linear-gradient(180deg, #ef4444, #dc2626); border-color: #b91c1c; color: #fff; box-shadow: 0 8px 16px -10px rgba(220, 38, 38, .8); }
.eatp .btn-error { background: var(--eatp-surface); color: var(--eatp-ink-2); }
.eatp .btn[onclick^="approveAll"] { border-color: #9fdcb6; color: var(--eatp-green-ink); background: var(--eatp-green-bg); }
.eatp .btn[onclick^="rejectAll"] { border-color: var(--eatp-red-200); color: var(--eatp-red-700); background: var(--eatp-red-50); }

/* ================================================================
   9. Form
   ================================================================ */
.eatp input[type="text"]:not(.combo-text):not(.spinner-text):not(.datagrid-editable-input):not(.pagination-num):not(.searchbox-text):not(.textbox-text),
.eatp input[type="password"], .eatp input[type="email"], .eatp input[type="number"], .eatp input[type="search"],
.eatp input[type="tel"], .eatp input[type="url"], .eatp input[type="date"], .eatp input[type="datetime"], .eatp input[type="time"],
.eatp .uneditable-input {
	height: 22px;
	padding: 7px 10px;
	border: 1px solid var(--eatp-line-2);
	border-radius: var(--eatp-radius-sm);
	background-color: var(--eatp-surface);
	box-shadow: var(--eatp-shadow-xs);
	color: var(--eatp-ink);
	font-size: 13px;
	line-height: 22px;
	transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
}
.eatp textarea {
	padding: 9px 11px;
	border: 1px solid var(--eatp-line-2);
	border-radius: var(--eatp-radius-sm);
	box-shadow: var(--eatp-shadow-xs);
	color: var(--eatp-ink);
	font-size: 13px;
	transition: border-color .15s ease, box-shadow .15s ease;
}
.eatp select {
	height: var(--eatp-ctl-h);
	padding: 7px 10px;
	border: 1px solid var(--eatp-line-2);
	border-radius: var(--eatp-radius-sm);
	background-color: var(--eatp-surface);
	box-shadow: var(--eatp-shadow-xs);
	color: var(--eatp-ink);
	font-size: 13px;
	line-height: 22px;
}
.eatp select[multiple], .eatp select[size] { height: auto; }
.eatp input:hover, .eatp textarea:hover, .eatp select:hover { border-color: #c2c6cf; }
.eatp input:focus, .eatp textarea:focus, .eatp select:focus { border-color: var(--eatp-red-600); box-shadow: var(--eatp-focus); outline: 0; }
.eatp input[disabled], .eatp input[readonly], .eatp select[disabled], .eatp textarea[disabled], .eatp textarea[readonly] { background-color: var(--eatp-soft-2); color: var(--eatp-muted); box-shadow: none; }
.eatp input::placeholder, .eatp textarea::placeholder { color: var(--eatp-subtle); }
.eatp label { color: var(--eatp-ink-2); font-weight: 500; }
.eatp .help-block, .eatp .help-inline { color: var(--eatp-muted); font-size: 12px; }

.eatp .form-horizontal .control-group { margin-bottom: 14px; }
.eatp .form-horizontal .control-label { padding-top: 9px; color: var(--eatp-ink-2); font-size: 12.5px; font-weight: 600; }
.eatp .control-group.error .control-label, .eatp .control-group.error .help-inline { color: var(--eatp-red-600); }
.eatp .control-group.error input, .eatp .control-group.error select, .eatp .control-group.error textarea { border-color: var(--eatp-red-500); }
.eatp .control-group.success input, .eatp .control-group.success select { border-color: var(--eatp-green); }

.eatp .input-append input, .eatp .input-append select { border-radius: var(--eatp-radius-sm) 0 0 var(--eatp-radius-sm); }
.eatp .input-prepend input, .eatp .input-prepend select { border-radius: 0 var(--eatp-radius-sm) var(--eatp-radius-sm) 0; }
.eatp .input-append .add-on, .eatp .input-prepend .add-on {
	height: 22px; padding: 7px 11px; line-height: 22px;
	border-color: var(--eatp-line-2); background: var(--eatp-soft); color: var(--eatp-muted); text-shadow: none;
}
.eatp .input-append .add-on:last-child, .eatp .input-append .btn:last-child { border-radius: 0 var(--eatp-radius-sm) var(--eatp-radius-sm) 0; }
.eatp .input-prepend .add-on:first-child, .eatp .input-prepend .btn:first-child { border-radius: var(--eatp-radius-sm) 0 0 var(--eatp-radius-sm); }
.eatp .input-append .btn, .eatp .input-prepend .btn { transform: none; box-shadow: none; }
.eatp .input-prepend .add-on.glyphicons i:before, .eatp .input-append .add-on.glyphicons i:before { top: 50%; margin-top: -7px; color: var(--eatp-subtle); }
.eatp .search-query { border-radius: 999px; padding-left: 14px; padding-right: 14px; }

/* Input bawaan EasyUI tetap mengikuti ukuran komponennya; hanya wrapper yang dipercantik */
.eatp .combo, .eatp .spinner, .eatp .searchbox { border-color: var(--eatp-line-2); border-radius: 8px; overflow: hidden; background: var(--eatp-surface); }
.eatp .combo-arrow, .eatp .spinner-arrow { background-color: var(--eatp-soft); opacity: .8; }
.eatp .combo-arrow:hover { background-color: var(--eatp-red-50); }

/* ================================================================
   10. Badge / label (pill lembut dengan titik status)
   ================================================================ */
.eatp .label, .eatp .badge {
	display: inline-block;
	padding: 3px 10px;
	border-radius: 999px;
	background: var(--eatp-soft-2);
	color: var(--eatp-ink-2);
	font-size: 11.5px;
	font-weight: 700;
	line-height: 18px;
	letter-spacing: .01em;
	text-shadow: none;
	vertical-align: middle;
	box-shadow: inset 0 0 0 1px rgba(16, 24, 40, .06);
}
.eatp .label:before, .eatp .badge:before {
	content: ""; display: inline-block; width: 6px; height: 6px; margin: 0 6px 1px 0;
	border-radius: 50%; background: currentColor; opacity: .85; vertical-align: middle;
}
.eatp .label-success, .eatp .badge-success { background: var(--eatp-green-bg); color: var(--eatp-green-ink); }
.eatp .label-info, .eatp .badge-info { background: var(--eatp-blue-bg); color: var(--eatp-blue-ink); }
.eatp .label-warning, .eatp .badge-warning { background: var(--eatp-amber-bg); color: var(--eatp-amber-ink); }
.eatp .label-important, .eatp .badge-important, .eatp .label-primary { background: var(--eatp-red-50); color: var(--eatp-red-700); }
.eatp .label-inverse, .eatp .badge-inverse { background: var(--eatp-ink); color: #fff; }

/* ================================================================
   11. Foto & gambar
   ================================================================ */
.eatp a.fancy img, .eatp .photobox img, .eatp .img-polaroid, .eatp .list-checklist img {
	border: 1px solid var(--eatp-line);
	border-radius: 12px;
	background: var(--eatp-soft);
	box-shadow: var(--eatp-shadow-sm);
	object-fit: cover;
	transition: transform .3s var(--eatp-ease), box-shadow .3s ease;
}
.eatp a.fancy:hover img, .eatp .photobox:hover img, .eatp .img-polaroid:hover { transform: scale(1.04); box-shadow: 0 16px 30px -14px rgba(16, 24, 40, .45); }
/* Thumbnail ber-atribut height="80": Bootstrap memaksa height:auto sehingga foto tampil penuh */
.eatp a.fancy img[height="80"], .eatp .list-checklist img[height="80"] { width: 120px; height: 80px; max-width: none; }
.eatp a.fancy { display: inline-block; border-radius: 12px; cursor: zoom-in; }
.eatp .img-polaroid { padding: 3px; }

/* ================================================================
   12. EasyUI (datagrid, panel, window, tabs)
   ================================================================ */
.eatp .panel-header {
	padding: 12px 16px;
	border-color: var(--eatp-line);
	border-radius: var(--eatp-radius-md) var(--eatp-radius-md) 0 0;
	background: var(--eatp-surface); background-image: none; filter: none;
}
.eatp .panel-title { color: var(--eatp-ink); font-size: 14px; font-weight: 700; }
.eatp .panel-body { border-color: var(--eatp-line); }
.eatp .datagrid .panel-body { border-radius: 0 0 var(--eatp-radius-md) var(--eatp-radius-md); }
.eatp .datagrid-header, .eatp .datagrid-htable { background: var(--eatp-soft); background-image: none; filter: none; border-color: var(--eatp-line); }
.eatp .datagrid-toolbar { padding: 8px 10px; background: var(--eatp-surface); border-color: var(--eatp-line); }
.eatp .datagrid-pager { padding: 6px 8px; background: var(--eatp-surface); border-color: var(--eatp-line); }
.eatp .datagrid-header-row, .eatp .datagrid-row { height: 40px; }
.eatp .datagrid-header td, .eatp .datagrid-body td, .eatp .datagrid-footer td { border-color: #eef0f3; border-left-color: transparent; border-right-color: transparent; }
.eatp .datagrid-header td { border-right-color: #eef0f3; }
.eatp .datagrid-header .datagrid-cell span {
	color: var(--eatp-muted); font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
}
.eatp .datagrid-cell, .eatp .datagrid-cell-rownumber { font-size: 12.5px; color: var(--eatp-ink); }
.eatp .datagrid-cell-rownumber { color: var(--eatp-subtle); }
.eatp .datagrid-header td.datagrid-header-over { background: var(--eatp-red-50); }
.eatp .datagrid-row { transition: background-color .15s ease; }
.eatp .datagrid-row-alt { background: #fbfbfd; }
.eatp .datagrid-row-over { background: #fff5f5; color: inherit; }
.eatp .datagrid-row-selected { background: var(--eatp-red-50); color: var(--eatp-ink); }
.eatp .datagrid-cell .btn { padding: 2px 9px; font-size: 11.5px; line-height: 18px; transform: none; }
.eatp .datagrid-cell .glyphicons.btn-icon, .eatp .datagrid-cell .glyphicons.btn-small { padding-left: 27px; }
.eatp .datagrid-cell .glyphicons.btn-icon i:before, .eatp .datagrid-cell .glyphicons.btn-small i:before { width: 24px; font-size: 11px; }
.eatp .datagrid-mask { background: rgba(255, 255, 255, .7); }
.eatp .datagrid-mask-msg { height: auto; padding: 12px 16px 12px 40px; border: 0; border-radius: 12px; box-shadow: var(--eatp-shadow); font-weight: 600; }
.eatp .pagination .pagination-num { border: 1px solid var(--eatp-line-2); border-radius: 6px; box-shadow: none; text-align: center; }
.eatp .pagination-page-list { border-color: var(--eatp-line-2); border-radius: 6px; height: auto; }
.eatp .pagination-info { color: var(--eatp-muted); }
.eatp .l-btn-plain:hover { border-color: transparent; background: var(--eatp-red-50); border-radius: 6px; }

.eatp .window { border: 0; border-radius: 18px; background: var(--eatp-surface); background-image: none; box-shadow: var(--eatp-shadow-lg); filter: none; }
.eatp .window .window-header, .eatp .window .panel-header { background: transparent; border: 0; }
.eatp .window .window-body { border-color: var(--eatp-line); border-radius: 0 0 14px 14px; }
.eatp .window-shadow { display: none; }
.eatp .window-mask { background: rgba(17, 20, 28, .5); filter: none; opacity: 1; -webkit-backdrop-filter: blur(2px); backdrop-filter: blur(2px); }
.eatp .dialog-button { padding: 10px 14px; border-color: var(--eatp-line); background: var(--eatp-soft); }
.eatp .dialog-toolbar { border-color: var(--eatp-line); background: var(--eatp-soft); }
.eatp a.l-btn {
	margin-left: 6px; border: 1px solid var(--eatp-line-2); border-radius: 9px;
	background: var(--eatp-surface); background-image: none; color: var(--eatp-ink-2); font-weight: 600; filter: none;
	transition: background-color .15s ease, border-color .15s ease, color .15s ease;
}
.eatp a.l-btn span.l-btn-left { background: none; }
.eatp a.l-btn:hover { border-color: var(--eatp-red-600); background: var(--eatp-red-50); color: var(--eatp-red-600); }
.eatp .dialog-button a.l-btn:first-child { border-color: var(--eatp-red-700); background: var(--eatp-red-600); color: #fff; }
.eatp .dialog-button a.l-btn:first-child:hover { background: var(--eatp-red-700); }
.eatp a.l-btn-plain { border-color: transparent; background: transparent; }

.eatp .tabs-header, .eatp .tabs-tool { background: var(--eatp-surface); border-color: var(--eatp-line); }
.eatp .tabs { border-color: var(--eatp-line); }
.eatp .tabs li a.tabs-inner { border: 0; border-radius: 0; background: transparent; background-image: none; filter: none; color: var(--eatp-muted); font-weight: 600; }
.eatp .tabs li a.tabs-inner:hover { color: var(--eatp-red-600); background: var(--eatp-soft); }
.eatp .tabs li.tabs-selected a.tabs-inner { background: transparent; color: var(--eatp-red-600); font-weight: 700; box-shadow: inset 0 -2px 0 var(--eatp-red-600); }

.eatp .combo-panel, .eatp .menu { border-color: var(--eatp-line); border-radius: 12px; box-shadow: var(--eatp-shadow-lg); }
.eatp .combobox-item { padding: 6px 10px; }
.eatp .combobox-item-hover, .eatp .menu-active { background: var(--eatp-red-50); color: var(--eatp-red-600); border-color: transparent; }
.eatp .combobox-item-selected { background: var(--eatp-red-100); }
.eatp .calendar-header { background: var(--eatp-soft); }
.eatp .calendar-selected { background: var(--eatp-red-600); color: #fff; border-radius: 6px; }

/* ================================================================
   13. Komponen Bootstrap lain
   ================================================================ */
.eatp .nav-tabs { border-bottom: 1px solid var(--eatp-line); }
.eatp .nav-tabs > li > a {
	margin-right: 4px; padding: 10px 14px; border: 0; border-radius: 0;
	border-bottom: 2px solid transparent; color: var(--eatp-muted); font-weight: 600;
	transition: color .15s ease, border-color .15s ease, background-color .15s ease;
}
.eatp .nav-tabs > li > a:hover { background: transparent; border-bottom-color: var(--eatp-line-2); color: var(--eatp-ink); }
.eatp .nav-tabs > .active > a, .eatp .nav-tabs > .active > a:hover {
	border: 0; border-bottom: 2px solid var(--eatp-red-600); background: transparent; color: var(--eatp-red-600); font-weight: 700;
}
.eatp .nav-pills > li > a { border-radius: 999px; font-weight: 600; }
.eatp .nav-pills > .active > a, .eatp .nav-pills > .active > a:hover { background: var(--eatp-red-600); }

.eatp .dropdown-menu { padding: 6px; border: 1px solid var(--eatp-line); border-radius: 14px; box-shadow: var(--eatp-shadow-lg); }
.eatp .dropdown-menu > li > a { border-radius: 9px; padding: 8px 12px; transition: background-color .15s ease, color .15s ease; }
.eatp .dropdown-menu > li > a:hover, .eatp .dropdown-menu > li > a:focus,
.eatp .dropdown-menu > .active > a, .eatp .dropdown-menu > .active > a:hover { background: var(--eatp-red-50); background-image: none; color: var(--eatp-red-600); }
.eatp .dropdown-menu .divider { background-color: var(--eatp-line); border: 0; }
.eatp .open > .dropdown-menu { -webkit-animation: eatp-pop .18s var(--eatp-ease); animation: eatp-pop .18s var(--eatp-ease); }

.eatp .pagination ul { border-radius: 0; box-shadow: none; }
.eatp .pagination ul > li > a, .eatp .pagination ul > li > span {
	margin: 0 3px; min-width: 16px; padding: 5px 10px; border: 1px solid var(--eatp-line);
	border-radius: 9px; background: var(--eatp-surface); color: var(--eatp-ink-2); text-align: center; font-weight: 600;
	transition: background-color .15s ease, color .15s ease, border-color .15s ease;
}
.eatp .pagination ul > li:first-child > a, .eatp .pagination ul > li:last-child > a,
.eatp .pagination ul > li:first-child > span, .eatp .pagination ul > li:last-child > span { border-radius: 9px; border-left-width: 1px; }
.eatp .pagination ul > li > a:hover { background: var(--eatp-red-50); border-color: var(--eatp-red-200); color: var(--eatp-red-600); }
.eatp .pagination ul > .active > a, .eatp .pagination ul > .active > span { background: var(--eatp-red-600); border-color: var(--eatp-red-600); color: #fff; box-shadow: 0 6px 14px -8px rgba(192, 5, 5, .8); }

.eatp .alert { border: 1px solid transparent; border-radius: 12px; padding: 12px 16px; text-shadow: none; box-shadow: none; -webkit-animation: eatp-rise .3s var(--eatp-ease); animation: eatp-rise .3s var(--eatp-ease); }
.eatp .alert, .eatp .alert-warning { background: var(--eatp-amber-bg); border-color: #fbe0bb; color: var(--eatp-amber-ink); }
.eatp .alert-error, .eatp .alert-danger { background: var(--eatp-red-50); border-color: var(--eatp-red-100); color: var(--eatp-red-700); }
.eatp .alert-success { background: var(--eatp-green-bg); border-color: #c7ecd6; color: var(--eatp-green-ink); }
.eatp .alert-info { background: var(--eatp-blue-bg); border-color: #c4e5f5; color: var(--eatp-blue-ink); }
.eatp .alert-primary { background: var(--eatp-red-600); color: #fff; }

.eatp .progress { height: 10px; border-radius: 999px; background: #eceef2; box-shadow: none; }
.eatp .progress .bar, .eatp .progress-primary .bar { background: var(--eatp-red-600); background-image: linear-gradient(90deg, var(--eatp-red-700), var(--eatp-red-400)); box-shadow: none; transition: width .6s var(--eatp-ease); }
.eatp .widget-head.progress { height: auto; border-radius: 0; background: var(--eatp-soft); }
.eatp .widget-head.progress .bar { color: #fff; font-size: 12px; line-height: 26px; height: 26px; }

.eatp .modal { border: 0; border-radius: 20px; box-shadow: var(--eatp-shadow-lg); }
.eatp .modal-header { padding: 16px 20px; border-bottom-color: var(--eatp-line); }
.eatp .modal-header h3, .eatp .modal-title { font-size: 17px; font-weight: 800; }
.eatp .modal-header .close { margin-top: 2px; width: 30px; height: 30px; border-radius: 50%; line-height: 28px; text-align: center; opacity: .5; transition: background-color .15s ease, opacity .15s ease; }
.eatp .modal-header .close:hover { background: var(--eatp-soft-2); opacity: 1; }
.eatp .modal-body { padding: 18px 20px; }
.eatp .modal-footer { padding: 14px 20px; border-top-color: var(--eatp-line); background: var(--eatp-soft); box-shadow: none; border-radius: 0 0 20px 20px; }
/* Opacity hanya diubah pada state .in: Bootstrap menunggu transisi backdrop (0 -> .5) selesai
   sebelum menampilkan modal; bila backdrop sudah gelap sejak awal, modal tidak pernah muncul. */
.eatp .modal-backdrop { background: #11141c; }
.eatp .modal-backdrop.in, .eatp .modal-backdrop.fade.in { opacity: .5; }
.eatp .modal.fade { transition: opacity .25s ease, top .35s var(--eatp-ease); }

.eatp .tooltip-inner { padding: 6px 10px; border-radius: 8px; background: var(--eatp-ink); font-weight: 600; }
.eatp .tooltip.top .tooltip-arrow { border-top-color: var(--eatp-ink); }
.eatp .tooltip.bottom .tooltip-arrow { border-bottom-color: var(--eatp-ink); }
.eatp .popover { border: 1px solid var(--eatp-line); border-radius: 14px; box-shadow: var(--eatp-shadow-lg); }
.eatp .popover-title { border-radius: 14px 14px 0 0; background: var(--eatp-soft); font-weight: 700; }

.eatp .fancybox-skin { border-radius: 18px; box-shadow: var(--eatp-shadow-lg); }
.eatp .fancybox-overlay { background: rgba(17, 20, 28, .72); }
.eatp .blockUI.blockMsg { border-radius: 16px !important; }

/* Notifikasi (coolNotification / notyfy) */
.eatp .notyfy_wrapper { border: 0 !important; border-radius: 18px !important; overflow: hidden; box-shadow: var(--eatp-shadow-lg) !important; }
.eatp .notyfy_message { padding: 18px 22px !important; font-family: var(--eatp-font); }
.eatp .notyfy_buttons { padding: 10px 16px !important; border-top: 1px solid var(--eatp-line) !important; background: var(--eatp-soft) !important; }
.eatp .notyfy_error .notyfy_message { border-top: 4px solid #dc2626; }
.eatp .notyfy_success .notyfy_message { border-top: 4px solid var(--eatp-green); }
.eatp .notyfy_warning .notyfy_message { border-top: 4px solid var(--eatp-amber); }
.eatp .notyfy_information .notyfy_message, .eatp .notyfy_alert .notyfy_message { border-top: 4px solid var(--eatp-blue); }
.eatp .notyfy_modal { background: rgba(17, 20, 28, .5) !important; }
.eatp #gritter-notice-wrapper .gritter-item-wrapper { border-radius: 14px; overflow: hidden; box-shadow: var(--eatp-shadow-lg); }

/* Wizard */
.eatp .wizard .wizard-head { border-color: var(--eatp-line); border-radius: var(--eatp-radius) var(--eatp-radius) 0 0; background: var(--eatp-surface); overflow: hidden; }
.eatp .wizard .wizard-head ul { margin: 0; padding: 0; list-style: none; }
.eatp .wizard .wizard-head ul li.active a, .eatp .wizard .wizard-head ul li.active { color: var(--eatp-red-600); font-weight: 700; }
.eatp .bwizard-steps li { background: var(--eatp-soft); color: var(--eatp-muted); font-weight: 600; }
.eatp .bwizard-steps li:after { border-left-color: var(--eatp-soft); }
.eatp .bwizard-steps li.active { background: var(--eatp-red-600); color: #fff; }
.eatp .bwizard-steps li.active:after { border-left-color: var(--eatp-red-600); }
.eatp .bwizard-steps li.active a { color: #fff; }
.eatp .bwizard-steps .active .label { background-color: var(--eatp-red-800); color: #fff; }

/* ================================================================
   14. Animasi & efek
   ================================================================ */
@-webkit-keyframes eatp-rise { from { opacity: 0; -webkit-transform: translateY(12px); } to { opacity: 1; -webkit-transform: none; } }
@keyframes eatp-rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
@-webkit-keyframes eatp-pop { from { opacity: 0; -webkit-transform: translateY(-4px) scale(.98); } to { opacity: 1; -webkit-transform: none; } }
@keyframes eatp-pop { from { opacity: 0; transform: translateY(-4px) scale(.98); } to { opacity: 1; transform: none; } }
@keyframes eatp-fade { from { opacity: 0; } to { opacity: 1; } }
@keyframes eatp-slide { from { transform: translateX(-100%); } to { transform: none; } }

/* Fill-mode "backwards": setelah animasi selesai tidak ada transform tersisa,
   sehingga elemen position:fixed (modal) di dalamnya tetap benar posisinya. */
.eatp #content > .breadcrumb, .eatp #content > .innerLR, .eatp #content > .widget,
.eatp #content > h3, .eatp #content > .heading-buttons { -webkit-animation: eatp-rise .5s var(--eatp-ease) backwards; animation: eatp-rise .5s var(--eatp-ease) backwards; }
.eatp #content > .innerLR { -webkit-animation-delay: .06s; animation-delay: .06s; }
.eatp .innerLR > .widget:nth-of-type(1) { animation: eatp-rise .5s var(--eatp-ease) .1s backwards; }
.eatp .innerLR > .widget:nth-of-type(2) { animation: eatp-rise .5s var(--eatp-ease) .16s backwards; }
.eatp .innerLR > .widget:nth-of-type(3) { animation: eatp-rise .5s var(--eatp-ease) .22s backwards; }
.eatp .innerLR > .widget:nth-of-type(4) { animation: eatp-rise .5s var(--eatp-ease) .28s backwards; }

#eatp-progress {
	position: fixed; top: 0; left: 0; z-index: 100000; width: 0; height: 3px;
	background: linear-gradient(90deg, #ff8a8a, #fff 50%, #ffb3b3);
	box-shadow: 0 0 12px rgba(255, 255, 255, .7);
	opacity: 0; pointer-events: none;
	transition: width .4s var(--eatp-ease), opacity .3s ease;
}
#eatp-top {
	position: fixed; right: 22px; bottom: 22px; z-index: 1020;
	width: 44px; height: 44px; border: 0; border-radius: 14px;
	background: var(--eatp-red-600); background-image: linear-gradient(160deg, var(--eatp-red-500), var(--eatp-red-700));
	color: #fff; font-size: 18px; line-height: 44px; text-align: center; cursor: pointer;
	box-shadow: 0 14px 28px -12px rgba(192, 5, 5, .8);
	opacity: 0; visibility: hidden; transform: translateY(12px);
	transition: opacity .25s ease, transform .25s var(--eatp-ease), visibility 0s linear .25s;
}
#eatp-top.is-visible { opacity: 1; visibility: visible; transform: none; transition: opacity .25s ease, transform .25s var(--eatp-ease); }
#eatp-top:hover { transform: translateY(-2px); }

/* ================================================================
   15. Responsif
   ================================================================ */
@media (max-width: 979px) {
	.eatp .innerLR { padding: 0 16px; }
	.eatp .form-horizontal .control-label { width: auto; float: none; text-align: left; padding-top: 0; margin-bottom: 6px; }
	.eatp .form-horizontal .controls { margin-left: 0; }
}
@media (max-width: 767px) {
	.eatp .navbar.main .topnav > li > a .text { display: none; }
	.eatp #menu {
		position: fixed; top: var(--eatp-nav-h); bottom: 0; left: 0; z-index: 1040;
		width: 284px; max-width: 86vw; min-height: 0; margin: 0; padding-bottom: 24px;
		overflow-y: auto; -webkit-overflow-scrolling: touch;
		box-shadow: var(--eatp-shadow-lg); animation: eatp-slide .28s var(--eatp-ease);
	}
	.eatp #content { margin: 0 !important; }
	.eatp .innerLR { padding: 0 10px; }
	.eatp .breadcrumb { padding: 0 12px; }
	.eatp .heading-buttons { padding: 14px; }
	.eatp .widget .widget-body { padding: 14px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
	.eatp .modal { left: 10px; right: 10px; width: auto; margin: 0; }
	#eatp-top { right: 14px; bottom: 14px; }
}
#eatp-overlay {
	display: none; position: fixed; top: var(--eatp-nav-h); right: 0; bottom: 0; left: 0; z-index: 1035;
	background: rgba(17, 20, 28, .5); animation: eatp-fade .2s ease;
}
body.eatp-menu-open #eatp-overlay { display: block; }

/* Halaman tanpa sidebar (lupa password, allow login) */
.eatp-auth .innerLR { max-width: 980px; margin: 32px auto; }
.eatp-auth .navbar.main .appbrand { width: auto; padding: 0 24px; font-size: 17px; font-weight: 800; letter-spacing: .04em; }

@media (prefers-reduced-motion: reduce) {
	/* Hanya animasi dekoratif yang dimatikan. Transisi TIDAK dimatikan: modal & accordion
	   Bootstrap menunggu event transitionend dan akan macet tanpa transisi. */
	.eatp *, .eatp *::before, .eatp *::after, #eatp-top { animation: none !important; }
	.eatp .btn:hover, .eatp .btn-action:hover, .eatp .widget-stats:hover, #eatp-top:hover { transform: none !important; }
}
@media print {
	.eatp .navbar.main, .eatp #menu, #eatp-progress, #eatp-top, #eatp-overlay { display: none !important; }
	.eatp #content { margin: 0 !important; }
	.eatp .widget { box-shadow: none; }
}
</style>

<script>
(function () {
'use strict';

// Sebagian layout memuat jQuery di akhir halaman; tunggu sampai tersedia
function eatpInit($) {

	/* ---------- Progress bar saat AJAX / pindah halaman ---------- */
	var bar = null, active = 0, hideTimer = null;

	function getBar() {
		if (!bar) { bar = $('<div id="eatp-progress" aria-hidden="true"></div>'); }
		if (!bar[0].parentNode && document.body) { bar.appendTo(document.body); }
		return bar;
	}
	function start() {
		var b = getBar();
		clearTimeout(hideTimer);
		if (active++ === 0) {
			b.css({ width: '0%', opacity: 1 });
			if (b[0]) { b[0].offsetWidth; }
			b.css('width', '72%');
		}
	}
	function done() {
		active = Math.max(0, active - 1);
		if (active) { return; }
		var b = getBar();
		b.css('width', '100%');
		hideTimer = setTimeout(function () {
			b.css('opacity', 0);
			setTimeout(function () { if (!active) { b.css('width', '0%'); } }, 320);
		}, 220);
	}

	$(document).ajaxSend(start).ajaxComplete(done);

	$(document).on('click', 'a[href]', function (e) {
		if (e.which > 1 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) { return; }
		var a = this, href = a.getAttribute('href') || '';
		if (!href || href.charAt(0) === '#' || /^javascript:/i.test(href) || a.target === '_blank'
			|| a.getAttribute('download') !== null || a.getAttribute('data-toggle')
			|| (a.hostname && a.hostname !== window.location.hostname)) { return; }
		setTimeout(function () {
			if (!e.isDefaultPrevented()) {
				start();
				setTimeout(done, 8000);   // cadangan bila tautan ternyata unduhan
			}
		}, 0);
	});
	$(window).on('pageshow', function () { active = 1; done(); });

	$(function () {
		/* ---------- Tombol kembali ke atas ---------- */
		var $top = $('<button type="button" id="eatp-top" aria-label="Kembali ke atas" title="Kembali ke atas">&#8593;</button>').appendTo('body');
		var ticking = false;
		function onScroll() {
			ticking = false;
			$top.toggleClass('is-visible', (window.pageYOffset || document.documentElement.scrollTop) > 480);
		}
		$(window).on('scroll', function () {
			if (!ticking) { ticking = true; (window.requestAnimationFrame || setTimeout)(onScroll); }
		});
		$top.on('click', function () { $('html, body').animate({ scrollTop: 0 }, 350); });

		/* ---------- Sidebar geser di layar kecil ---------- */
		if (!$('#menu').length) { return; }
		var $overlay = $('<div id="eatp-overlay" aria-hidden="true"></div>').appendTo('body');
		var mq = window.matchMedia ? window.matchMedia('(max-width: 767px)') : null;

		function isPhone() { return mq ? mq.matches : $(window).width() < 768; }
		function sync() { $('body').toggleClass('eatp-menu-open', isPhone() && $('#menu').is(':visible')); }
		function toggleMenu() { $('.navbar.main .btn-navbar').first().trigger('click'); }

		$('.navbar.main .btn-navbar').on('click', function () { setTimeout(sync, 0); });
		$overlay.on('click', toggleMenu);
		$(document).on('keyup', function (e) {
			if (e.keyCode === 27 && $('body').hasClass('eatp-menu-open')) { toggleMenu(); }
		});
		$(window).on('resize', sync);
		sync();
	});
}

if (window.jQuery) {
	eatpInit(window.jQuery);
} else if (window.addEventListener) {
	window.addEventListener('DOMContentLoaded', function () {
		if (window.jQuery) { eatpInit(window.jQuery); }
	});
}
})();
</script>
