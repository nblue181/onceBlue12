# Anmitech — Design tokens

Màu đọc trực tiếp từ bộ logo chính thức trong `logo svg/` (bản Founder đã chốt).
Không còn dùng giá trị tách từ `.ai` nữa — bản đó ra `#0074EE`, lệch so với file thật.

## Màu chính

| | Hex | HSL | Vai trò |
|---|---|---|---|
| **Brand** | **`#0073EA`** | `210.5 100% 45.9%` | Màu chủ đạo, đầu đậm của gradient |
| **Cyan** | **`#03D0FF`** | `191.2 100% 50.6%` | Đầu sáng của gradient, chỉ dùng làm điểm nhấn |

Giữ phần thập phân trong HSL. Làm tròn `210.5` thành `211` sẽ ra `#0075EB` — lệch
2 mức ở kênh xanh lá, đủ để logo và nền không khớp khi đặt cạnh nhau.

## Gradient logo

Logo **không phải một màu phẳng**. Nó dùng gradient sáu điểm dừng:

| Offset | Hex |
|---|---|
| 0% | `#03D0FF` |
| 18% | `#02B0F8` |
| 38% | `#0195F2` |
| 58% | `#0082ED` |
| 78% | `#0077EB` |
| 100% | `#0073EA` |

```css
--brand-gradient: linear-gradient(
  135deg,
  #03d0ff 0%, #02b0f8 18%, #0195f2 38%,
  #0082ed 58%, #0077eb 78%, #0073ea 100%
);
```

Đây là chi tiết khiến giao diện khớp logo chứ không chỉ "cùng màu xanh". Dùng cho:

- Nút chính — class `.fx-btn-brand`
- Một cụm chữ nhấn trong tiêu đề — class `.fx-text-brand`
- Hai quầng sáng của nền Aura (`--aura-glow-1` đậm, `--aura-glow-2` cyan)

Không dùng gradient cho: nền section lớn (băng màu), chữ body, viền mỏng dưới 2px.

## Tương phản — kiểm tra trước khi dùng làm màu chữ

| Màu | Trên nền trắng | Trên nền tối `#041627` |
|---|---|---|
| `#0073EA` | **4.5:1** — vừa đủ chuẩn AA cho chữ thường | 4.0:1 — chỉ đạt cho chữ lớn |
| `#03D0FF` | 1.8:1 — **không dùng làm chữ** | 10.0:1 — rất tốt |

Rút ra: nền sáng thì chữ nhấn dùng `#0073EA`, nền tối thì dùng `#03D0FF`.
Dùng ngược lại là chữ chìm mất.

`#0073EA` trên nền trắng đạt 4.5:1 sát ngưỡng — không còn biên an toàn. Nếu cần
chữ xanh nhỏ hơn 16px trên nền trắng thì lùi xuống `brand-600` (`#005EC2`).

## Thang màu

H=211, S=100%, chỉ đổi độ sáng. Bậc 500 là màu logo thật.

| Bậc | Hex | HSL |
|---|---|---|
| 50 | `#F0F7FF` | `211 100% 97%` |
| 100 | `#DBEDFF` | `211 100% 93%` |
| 200 | `#A3D0FF` | `211 100% 82%` |
| 300 | `#5CABFF` | `211 100% 68%` |
| 400 | `#248EFF` | `211 100% 57%` |
| **500** | **`#0073EA`** | `210.5 100% 45.9%` ← màu logo |
| 600 | `#005EC2` | `211 100% 38%` |
| 700 | `#004A99` | `211 100% 30%` |
| 800 | `#003B7A` | `211 100% 24%` |
| 900 | `#002C5C` | `211 100% 18%` |
| 950 | `#001B38` | `211 100% 11%` |

## tailwind.config.js

```js
colors: {
  brand: {
    50: '#F0F7FF',  100: '#DBEDFF', 200: '#A3D0FF', 300: '#5CABFF',
    400: '#248EFF', 500: '#0073EA', 600: '#005EC2', 700: '#004A99',
    800: '#003B7A', 900: '#002C5C', 950: '#001B38',
  },
  cyan: { brand: '#03D0FF' },
},
backgroundImage: {
  'brand-gradient': 'linear-gradient(135deg, #03D0FF 0%, #02B0F8 18%, #0195F2 38%, #0082ED 58%, #0077EB 78%, #0073EA 100%)',
  'brand-fade': 'linear-gradient(135deg, #03D0FF 0%, #0073EA 100%)',
},
```

Nếu dùng shadcn/ui thì khai HSL không có `hsl()` cho hợp quy ước của nó:

```css
:root {
  --primary: 210.5 100% 45.9%;
  --primary-foreground: 0 0% 100%;
}
```

## So sánh với XimiTech

| | XimiTech | Anmitech |
|---|---|---|
| Primary | `195 100% 50%` — cyan `#00BFFF` | `210.5 100% 45.9%` — xanh dương `#0073EA` |

Cách nhau 15° trên vòng màu. Cùng họ xanh nhưng phân biệt được: XimiTech ngả cyan,
Anmitech ngả xanh dương chuẩn.

Lưu ý: đầu cyan của gradient Anmitech (`#03D0FF`, hue 191°) **nằm rất gần** màu chính
của XimiTech. Nên chỉ dùng nó làm điểm nhấn nhỏ và đầu gradient — lấy nó làm màu chủ
đạo thì giao diện sẽ giống XimiTech.

## Bộ logo

Trong `logo svg/`, tất cả `viewBox` gốc, nền trong suốt:

| File | Dùng ở đâu |
|---|---|
| `logo_anmitech_ngang.svg` | Header — bản chính, có gradient |
| `logo_anmitech_ngang_trang.svg` | Header trên nền tối |
| `logo_anmitech_ngang_den.svg` | Bản in, tài liệu một màu |
| `logo_anmitech.svg` | Bản dọc, có slogan |
| `logo_anmitech_trang.svg` / `_den.svg` | Bản dọc, trắng / đen |
| `logo_anmitech_khong_slogan.svg` (+ `_trang`, `_den`) | Chỗ hẹp, slogan sẽ không đọc được |
| `logo_favicon_anmitech.svg` | Favicon — `viewBox 0 0 500 500` |
| `logo_facebook_anmitech.svg` | Ảnh chia sẻ mạng xã hội |

Bản trắng dùng `fill:#fff` phẳng, không gradient — đúng, vì gradient trên nền tối
sẽ mất đầu đậm.

Đặt trong `public/` của Next.js, **không upload lên WordPress**: không phải bật SVG
(rủi ro XSS), chạy qua CDN Vercel, và theo Git nên rollback được.

```
public/logo.svg          ← logo_anmitech_ngang.svg
public/logo-white.svg    ← logo_anmitech_ngang_trang.svg
public/favicon.svg       ← logo_favicon_anmitech.svg
```

File `logo-anmitech.svg` ở thư mục gốc là bản tôi tách từ `.ai`, nay không cần nữa —
xoá được.
