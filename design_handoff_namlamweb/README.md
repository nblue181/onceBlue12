# Handoff: namlamweb.com — Business Identity Website (5 trang)

## Overview
Website giới thiệu cho **Nam làm WEB** — dịch vụ làm website trọn gói cho doanh nghiệp nhỏ không rành công nghệ.
Định vị thương hiệu: *người xây dựng niềm tin cho doanh nghiệp thông qua website*.

Core message (không đổi): **"Bạn sẽ hiểu website của mình trước khi sở hữu nó."**
Tagline: **"Hiểu đúng. Làm đúng."**
Ngôn ngữ giao diện: **Tiếng Việt**.

Hành động ưu tiên: **liên hệ trực tiếp qua Zalo/Messenger** (nổi bật ở mọi trang); form đăng ký tư vấn là phụ.

## About the Design Files
Các file trong gói này là **design reference viết bằng HTML/CSS** — bản prototype thể hiện bố cục, màu, chữ và hành vi mong muốn. **Không phải production code để copy nguyên.**

**Mục tiêu triển khai: WordPress + theme Flatsome + SCF (Secure Custom Fields).**
Việc cần làm là **dựng lại các trang này trong WordPress**, dùng:
- **Flatsome UX Builder** cho bố cục section/row/column,
- **SCF** cho các field nội dung động (dự án, dịch vụ, bảng giá),
- **Custom CSS** (Flatsome → Advanced → Global Custom CSS, hoặc child theme) cho design tokens và các thành phần Flatsome không có sẵn,
- **Custom JS** cho 2 hiệu ứng scroll-driven ở trang chủ (xem mục Interactions).

Nội dung chữ trong file là **placeholder đúng giọng thương hiệu** — nội dung thật sẽ được cung cấp riêng. Ảnh là placeholder gạch chéo có nhãn, cần thay bằng screenshot dự án thật.

## Fidelity
**High-fidelity (hifi).** Màu, typography, spacing, border, hover/active state đều là giá trị cuối. Hãy dựng lại **đúng pixel** các giá trị nêu trong mục Design Tokens; không tự làm tròn về grid 4/8px hay default của Flatsome.

Hai điểm bắt buộc tuân thủ:
1. **`border-radius: 0` cho TẤT CẢ** — nút, thẻ, ô nhập, ảnh, chip. Góc vuông là đặc trưng thương hiệu. Flatsome mặc định bo góc → phải override.
2. **Gradient thương hiệu chỉ dùng 1 lần** ở hero trang chủ. Không dùng lại ở trang khác.

---

## Design Tokens

### Colors
| Token | Hex | Dùng cho |
|---|---|---|
| `--brand-50` | `#EAF6FA` | nền nhạt |
| `--brand-100` | `#C9E9F1` | viền nhấn, số thứ tự mờ |
| `--brand-500` | `#027EA6` | **primary** — nút, link, viền active |
| `--brand-700` | `#024E68` | hover của primary |
| `--brand-900` | `#002735` | nền tối (footer, band), màu heading |
| `--accent` | `#6BDFEE` | **chỉ nền/nhấn** — quá sáng, KHÔNG dùng cho chữ nhỏ trên nền trắng |
| `--accent-text` | `#12889C` | chữ cyan trên nền trắng (eyebrow) |
| `--white` / `--surface` | `#FFFFFF` | nền chính |
| `--surface-alt` | `#F6F8F9` | nền section phụ |
| `--border` | `#DBE2E6` | viền 1px mặc định |
| `--text` | `#222A2F` | chữ chính |
| `--text-muted` | `#4D5A61` | chữ phụ, mô tả |
| `--success` | `#1E9E57` | chỉ trạng thái |
| `--warning` | `#E29100` | chỉ trạng thái |
| `--error` | `#D93C3C` | chỉ trạng thái |
| `--info` | `#2C7BE5` | chỉ trạng thái |

**Gradient thương hiệu:** `linear-gradient(135deg, #6BDFEE 0%, #027EA6 100%)` — dùng **duy nhất** cho hero trang chủ.

Màu trên nền tối (`--brand-900`): chữ chính `#FFFFFF`, chữ phụ `#9DB6C0`, chữ footer bottom `#6F8A94`, viền `rgba(255,255,255,.12)`–`rgba(255,255,255,.16)`.

### Typography
- **Heading: Archivo** (variable TTF, weight 100–900)
- **Body: Space Grotesk** (variable TTF, weight 300–700)
- File font self-hosted trong `assets/fonts/` (kèm trong gói này). Trên WordPress: upload vào child theme và khai báo `@font-face`, **không** dùng Google Fonts CDN của Flatsome để tránh lệch weight.

Type scale (px):

| Token | Desktop | Tablet ≤1024 | Mobile ≤640 |
|---|---|---|---|
| `--fs-display` | 48 | 40 | 32 |
| `--fs-h1` | 36 | 30 | 26 |
| `--fs-h2` | 28 | 24 | 22 |
| `--fs-h3` | 22 | 20 | 19 |
| `--fs-h4` | 18 | 17 | 16 |
| `--fs-body` | 16 | 16 | **16 (luôn 16)** |
| `--fs-caption` | 13 | 13 | 13 |
| `--fs-button` | 15 | 15 | 15 |

Weights: `400 / 500 / 600 / 700 / 800`. Heading dùng **800 (black)**.
Line-height: heading `1.25`, body `1.65`.
Display & H1 có `letter-spacing: -.01em`. Eyebrow có `letter-spacing: .14em` + `text-transform: uppercase`.

### Spacing
`--space-1..9` = `4 · 8 · 12 · 16 · 24 · 32 · 48 · 64 · 96` px.
`--container: 1200px` (padding ngang `32px`).
`--section-y: 96px` (mobile ≤900px giảm về `60–64px`).

### Shape & Shadow
- `--radius: 0px` — **mọi thành phần**.
- `--shadow-sm: 0 1px 2px rgba(0,39,53,.06)`
- `--shadow-md: 0 4px 16px rgba(0,39,53,.08)`
- Viền mặc định: `1px solid var(--border)`. Viền nhấn: `3px solid var(--brand-500)` (top hoặc left).

### Motion
- Transition chuẩn: `.16s` cho màu/viền, `.18s` cho shadow/transform, `.6s` cho reveal.
- Reveal khi cuộn: `opacity 0 → 1` + `translateY(20px) → 0`, kích hoạt bằng IntersectionObserver ở `threshold .08–.12`.
- Hover thẻ: `translateY(-3px)` + `--shadow-md` + viền chuyển `--brand-100`.
- Active nút: `translateY(1px)`.
- Hover mũi tên trong link: `translateX(3–4px)`.
- Tôn trọng `@media (prefers-reduced-motion: reduce)` — tắt transform, chỉ giữ fade `.15s`.

---

## Components (dùng lại trên mọi trang)

### Button
Base: `display:inline-flex; align-items:center; gap:9px; font-family:Archivo; font-weight:700; font-size:15px; padding:14px 26px; border:1.5px solid transparent; line-height:1; border-radius:0`.
Size lớn (`.btn-lg`): `padding:17px 32px; font-size:16px`.
Focus: `outline:3px solid #6BDFEE; outline-offset:2px`.

| Variant | Default | Hover |
|---|---|---|
| `btn-primary` | bg `#027EA6`, chữ trắng | bg `#024E68` |
| `btn-onbrand` (trên nền brand) | bg trắng, chữ `#024E68` | bg `#EAF6FA` |
| `btn-ghost` | trong suốt, viền `#DBE2E6`, chữ `#002735` | viền + chữ `#027EA6` |
| `btn-white-out` (trên nền tối) | trong suốt, viền `rgba(255,255,255,.55)`, chữ trắng | bg `rgba(255,255,255,.12)` |

### Header (sticky)
`position:sticky; top:0; height:74px; background:rgba(255,255,255,.92); backdrop-filter:blur(8px); border-bottom:1px solid #DBE2E6`.
Logo cao `30px` (bên trái) · nav giữa/phải · nút "Nhắn Zalo" (`btn-primary`) bên phải.
Nav item: Archivo 600, 15px, `padding:8px 14px`, `border-bottom:2px solid transparent`; **active** → chữ `#002735` + `border-bottom-color:#027EA6`; **hover** → chữ `#027EA6`.
≤900px: ẩn nav + nút, hiện hamburger 3 gạch (`24×2px`, `#002735`).

### Footer
Nền `#002735`, `padding:64px 0 30px`. 4 cột `1.5fr 1fr 1fr 1.2fr`, gap `40px`:
1. Logo trắng (28px) + mô tả + tagline màu `#6BDFEE`
2. Điều hướng (5 trang)
3. Liên hệ (Zalo / Messenger / Hotline / Email)
4. "Bắt đầu ngay" + nút `btn-primary`
Heading cột: Archivo 700, 13px, uppercase, `letter-spacing:.08em`, màu `#6BDFEE`.
Link: `#9DB6C0` → hover `#FFFFFF`.
Bottom bar: viền trên `rgba(255,255,255,.12)`, chữ `#6F8A94` 13px, hai đầu ("© 2026 Nam làm WEB" / "namlamweb.com").

### CTA band (cuối mỗi trang)
Nền `#027EA6`, chữ trắng, căn giữa, `padding:88px 0`. H2 (`--fs-h1`) + mô tả (18px, `rgba(255,255,255,.92)`, max 52ch) + 2 nút: `btn-onbrand` (Zalo) và `btn-white-out` (Messenger).

### Eyebrow
Archivo 700, 13px, uppercase, `letter-spacing:.14em`, màu `#12889C` (trên nền tối: `#6BDFEE`).

### Card (thẻ chung)
Nền trắng, viền `1px solid #DBE2E6`, radius 0, padding `22–34px`. Hover: `translateY(-3px)` + `--shadow-md` + viền `#C9E9F1`.

### Icon system
Toàn bộ icon là **CSS mask từ inline SVG data-URI** (Zalo, Messenger, arrow, check, clock, external-link, pin), kích thước mặc định `18×18px`, màu theo `currentColor` — nên đổi màu tự động theo context.
Trên WordPress: giữ nguyên cách này (paste vào Custom CSS) **hoặc** upload SVG vào media và dùng `<img>`/inline SVG. Không thay bằng emoji, không dùng icon font của Flatsome cho Zalo/Messenger vì Flatsome không có 2 icon này.

---

## Screens / Views

Toàn bộ 5 trang đều có: **Header sticky → nội dung → CTA band → Footer**.

### 1. Trang chủ — `pages/index.html`
Template WP: front page.

| # | Section | Layout & chi tiết |
|---|---|---|
| 1 | **Hero** | Nền **gradient thương hiệu** (dùng duy nhất ở đây). Grid `1.15fr .85fr`, gap 56px, padding `88px 0`. Trái: eyebrow "Nam làm WEB · Hiểu đúng. Làm đúng." → H1 core message (`--fs-display`, line-height 1.12) → mô tả 19px → 2 nút (`btn-onbrand` Zalo + `btn-white-out` "Xem dịch vụ") → dòng note Messenger. Phải: **stack 3 promise** (01 Rõ ràng / 02 Dễ hiểu / 03 Đồng hành), mỗi ô `background:rgba(2,78,104,.34)`, cách nhau 1px, viền ngoài `rgba(255,255,255,.35)`; số thứ tự Archivo 800 22px màu `#6BDFEE`. |
| 2 | **Giới thiệu ngắn** | Nền trắng. Grid bất đối xứng `.4fr .6fr`. Trái: nhãn dọc "GIỚI THIỆU". Phải: H2 + 2 đoạn 19px + pullquote (viền trái `3px #027EA6`, Archivo 600 20px). |
| 3 | **Dịch vụ nổi bật** | Nền `#F6F8F9`. Head (eyebrow + H2 + lead) rồi grid **3 thẻ** đánh số 01–03: số Archivo 800 15px màu `#C9E9F1`, H3, mô tả 15px, link "Tìm hiểu thêm →". Hover lift. |
| 4 | **Dự án tiêu biểu** — *scroll-driven* | Nền `#002735`, `height:440vh`, bên trong `position:sticky; top:0; height:100vh`. Hiển thị **1 dự án/lần** (5 dự án): ảnh full-bleed + phủ `linear-gradient(to top, rgba(0,39,53,.95), rgba(0,39,53,.55) 40%, rgba(0,39,53,.05) 75%)`; overlay chứa pill danh mục (nền `#6BDFEE`, chữ `#002735`), tên dự án `--fs-display`, mô tả, URL màu `#6BDFEE`. Điều hướng: **dãy số dọc 01–05** bên phải (active `#6BDFEE`, `scale(1.15)`) + dãy **chấm/gạch** dưới (`28×4px`) + đếm "n / 5". Cả hai bấm được để nhảy tới dự án. |
| 5 | **Vì sao chọn** | Nền `#002735`. Grid 4 cột, các ô cách nhau 1px (`rgba(255,255,255,.14)`). Mỗi ô: số Archivo 800 34px màu `#6BDFEE` + H4 trắng + mô tả `#9DB6C0` 14px. Nội dung: 01 Rõ ràng · 02 Dễ hiểu · 03 Tận tâm · 04 Thực tế. |
| 6 | **Quy trình 5 bước** — *scroll-driven* | Nền `#002735`, `height:420vh`, sticky 100vh. **Timeline ngang** kéo theo scroll dọc: track `translateX` sao cho **bước đang active luôn nằm giữa màn hình**. Mỗi item rộng `360px`, gap `32px`. Node `56×56px` vuông trên một đường ngang `2px`: *sắp tới* = nền `#002735` + viền `rgba(255,255,255,.3)`; *đang thực hiện* = nền `#6BDFEE`, chữ `#002735`; *đã xong* = nền `#027EA6` + dấu `✓` trắng. Đường nối có thanh fill `#6BDFEE` chạy theo tiến độ. Thẻ dưới node: H4 + pill trạng thái ("Sắp tới"/"Đang thực hiện"/"Hoàn thành") + label phụ màu `#6BDFEE` + mô tả. **5 bước:** 1 Tiếp nhận yêu cầu · 2 Phân tích và tư vấn · 3 Thiết kế và phát triển · 4 Kiểm thử và triển khai · 5 Bàn giao & đào tạo. |
| 7 | **CTA band** | Như component chung. |

### 2. Giới thiệu — `pages/About.html`
Template WP: page.
1. **Hero**: grid `1.2fr .8fr`, padding `80px 0 72px`, viền dưới. Trái: eyebrow + H1 + mô tả 19px + signature ("Nam — Nam làm WEB" / tagline). Phải: ảnh chân dung tỉ lệ `4/5`, viền 1px.
2. **Câu chuyện**: grid `.32fr .68fr`. Nhãn dọc + H2 + các đoạn 18px + pullquote 22px (viền trái brand).
3. **Tầm nhìn**: dải nền `#002735`, căn giữa. Eyebrow `#6BDFEE` + blockquote Archivo 800 **34px**, max `20ch`, + dòng attribution `#C9E9F1`.
4. **Giá trị cốt lõi**: nền `#F6F8F9`. **Danh sách editorial** (không phải thẻ): mỗi hàng grid `96px 1fr`, viền dưới 1px, số Archivo 800 **40px** màu `#C9E9F1` + H3 + mô tả (max 60ch). 4 hàng.
5. **Kinh nghiệm**: H2 + lead + **chips** lĩnh vực (nền trắng, viền 1px, Archivo 600 15px, `padding:11px 18px`).
6. **Cách làm việc**: nền `#F6F8F9`, 3 cột, mỗi cột **viền trên `3px #027EA6`** + số + H4 + mô tả 15px.
7. **CTA band**.

### 3. Dịch vụ (tổng) — `pages/Services.html`
Template WP: page (hub).
1. **Hero**: eyebrow + H1 "Dịch vụ thiết kế website" + mô tả.
2. **4 thẻ dịch vụ lớn**: grid **2×2**, gap 24px. Mỗi thẻ: nền trắng, viền 1px, **viền trái `4px #027EA6`**, padding `34px`. Nội dung: số `01–04` (Archivo 800 14px, `#C9E9F1`) → tên dịch vụ (Archivo 800, `--fs-h2`) → mô tả ngắn 16px (max 46ch) → "Chỉ từ **X đ**" (giá màu `#027EA6` 17px) → link "Xem bảng giá →". Hover lift + shadow-md.
3. **CTA band** ("Chưa chắc chọn dịch vụ nào?").

4 dịch vụ và giá khởi điểm:
| # | Dịch vụ | Chỉ từ | Trang |
|---|---|---|---|
| 01 | Website theo yêu cầu | 4.000.000đ | `ServiceCustom.html` |
| 02 | Website bán hàng | 4.900.000đ | `ServiceShop.html` |
| 03 | Website doanh nghiệp | 6.000.000đ | `ServiceBusiness.html` |
| 04 | Landing Page | 2.000.000đ | `ServiceLanding.html` |

### 4. Trang chi tiết từng dịch vụ (×4) — `pages/Service*.html`
Template WP: page (hoặc CPT `service` dùng chung 1 template).
Cấu trúc **giống nhau ở cả 4 trang**, chỉ khác nội dung:
1. **Hero**: breadcrumb "Dịch vụ / {tên}" → eyebrow "Dịch vụ & bảng giá" → H1 "Bảng giá *{tên dịch vụ}*" (phần tên màu `#027EA6`) → mô tả → 3 **chip**: "Giải pháp toàn diện" · "Bảo mật thông tin" · "Hoạt động mượt mà".
2. **Bảng giá — 3 gói**: grid 3 cột. Mỗi gói: tên gói, tag mô tả, **giá** (có tiền tố "chỉ từ" khi là giá sàn, hậu tố "đ"), thời gian thực hiện (kèm icon clock), blurb, **danh sách tính năng** với ô check vuông nền `#027EA6` + dấu ✓ trắng, và nút CTA ở cuối. **Gói giữa** là gói phổ biến: có ribbon "Gói phổ biến", viền brand nổi bật, nút `btn-primary` (2 gói còn lại dùng `btn-ghost`).
3. **Dự án liên quan**: eyebrow + H2 + 3 thẻ dự án (ảnh + tên + "Khách hàng" + "Năm hoàn thành").
4. **CTA band** ("Chưa chắc chọn gói nào?").

Toàn bộ nội dung 12 gói (4 dịch vụ × 3 gói) — tên gói, tag, giá, thời gian, blurb, list tính năng — nằm sẵn trong 4 file HTML tương ứng, **lấy trực tiếp từ đó**.

### 5. Dự án — Archive — `pages/Projects.html`
Template WP: `archive-project.php` (CPT `project`).
1. **Hero**: eyebrow + H1 "Những website đã thực hiện" + mô tả.
2. **Thanh lọc** (sticky dưới header, `top:74px`): 5 chip lọc theo taxonomy — `Tất cả` · `Website doanh nghiệp` · `Landing page` · `Website dịch vụ` · `Website bán hàng`. Chip: viền 1px, Archivo 600 14px, `padding:9px 16px`; **active** = nền `#027EA6` + chữ trắng; hover = viền + chữ brand.
3. **Lưới dự án**: 3 cột, gap 24px. Mỗi thẻ: ảnh `16/10` + body chứa danh mục (eyebrow nhỏ `#12889C`) → H3 tên dự án → **thông tin dự án** (Khách hàng / Lĩnh vực / Năm — *không phải mô tả ngắn*) → link "Xem chi tiết →". Hover lift. Có trạng thái rỗng "Chưa có dự án trong nhóm này."
4. **Phân trang**: các ô `44×44px`, viền 1px, active nền brand.
5. **CTA band**.

### 6. Dự án — Single (case study) — `pages/ProjectDetail.html`
Template WP: `single-project.php`.
1. **Hero + overview**: breadcrumb → eyebrow danh mục → H1 (max 20ch) → mô tả 19px → **thông tin khách hàng (`.meta-inline`) ngay sau mô tả**: 4 cặp key/value — Khách hàng · Lĩnh vực · Loại website · Thời gian (key: Archivo 700 12px uppercase `#4D5A61`; value: Archivo 700 17px `#002735`) → nút "Xem website" (`btn-ghost` + icon external) → ảnh hero `16/8`.
2. **Vấn đề**: cột hẹp `820px`, eyebrow + H2 + 2 đoạn 18px.
3. **Giải pháp**: nền `#F6F8F9`. Intro hẹp, rồi **2 hàng xen kẽ** grid `1fr 1fr` (hàng 2 đảo thứ tự): text (H3 + 2 đoạn) ↔ ảnh `4/3`.
4. **Kết quả**: grid 2 cột, mỗi item: ô check vuông `26×26px` nền `#027EA6` + ✓ trắng, kèm tiêu đề đậm + mô tả. 4 item.
5. **Gallery**: nền `#F6F8F9`, grid 2×2 ảnh `16/10`.
6. **Dịch vụ liên quan**: 3 thẻ (số + H4 + mô tả), hover viền brand.
7. **Dự án khác**: nền `#F6F8F9`, 3 thẻ dự án.
8. **CTA band**.

### 7. Liên hệ — `pages/Contact.html`
Template WP: page.
1. **Hero**: eyebrow + H1 + mô tả.
2. **Grid 2 cột** — *thứ tự có chủ đích*:
   - **Trái: "Cách nhanh nhất"** (nổi bật) — các kênh liên hệ trực tiếp dạng khối lớn: **Zalo**, **Messenger**, **Hotline**, **Email**; mỗi khối có icon + nhãn + giá trị, viền 1px, hover đổi viền brand.
   - **Phải: "Hoặc để lại thông tin"** (phụ) — form: Họ tên, Số điện thoại, Email, Dịch vụ quan tâm (select), Nội dung (textarea) + nút gửi `btn-primary`. Input: viền `1px #DBE2E6`, radius 0, focus viền `#027EA6`. Trên WP dùng **Contact Form 7 / WPForms** rồi override CSS về radius 0.
3. **Khu vực làm việc**: heading kèm icon pin + khối bản đồ/khu vực.
4. **CTA band**.

---

## Interactions & Behavior

### Reveal on scroll (mọi trang)
IntersectionObserver, `threshold: .08–.12`, thêm class → `opacity 0→1` + `translateY(20px)→0` trong `.6s`. Unobserve sau khi hiện (chỉ chạy 1 lần).

### Trang chủ — Dự án tiêu biểu (scroll-driven, cần custom JS)
Section cao `440vh`, phần tử con `position:sticky; top:0; height:100vh`.
Tính `progress = clamp(0..1)` của `-rect.top / (sectionHeight - innerHeight)`, rồi `index = floor(progress * 5)`. Chỉ card thứ `index` có `opacity:1` (transition `.5s`), đồng thời cập nhật dãy số + chấm + bộ đếm. Bấm số/chấm → `window.scrollTo` tới `sectionTop + total * ((i + 0.5) / 5)` với `behavior:'smooth'`.

### Trang chủ — Quy trình 5 bước (scroll-driven, cần custom JS)
Section cao `420vh`, sticky 100vh.
Từ cùng `progress`: `activeIndex = round(progress * 4)`. Dịch track bằng `translateX` sao cho **node active nằm chính giữa viewport** (offset = tâm viewport − tâm item active). Item `< active` nhận class *done* (✓), item `= active` nhận *active*, còn lại *sắp tới*. Thanh fill trên đường nối rộng `progress * 100%`.

> **Lưu ý Flatsome:** cả hai hiệu ứng này **không có sẵn** trong Flatsome/UX Builder. Dựng bằng một Text/HTML block + đoạn JS/CSS custom (enqueue trong child theme). Không dùng plugin scroll-jack chung vì cần logic riêng.

### Mobile (≤900px) — bắt buộc
- Header: ẩn nav + nút, hiện hamburger.
- **Bỏ hoàn toàn scroll-pin**: `height:auto`, sticky → `static`. Dự án tiêu biểu → **các card xếp dọc** (đều `opacity:1`, ẩn dãy số/chấm). Quy trình → timeline **cuộn ngang bằng tay** (`overflow-x:auto`, bỏ `translateX`), tất cả bước hiển thị trạng thái hoàn thành.
- Mọi grid nhiều cột → 1 cột (footer → 2 cột; thẻ dịch vụ tổng → 1 cột).
- Section padding giảm về `52–64px`.
- Body luôn `16px`.

### Form validation (trang Liên hệ)
Bắt buộc: Họ tên, Số điện thoại (hoặc Email). Email đúng định dạng; số điện thoại VN. Hiển thị lỗi bằng `--error #D93C3C` dưới field. Thành công: thông báo dùng `--success #1E9E57`. (Prototype chưa nối backend.)

### Hover/Focus states — checklist
Nút (4 variant, xem bảng trên) · nav item · thẻ card (lift + shadow + viền) · chip lọc · link mũi tên (dịch phải) · ô phân trang · input focus · số/chấm điều hướng dự án. Focus-visible: `outline:3px solid #6BDFEE; offset 2px`.

---

## State Management
Prototype dùng JS thuần, không framework. State cần có:
- `activeProjectIndex` (0–4) — trang chủ, dẫn xuất từ scroll progress.
- `activeStepIndex` (0–4) + `progress` (0–1) — timeline quy trình.
- `activeFilter` (`all` | slug taxonomy) — archive dự án, lọc client-side trong prototype; **trên WordPress nên dùng query theo taxonomy** (hoặc AJAX filter) thay vì ẩn/hiện bằng CSS.
- Trạng thái form: values, errors, submitting, success.

Trên WordPress, dữ liệu động lấy từ:
- **CPT `project`** + taxonomy danh mục dự án. SCF fields: khách hàng, lĩnh vực, loại website, thời gian, URL website, ảnh hero, gallery, vấn đề, giải pháp (repeater: tiêu đề/mô tả/ảnh), kết quả (repeater: tiêu đề/mô tả), dịch vụ liên quan (relationship).
- **CPT `service`** (hoặc 4 page). SCF fields: tên, mô tả ngắn, giá khởi điểm, mô tả dài, chips, **repeater gói giá** (tên gói, tag, tiền tố giá, giá, thời gian, blurb, repeater tính năng, cờ "phổ biến"), dự án liên quan.
- Trang chủ: SCF cho 3 promise, 3 dịch vụ nổi bật, 5 dự án showcase (relationship), 4 giá trị, 5 bước quy trình.
- Options page cho thông tin liên hệ (Zalo, Messenger, hotline, email) dùng lại ở header/footer/CTA/Contact.

---

## Assets
Kèm trong gói (`assets/`):
- `logo-horizontal.png` — logo ngang (dùng ở header, cao 30px)
- `logo-horizontal-white.png` — logo ngang trắng (footer, cao 28px)
- `logo-square.png`, `logo-square-white.png` — logo vuông
- `favicon.png`
- `fonts/Archivo-Variable.ttf`, `fonts/Archivo-Italic-Variable.ttf`, `fonts/SpaceGrotesk-Variable.ttf`

Nguồn: file thương hiệu do khách cung cấp (`logo_namlamweb_*.png`) và font Archivo + Space Grotesk (variable TTF).

**Còn thiếu — cần khách cung cấp:** screenshot các dự án thật, ảnh chân dung trang Giới thiệu, ảnh gallery case study. Hiện là placeholder gạch chéo có nhãn (`repeating-linear-gradient(135deg,#EAEFF2 0 16px,#E3EAEE 16px 32px)`).

Icon: không dùng icon font hay emoji — tất cả là CSS mask từ inline SVG data-URI (xem mục Icon system).

---

## Files

### Design reference (HTML)
```
pages/
├── index.html              Trang chủ (có 2 section scroll-driven)
├── About.html              Giới thiệu
├── Services.html           Dịch vụ — trang tổng (4 thẻ lớn)
├── ServiceCustom.html      Dịch vụ — Website theo yêu cầu
├── ServiceShop.html        Dịch vụ — Website bán hàng
├── ServiceBusiness.html    Dịch vụ — Website doanh nghiệp
├── ServiceLanding.html     Dịch vụ — Landing Page
├── Projects.html           Dự án — Archive (lọc + phân trang)
├── ProjectDetail.html      Dự án — Single (case study)
└── Contact.html            Liên hệ
```
Mở trực tiếp bằng browser. Các trang đã link chéo nhau qua nav nên click-through được cả flow.

### Design tokens (CSS)
```
styles.css          entry point — chỉ chứa @import
tokens/fonts.css        @font-face (Archivo, Space Grotesk)
tokens/colors.css       màu + alias semantic
tokens/typography.css   font family, type scale (kèm breakpoint), weight, line-height
tokens/spacing.css      spacing scale, radius, container, shadow
```
Copy 4 file token này vào child theme và enqueue **trước** CSS của Flatsome để override được. Lưu ý: đường dẫn font trong `tokens/fonts.css` là `../assets/fonts/…` — sửa lại theo cấu trúc child theme.

### Thứ tự triển khai đề xuất
1. Child theme + tokens + `@font-face` + reset radius 0 toàn site.
2. Header + Footer + CTA band (dùng lại mọi trang).
3. Các trang tĩnh: Giới thiệu → Liên hệ.
4. CPT `service` + 4 trang dịch vụ + trang tổng.
5. CPT `project` + Archive (lọc) + Single (case study).
6. Trang chủ, làm 2 section scroll-driven sau cùng.
7. Rà soát responsive ≤900px và `prefers-reduced-motion`.
