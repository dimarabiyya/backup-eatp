<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>E-ATP</title>
	<!-- eatp-no-theme: halaman login punya desain sendiri, jangan disisipi lapisan tema aplikasi (core/MY_Output.php) -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" />

	<!-- Font (fallback ke font sistem jika server tidak punya akses internet) -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<!-- Aset lama (dipertahankan) -->
	<link type="text/css" href="<?php echo base_url() ?>assets/css/demo.css" rel="stylesheet" media="screen" />
	<link type="text/css" href="<?php echo base_url() ?>assets/css/basic.css" rel="stylesheet" media="screen" />
	<!--[if lt IE 7]>
	<link type="text/css" href="http://data.smartfren.com//popup/css/basic_ie.css" rel="stylesheet" media="screen" />
	<![endif]-->
	<link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.css" />
	<link rel="stylesheet" href="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/themes/default.css" />

	<style type="text/css">
	/* ============================================================
	   E-ATP Login — design tokens
	   ============================================================ */
	/* Merah utama sama dengan dashboard (#c00505), lihat views/template/partials/eatp_theme.php */
	:root {
		--ink: #2A0404;
		--ink-soft: #3A0808;
		--brand: #C00505;
		--brand-strong: #8E0404;
		--brand-glow: #FA3131;
		--canvas: #F4F5F8;
		--surface: #FFFFFF;
		--line: #DCDFE6;
		--text: #1F2430;
		--muted: #6B7280;
		--danger: #B42318;
		--danger-bg: #FDECEA;
		--warn: #8A5A00;
		--warn-bg: #FFF4DB;
		--focus: 0 0 0 3px rgba(192, 5, 5, .22);
		--radius-field: 10px;
		--ease: cubic-bezier(.2, .8, .2, 1);
		--font: "Plus Jakarta Sans", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
	}

	@media (prefers-color-scheme: dark) {
		:root {
			--canvas: #14161C;
			--surface: #1D2028;
			--line: #333844;
			--text: #EEF0F4;
			--muted: #A3A9B6;
			--brand: #FF4D4D;
			--brand-strong: #FF7A7A;
			--danger: #FF8A80;
			--danger-bg: #3A1D22;
			--warn: #F5C46B;
			--warn-bg: #3A2F14;
		}
	}

	* , *::before, *::after { box-sizing: border-box; }

	html, html body {
		margin: 0;
		padding: 0;
		height: 100%;
	}

	html body.atp {
		background: var(--canvas);
		color: var(--text);
		font-family: var(--font);
		font-size: 15px;
		line-height: 1.55;
		-webkit-font-smoothing: antialiased;
	}

	.atp a { color: var(--brand); text-decoration: none; font-weight: 600; }
	.atp a:hover { text-decoration: underline; }
	.atp a:focus-visible,
	.atp button:focus-visible { outline: none; box-shadow: var(--focus); border-radius: 6px; }

	/* ============================================================
	   Layout
	   ============================================================ */
	.atp-shell {
		display: grid;
		grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
		min-height: 100vh;
	}

	/* ---------- Panel visual (kiri) ---------- */
	.atp-visual {
		position: relative;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		gap: 40px;
		padding: 48px 56px;
		background: var(--ink);
		background-image: linear-gradient(140deg, #1F0303 0%, #5D0202 45%, #8E0404 75%, #C00505 100%);
		color: #fff;
		overflow: hidden;
	}

	/* Cahaya lembut yang bergerak pelan di panel kiri */
	.atp-visual::before,
	.atp-visual::after {
		content: "";
		position: absolute;
		width: 520px;
		height: 520px;
		border-radius: 50%;
		filter: blur(70px);
		opacity: .45;
		pointer-events: none;
		animation: atp-float 16s ease-in-out infinite alternate;
	}
	.atp-visual::before { top: -180px; right: -140px; background: var(--brand-glow); }
	.atp-visual::after { bottom: -220px; left: -160px; background: #FF7A45; opacity: .22; animation-duration: 20s; }
	.atp-visual > * { position: relative; z-index: 1; }

	@keyframes atp-float {
		from { transform: translate(0, 0) scale(1); }
		to   { transform: translate(-50px, 40px) scale(1.12); }
	}
	@keyframes atp-rise {
		from { opacity: 0; transform: translateY(14px); }
		to   { opacity: 1; transform: none; }
	}
	@keyframes atp-shake {
		10%, 90% { transform: translateX(-1px); }
		20%, 80% { transform: translateX(2px); }
		30%, 50%, 70% { transform: translateX(-4px); }
		40%, 60% { transform: translateX(4px); }
	}

	.atp-brand {
		display: flex;
		align-items: baseline;
		gap: 14px;
	}

	.atp-wordmark {
		margin: 0;
		font-size: clamp(40px, 5vw, 64px);
		font-weight: 800;
		letter-spacing: -0.035em;
		line-height: 1;
	}

	.atp-brand small {
		font-size: 15px;
		font-weight: 500;
		color: rgba(255, 255, 255, .62);
	}

	.atp-carousel {
		position: relative;
		width: 100%;
		max-width: 640px;
		margin: 0 auto;
	}

	.atp-slides {
		position: relative;
		width: 100%;
		aspect-ratio: 11 / 6;          /* ukuran asli slide 550x300 */
		border-radius: 18px;
		overflow: hidden;
		background: var(--ink-soft);
		box-shadow: 0 30px 60px -30px rgba(0, 0, 0, .7);
	}

	.atp-slide {
		position: absolute;
		inset: 0;
		opacity: 0;
		visibility: hidden;
		transition: opacity .7s ease, visibility 0s linear .7s;
	}

	.atp-slide.is-active {
		opacity: 1;
		visibility: visible;
		transition: opacity .7s ease, visibility 0s;
	}

	.atp-slide a,
	.atp-slide img {
		display: block;
		width: 100%;
		height: 100%;
	}

	.atp-slide img { object-fit: contain; }

	/* Indikator slide berbentuk bar sinyal */
	.atp-signal {
		display: flex;
		align-items: flex-end;
		gap: 6px;
		margin-top: 22px;
		height: 24px;
	}

	.atp-signal button {
		width: 8px;
		padding: 0;
		border: 0;
		border-radius: 2px;
		background: rgba(255, 255, 255, .22);
		cursor: pointer;
		transition: background-color .3s ease;
	}

	.atp-signal button:nth-child(1) { height: 8px; }
	.atp-signal button:nth-child(2) { height: 13px; }
	.atp-signal button:nth-child(3) { height: 18px; }
	.atp-signal button:nth-child(4) { height: 24px; }
	.atp-signal button:nth-child(n+5) { height: 24px; }

	.atp-signal button.is-on { background: var(--brand); }
	.atp-signal button:hover { background: rgba(255, 255, 255, .5); }
	.atp-signal button.is-on:hover { background: var(--brand-strong); }

	.atp-visual-foot {
		font-size: 13px;
		color: rgba(255, 255, 255, .55);
		max-width: 46ch;
	}

	.atp-visual-foot a { color: #fff; font-weight: 600; }

	/* ---------- Panel form (kanan) ---------- */
	.atp-main {
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding: 48px 32px;
	}

	.atp-card {
		width: 100%;
		max-width: 380px;
		margin: 0 auto;
	}

	/* Elemen form muncul bertahap */
	.atp-card > * { animation: atp-rise .6s var(--ease) backwards; }
	.atp-card > *:nth-child(1) { animation-delay: .05s; }
	.atp-card > *:nth-child(2) { animation-delay: .12s; }
	.atp-card > *:nth-child(3) { animation-delay: .18s; }
	.atp-card > *:nth-child(4) { animation-delay: .24s; }
	.atp-card > *:nth-child(5) { animation-delay: .32s; }
	.atp-brand, .atp-carousel, .atp-visual-foot { animation: atp-rise .8s var(--ease) backwards; }
	.atp-carousel { animation-delay: .15s; }
	.atp-visual-foot { animation-delay: .3s; }

	.atp-card h2 {
		margin: 0 0 6px;
		font-size: 28px;
		font-weight: 700;
		letter-spacing: -0.02em;
		line-height: 1.2;
	}

	.atp-card .atp-lead {
		margin: 0 0 28px;
		color: var(--muted);
	}

	/* Pesan inline */
	.atp-alert {
		display: none;
		margin-bottom: 20px;
		padding: 12px 14px;
		border-radius: var(--radius-field);
		font-size: 14px;
		line-height: 1.45;
	}
	.atp-alert.is-visible { display: block; animation: atp-rise .3s var(--ease); }
	.atp-alert.is-visible.is-error { animation: atp-shake .45s ease; }
	.atp-alert.is-error { background: var(--danger-bg); color: var(--danger); }
	.atp-alert.is-warning { background: var(--warn-bg); color: var(--warn); }

	/* Field */
	.atp-field { margin-bottom: 18px; }

	.atp-field label {
		display: block;
		margin-bottom: 7px;
		font-size: 14px;
		font-weight: 600;
	}

	.atp-input-wrap { position: relative; }

	.atp-input {
		display: block;
		width: 100%;
		height: 48px;
		padding: 0 14px;
		border: 1.5px solid var(--line);
		border-radius: var(--radius-field);
		background: var(--surface);
		color: var(--text);
		font: inherit;
		font-size: 15px;
		transition: border-color .15s ease, box-shadow .15s ease;
		-webkit-appearance: none;
		appearance: none;
	}

	.atp-input::placeholder { color: var(--muted); opacity: .7; }
	.atp-input:hover { border-color: #B9B5CB; }
	.atp-input:focus { outline: none; border-color: var(--brand); box-shadow: var(--focus); }
	.atp-input.is-invalid { border-color: var(--danger); }

	.atp-input--lower { text-transform: lowercase; }
	.atp-input--pass { padding-right: 76px; }

	.atp-toggle-pass {
		position: absolute;
		top: 50%;
		right: 6px;
		transform: translateY(-50%);
		height: 36px;
		padding: 0 12px;
		border: 0;
		border-radius: 7px;
		background: transparent;
		color: var(--muted);
		font: inherit;
		font-size: 13px;
		font-weight: 600;
		cursor: pointer;
	}
	.atp-toggle-pass:hover { color: var(--text); background: var(--canvas); }

	.atp-hint {
		display: none;
		margin-top: 6px;
		font-size: 13px;
		color: var(--warn);
	}
	.atp-hint.is-visible { display: block; }

	/* Captcha */
	.atp-captcha {
		display: flex;
		align-items: stretch;
		gap: 8px;
		margin-bottom: 10px;
	}

	.atp-captcha-img {
		flex: 1 1 auto;
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 56px;
		padding: 4px;
		border: 1.5px dashed var(--line);
		border-radius: var(--radius-field);
		background: #fff;
		overflow: hidden;
	}

	.atp-captcha-img img {
		display: block;
		max-width: 100%;
		height: auto;
	}

	.atp-refresh {
		flex: 0 0 56px;
		display: flex;
		align-items: center;
		justify-content: center;
		border: 1.5px solid var(--line);
		border-radius: var(--radius-field);
		background: var(--surface);
		color: var(--muted);
		cursor: pointer;
		transition: color .15s ease, border-color .15s ease;
	}
	.atp-refresh:hover { color: var(--brand); border-color: var(--brand); }
	.atp-refresh svg { width: 20px; height: 20px; }
	.atp-refresh.is-spinning svg { animation: atp-spin .6s ease; }

	@keyframes atp-spin { to { transform: rotate(360deg); } }

	/* Tombol */
	.atp-submit {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 10px;
		width: 100%;
		height: 50px;
		margin-top: 26px;
		border: 0;
		border-radius: var(--radius-field);
		background: var(--brand);
		background-image: linear-gradient(180deg, #E02424, var(--brand));
		color: #fff;
		font: inherit;
		font-size: 16px;
		font-weight: 700;
		cursor: pointer;
		box-shadow: 0 10px 22px -12px rgba(192, 5, 5, .8);
		transition: background-color .15s ease, transform .2s var(--ease), box-shadow .2s ease;
	}
	.atp-submit:hover { background: var(--brand-strong); background-image: linear-gradient(180deg, var(--brand), var(--brand-strong)); transform: translateY(-1px); box-shadow: 0 14px 26px -12px rgba(192, 5, 5, .85); }
	.atp-submit:active { transform: translateY(1px); box-shadow: 0 6px 14px -10px rgba(192, 5, 5, .8); }
	.atp-submit:focus-visible { outline: none; box-shadow: var(--focus); }
	.atp-submit[disabled] { opacity: .75; cursor: progress; }

	.atp-spinner {
		display: none;
		width: 18px;
		height: 18px;
		border: 2.5px solid rgba(255, 255, 255, .35);
		border-top-color: #fff;
		border-radius: 50%;
		animation: atp-spin .8s linear infinite;
	}
	.atp-submit.is-loading .atp-spinner { display: inline-block; }

	.atp-links {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		gap: 10px 16px;
		margin-top: 20px;
		font-size: 14px;
	}

	.atp-footnote {
		max-width: 380px;
		margin: 40px auto 0;
		padding-top: 18px;
		border-top: 1px solid var(--line);
		font-size: 12.5px;
		color: var(--muted);
		line-height: 1.6;
	}

	.visually-hidden {
		position: absolute !important;
		width: 1px; height: 1px;
		margin: -1px; padding: 0;
		overflow: hidden;
		clip: rect(0 0 0 0);
		white-space: nowrap;
		border: 0;
	}

	/* ============================================================
	   Responsive
	   ============================================================ */
	@media (max-width: 1100px) {
		.atp-visual { padding: 40px; }
	}

	@media (max-width: 900px) {
		.atp-shell {
			grid-template-columns: 1fr;
			grid-template-rows: auto 1fr;
		}
		.atp-visual {
			padding: 22px 24px;
			gap: 0;
		}
		.atp-wordmark { font-size: 30px; }
		.atp-carousel,
		.atp-visual-foot { display: none; }
		.atp-main {
			justify-content: flex-start;
			padding: 36px 22px 32px;
		}
	}

	@media (max-width: 380px) {
		.atp-card h2 { font-size: 24px; }
		.atp-captcha-img { min-height: 48px; }
	}

	@media (prefers-reduced-motion: reduce) {
		.atp-visual::before, .atp-visual::after,
		.atp-card > *, .atp-brand, .atp-carousel, .atp-visual-foot,
		.atp-alert.is-visible { animation: none; }
		.atp-slide,
		.atp-slide.is-active { transition: none; }
		.atp-refresh.is-spinning svg { animation: none; }
		.atp-spinner { animation-duration: 1.6s; }
	}
	</style>
</head>
<body class="atp">

<div class="atp-shell">

	<!-- ================= PANEL VISUAL ================= -->
	<aside class="atp-visual" aria-label="Informasi E-ATP">
		<div class="atp-brand">
			<h1 class="atp-wordmark">E-ATP</h1>
		</div>

		<div class="atp-carousel" id="atpCarousel" aria-roledescription="carousel" aria-label="Pengumuman">
			<div class="atp-slides" id="atpSlides">
				<div class="atp-slide is-active" aria-hidden="false">
					<a href="#" target="_blank" tabindex="-1">
						<img src="<?php echo base_url() ?>assets/images/BannerTrust.png" alt="Banner Trust">
					</a>
				</div>
				<div class="atp-slide" aria-hidden="true">
					<a href="#" target="_blank" tabindex="-1">
						<img src="<?php echo base_url() ?>assets/images/BannerPP.png" alt="Banner PP" loading="lazy">
					</a>
				</div>
			</div>
			<!-- tombol indikator dibuat otomatis oleh script sesuai jumlah slide -->
			<div class="atp-signal" id="atpSignal" role="group" aria-label="Pilih slide"></div>
		</div>

		<p class="atp-visual-foot">
			Browser yang disarankan:
			<a href="<?php echo base_url() ?>software/chrome.exe" target="_blank" title="Download">Google Chrome</a>,
			<a href="<?php echo base_url() ?>software/firefox.exe" target="_blank" title="Download">Mozilla Firefox</a>, atau
			<a href="<?php echo base_url() ?>software/ie11.exe" target="_blank" title="Download">Internet Explorer 11</a>.
		</p>
	</aside>

	<!-- ================= PANEL FORM ================= -->
	<main class="atp-main">
		<div class="atp-card">
			<h2>Masuk ke E-ATP</h2>
			<p class="atp-lead">Gunakan User ID dan password akun Anda.</p>

			<div class="atp-alert" id="alert-msg" role="alert" aria-live="assertive"></div>

			<form class="form-signin" id="loginForm" method="post" novalidate>
				<div class="atp-field">
					<label for="user_name">User ID</label>
					<!-- Add by Hadi 2018-01-25: tampilan lowercase dipertahankan -->
					<input type="text" class="atp-input atp-input--lower" id="user_name" name="username"
						tabindex="1" autocomplete="username" autocapitalize="none" autocorrect="off"
						spellcheck="false" autofocus required>
				</div>

				<div class="atp-field">
					<label for="user_pass">Password</label>
					<div class="atp-input-wrap">
						<input type="password" class="atp-input atp-input--pass" id="user_pass" name="password"
							tabindex="2" autocomplete="current-password" required>
						<button type="button" class="atp-toggle-pass" id="togglePass" aria-controls="user_pass" aria-pressed="false">Lihat</button>
					</div>
					<div class="atp-hint" id="capsHint">Caps Lock sedang aktif.</div>
				</div>

				<div class="atp-field">
					<label for="ractha">Kode keamanan</label>
					<div class="atp-captcha" id="captcha">
						<div class="atp-captcha-img">
							<img src="<?php echo site_url('/login/capcay') ?>" id="ratcha_image" alt="Kode keamanan (CAPTCHA)" />
						</div>
						<button type="button" class="atp-refresh" id="refreshCaptcha" title="Ganti kode" aria-label="Ganti kode keamanan">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M21 12a9 9 0 1 1-2.64-6.36"/>
								<polyline points="21 3 21 9 15 9"/>
							</svg>
						</button>
					</div>
					<input type="text" class="atp-input" id="ractha" name="ractha" value="" tabindex="3"
						autocomplete="off" autocapitalize="none" spellcheck="false"
						placeholder="Ketik kode di atas" required>
				</div>

				<div id="wait_login"></div>
				<button type="submit" name="login" value="Login" class="atp-submit" id="btnLogin" tabindex="4">
					<span class="atp-spinner" aria-hidden="true"></span>
					<span class="atp-submit-label">Masuk</span>
				</button>

				<div class="atp-links">
					<a href="<?php echo site_url('login/AllowMeToLogin') ?>">Allow Me To Login</a>
					<a href="<?php echo site_url('login/ForgotPassword') ?>">Lupa password?</a>
				</div>
			</form>

			<p class="atp-footnote">
				Demi keamanan, sesi akan berakhir otomatis setelah 5 menit tanpa aktivitas.
			</p>
		</div>
	</main>
</div>

<!-- ================= SCRIPT ================= -->
<script src="<?php echo base_url() ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url('assets/js/hehehe.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.simplemodal.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>file/template/smashing/theme/scripts/notyfy/jquery.notyfy.js"></script>
<script src="<?php echo base_url('/assets/js/jquery.blockUI.js') ?>"></script>

<script>
var baseURL = '<?php echo base_url(); ?>';

(function ($) {
	'use strict';

	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* ---------- Notifikasi: pakai coolNotification (hehehe.js) jika ada ---------- */
	var $alert = $('#alert-msg');

	function showInline(message, type) {
		$alert
			.removeClass('is-error is-warning')
			.addClass('is-visible ' + (type === 'warning' ? 'is-warning' : 'is-error'))
			.html(message);
	}

	function hideInline() {
		$alert.removeClass('is-visible is-error is-warning').empty();
	}

	function notify(message, type) {
		// pak bayu ingin error'a ada di middle center. klo pake alert browser yg berbeda , beda posisi juga
		if (typeof window.coolNotification === 'function') {
			window.coolNotification(message, type);
		} else {
			showInline(message, type);
		}
	}

	function blockShow(text) {
		if (typeof window.block !== 'undefined' && window.block && typeof window.block.show === 'function') {
			window.block.show(text);
		}
	}

	function blockClose() {
		if (typeof window.block !== 'undefined' && window.block && typeof window.block.close === 'function') {
			window.block.close();
		}
	}

	/* ---------- Carousel (fade) ---------- */
	var $slides = $('#atpSlides .atp-slide');
	var $signal = $('#atpSignal');
	var current = 0;
	var timer = null;
	var DELAY = 5000;

	$slides.each(function (i) {
		$('<button type="button"></button>')
			.attr('aria-label', 'Tampilkan slide ' + (i + 1))
			.attr('data-index', i)
			.appendTo($signal);
	});
	var $bars = $signal.children('button');

	function goTo(n) {
		if (!$slides.length) { return; }
		current = (n + $slides.length) % $slides.length;

		$slides.removeClass('is-active').attr('aria-hidden', 'true');
		$slides.eq(current).addClass('is-active').attr('aria-hidden', 'false');

		$bars.each(function (i) {
			$(this)
				.toggleClass('is-on', i <= current)
				.attr('aria-current', i === current ? 'true' : 'false');
		});
	}

	function stop() {
		if (timer) { clearInterval(timer); timer = null; }
	}

	function play() {
		stop();
		if (reduceMotion || $slides.length < 2) { return; }
		timer = setInterval(function () { goTo(current + 1); }, DELAY);
	}

	$bars.click(function () {
		goTo(parseInt($(this).attr('data-index'), 10));
		play();
	});

	$('#atpCarousel')
		.mouseenter(stop)
		.mouseleave(play)
		.bind('focusin', stop)
		.bind('focusout', play);

	if (document.addEventListener) {
		document.addEventListener('visibilitychange', function () {
			if (document.hidden) { stop(); } else { play(); }
		});
	}

	goTo(0);
	play();

	/* ---------- Tampilkan / sembunyikan password ---------- */
	var passEl = document.getElementById('user_pass');

	$('#togglePass').click(function () {
		var show = passEl.type === 'password';
		passEl.type = show ? 'text' : 'password';
		$(this).text(show ? 'Sembunyikan' : 'Lihat').attr('aria-pressed', show ? 'true' : 'false');
		passEl.focus();
	});

	/* ---------- Peringatan Caps Lock ---------- */
	$(passEl).bind('keyup keydown', function (e) {
		var ev = e.originalEvent || e;
		if (ev && typeof ev.getModifierState === 'function') {
			$('#capsHint').toggleClass('is-visible', ev.getModifierState('CapsLock'));
		}
	}).bind('blur', function () {
		$('#capsHint').removeClass('is-visible');
	});

	/* ---------- Captcha ---------- */
	function refreshCaptcha() {
		var random = new Date().getTime();
		$('#ratcha_image').attr('src', '<?php echo site_url('login/capcay') ?>?random=' + random);
		$('#ractha').val('');

		var $btn = $('#refreshCaptcha').removeClass('is-spinning');
		setTimeout(function () { $btn.addClass('is-spinning'); }, 10);
	}

	$('#refreshCaptcha').click(function () {
		refreshCaptcha();
		$('#ractha').focus();
	});

	/* ---------- Validasi & submit login (alur AJAX lama dipertahankan) ---------- */
	var $form = $('#loginForm');
	var $btnLogin = $('#btnLogin');

	function setLoading(isLoading) {
		$btnLogin.prop ? $btnLogin.prop('disabled', isLoading) : $btnLogin.attr('disabled', isLoading);
		$btnLogin.toggleClass('is-loading', isLoading);
		$btnLogin.find('.atp-submit-label').text(isLoading ? 'Memproses...' : 'Masuk');
	}

	function validate() {
		var fields = [
			{ el: $('#user_name'), msg: 'Isi User ID Anda.' },
			{ el: $('#user_pass'), msg: 'Isi password Anda.' },
			{ el: $('#ractha'),    msg: 'Isi kode keamanan sesuai gambar.' }
		];
		var i, f;

		$form.find('.atp-input').removeClass('is-invalid');

		for (i = 0; i < fields.length; i++) {
			f = fields[i];
			if ($.trim(f.el.val()) === '') {
				f.el.addClass('is-invalid').focus();
				showInline(f.msg, 'error');
				return false;
			}
		}
		return true;
	}

	$form.find('.atp-input').bind('input keyup', function () {
		if ($.trim($(this).val()) !== '') { $(this).removeClass('is-invalid'); }
	});

	$form.submit(function (e) {
		if (e && e.preventDefault) { e.preventDefault(); }
		hideInline();

		if (!validate()) { return false; }

		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('/login/proses_login') ?>',
			data: $form.serialize(),
			dataType: 'json',
			beforeSend: function () {
				setLoading(true);
				blockShow('Processing');
			},
			error: function (jqXHR, textStatus, errorThrown) {
				notify('Login Failed. <br>' + String(textStatus).toUpperCase() + ' : ' + errorThrown, 'error');
				refreshCaptcha();
			},
			success: function (data) {
				if (data && data.status == 'OK') {
					$btnLogin.find('.atp-submit-label').text('Mengalihkan...');
					window.location.replace(data.hasil);
				} else {
					notify(data && data.hasil ? data.hasil : 'Login gagal. Periksa kembali data Anda.', 'error');
					refreshCaptcha();
					$('#ractha').focus();
				}
			},
			complete: function (jqXHR) {
				blockClose();
				// tetap loading jika sedang redirect
				var ok = false;
				try { ok = $.parseJSON(jqXHR.responseText).status == 'OK'; } catch (err) {}
				if (!ok) { setLoading(false); }
			}
		});

		return false;
	});

})(jQuery);
</script>

<?php if ($session_expired == true) { ?>
<script>
$(function () {
	if ($.unblockUI) { $.unblockUI(); }

	var message = "For security reason, you are automatically logged out by System because there's no activity detected on System (idle) for 5 minutes.Please re-login if you still want to work on SiMS.";

	if (typeof coolNotification === 'function') {
		coolNotification(message, 'warning');
	} else {
		$('#alert-msg').addClass('is-visible is-warning').text(message);
	}
});
</script>
<?php } ?>

<!-- ================= FUNGSI LAMA =================
     Tidak dipakai langsung oleh halaman login ini, tetapi dipertahankan
     agar tidak memutus pemanggilan dari script/halaman lain. -->
<script type="text/javascript">
var xmlhttp;

function showUser(str) {
	xmlhttp = GetXmlHttpObject();
	if (xmlhttp == null) {
		alert("Browser does not support HTTP Request");
		return;
	}
	if (str.length == 0) {
		str = "2";
	}
	var url = "http://data.smartfren.com/index.php/new_controller/ajax_subs/get";
	url = url + str;
	xmlhttp.onreadystatechange = stateChanged;
	xmlhttp.open("GET", url, true);
	xmlhttp.send(null);
}

function stateChanged() {
	if (xmlhttp.readyState == 4) {
		var el = document.getElementById("txtHint");
		if (el) { el.innerHTML = xmlhttp.responseText; }
	}
}

function GetXmlHttpObject() {
	if (window.XMLHttpRequest) { return new XMLHttpRequest(); }
	if (window.ActiveXObject) { return new ActiveXObject("Microsoft.XMLHTTP"); }
	return null;
}

function check_field(field_name, field_name2) {
	if (field_name.value != "" && field_name2.value == "") {
		alert("Please fill the security code");
		field_name2.focus();
		return false;
	}
	return true;
}

function show_form_email() {
	var el = document.getElementById('update_email');
	if (el) { el.style.display = ""; }
}

function confirm_reset_email() {
	return confirm('Anda yakin akan mengirim verifikasi ke email anda');
}

function confirm_update_email() {
	var email = document.getElementById("email_new");
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email.value) || email.value == 'customercare@smartfren.com') {
		alert('Silahkan masukan email anda dengan benar.  Please input your email correctly.   ex: jhon@smartfren.com');
		email.focus();
		return false;
	}
	if (confirm('Anda yakin akan mengubah alamat email ke ' + email.value + '')) {
		document.getElementById("wait_update_email").innerHTML = "<font color='red'>Silahkan tunggu ...</font>";
		return true;
	}
	return false;
}

function usernameCek() {
	var wait = document.getElementById('wait');
	wait.innerHTML = "<font color='red'>Please wait...</font>";

	var mdn = document.getElementById('nomor_smrt').value;
	var eml = document.getElementById('email_addrss').value;
	var pass = document.getElementById('new_pass').value;
	var stt_email = document.getElementById('id_email_lgn').value;
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

	if (!filter.test(eml) || eml == 'customercare@smartfren.com') {
		alert('Maaf format alamat email yang anda masukan tidak benar.');
		wait.innerHTML = "";
		return false;
	}
	window.location = "http://data.smartfren.com/index.php/new_controller/registration/add_usr/" + mdn + "/" + eml + "/" + pass + "/" + stt_email;
	return true;
}
</script>

</body>
</html>