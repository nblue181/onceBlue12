# Phân tích hiệu ứng — ximitech.vn

Khảo sát ngày 13/08/2026, phục vụ việc dựng giao diện mới cho **anmitech.vn**.
Toàn bộ số liệu dưới đây đọc trực tiếp từ CSSOM và DOM của site, không phải phỏng đoán.

> **Lưu ý khi tái sử dụng:** hiệu ứng và kỹ thuật là kiến thức chung, dùng lại được.
> Nhưng **không sao chép** nội dung chữ, hình ảnh, bảng giá, feedback khách hàng hay
> nhận diện của XimiTech — đó là tài sản của họ. anmitech.vn cần nội dung và bảng màu riêng.

---

## 1. Nền tảng kỹ thuật

| Hạng mục | Thực tế |
|---|---|
| Build | Vite — chỉ 1 file JS (`index-*.js`) và 1 file CSS (`index-*.css`) |
| Framework | React SPA |
| CSS | Tailwind + shadcn/ui (có `accordion-up/down`, `enter/exit`) + sonner (toast) |
| Thư viện animation | **Không có** GSAP / AOS / Swiper / Lenis / jQuery. Toàn bộ bằng CSS keyframes + React state |
| Tổng phần tử DOM | 1.765 |
| Chiều cao trang | 14.845px (~15 màn hình) |
| Ảnh | 55 ảnh, 21 ảnh dùng `loading="lazy"` |

**Kết luận đáng chú ý:** một trang nhiều hiệu ứng như vậy mà không dùng thư viện animation nào.
Tất cả là `@keyframes` thuần + Tailwind utility. Đây là hướng nên học theo — nhẹ và dễ kiểm soát.

## 2. Bảng màu và chữ

```
--primary     195 100% 50%   →  #00BFFF  (cyan điện)
--accent      190 80% 45%    →  #17B8CE
--background  220 40% 10%    →  #0F1624  (navy rất tối)
--foreground  0 0% 100%      →  trắng
--border      220 30% 20%
--muted       220 35% 15%
--radius      0.5rem
```

| | Font | Ghi chú |
|---|---|---|
| Tiêu đề | **Epilogue** 700 | `letter-spacing: -0.045em` — bó chữ rất chặt, H1 desktop 72px |
| Nội dung | **Manrope** 400 | 16px, màu trắng |

Nút chính bo tròn hoàn toàn (`border-radius: 9999px`) — ngược hẳn với hệ góc vuông của Nam làm WEB.

Site chạy **dark mode duy nhất**, không có công tắc sáng/tối.

---

## 3. Kiến trúc trang — 15 khối

| # | id | Vai trò | Cao (px) |
|---|---|---|---|
| 1 | *(header)* | `fixed top-0 z-50`, nền trong suốt, **không đổi khi cuộn** | 144 |
| 2 | `home` | Hero, `min-h-[92svh]` | 1172 |
| 3 | `ai-advisor` | Ô chat tư vấn AI | 900 |
| 4 | `word-cloud` | Mây chữ trôi | 1092 |
| 5 | `services` | Dịch vụ | 1092 |
| 6 | `kho-giao-dien` | Thư viện mẫu, lọc theo ngành | 1012 |
| 7 | *(không id)* | Dải gradient primary → cyan | 1092 |
| 8 | `pricing` | Bảng giá | 1092 |
| 9 | `feedback` | Ảnh phản hồi khách | 1092 |
| 10 | `tech` | Công nghệ | 1060 |
| 11 | `faq` | Câu hỏi thường gặp | 1060 |
| 12 | `contact-section` | CTA Zalo | 1092 |
| 13 | `about` | Giới thiệu + quy trình 01–04 | 1092 |
| 14 | `contact` | Footer | 737 |

Nhịp nền: gần như **toàn bộ đều tối**, chỉ khác nhau ở độ đậm (`hsl(220 40% 4%)` → `hsl(220 35% 8%)`)
và một dải gradient sáng ở khối 7 để cắt mạch. Cách này khác với cách đan xen sáng–tối mình đang dùng.

---

## 4. Danh mục hiệu ứng

### 4.1 Nền động — "tech background"

Bốn lớp chồng nhau, tất cả `position: absolute; pointer-events: none`:

**`.tech-grid`** — lưới kỹ thuật
```css
background-image: linear-gradient(to right, rgba(41,201,255,.1) 1px, transparent 1px),
                  linear-gradient(rgba(41,201,255,.1) 1px, transparent 1px);
background-size: 52px 52px;
/* + mask để mờ dần ở rìa */
animation: tech-grid-drift 20s ease-in-out infinite;
```
`tech-grid-drift` dịch `background-position` 18px/-12px và `scale(1.012)` — lưới trôi rất chậm, gần như không nhận ra nhưng khiến nền "sống".

**`.tech-grid::before`** — quầng sáng giữa lưới, `mix-blend-mode: screen`, animation `tech-grid-light` 9s đổi `opacity 0.38 ↔ 0.72`.

**`.tech-grid::after`** — vệt sáng quét ngang, `tech-grid-shimmer` 8s chạy `translateX(-55% ↔ 55%)`.

**`.tech-wave-a/.tech-wave-b`** — hai quả cầu mờ khổng lồ
```css
width: 70vw; height: 70vw; min-width: 520px;
border-radius: 9999px; filter: blur(54px); opacity: .35;
```
- `a`: `left:-18% top:12%`, `rgba(0,191,255,.28)`, drift 18s
- `b`: `right:-20% bottom:-28%`, `rgba(10,220,235,.2)`, drift 22s

Drift = `translate3d(8%, -6%, 0) scale(1.08)` ở mốc 50%.

**`.tech-scanline`** — vệt quét dọc, gradient trong suốt → `rgba(102,217,255,.08)` → trong suốt, `tech-scan` 7s linear chạy `translateY(-100% → 100%)`.

> So sánh với aura mình vừa làm cho namtrandang: cùng ý tưởng lưới chấm + quầng sáng, nhưng
> XimiTech **cho các lớp chuyển động chậm** thay vì đứng yên, và dùng `blur(54px)` chứ không
> phải 175px nên chi phí vẽ thấp hơn nhiều.

### 4.2 Hiệu ứng chữ

**Karaoke reveal** — chữ hiện dần từ trái sang, kèm khử mờ:
```css
@keyframes karaoke-text-reveal {
  0%   { clip-path: inset(-0.08em 100% -0.2em -0.02em); filter: blur(2px); }
  55%  { filter: blur(0); }
  100% { clip-path: inset(-0.08em -0.02em -0.2em); filter: blur(0); }
}
@keyframes karaoke-highlight-sweep {
  0%   { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
```
Hai keyframe chạy song song trên `.karaoke-reveal-text`. Trên site chỉ dùng cho **một cụm từ
trong H1** ("cá nhân hóa") — điểm nhấn duy nhất, không lạm dụng.

**Word soft reveal** — từng chữ hiện lên mềm:
```css
@keyframes wordSoftReveal {
  0%   { opacity: 0;    filter: blur(10px) brightness(1.3); }
  45%  { opacity: .75;  filter: blur(3px)  brightness(1.18); }
  100% { opacity: 1;    filter: blur(0)    brightness(1); }
}
.word-soft-in { animation-duration: 760ms; ease-out; fill-mode: both; }
```
Dùng ở section `word-cloud`, mỗi từ có `animation-delay` lệch nhau tạo hiệu ứng gợn sóng.

**Con trỏ nhấp nháy** sau chữ: `animate-pulse` trên một `div` 2px × 24px màu primary — giả lập con trỏ đang gõ.

### 4.3 Trôi lơ lửng (float)

Sáu biến thể, chu kỳ dài để không gây rối mắt:

| Class | Thời lượng | Quỹ đạo |
|---|---|---|
| `animate-float` | 3s (desktop) / 4s (mobile) | lên xuống 15px |
| `animate-float-1` | 12s | 4 điểm: (10,-15) → (-5,-25) → (-15,-10) |
| `animate-float-2` | 15s | tương tự, lệch pha |
| `animate-float-3` | 18s | |
| `animate-float-4` | 14s | |
| `animate-float-5` | 16s | |
| `animate-float-vertical` | 13s | như float1 nhưng kèm `rotate(-90deg)` |

Mỗi từ trong mây chữ nhận ngẫu nhiên một trong 5 lớp → không từ nào trôi giống từ nào.

### 4.4 Spotlight theo con trỏ

Ở `word-cloud` có một `div` 500×500px:
```
pointer-events-none absolute w-[500px] h-[500px] rounded-full
transition-opacity duration-300
background: radial-gradient(circle, rgba(34,211,238,.08), transparent)
```
`opacity: 0` mặc định, JS cập nhật `left/top` theo chuột và bật opacity khi hover — vệt sáng đi theo con trỏ.

Tương tự, nền hero có `.lightweight-tech-bg.is-hovering .tech-wave-a { opacity: .48 }` — rê chuột vào vùng nền thì quầng sáng đậm lên (từ .35 → .48).

### 4.5 Marquee

```css
@keyframes scroll-left  { 0% { transform: translateX(0); }    100% { transform: translateX(-50%); } }
@keyframes scroll-right { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
.animate-scroll-left  { animation: scroll-left  30s linear infinite; }
.animate-scroll-right { animation: scroll-right 30s linear infinite; }
```
Kỹ thuật chuẩn: nhân đôi danh sách rồi chạy tới `-50%` để nối liền vô hạn.
**Ghi chú:** class có trong CSS nhưng lúc khảo sát không tìm thấy phần tử nào đang dùng —
có thể đã gỡ khỏi giao diện hoặc chỉ dùng ở trang con.

### 4.6 Hover — thống kê tần suất thực tế

| Class | Số lần dùng |
|---|---|
| `transition-all` | 161 |
| `transition-colors` | 84 |
| `hover:text-primary` | 82 |
| `duration-200` | 73 |
| `hover:border-primary/50` | 48 |
| `group-hover:opacity-100` | 40 |
| `backdrop-blur-sm` | 38 |
| `hover:shadow-lg` | 29 |
| `hover:-translate-y-0.5` | 21 |

Công thức hover của thẻ: **viền sáng lên + nhấc 2px + đổ bóng + đổi màu tiêu đề**, thời lượng
200ms. Rất nhất quán trên toàn site.

### 4.7 Accordion FAQ

Tự viết, không dùng radix (không có `aria-expanded`). Bấm nút → panel giãn từ 0 lên 197px,
icon `chevron-down` của lucide xoay. Có sẵn keyframe `accordion-up/down` của shadcn nhưng
phần FAQ dùng cách khác.

### 4.8 Chấm trạng thái

`animate-ping` (Tailwind) trên chấm "Online" ở ô chat AI — vòng tròn lan ra rồi mờ dần.

---

## 5. Cách họ xử lý hiệu năng và khả năng tiếp cận

Đây là phần đáng học nhất:

**Tôn trọng "giảm chuyển động".** Site dùng biến thể `motion-safe:` của Tailwind —
`motion-safe:will-change-transform` (25 lần), `motion-safe:will-change-[filter,opacity]` (24 lần).
Khi máy khách bật giảm chuyển động, mọi animation về gần 0 giây. Trình duyệt lúc khảo sát
đang bật chế độ này nên phần lớn hiệu ứng đứng yên — đúng như thiết kế.

**`will-change` chỉ đặt khi cần**, và chỉ cho `transform` / `filter, opacity` — hai nhóm thuộc tính
GPU xử lý được, không gây reflow.

**`blur(54px)` chứ không phải 150–250px.** Blur càng lớn càng đắt; 54px đã đủ mềm.

**`transform: translateZ(0)`** trên các lớp nền để đẩy lên GPU layer.

**Chu kỳ animation rất dài** (7–22 giây). Nền chuyển động nhưng không cướp sự chú ý khỏi nội dung.

---

## 6. Gợi ý cho anmitech.vn

Những gì nên mang sang:

- Kiến trúc nền nhiều lớp (lưới + quầng + scanline) — hiệu quả thị giác cao, chi phí thấp.
- Chu kỳ dài 7–22s và `motion-safe:` cho mọi animation.
- Công thức hover thống nhất: viền + nhấc nhẹ + bóng, 200ms.
- Karaoke reveal chỉ cho **một** cụm từ trọng tâm ở H1.
- Không cần thư viện animation.

Những gì cần quyết định khác đi:

- **Bảng màu.** Cyan `#00BFFF` là màu của XimiTech. anmitech.vn cần hệ màu riêng.
- **Font.** Epilogue + Manrope là lựa chọn của họ.
- **Bo góc.** Họ bo tròn hoàn toàn nút; đây là quyết định nhận diện, không phải mặc định.
- **Chỉ có dark mode.** Cân nhắc làm cả hai giao diện như đã dựng cho namtrandang.
- **Trang dài 14.845px** với 15 khối — cân nhắc rút gọn.

Những gì tuyệt đối không mang sang: nội dung chữ, bảng giá, ảnh feedback, tên khách hàng,
và các con số thành tích (244+, 50+, 36…) — đó là dữ liệu của họ.
