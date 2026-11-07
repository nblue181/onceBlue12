# Anmitech — Hướng dẫn dùng bộ hiệu ứng

## File trong gói

```
tailwind.config.js          màu thương hiệu + keyframe cho Tailwind
src/styles/effects.css      toàn bộ hiệu ứng, CSS thuần
src/lib/effects.js          JavaScript thuần, điều khiển bằng data-*
src/react/fx.jsx            hook và component cho React
logo-anmitech.svg           logo tách từ file .ai
docs/BRAND-TOKENS.md        bảng màu
docs/XIMITECH-PHAN-TICH-HIEU-UNG.md   khảo sát gốc
```

Bộ này **không phụ thuộc thư viện animation nào** — không GSAP, không AOS, không Framer Motion.
Toàn bộ là `@keyframes` + IntersectionObserver, giống cách ximitech.vn làm.

## Cài vào dự án React + Vite

```js
// src/main.jsx
import './styles/effects.css';
```

```jsx
// src/App.jsx
import { useEffects } from './react/fx';

export default function App() {
  useEffects();          // chạy một lần
  return <>…</>;
}
```

Khi nội dung đến từ WordPress REST API, truyền deps để hiệu ứng gắn lại vào phần tử mới:

```jsx
const { data } = usePosts();
useEffects([data]);
```

---

## Bảng hiệu ứng

| Hiệu ứng | Cách dùng | Thông số |
|---|---|---|
| **Nền Aura** — ma trận chấm + 2 quầng blur | `.aura-bg` + 3 lớp `.aura-layer-*` | chấm 1px/20px trôi 24s · blur 175px và 138px (252/198 trên màn lớn) · trộn `screen` |
| **Đánh chữ** | `data-fx-type='["cụm 1","cụm 2"]'` | 70ms/ký tự, dừng 1600ms, lặp vô hạn |
| Nền lưới kẻ ô (kiểu XimiTech) | `<TechBackground />` | lưới 20s · quầng 9s · vệt ngang 8s · cầu mờ 18s và 22s · quét dọc 7s |
| Vệt sáng theo chuột | `<Spotlight>` hoặc `data-fx-spotlight` | 500px, hiện/ẩn 300ms |
| Nền đậm lên khi rê chuột | `data-fx-hover-bg` trên section | opacity .35 → .48 |
| Chữ karaoke | `<Karaoke>` | 1100ms, `clip-path` + khử mờ |
| Từng từ hiện so le | `<Words text="…" />` | 760ms, lệch 70ms mỗi từ |
| Trôi lơ lửng | `fx-float-1` … `fx-float-5` | 12–18s, 5 quỹ đạo khác nhau |
| Hiện dần khi cuộn | `<Reveal>` hoặc `data-fx-reveal` | 600ms, ngưỡng 12% |
| Băng chạy | `<Marquee>` | 30s, dừng khi rê chuột |
| Đếm số | `<CountUp to={244} suffix="+" />` | 1400ms, ease-out |
| Accordion | `data-fx-accordion` | 260ms theo chiều cao thật |
| Thẻ hover | `fx-card` | viền + nhấc 2px + bóng, 200ms |

## Ví dụ một section hoàn chỉnh

```jsx
<section className="relative overflow-hidden py-20" data-fx-hover-bg>
  <TechBackground />

  <div className="container relative z-10">
    <Reveal>
      <p className="text-brand-400 text-sm font-bold uppercase tracking-[0.14em]">
        Dịch vụ
      </p>
      <h2 className="mt-3 text-4xl font-bold tracking-tightest text-white">
        Website <Karaoke>đúng nhu cầu</Karaoke>
      </h2>
    </Reveal>

    <div className="mt-10 grid gap-6 md:grid-cols-3">
      {items.map((it, i) => (
        <Reveal key={it.id} delay={i} className="fx-card p-6">
          <h3 className="text-xl font-bold text-white">{it.title}</h3>
          <p className="mt-2 text-white/70">{it.desc}</p>
        </Reveal>
      ))}
    </div>
  </div>
</section>
```

Ba điều bắt buộc nhớ:

1. Khối cha phải có `relative overflow-hidden`.
2. Nội dung phải có `relative z-10`, nếu không sẽ bị các lớp nền che.
3. `delay={i}` để các thẻ hiện so le chứ không bật cùng lúc.

---

## Ba nguyên tắc lấy từ khảo sát

**Chu kỳ dài.** Mọi animation nền đều 7–22 giây. Nền chuyển động nhưng không cướp sự chú ý
khỏi nội dung. Đây là điểm khác biệt giữa "sang" và "rối".

**Blur 54px, không phải 150px.** Blur càng lớn trình duyệt càng phải vẽ lại nhiều. 54px đã đủ
mềm cho quầng sáng. Đây cũng là lý do bộ này bỏ `filter: blur()` ở lớp phủ toàn màn hình.

**`will-change` chỉ bật khi có chuyển động.** Trong `effects.css`, `will-change` nằm trong
`@media (prefers-reduced-motion: no-preference)`. Bật vô điều kiện sẽ tốn bộ nhớ GPU cho cả
những máy đã tắt hiệu ứng.

## Nếu không thấy hiệu ứng nào chạy

Triệu chứng: chữ **hiện ra** nhưng không trượt, không đánh chữ, marquee đứng im.

Gần như chắc chắn là máy đang bật **giảm chuyển động** ở hệ điều hành, không phải lỗi code.

| Hệ | Đường dẫn |
|---|---|
| Windows 11 | Settings → Accessibility → Visual effects → **Animation effects** |
| macOS | System Settings → Accessibility → Display → **Reduce motion** |
| Chrome DevTools | Rendering → Emulate CSS `prefers-reduced-motion` |

Muốn xem hiệu ứng bất chấp thiết lập đó (chỉ dùng cho demo, **không dùng trên production**):

```js
import { forceMotion } from './lib/effects';
forceMotion(true);   // thêm .fx-force-motion vào <html>
```

Trang `demo.html` bật sẵn chế độ này và hiện một dải cảnh báo màu vàng khi phát hiện máy
đang tắt chuyển động.

## Giảm chuyển động

Toàn bộ hiệu ứng tự tắt khi người dùng bật "giảm chuyển động" ở hệ điều hành — nền đứng yên,
chữ hiện luôn, số hiện luôn kết quả, `reveal` rút còn 150ms và bỏ dịch chuyển.

Đây không phải chi tiết phụ: người bật thiết lập này thường vì chóng mặt hoặc đau nửa đầu.
Trình duyệt lúc khảo sát ximitech.vn đang bật chế độ đó, và site của họ xử lý đúng — nên
phần lớn hiệu ứng đứng yên khi tôi xem.

## Hai giao diện Sáng / Tối

Đặt `data-theme` trên thẻ `<html>`, giá trị `light` hoặc `dark`. Không đặt thì mặc định tối.

Bộ hiệu ứng đã có sẵn hai bảng biến. Bản sáng **không phải chỉ đảo màu** — lưới và quầng sáng
phải dùng màu đậm hơn với alpha thấp hơn, nếu không lưới sẽ chìm mất còn quầng sáng loang
thành vệt bẩn trên nền trắng.

| Biến | Tối | Sáng |
|---|---|---|
| `--fx-bg` | `211 60% 7%` | `210 40% 98%` |
| `--fx-grid-line` | brand-400 / 10% | brand-500 / 10% |
| `--fx-card-bg` | brand-400 / 3% | `#FFFFFF` |
| `--fx-wave-opacity` | 0.35 | 0.5 |

Đặt giao diện **trước khi trang vẽ**, bằng script nội tuyến trong `<head>`:

```html
<script>
(function () {
  try {
    var saved = localStorage.getItem('anmitech-theme');
    var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
  } catch (e) { document.documentElement.setAttribute('data-theme', 'dark'); }
})();
</script>
```

Nếu đợi tới khi React chạy xong mới đặt, khách sẽ thấy một nháy trắng rồi mới chuyển sang tối.

## Chỉnh tốc độ khi trình bày

Mọi thời lượng đều nhân với `--fx-speed`. Mặc định `1` là tốc độ thật.

```js
document.documentElement.style.setProperty('--fx-speed', '4'); // chậm gấp 4
```

Dùng khi cần soi từng bước hiệu ứng, hoặc khi demo cho khách xem. **Đừng để khác 1 khi lên
production** — hiệu ứng chậm gấp đôi sẽ thành lê thê.

## Điều chỉnh màu hiệu ứng

Tất cả nằm ở biến CSS đầu file `effects.css`:

```css
--fx-grid-line   /* màu đường lưới */
--fx-glow-core   /* quầng sáng giữa */
--fx-wave-a/b    /* hai quả cầu mờ */
--fx-scan        /* vệt quét dọc */
```

Muốn nền dịu hơn thì giảm alpha; muốn mạnh hơn thì tăng. Không nên đổi màu gốc `--brand`
vì nó lấy từ logo.

---

## Còn thiếu để bắt đầu dựng

- **Nội dung thật** trong WordPress — tối thiểu 3 Dịch vụ, 2 Dự án, trang Cấu hình.
  Frontend đọc được rồi nhưng `/wp/v2/services` vẫn trả `[]`.
- ~~Font~~ Xong. **Source Sans 3** (Adobe, qua Google Fonts). Đã xác minh có subset
  `vietnamese` đầy đủ: `U+1EA0-1EF9`, `Ăă Đđ Ơơ Ưư` và `₫`. Khai ở `tailwind.config.js`,
  `demo.html` và `build-demo.ps1` — sửa font thì phải sửa cả ba, nếu không build lại
  `demo.html` sẽ quay về font cũ.
- ~~Cấu trúc trang~~ Xong. Bố cục theo namtrandang.com — 7 section, xem bảng dưới.
  Riêng phần Dịch vụ dùng bảng so sánh nhiều cột kiểu ximitech.vn, dữ liệu lấy từ
  nhóm trường `cmp_*` mới thêm vào CPT Dịch vụ.
- ~~SPA hay Next.js~~ Chốt **Next.js** — cần SSR cho SEO, chạy trên Vercel,
  WordPress chỉ còn vai trò nhập liệu.
- ~~Logo~~ Xong. Bộ logo chính thức nằm ở `logo svg/` — đủ bản ngang, dọc,
  trắng, đen, không slogan và favicon. Xem `docs/BRAND-TOKENS.md`.

## Bố cục trang chủ

Bảy section, thứ tự lấy theo namtrandang.com. Cột cuối là nguồn dữ liệu — trường nào
chưa có trong WordPress thì frontend không dựng được section đó.

| # | Section | Hiệu ứng dùng | Nguồn dữ liệu |
|---|---|---|---|
| 1 | Hero | Đánh chữ + `Karaoke` | `hero_*` ở trang Cấu hình |
| 2 | Giới thiệu | `Reveal` + `Words` | `about_*` ở Cấu hình |
| 3 | **Dịch vụ — bảng so sánh** | `Reveal` so le, `fx-card` ở cột đề xuất | `cmp_*` trên CPT Dịch vụ |
| 4 | Dự án | **Gắn với cuộn** — ghim màn hình, chạy ngang | CPT Dự án, lọc `featured` |
| 5 | Giá trị cốt lõi | `Reveal` so le + `CountUp` | repeater `values`, `stats` |
| 6 | Quy trình | **Gắn với cuộn** — đường mốc chạy dần | `process_*` + repeater `process` |
| 7 | CTA | `fx-btn-brand` | `cta_title`, `cta_desc` |

Không section nào tự dựng nền. Nền là **của cả website**, xem mục dưới.

Cả bảy section đều đã có nơi nhập liệu trong WordPress. Muốn đổi thứ tự section thì
sửa ở frontend, không cần đụng cấu trúc dữ liệu.

## Hai loại hiệu ứng, đừng lẫn

| | `Reveal` / `Karaoke` / `Words` / `CountUp` | Section Dự án và Quy trình |
|---|---|---|
| Cơ chế | IntersectionObserver, bật **một lần** | Đo vị trí cuộn **liên tục** |
| Cuộn ngược lại | giữ nguyên trạng thái đã hiện | chuyển động lùi theo |
| Code | `src/lib/effects.js` | `src/react/motion.js` |

### Hiệu ứng chỉ chạy khi cuộn tới — `.is-in`

`.fx-karaoke` và `.fx-word` trước đây khai `animation` ngay ở class gốc, nên chúng chạy
từ giây đầu tiên khi tải trang. Với một cụm chữ nằm ở section thứ hai trở xuống thì hiệu
ứng đã diễn ra xong trước khi khách cuộn tới — **khách không bao giờ thấy**.

Nay animation nằm ở `.fx-karaoke.is-in` và `.is-in .fx-word`. JS thêm `.is-in` vào
`[data-fx-reveal]` khi khối lọt vào khung nhìn, nên mọi `Karaoke` / `Words` đặt trong
`<Reveal>` đều tự chờ đúng lúc.

Không gate bằng cách đặt `opacity: 0` rồi chờ JS: máy không chạy được JS sẽ **mất chữ
vĩnh viễn**. Ở đây chữ luôn hiện, chỉ animation là phần thêm vào.

### `prefersReducedMotion()` phải kiểm tra `.fx-force-motion`

JS và CSS phải nói cùng một chuyện. CSS dùng `html:not(.fx-force-motion)`; nếu JS chỉ đọc
`matchMedia` thì khi bấm nút bật hiệu ứng, CSS mở animation trong khi JS vẫn tưởng đang
giảm chuyển động nên đã gán `is-in` cho **mọi** phần tử từ lúc tải — toàn bộ hiệu ứng nổ
một lượt ngoài khung nhìn, đúng lỗi vừa đi sửa.

Vì vậy `prefersReducedMotion()` trong `src/lib/effects.js` là nguồn duy nhất, cả
`effects.js` và `motion.js` đều gọi nó.

### Giảm chuyển động: đổi bố cục, không để section đứng im

Hai section gắn với cuộn **không** render bản ghim khi giảm chuyển động — chúng đổi sang
lưới dọc và đường mốc dọc. Để nguyên bản ghim thì khách phải cuộn qua `n × 100vh` mà nội
dung không đổi, trông như trang bị treo.

Mobile cũng dùng đúng bố cục dọc đó: ghim màn hình trên điện thoại rất khó chịu.

## Nền Aura chạy suốt cả website

Đặt **một lần** ở `app/layout.jsx` bằng `<AuraBackground />`, không đặt trong từng section.

```
src/components/AuraBackground.jsx   5 lớp
app/aura-theme.css                  ghi đè biến, phải nạp SAU effects.css
```

| Lớp | Là gì | Chu kỳ |
|---|---|---|
| `.aura-layer-1` | ma trận chấm 1px / 20px | 24s |
| `.aura-layer-2` | quầng sáng lớn | 20s |
| `.aura-layer-3` | quầng sáng phụ, ngược chiều | 26s |
| `.aura-sweep` | vệt sáng quét ngang, blur 54px | 19s |
| `.aura-sweep-2` | vệt thứ hai, hẹp hơn, ngược chiều | 31s |

Ba điều quyết định cách nó hoạt động:

**`position: fixed`, không phải `absolute`.** Nền phải đứng yên khi cuộn. Để `absolute`
thì trang dài bảy section sẽ có quầng sáng ở trên cùng rồi cuộn mất, phần dưới trơ nền phẳng.

**`isolation: isolate` trên `.aura-fixed`.** Ba lớp dùng `mix-blend-mode`. Không cắt ngữ
cảnh trộn thì chúng trộn màu với cả chữ và thẻ nội dung phía trên.

**Màu nền gốc đặt ở `<html>`, `<body>` để trong suốt.** Lớp Aura nằm ở `z-index: -1`,
tức phía sau `body` — `body` có màu đục là che mất Aura.

### Không dùng nền lưới kiểu XimiTech

`TechBackground` (lưới kẻ ô + hai quầng `.fx-wave` + vệt quét dọc) **không dùng trong dự
án này**. Nó vẫn còn trong `src/react/fx.jsx` cho dự án khác, nhưng trang Anmitech chỉ
dùng Aura một màu.

Kèm theo đó, `data-fx-hover-bg` cũng vô dụng ở đây: nó chỉ đổi opacity của `.fx-wave`,
mà không còn `.fx-wave` nào trên trang.

### Nền một màu

`effects.css` để `--aura-glow-2` là cyan `#03D0FF`. `app/aura-theme.css` ghi đè về cùng
họ xanh dương, vì `docs/BRAND-TOKENS.md` đã cảnh báo: cyan ở hue 191° nằm rất gần màu chủ
đạo của XimiTech (195°) — lấy nó làm quầng nền lớn thì nền đọc ra là XimiTech.

Cyan vẫn giữ vai trò điểm nhấn nhỏ ở `.fx-btn-brand` và `.fx-text-brand`, vì đó là đầu
sáng của gradient logo.

| Biến | Tối | Sáng |
|---|---|---|
| `--aura-base` | `#041627` | `#F4F8FC` |
| `--aura-glow-2` | brand-400 / 28% | brand-500 / 12% |
| `--aura-sweep` | brand-400 / 16% | brand-500 / 7% |
| `--aura-blend` | `screen` | `multiply` |

Alpha ở bản sáng thấp hơn hẳn: `multiply` trên nền trắng đẩy màu đậm lên rất nhanh, để
alpha cao thì vệt sáng thành vệt bẩn xám.
