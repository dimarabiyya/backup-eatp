<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Terjadi kesalahan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
<style type="text/css">
* { box-sizing: border-box; }
body {
	margin: 0;
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 24px;
	background: #f4f5f8;
	color: #1f2430;
	font-family: "Plus Jakarta Sans", "Segoe UI", Roboto, Arial, sans-serif;
}
.card {
	width: 100%;
	max-width: 460px;
	padding: 40px 32px 32px;
	border: 1px solid #e7e9ee;
	border-top: 4px solid #c00505;
	border-radius: 18px;
	background: #fff;
	box-shadow: 0 28px 70px -30px rgba(16, 24, 40, .35);
	text-align: center;
	animation: rise .5s cubic-bezier(.2, .8, .2, 1) backwards;
}
.code {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 72px;
	height: 72px;
	border-radius: 50%;
	background: #fff1f1;
	font-size: 36px !important;
	color: #c00505 !important;
	-webkit-text-fill-color: #c00505;
	margin: 0;
	font-size: 88px;
	font-weight: 800;
	line-height: 1;
	letter-spacing: -.04em;
	background: linear-gradient(120deg, #5d0202, #c00505 55%, #fa3131);
	-webkit-background-clip: text;
	background-clip: text;
	color: transparent;
}
h1 { margin: 14px 0 6px; font-size: 22px; font-weight: 800; }
.msg { margin: 0 0 26px; color: #6b7280; line-height: 1.6; }
.msg p { margin: 0; }
.btn {
	display: inline-block;
	padding: 12px 22px;
	border: 0;
	border-radius: 10px;
	background: linear-gradient(180deg, #e02424, #c00505);
	color: #fff;
	font: inherit;
	font-weight: 600;
	text-decoration: none;
	cursor: pointer;
	box-shadow: 0 10px 22px -12px rgba(192, 5, 5, .8);
	transition: transform .2s ease, box-shadow .2s ease;
}
.btn:hover { transform: translateY(-1px); box-shadow: 0 14px 26px -12px rgba(192, 5, 5, .85); }
.code.icon { width: 72px; height: 72px; border-radius: 50%; background: #fff1f1; -webkit-background-clip: border-box; background-clip: border-box; color: #c00505; -webkit-text-fill-color: #c00505; font-size: 36px; }
@keyframes rise { from { opacity: 0; transform: translateY(12px); } }
@media (prefers-reduced-motion: reduce) { .card { animation: none; } .btn { transition: none; } }
</style>
</head>
<body>
	<div class="card">
		<p class="code icon">!</p>
		<h1><?php echo $heading; ?></h1>
		<div class="msg"><?php echo $message; ?></div>
		<a class="btn" href="javascript:history.back()">Kembali</a>
	</div>
</body>
</html>
