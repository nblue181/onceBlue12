# Chuyen luong ve cua file .ai (PDF content stream) sang SVG.
# Chi ho tro cac toan tu ma logo nay dung: q Q cm m l c v y h re W n f f* scn cs

$ErrorActionPreference = 'Stop'
$inPath  = "D:\NBLUE\anmitech-web\logo-content-stream.txt"
$outPath = "D:\NBLUE\anmitech-web\logo-anmitech.svg"
# Ten bien phai dai: PowerShell KHONG phan biet hoa thuong, $W se bi $w cua lenh 're' ghi de.
$artW = 500.0
$artH = 200.0

$inv = [System.Globalization.CultureInfo]::InvariantCulture
function N([string]$s) { [double]::Parse($s, $inv) }
function F([double]$v) { [math]::Round($v, 3).ToString($inv) }

# Adobe RGB (1998) -> sRGB
function Enc([double]$v) {
  if ($v -lt 0) { $v = 0 }
  if ($v -gt 1) { $v = 1 }
  if ($v -le 0.0031308) { return $v * 12.92 }
  return 1.055 * [math]::Pow($v, 1.0/2.4) - 0.055
}
function ToHex([double]$ar, [double]$ag, [double]$ab) {
  $l0 = [math]::Pow($ar, 2.19921875); $l1 = [math]::Pow($ag, 2.19921875); $l2 = [math]::Pow($ab, 2.19921875)
  $X = 0.5767309*$l0 + 0.1855540*$l1 + 0.1881852*$l2
  $Y = 0.2973769*$l0 + 0.6273491*$l1 + 0.0752741*$l2
  $Z = 0.0270343*$l0 + 0.0706872*$l1 + 0.9911085*$l2
  $r = Enc( 3.2404542*$X - 1.5371385*$Y - 0.4985314*$Z)
  $g = Enc(-0.9692660*$X + 1.8760108*$Y + 0.0415560*$Z)
  $b = Enc( 0.0556434*$X - 0.2040259*$Y + 1.0572252*$Z)
  $ri = [int][math]::Round($r * 255); $gi = [int][math]::Round($g * 255); $bi = [int][math]::Round($b * 255)
  return '#' + $ri.ToString('X2') + $gi.ToString('X2') + $bi.ToString('X2')
}

$content = Get-Content $inPath -Raw
$tokens  = $content -split '\s+' | Where-Object { $_ -ne '' }

$out   = New-Object System.Text.StringBuilder
$ops   = New-Object System.Collections.ArrayList   # toan hang dang cho
$gOpen = New-Object System.Collections.Stack       # moi 'q' day 1 gia tri: da mo <g> hay chua
$path  = New-Object System.Text.StringBuilder
$fill  = '#000000'
$curX  = 0.0; $curY = 0.0
$isClip = $false
$soHinh = 0

foreach ($t in $tokens) {
  if ($t -match '^-?[\d.]+$') { [void]$ops.Add($t); continue }

  # -CaseSensitive la bat buoc: 'q' va 'Q', 'f' va 'F', 'n' va 'N' la cac lenh khac nhau.
  switch -CaseSensitive ($t) {
    'q'  { [void]$gOpen.Push($false); [void]$ops.Clear() }
    'Q'  { if ($gOpen.Count -gt 0) { if ($gOpen.Pop()) { [void]$out.AppendLine('  </g>') } }; [void]$ops.Clear() }
    'cm' {
      if ($ops.Count -ge 6) {
        $m = @($ops[-6], $ops[-5], $ops[-4], $ops[-3], $ops[-2], $ops[-1])
        [void]$out.AppendLine('  <g transform="matrix(' + ($m -join ' ') + ')">')
        if ($gOpen.Count -gt 0) { [void]$gOpen.Pop(); [void]$gOpen.Push($true) }
      }
      [void]$ops.Clear()
    }
    'scn' {
      if ($ops.Count -ge 3) { $fill = ToHex (N $ops[-3]) (N $ops[-2]) (N $ops[-1]) }
      [void]$ops.Clear()
    }
    'm' { if ($ops.Count -ge 2) { $curX = N $ops[-2]; $curY = N $ops[-1]; [void]$path.Append('M ' + (F $curX) + ' ' + (F $curY) + ' ') }; [void]$ops.Clear() }
    'l' { if ($ops.Count -ge 2) { $curX = N $ops[-2]; $curY = N $ops[-1]; [void]$path.Append('L ' + (F $curX) + ' ' + (F $curY) + ' ') }; [void]$ops.Clear() }
    'c' {
      if ($ops.Count -ge 6) {
        $v = $ops[-6..-1] | ForEach-Object { F (N $_) }
        [void]$path.Append('C ' + ($v -join ' ') + ' '); $curX = N $ops[-2]; $curY = N $ops[-1]
      }
      [void]$ops.Clear()
    }
    'v' {
      if ($ops.Count -ge 4) {
        $v = $ops[-4..-1] | ForEach-Object { F (N $_) }
        [void]$path.Append('C ' + (F $curX) + ' ' + (F $curY) + ' ' + ($v -join ' ') + ' '); $curX = N $ops[-2]; $curY = N $ops[-1]
      }
      [void]$ops.Clear()
    }
    'y' {
      if ($ops.Count -ge 4) {
        $x1 = F (N $ops[-4]); $y1 = F (N $ops[-3]); $x3 = F (N $ops[-2]); $y3 = F (N $ops[-1])
        [void]$path.Append('C ' + $x1 + ' ' + $y1 + ' ' + $x3 + ' ' + $y3 + ' ' + $x3 + ' ' + $y3 + ' '); $curX = N $ops[-2]; $curY = N $ops[-1]
      }
      [void]$ops.Clear()
    }
    'h' { [void]$path.Append('Z '); [void]$ops.Clear() }
    're' {
      if ($ops.Count -ge 4) {
        $rx0 = N $ops[-4]; $ry0 = N $ops[-3]; $rw = N $ops[-2]; $rh = N $ops[-1]
        [void]$path.Append('M ' + (F $rx0) + ' ' + (F $ry0) + ' H ' + (F ($rx0+$rw)) + ' V ' + (F ($ry0+$rh)) + ' H ' + (F $rx0) + ' Z ')
      }
      [void]$ops.Clear()
    }
    'W'  { $isClip = $true; [void]$ops.Clear() }
    'W*' { $isClip = $true; [void]$ops.Clear() }
    'n'  { [void]$path.Clear(); $isClip = $false; [void]$ops.Clear() }
    default {
      if ($t -eq 'f' -or $t -eq 'f*' -or $t -eq 'F' -or $t -eq 'b' -or $t -eq 'B' -or $t -eq 'b*' -or $t -eq 'B*') {
        if (-not $isClip -and $path.Length -gt 0) {
          $rule = if ($t -like '*`**') { ' fill-rule="evenodd"' } else { '' }
          [void]$out.AppendLine('    <path fill="' + $fill + '"' + $rule + ' d="' + $path.ToString().Trim() + '"/>')
          $soHinh++
        }
        [void]$path.Clear(); $isClip = $false
      }
      [void]$ops.Clear()
    }
  }
}

while ($gOpen.Count -gt 0) { if ($gOpen.Pop()) { [void]$out.AppendLine('  </g>') } }

$svg = @"
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 $artW $artH" width="$artW" height="$artH" role="img" aria-label="ANMITECH">
 <g transform="translate(0 $artH) scale(1 -1)">
$($out.ToString())
 </g>
</svg>
"@

[System.IO.File]::WriteAllText($outPath, $svg, (New-Object System.Text.UTF8Encoding($false)))
Write-Output "Da tao: $outPath"
Write-Output "So hinh ve: $soHinh"
Write-Output "Kich thuoc: $([math]::Round((Get-Item $outPath).Length/1KB,1)) KB"
