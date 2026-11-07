# Bàn giao — namtrandang.com

Tài liệu để tiếp tục công việc trên máy khác. Đọc hết mục 1–3 trước khi làm gì.

---

## 1. Tình trạng hiện tại

Website **đang chạy thật** tại https://namtrandang.com với child theme trong repo này (v1.4.0).

| Trang | Trạng thái |
|---|---|
| Trang chủ | Xong nội dung + giao diện. 8 section chạy đúng |
| Giới thiệu | Giao diện xong. **Còn 4 khối nội dung chờ dán** (mục 6) |
| Dự án — danh sách | Chạy. Lọc theo Nhóm dự án và theo Lĩnh vực (`?linh-vuc=`) |
| Dự án — chi tiết | Template xong, chưa có dự án thật để kiểm chứng đầy đủ |
| Dịch vụ (tổng + 4 trang con) | Template + dữ liệu xong, **chưa dựng trang tổng** |
| Liên hệ | **Chưa dựng** |

Dữ liệu thật hiện có: **1 dự án** (Surface Shop — lĩnh vực "Máy tính"), 4 dịch vụ, 12 gói giá.

---

## 2. Cấu trúc thư mục

```
namtrandang-web/
├── flatsome-child/          ← child theme, đây là thứ được deploy lên server
│   ├── functions.php        ← NLW_VERSION nằm ở đây, phải tăng mỗi lần sửa CSS/JS
│   ├── style.css            ← chỉ có header theme, không chứa CSS
│   ├── inc/                 setup · helpers · post-types · fields · options · shortcodes · seo · media · seed
│   ├── assets/              css/tokens.css · css/theme.css · js/site.js · fonts · img
│   ├── archive-project.php · taxonomy-project_cat.php · single-project.php · single-service.php
│   └── template-parts/card-project.php
├── docs/
│   ├── HUONG-DAN-TRIEN-KHAI.md   kiến trúc, plugin, cách dựng từng trang, bảng shortcode
│   ├── HOMEPAGE-CONTENT-V1.md    nội dung trang chủ cần dán trong wp-admin
│   ├── ABOUT-CONTENT-V1.md       nội dung trang Giới thiệu cần dán
│   ├── cf7-form.html             mẫu form Contact Form 7
│   └── BAN-GIAO.md               file này
├── backup-remote/           bản tải về từ server trước mỗi lần deploy (không cần mang đi)
└── deploy.ps1               script deploy, tự suy đường dẫn theo vị trí file
```

---

## 3. Dựng lại môi trường trên máy mới

### 3.1 Copy mã nguồn

Copy cả thư mục `namtrandang-web`. Có thể bỏ `backup-remote/` — script sẽ tự tạo lại khi deploy.

### 3.2 Cài WinSCP

Tải từ winscp.net. Script deploy cần `WinSCP.com` (bản dòng lệnh, đi kèm bản cài mặc định).

### 3.3 Tạo lại session FTP

**Mật khẩu không đi theo thư mục** — nó nằm trong kho của WinSCP trên máy cũ. Phải tạo lại:

| Trường | Giá trị |
|---|---|
| File protocol | FTP |
| Encryption | TLS/SSL Explicit encryption |
| Host name | `103.173.66.99` |
| Port | `21` |
| User name | `deloy@namtrandang.com` |
| Password | mật khẩu tài khoản FTP `deloy` |

Lưu site với tên **đúng** `deloy@namtrandang.com` — `deploy.ps1` gọi session theo tên này.
Muốn đổi tên khác thì sửa biến `$Session` đầu file `deploy.ps1`.

Nếu quên mật khẩu: cPanel → FTP Accounts → Change Password cho tài khoản `deloy`.

### 3.4 Kiểm tra

```bash
powershell -ExecutionPolicy Bypass -File .\deploy.ps1 -NoBackup
```

Chạy xong không báo lỗi là môi trường đã sẵn sàng.

---

## 4. Quy trình sửa và deploy

1. Sửa file trong `flatsome-child/`.
2. Chạy `deploy.ps1`.
3. Nếu đang bật LiteSpeed Cache: vào wp-admin purge cache.
4. Mở site với tham số lạ (`?v=123`) để chắc chắn không dính cache trang.

**Không cần tăng `NLW_VERSION` nữa.** Từ v1.4.1, phiên bản của `tokens.css`, `theme.css` và
`site.js` lấy theo `filemtime()` — sửa file nào thì URL file đó tự đổi, trình duyệt buộc phải
tải bản mới. `NLW_VERSION` giờ chỉ còn là số hiệu của theme.

Bối cảnh: máy chủ đặt `Cache-Control: public, max-age=604800` cho CSS/JS, tức trình duyệt giữ
bản cũ **7 ngày**. Xoá cache trong wp-admin chỉ xoá cache phía máy chủ, không đụng tới bản nằm
trong trình duyệt — đó là lý do mở bằng trình duyệt khác thì thấy giao diện mới ngay.

Ranh giới quan trọng: **file theme sửa được qua FTP, nội dung thì không.** Nội dung nằm trong
database (Options Page, nội dung trang trong UX Builder, CPT), chỉ sửa được trong wp-admin.

---

## 5. Truy cập máy chủ

| Kênh | Trạng thái |
|---|---|
| FTPS cổng 21 | **Đang dùng** — tài khoản `deloy`, giới hạn trong `public_html/wp-content/themes/` |
| SSH | **Chưa mở.** Đã quét 12 cổng phổ biến, tất cả đóng. Khoá công khai đã Authorize sẵn trong cPanel |
| Git Version Control (cPanel) | Có giao diện nhưng bị chặn vì cùng lý do với SSH (cần shell access) |

**Khoá SSH riêng nằm ở `~/.ssh/namtrandang` trên máy cũ, có passphrase.** Đừng gửi khoá này
qua chat/email. Trên máy mới nên tạo cặp khoá mới rồi Import + Authorize trong cPanel — nhanh
hơn và an toàn hơn là mang khoá cũ đi.

Ticket yêu cầu nhà cung cấp bật SSH vẫn chưa gửi. Khi SSH mở sẽ dùng được WP-CLI và đọc log lỗi,
những thứ FTPS không làm được.

---

## 6. Việc còn dang dở

### Trang Giới thiệu — dán 4 khối nội dung
Xem `docs/ABOUT-CONTENT-V1.md`: Hero, Câu chuyện, Tầm nhìn, CTA. Hai section Giá trị cốt lõi và
Cách làm việc đã đúng, không cần đụng.

### Danh sách lĩnh vực chưa phải dữ liệu thật
Trang Giới thiệu từng liệt kê 8 ngành (Nhà hàng & F&B, Xây dựng…) vốn là nội dung mẫu trong tài
liệu hướng dẫn, không phải kinh nghiệm thật. Hiện đã chuyển sang lấy từ dự án thật nên chỉ còn
một chip "Máy tính". Cần Nam rà lại và quyết định.

### Chưa dựng
Trang Dịch vụ (tổng) và trang Liên hệ. Shortcode và template đã sẵn sàng, xem
`docs/HUONG-DAN-TRIEN-KHAI.md` mục 5.3 và 5.5.

### Trong wp-admin
- Nhãn nút header đang là `Nhắn zalo` (chữ z thường) → Customize → Header → Buttons.
- Dòng `Copyright 2026 © Flatsome Theme` ở đáy trang → Customize → Footer → Absolute Footer.
- Tagline đang là "Hiểu đúng để Làm đúng.", spec ghi "Hiểu đúng. Làm đúng." → Nam làm WEB → Cấu hình.
- Mở trang Cấu hình bấm **Cập nhật** một lần để lưu các giá trị SEO mặc định vào database.

### CSS cũ cần dọn — việc này ảnh hưởng lâu dài
Trong Flatsome → Advanced → Global Custom CSS còn CSS của bộ thiết kế trước, dùng `!important`
và biến `--neutral-900`, `--accent-600`:

```
h4            { font-size: var(--fs-h4) !important }
#footer h1…h6 { font-size: var(--fs-h3) !important }
.home h2      { color: var(--accent-600) }
```

Chúng đã đè lên CSS của theme nhiều lần. Hiện tôi phải chống lại bằng `!important` ngược ở
3 chỗ trong `theme.css` (đều có ghi chú "gỡ sau khi dọn CSS cũ"). Dọn xong thì gỡ được.

### Bản quyền Flatsome
Theme cha đang là bản nulled. File `inc/custom/key.php` trên server ghi đè license bằng mã giả.
Child theme mới không nạp file đó nữa. Hệ quả: Flatsome không nhận cập nhật bảo mật.
Cân nhắc mua license chính chủ và rà soát mã độc ở `wp-content/themes/flatsome/`.

---

## 7. Quy ước viết nội dung trong shortcode

| Cú pháp | Tác dụng | Dùng ở |
|---|---|---|
| `**chữ**` | In đậm, màu brand | Nội dung `[nlw_intro]`, `[nlw_page_hero]` |
| `\|` | Ngắt dòng có chủ đích | `title`/`desc` của `[nlw_cta]`, `title` của `[nlw_intro]` |
| `- ` đầu dòng | Thành mục danh sách | Nội dung `[nlw_intro]`, `[nlw_page_hero]` |
| `\|` | **Dấu ngăn cột** (Tiêu đề \| Mô tả) | `[nlw_ways]`, `[nlw_numbered_list]` — khác nghĩa, dễ nhầm |

Bảng shortcode đầy đủ nằm ở `docs/HUONG-DAN-TRIEN-KHAI.md` mục 6.

---

## 8. Lưu ý kỹ thuật đã rút ra

- **Độ ưu tiên CSS**: quy tắc gốc kiểu `body.nlw h2` (0,1,2) sẽ đè quy tắc component
  `.nlw-values h2` (0,1,1). Đã xử lý bằng `:where()` cho quy tắc gốc. Khi thêm component mới
  trên nền tối, nhớ kiểm tra lại màu chữ.
- **Chưa lint được PHP** trên máy này (không có PHP CLI). Mọi thay đổi PHP nên thử trên staging
  hoặc deploy vào lúc ít khách.
- **`get_field()` trên Options Page trả về rỗng** cho tới khi trang Cấu hình được Lưu lần đầu —
  `default_value` chỉ điền vào form chứ không ghi database. Vì vậy `inc/seo.php` có hằng số
  dự phòng.
- **PowerShell 5.1 đọc file UTF-8 không BOM theo bảng mã ANSI**, ký tự như `—` sẽ làm vỡ script.
  Giữ `deploy.ps1` thuần ASCII.
