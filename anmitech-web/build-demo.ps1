# Ghep demo.html tu cac file nguon THAT (effects.css + effects.js + demo/body.html)
# Chay lai script nay moi khi sua file nguon de demo khong bi lech.
#
#   powershell -ExecutionPolicy Bypass -File build-demo.ps1

$ErrorActionPreference = 'Stop'
$root = $PSScriptRoot

# PHAI doc bang UTF-8 tuong minh. Get-Content -Raw tren PowerShell 5.1 doc file
# UTF-8 khong BOM theo bang ma ANSI, lam vo toan bo dau tieng Viet.
$u8 = New-Object System.Text.UTF8Encoding($false)
$css  = [System.IO.File]::ReadAllText((Join-Path $root 'src\styles\effects.css'), $u8)
$js   = [System.IO.File]::ReadAllText((Join-Path $root 'src\lib\effects.js'),     $u8)
$body = [System.IO.File]::ReadAllText((Join-Path $root 'demo\body.html'),         $u8)

# effects.js dung cu phap ES module. File mo bang file:// khong nap duoc module
# (trinh duyet chan vi CORS), nen bo cac dong export di de chay nhu script thuong.
$js = $js -replace '(?m)^export\s+\{[\s\S]*?\};\s*$', ''
$js = $js -replace '(?m)^export\s+', ''

$demoCss = @'
  /* ---- CSS rieng cua trang demo, khong thuoc bo hieu ung ---- */

  html[data-theme="dark"] {
    --d-text: #D7E5EB; --d-muted: #7C93A5; --d-head: #FFFFFF;
    --d-line: rgba(36,142,255,.18); --d-bar: rgba(8,19,31,.92);
    --d-alt: rgba(255,255,255,.02); --d-code: #5CABFF;
    --d-chip-bg: rgba(36,142,255,.06); --d-chip-line: rgba(36,142,255,.28); --d-chip-text: #A3D0FF;
    --d-eyebrow: #248EFF;
  }
  html[data-theme="light"] {
    --d-text: #33485C; --d-muted: #6B7F91; --d-head: #0B2138;
    --d-line: rgba(0,116,238,.16); --d-bar: rgba(247,250,253,.92);
    --d-alt: rgba(0,116,238,.035); --d-code: #005EC2;
    --d-chip-bg: #FFFFFF; --d-chip-line: rgba(0,115,234,.22); --d-chip-text: #0073EA;
    --d-eyebrow: #0073EA;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: "Source Sans 3", "Segoe UI", system-ui, sans-serif;
    background: hsl(var(--fx-bg)); color: var(--d-text); line-height: 1.65;
    -webkit-font-smoothing: antialiased;
    transition: background-color .3s ease, color .3s ease;
  }
  code { font-family: ui-monospace, monospace; font-size: .9em; color: var(--d-code); }

  .demo-bar {
    position: sticky; top: 0; z-index: 50;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    padding: 12px 24px; background: var(--d-bar); backdrop-filter: blur(8px);
    border-bottom: 1px solid var(--d-line); font-size: 14px;
  }
  .demo-bar strong { color: var(--d-head); letter-spacing: -.02em; margin-right: auto; }
  .demo-hint { color: var(--d-muted); }
  .demo-ctl { display: inline-flex; align-items: center; gap: 8px; color: var(--d-muted); font-size: 13px; }
  .demo-select {
    font: inherit; font-size: 13px; padding: 6px 10px; border-radius: 8px; cursor: pointer;
    border: 1px solid var(--d-line); background: var(--d-chip-bg); color: var(--d-text);
  }
  .demo-btn {
    cursor: pointer; font: inherit; font-size: 13px;
    padding: 7px 14px; border-radius: 8px;
    border: 1px solid var(--d-chip-line); background: var(--d-chip-bg); color: var(--d-chip-text);
    transition: background .2s ease, border-color .2s ease, transform .15s ease;
  }
  .demo-btn:hover { border-color: #0073EA; transform: translateY(-1px); }
  .demo-btn-primary { background: #0073EA; border-color: #0073EA; color: #fff; font-weight: 600; }
  .demo-btn-primary:hover { background: #005EC2; border-color: #005EC2; }

  .demo-sec { position: relative; overflow: hidden; padding: 100px 0; }
  .demo-sec-alt { background: var(--d-alt); }

  .demo-aura { min-height: 92vh; display: flex; align-items: center; padding: 120px 0; }
  .demo-type { color: #248EFF; }
  html[data-theme="light"] .demo-type { color: #0073EA; }

  .demo-warn {
    margin: 0; padding: 14px 24px; font-size: 13.5px; line-height: 1.6;
    background: rgba(255,176,32,.10); border-bottom: 1px solid rgba(255,176,32,.35); color: #E0AE5A;
  }
  .demo-warn b { color: #FFC46B; }
  html[data-theme="light"] .demo-warn { background: #FFF7E6; color: #8A6220; border-bottom-color: #F0D9A8; }
  html[data-theme="light"] .demo-warn b { color: #6B4A12; }
  .demo-wrap { position: relative; z-index: 10; max-width: 1100px; margin: 0 auto; padding: 0 24px; }
  .demo-wrap-narrow { max-width: 760px; }

  .demo-eyebrow {
    font-size: 12px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
    color: var(--d-eyebrow); margin-bottom: 14px;
  }
  .demo-h1 { font-size: clamp(34px, 6vw, 64px); font-weight: 800; letter-spacing: -.045em; color: var(--d-head); line-height: 1.08; }
  .demo-h2 { font-size: clamp(24px, 3.4vw, 36px); font-weight: 700; letter-spacing: -.035em; color: var(--d-head); margin-bottom: 32px; }
  .demo-lead { margin-top: 20px; max-width: 62ch; color: var(--d-text); font-size: 18px; }
  .demo-note { margin-top: 22px; color: var(--d-muted); font-size: 14px; max-width: 70ch; }

  .demo-stats { display: flex; gap: 44px; flex-wrap: wrap; margin-top: 44px; }
  .demo-stats span { display: block; font-size: 40px; font-weight: 800; color: var(--d-eyebrow); letter-spacing: -.03em; }
  .demo-stats small { color: var(--d-muted); font-size: 13px; }

  .demo-spot {
    position: relative; overflow: hidden; display: grid; place-items: center;
    height: 260px; border: 1px solid var(--d-line); border-radius: 12px;
    background: var(--d-alt); color: var(--d-muted); font-size: 15px; text-align: center; padding: 0 20px;
  }

  .demo-floats { display: flex; flex-wrap: wrap; gap: 18px; }
  .demo-chip {
    display: inline-block; padding: 12px 22px; border-radius: 999px;
    border: 1px solid var(--d-chip-line); background: var(--d-chip-bg);
    color: var(--d-chip-text); font-weight: 600; font-size: 15px;
  }

  .demo-grid { display: grid; gap: 20px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
  .demo-grid-2 { margin-top: 20px; }
  .demo-card { padding: 26px; border-radius: 12px; }
  .demo-card h3 { color: var(--d-head); font-size: 19px; font-weight: 700; margin-bottom: 8px; }
  .demo-card p { color: var(--d-text); font-size: 15px; }
  .demo-tag {
    display: inline-block; margin-right: 8px; padding: 3px 9px; border-radius: 6px;
    background: var(--d-chip-bg); border: 1px solid var(--d-chip-line);
    color: var(--d-chip-text); font-size: 12px; font-family: ui-monospace, monospace;
  }

  .demo-marquee { margin-top: 18px; padding: 10px 0; }
  .demo-pill {
    flex: none; margin-right: 14px; padding: 12px 24px; border-radius: 999px;
    background: var(--d-chip-bg); border: 1px solid var(--d-chip-line);
    color: var(--d-chip-text); font-weight: 600; white-space: nowrap;
  }
  .demo-pill-ghost { background: transparent; color: var(--d-muted); }

  .demo-acc-item { border: 1px solid var(--d-line); border-radius: 10px; margin-bottom: 12px; background: var(--d-alt); }
  .demo-acc-btn {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    width: 100%; padding: 18px 20px; cursor: pointer; font: inherit; text-align: left;
    background: none; border: 0; color: var(--d-head); font-weight: 600;
  }
  .demo-acc-btn .fx-acc-icon { color: var(--d-eyebrow); flex: none; }
  .demo-acc-body { padding: 0 20px 20px; color: var(--d-text); font-size: 15px; }

  .demo-grad {
    height: 56px; border-radius: 8px; margin-top: 22px;
    border: 1px solid var(--d-line);
    background-image: var(--brand-gradient);
  }
  .demo-row { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 22px; }
  .demo-btn-brand {
    display: inline-block; text-decoration: none;
    background-image: var(--brand-gradient);
    background-size: 200% 100%; background-position: 100% 0;
    color: #fff; font-weight: 600; border: 0;
    transition: background-position .32s ease, box-shadow .2s ease, transform .15s ease;
  }
  .demo-btn-brand:hover {
    background-position: 0 0;
    box-shadow: 0 8px 24px hsl(var(--brand) / .38);
    transform: translateY(-1px);
  }

  .demo-swatches { display: flex; flex-wrap: wrap; gap: 10px; }
  .demo-swatches div { text-align: center; }
  .demo-swatches i { display: block; width: 74px; height: 56px; border-radius: 8px; border: 1px solid var(--d-line); }
  .demo-swatches b { display: block; margin-top: 6px; font-size: 12px; color: var(--d-muted); font-weight: 600; }

  .demo-foot { padding: 60px 24px 80px; text-align: center; border-top: 1px solid var(--d-line); }
  .demo-foot p { color: var(--d-muted); font-size: 14px; }
  .demo-foot .demo-note { margin: 10px auto 0; }

  /* Mo phong "giam chuyen dong" bang nut bam, de xem truoc trang thai du phong */
  html.demo-no-motion *,
  html.demo-no-motion *::before,
  html.demo-no-motion *::after {
    animation: none !important;
    transition-duration: .12s !important;
  }
  html.demo-no-motion .fx-reveal { opacity: 1 !important; transform: none !important; }
  html.demo-no-motion .fx-karaoke { clip-path: none !important; filter: none !important; }
  html.demo-no-motion .fx-word { opacity: 1 !important; filter: none !important; }
  html.demo-no-motion .fx-scanline { display: none; }
'@

$demoJs = @'
  var root = document.documentElement;

  // QUAN TRONG: bat cuong buc chuyen dong cho trang demo.
  // May dang bat "giam chuyen dong" thi moi hieu ung se bi tat, va nguoi xem
  // chi thay chu hien ra chu khong thay truot / danh chu / marquee chay.
  var heThongDangTat = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  root.classList.add('fx-force-motion');
  if (heThongDangTat) {
    var warn = document.getElementById('motionWarn');
    if (warn) warn.hidden = false;
  }

  // Cac bien the reveal khai bao qua data-fx-reveal-variant
  document.querySelectorAll('[data-fx-reveal-variant]').forEach(function (el) {
    el.classList.add('fx-reveal-' + el.getAttribute('data-fx-reveal-variant'));
  });

  var cleanup = initEffects(document);

  // ---- Toc do ----
  var sel = document.getElementById('selSpeed');
  function applySpeed(v) {
    root.style.setProperty('--fx-speed', v);
    try { localStorage.setItem('demo-speed', v); } catch (e) {}
  }
  var savedSpeed = null;
  try { savedSpeed = localStorage.getItem('demo-speed'); } catch (e) {}
  if (savedSpeed && sel) sel.value = savedSpeed;
  applySpeed(sel ? sel.value : '2');
  if (sel) sel.addEventListener('change', function () { applySpeed(sel.value); replay(); });

  // ---- Phat lai ----
  // Cac hieu ung mot lan da chay xong va IntersectionObserver da ngat theo doi.
  // Muon xem lai phai xoa dau vet roi khoi tao lai tu dau.
  function restart(el) {
    el.style.animation = 'none';
    void el.offsetWidth;          // buoc trinh duyet tinh lai, neu khong se khong chay lai
    el.style.animation = '';
  }
  function replay() {
    cleanup();
    document.querySelectorAll('.fx-reveal.is-in').forEach(function (el) { el.classList.remove('is-in'); });
    document.querySelectorAll('.fx-karaoke, .fx-word').forEach(restart);
    document.querySelectorAll('[data-fx-count]').forEach(function (el) { el.textContent = '0'; });
    window.scrollTo({ top: 0, behavior: 'smooth' });
    cleanup = initEffects(document);
  }
  var btnReplay = document.getElementById('btnReplay');
  if (btnReplay) btnReplay.addEventListener('click', replay);

  // ---- Giao dien sang / toi ----
  var btnTheme = document.getElementById('btnTheme');
  function syncThemeLabel() {
    if (btnTheme) btnTheme.textContent = 'Giao dien: ' + (root.getAttribute('data-theme') === 'dark' ? 'Toi' : 'Sang');
  }
  syncThemeLabel();
  if (btnTheme) btnTheme.addEventListener('click', function () {
    var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    try { localStorage.setItem('nlw-theme', next); } catch (e) {}
    syncThemeLabel();
  });

  // ---- Mo phong giam chuyen dong ----
  // Nut nay tat che do cuong buc de xem dung trang thai khach se gap
  var btnMotion = document.getElementById('btnMotion');
  if (btnMotion) btnMotion.addEventListener('click', function () {
    var tat = root.classList.toggle('demo-no-motion');
    root.classList.toggle('fx-force-motion', !tat);
    btnMotion.textContent = tat ? 'Bat lai chuyen dong' : 'Thu giam chuyen dong';
    if (!tat) replay();
  });
'@

$html = @"
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ANMITECH — Demo hiệu ứng</title>
<script>
/* Dat giao dien TRUOC khi trang ve, neu khong se thay mot nhay trang/den. */
(function () {
  try {
    var saved = localStorage.getItem('nlw-theme');
    var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
  } catch (e) {
    document.documentElement.setAttribute('data-theme', 'dark');
  }
})();
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
/* ================= src/styles/effects.css ================= */
$css
/* ================= CSS rieng cua demo ================= */
$demoCss
</style>
</head>
<body>
$body
<script>
/* ================= src/lib/effects.js ================= */
$js
/* ================= JS rieng cua demo ================= */
$demoJs
</script>
</body>
</html>
"@

$out = Join-Path $root 'demo.html'
# Ghi KEM BOM: file mo bang file:// (bam dup) khong co header HTTP bao bang ma,
# mot so trinh doc doan sai va lam vo dau tieng Viet. BOM cong voi <meta charset>
# la chac chan nhat.
[System.IO.File]::WriteAllText($out, $html, (New-Object System.Text.UTF8Encoding($true)))

Write-Output "Da tao: $out"
Write-Output ("Kich thuoc: {0:N1} KB" -f ((Get-Item $out).Length / 1KB))
