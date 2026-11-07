# Dung file Excel ke hoach Anmitech qua COM.
#
# File nay CHU Y giu toan bo noi dung o ASCII. Moi chuoi tieng Viet nam trong
# _xlsx-data.json va duoc doc bang UTF-8 tuong minh.
#
# Ly do: PowerShell 5.1 doc file .ps1 theo bang ma ANSI khi khong co BOM,
# nen tieng Viet viet thang trong script se bien thanh ky tu rac.

$ErrorActionPreference = 'Stop'

$jsonPath = Join-Path $PSScriptRoot '_xlsx-data.json'
$raw  = [System.IO.File]::ReadAllText($jsonPath, [System.Text.Encoding]::UTF8)
$data = ConvertFrom-Json $raw

# Mau thuong hieu, Excel dung thu tu BGR chu khong phai RGB
$BRAND   = 234 * 65536 + 115 * 256 + 0      # #0073EA
$ZEBRA   = 255 * 65536 + 247 * 256 + 240    # #F0F7FF
$WHITE   = 16777215
$GREY    = 128 * 65536 + 128 * 256 + 128
$DONEBG  = 214 * 65536 + 245 * 256 + 214    # xanh la nhat
$DONEFG  = 60  * 65536 + 120 * 256 + 30
$ACTBG   = 200 * 65536 + 240 * 256 + 255    # vang nhat
$ACTFG   = 20  * 65536 + 100 * 256 + 150

$excel = New-Object -ComObject Excel.Application
$excel.Visible = $false
$excel.DisplayAlerts = $false

try {
    $book = $excel.Workbooks.Add()

    # Workbooks.Add() tao san mot so sheet mac dinh; xoa bot cho du so can
    while ($book.Sheets.Count -gt $data.sheets.Count) {
        $book.Sheets.Item($book.Sheets.Count).Delete()
    }
    while ($book.Sheets.Count -lt $data.sheets.Count) {
        [void]$book.Sheets.Add([System.Reflection.Missing]::Value, $book.Sheets.Item($book.Sheets.Count))
    }

    $statusList = $data.statuses -join ','

    for ($s = 0; $s -lt $data.sheets.Count; $s++) {

        $def = $data.sheets[$s]
        $ws  = $book.Sheets.Item($s + 1)
        $ws.Name = $def.name

        $nCol = $def.headers.Count
        $lastColLetter = [char](64 + $nCol)

        # --- Tieu de va ghi chu -------------------------------------------
        $ws.Cells.Item(1, 1).Value2 = $def.title
        $tRange = $ws.Range("A1:$lastColLetter`1")
        $tRange.Merge()
        $tRange.Font.Bold = $true
        $tRange.Font.Size = 15
        $tRange.Font.Color = $BRAND
        $ws.Rows.Item(1).RowHeight = 26

        $ws.Cells.Item(2, 1).Value2 = $def.note
        $nRange = $ws.Range("A2:$lastColLetter`2")
        $nRange.Merge()
        $nRange.Font.Italic = $true
        $nRange.Font.Color = $GREY
        $nRange.WrapText = $true
        $nRange.VerticalAlignment = -4160          # xlTop
        $ws.Rows.Item(2).RowHeight = 30

        # --- Hang tieu de cot ---------------------------------------------
        $headRow = 4
        for ($c = 0; $c -lt $nCol; $c++) {
            $ws.Cells.Item($headRow, $c + 1).Value2 = $def.headers[$c]
        }
        $hRange = $ws.Range("A$headRow`:$lastColLetter$headRow")
        $hRange.Interior.Color = $BRAND
        $hRange.Font.Color = $WHITE
        $hRange.Font.Bold = $true
        $hRange.HorizontalAlignment = -4131        # xlLeft
        $hRange.VerticalAlignment = -4108          # xlCenter
        $ws.Rows.Item($headRow).RowHeight = 22

        # --- Du lieu -------------------------------------------------------
        $r = $headRow + 1
        foreach ($row in $def.rows) {
            for ($c = 0; $c -lt $nCol; $c++) {
                $cell = $ws.Cells.Item($r, $c + 1)

                # COM tu choi Int32 khi gan thang vao Value2. Ep so ve Double
                # va moi thu con lai ve String de tranh loi ep kieu.
                $v = $row[$c]
                if ($v -is [int] -or $v -is [long] -or $v -is [double] -or $v -is [decimal]) {
                    $cell.Value2 = [double]$v
                } else {
                    $cell.Value2 = [string]$v
                }
                if ($def.wrap[$c]) { $cell.WrapText = $true }
                $cell.VerticalAlignment = -4160
            }
            if ($r % 2 -eq 0) {
                $ws.Range("A$r`:$lastColLetter$r").Interior.Color = $ZEBRA
            }
            $r++
        }
        $lastRow = $r - 1

        # --- Do rong cot ---------------------------------------------------
        for ($c = 0; $c -lt $nCol; $c++) {
            $ws.Columns.Item($c + 1).ColumnWidth = $def.widths[$c]
        }

        $body = $ws.Range("A$headRow`:$lastColLetter$lastRow")

        # Vien mong quanh moi o
        foreach ($i in 7, 8, 9, 10, 11, 12) {
            $b = $body.Borders.Item($i)
            $b.LineStyle = 1
            $b.Weight = 2
            $b.Color = 13421772
        }

        $body.AutoFilter() | Out-Null

        # Dong bang phan tieu de de cuon van thay ten cot
        $ws.Activate()
        $ws.Range("A$($headRow + 1)").Select() | Out-Null
        $excel.ActiveWindow.FreezePanes = $true

        # --- Cot trang thai: danh sach chon + to mau theo gia tri ----------
        if ($def.statusCol) {
            $sc = [char](64 + $def.statusCol)
            $stRange = $ws.Range("$sc$($headRow + 1)`:$sc$lastRow")

            $stRange.Validation.Delete()
            # 3 = xlValidateList, 1 = xlValidAlertStop, 1 = xlBetween
            $stRange.Validation.Add(3, 1, 1, $statusList) | Out-Null
            $stRange.Validation.InCellDropdown = $true
            $stRange.HorizontalAlignment = -4108

            $fcDone = $stRange.FormatConditions.Add(1, 3, $data.doneValue)   # 1=xlCellValue, 3=xlEqual
            $fcDone.Interior.Color = $DONEBG
            $fcDone.Font.Color = $DONEFG
            $fcDone.Font.Bold = $true

            $fcAct = $stRange.FormatConditions.Add(1, 3, $data.activeValue)
            $fcAct.Interior.Color = $ACTBG
            $fcAct.Font.Color = $ACTFG
            $fcAct.Font.Bold = $true
        }

        $ws.Range("A1").Select() | Out-Null
    }

    $book.Sheets.Item(1).Activate()

    if (Test-Path $data.file) { Remove-Item $data.file -Force }
    $book.SaveAs($data.file, 51)          # 51 = xlOpenXMLWorkbook (.xlsx)
    $book.Close($false)

    $info = Get-Item $data.file
    "Da tao: {0}" -f $info.FullName
    "Kich thuoc: {0:N1} KB" -f ($info.Length / 1KB)
    "So sheet: {0}" -f $data.sheets.Count
}
finally {
    $excel.Quit()
    [void][System.Runtime.InteropServices.Marshal]::ReleaseComObject($excel)
    [GC]::Collect()
}
