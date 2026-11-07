# namtrandang.com — Phương án triển khai & hướng dẫn dựng site

WordPress + Flatsome + SCF. Bản này bám đúng `design_handoff_namlamweb/README.md`
và kiến trúc trong `WEBSITE_INFORMATION_ARCHITECTURE_v1_0_final.docx`.

> **Giả định đang dùng:** namtrandang.com là **tên miền mới của chính website Nam làm WEB**
> (bộ handoff và WEA đều viết cho namlamweb.com). Thương hiệu, thông điệp lõi, IA, design
> system giữ nguyên; chỉ đổi tên miền hiển thị. Tên miền + tên thương hiệu nằm trong
> **Nam làm WEB → Cấu hình**, đổi một chỗ là đổi toàn site. Nếu namtrandang.com thực chất là
> *website cá nhân riêng*, khác định vị với Nam làm WEB, cần xác nhận lại — phần Brand/IA sẽ
> phải làm mới, chưa đủ căn cứ để tự quyết.

---

## 1. Phương án tổng thể

### 1.1 Nguyên tắc chia việc giữa Flatsome và code

| Phần | Làm bằng | Lý do |
|---|---|---|
| Header, menu, mobile menu | **Flatsome Header Builder** + CSS override | Giữ được menu, mega menu, mobile drawer sẵn có |
| Footer | **Flatsome Block** chứa `[nlw_footer]` | Footer 4 cột lấy dữ liệu động từ trang Cấu hình |
| Các section hi-fi (hero, showcase, timeline, bảng giá…) | **Shortcode của child theme** | Handoff là hi-fi, yêu cầu đúng từng px; dựng lại bằng UX Builder sẽ lệch và rất khó bảo trì |
| Trang tĩnh (Trang chủ, Giới thiệu, Dịch vụ, Liên hệ) | **Trang WordPress + UX Builder**, mỗi section là 1 element Text chứa shortcode | Nam vẫn kéo thả đổi thứ tự section được |
| Dự án (archive + case study) | **Template PHP** (`archive-project.php`, `single-project.php`) | Có phân trang, lọc theo taxonomy, SEO tốt hơn lọc bằng CSS |
| Dịch vụ chi tiết | **Template PHP** (`single-service.php`) | 4 trang dùng chung 1 template, chỉ khác dữ liệu |

**Không** dùng UX Builder để dựng lại hero gradient, showcase 440vh hay timeline ngang —
Flatsome không có sẵn và mọi cách "chắp vá" đều vỡ ở mobile.

### 1.2 Cấu trúc nội dung

```
CPT project      → Dự án (case study) + taxonomy project_cat (Nhóm dự án)
CPT service      → 4 dịch vụ; bảng giá là repeater `packages` ngay trong dịch vụ
Options Page     → thương hiệu, liên hệ, trang đích, nội dung cố định trang chủ
```

Dùng đầy đủ tính năng SCF:

| Nội dung | Field |
|---|---|
| 3 gói giá của một dịch vụ | Repeater `packages` (trong CPT `service`) |
| Danh sách tính năng của từng gói | Repeater `features` lồng trong `packages` |
| Chip cam kết của dịch vụ | Repeater `chips` |
| Các khối giải pháp của dự án | Repeater `solutions` (tiêu đề + mô tả + ảnh), tự xen kẽ trái/phải |
| Kết quả dự án | Repeater `results` |
| Thư viện ảnh dự án | Gallery `gallery` |
| Dịch vụ / dự án liên quan | Relationship (giới hạn 3) |
| 3 lời hứa, 4 giá trị, 5 bước quy trình | Repeater trên Options Page |
| Thương hiệu, liên hệ, trang đích | Options Page `nlw-options` |

Toàn bộ chỉ có **2 CPT** — Nam vào một bản ghi dịch vụ là sửa được cả 3 gói giá,
không phải nhảy qua lại giữa nhiều màn hình.

### 1.3 Plugin

| Plugin | Vai trò | Bắt buộc |
|---|---|---|
| Secure Custom Fields (SCF) | Field nội dung cho Dự án / Dịch vụ / Gói giá | ✅ |
| Contact Form 7 (hoặc WPForms Lite) | Form liên hệ | ✅ |
| Flying Scripts / Perfmatters / WP Rocket | Tối ưu tốc độ | Nên có |
| Rank Math hoặc Yoast | SEO, sitemap, schema | Nên có |
| UpdraftPlus | Sao lưu | Nên có |
| Wordfence / iThemes Security | Bảo mật | Nên có |

Đo lường theo WEA chương IX: **GA4 + GTM + Microsoft Clarity**, gắn event cho nút Zalo,
Messenger, gọi điện và submit form.

### 1.4 Thứ tự triển khai

1. Cài WordPress + Flatsome + SCF + child theme này → kích hoạt child theme.
2. Nam làm WEB → **Dữ liệu mẫu**: bấm nhập 4 dịch vụ + 12 gói giá + 4 nhóm dự án + nội dung cố định trang chủ.
3. Nam làm WEB → **Cấu hình**: điền Zalo, Messenger, hotline, email, tên miền, trang đích.
4. Dựng Header (Flatsome) + Footer (Flatsome Block với `[nlw_footer]`).
5. Tạo 5 trang: Trang chủ, Giới thiệu, Dịch vụ, Liên hệ (Dự án dùng archive `/du-an/`).
6. Nhập dự án thật (ảnh screenshot, nội dung case study).
7. Rà soát mobile ≤900px, `prefers-reduced-motion`, tốc độ, form.

---

## 2. Cài đặt

1. Copy thư mục `flatsome-child/` vào `wp-content/themes/` (đổi tên tuỳ ý, ví dụ `namtrandang-child`).
2. Giao diện → Giao diện → kích hoạt **Nam làm WEB — Flatsome Child**.
3. Cài & bật **Secure Custom Fields**. Không cần tự tạo field: theme đã đăng ký sẵn.
4. Cài đặt → Đường dẫn tĩnh → chọn **Tên bài viết** (`/%postname%/`), rồi Lưu để làm mới rewrite.

Font Archivo + Space Grotesk đã nằm trong `assets/fonts/` và được khai báo `@font-face`.
Theme tự tắt Google Fonts của Flatsome để không lệch weight.

---

## 3. Header (Flatsome)

Flatsome → Customize → Header:

- Header height: **74**, Sticky: bật, "Shrink header": tắt.
- Logo height: **30**.
- Bố cục: `Logo | Menu | Nút Zalo`.
- Thêm một **Button** vào Header Elements, link tới Zalo, class `nlw-btn nlw-btn-primary`
  (hoặc dùng nút mặc định — CSS đã ép về góc vuông, màu brand).
- Mobile: hiện hamburger, ẩn menu ngang (mặc định của Flatsome, ≤900px đã đúng).

CSS đã override sẵn: nền `rgba(255,255,255,.92)` + `backdrop-filter: blur(8px)`,
viền dưới 1px `#DBE2E6`, nav item Archivo 600/15px với gạch chân active `#027EA6`.

---

## 4. Footer

1. Flatsome → **Blocks** → Add New, tên `Footer NLW`.
2. Trong block, thêm 1 element **Text** và dán: `[nlw_footer]`
3. Customize → Footer → Footer 1: chọn block `Footer NLW`; Footer 2: tắt; "Absolute footer": tắt.

Footer tự lấy Zalo / Messenger / hotline / email / tagline / tên miền từ trang Cấu hình.

---

## 5. Dựng từng trang

Mỗi section = **một element Text trong UX Builder**, dán đúng shortcode dưới đây.
Row/Column của Flatsome đặt full-width, padding 0 (shortcode tự lo container 1200px).

### 5.1 Trang chủ

```
[nlw_hero]

[nlw_intro label="Giới thiệu" title="Người xây dựng niềm tin cho doanh nghiệp qua website" quote="Hiểu đúng thì mới làm đúng — và website mới thật sự phục vụ việc kinh doanh của bạn."]
Nam làm WEB giúp chủ doanh nghiệp nhỏ có một website chuyên nghiệp mà vẫn hiểu rõ mình đang sở hữu điều gì — từ cấu trúc, nội dung, đến cách vận hành.
Không cần rành công nghệ. Việc của bạn là kể về công việc kinh doanh; phần còn lại để chúng tôi lo, và giải thích lại cho bạn theo cách dễ hiểu nhất.
[/nlw_intro]

[nlw_featured_services lead="Từ website giới thiệu đến landing page bán hàng — làm gọn, làm đúng nhu cầu thật của doanh nghiệp bạn."]

[nlw_projects_showcase]

[nlw_values]

[nlw_process]

[nlw_cta]
```

Cài đặt → Đọc → Trang chủ hiển thị: chọn trang này.

Nội dung hero, 3 lời hứa, 4 giá trị, 5 bước quy trình nằm ở **Nam làm WEB → Cấu hình**
(không sửa trong UX Builder) để dùng lại nhất quán.

### 5.2 Giới thiệu

```
[nlw_page_hero eyebrow="Giới thiệu" title="Người xây dựng niềm tin cho doanh nghiệp qua website" desc="Tôi là Nam. Tôi làm website cho các doanh nghiệp nhỏ — những người giỏi trong lĩnh vực của mình nhưng chưa quen với công nghệ."]

[nlw_intro label="Câu chuyện" title="Bắt đầu từ một điều đơn giản: khách hàng cần được hiểu" quote="Bạn sẽ hiểu website của mình trước khi sở hữu nó."]
Trong nhiều năm làm website, tôi gặp không ít chủ doanh nghiệp nhỏ từng làm web nhưng không hiểu mình đang có gì.
Một website tốt là website mà chủ của nó hiểu được — biết nó nói gì, hoạt động ra sao, và tự tin sử dụng.
[/nlw_intro]

[nlw_vision eyebrow="Tầm nhìn" by="— Định hướng của Nam làm WEB"]
Mỗi doanh nghiệp nhỏ đều xứng đáng có một website họ thật sự hiểu và làm chủ.
[/nlw_vision]

[nlw_numbered_list eyebrow="Giá trị cốt lõi" title="Bốn điều tôi giữ trong mọi dự án"]
Rõ ràng | Minh bạch về báo giá, tiến độ và những gì bạn nhận được.
Dễ hiểu | Ưu tiên lợi ích kinh doanh, hạn chế tối đa thuật ngữ kỹ thuật.
Tận tâm | Đồng hành từ ý tưởng đầu tiên đến khi bàn giao và sau đó.
Thực tế | Giải pháp vừa đủ, đúng nhu cầu — không vẽ vời tốn kém.
[/nlw_numbered_list]

[nlw_chips title="Lĩnh vực đã làm" lead="Một số ngành tôi đã có kinh nghiệm triển khai."]
Nhà hàng & F&B
Xây dựng
Spa & làm đẹp
Thời trang
Giáo dục
Nội thất
Phòng khám
Sản xuất
[/nlw_chips]

[nlw_ways title="Cách tôi làm việc"]
Hỏi trước khi làm | Hiểu công việc kinh doanh của bạn trước khi bàn tới giao diện.
Giải thích từng bước | Mọi quyết định đều được nói lại bằng ngôn ngữ đời thường.
Bàn giao là bắt đầu | Hướng dẫn bạn tự quản trị và đồng hành sau khi website chạy.
[/nlw_ways]

[nlw_cta]
```

### 5.3 Dịch vụ (trang tổng)

```
[nlw_page_hero align="center" eyebrow="Dịch vụ" title="Dịch vụ thiết kế website" desc="Bốn dịch vụ chính, mỗi dịch vụ có bảng giá minh bạch riêng. Chọn dịch vụ phù hợp để xem chi tiết các gói."]

[nlw_services_grid]

[nlw_cta title="Chưa chắc chọn dịch vụ nào?" desc="Nhắn tin mô tả nhu cầu của bạn — tôi sẽ tư vấn dịch vụ và gói phù hợp nhất, miễn phí và không ràng buộc."]
```

4 trang chi tiết dịch vụ **không cần tạo tay** — mỗi bản ghi CPT `service` tự có URL
`/dich-vu/website-theo-yeu-cau/` và dùng `single-service.php`.

**Sửa bảng giá:** Dịch vụ → mở bản ghi → tab **Bảng giá**. Mỗi gói là một khối repeater
(tên gói, mô tả một dòng, tiền tố giá, giá, thời gian, câu giới thiệu, danh sách tính năng,
công tắc "Gói phổ biến"). Kéo thả để đổi thứ tự cột; bật "Gói phổ biến" cho đúng một gói.
Trường **Giá** nhận cả số thuần (`4000000` → tự thành 4.000.000) lẫn khoảng giá
(`9.000.000 - 24.000.000`).

### 5.4 Dự án

Archive `/du-an/` đã có sẵn: hero, thanh lọc theo nhóm, lưới 3 cột, phân trang, CTA band.
Chỉ cần thêm vào menu. Nếu muốn URL khác, tạo một trang trống và trỏ menu — nhưng
**nên giữ archive** để phân trang và lọc chạy bằng query thật (đúng khuyến nghị trong handoff).

Nhập một dự án:

- Tiêu đề = tên dự án; Ảnh đại diện = screenshot (16:10) dùng cho thẻ ở lưới.
- Tóm tắt (Excerpt) = mô tả ngắn hiển thị ở hero case study và showcase trang chủ.
- Nhóm dự án = 1 nhóm.
- Tab **Tổng quan**: khách hàng, lĩnh vực, loại website, thời gian, năm, URL, ảnh hero.
- Tab **Vấn đề & Giải pháp**: vấn đề + repeater **Các khối giải pháp** (tiêu đề, mô tả, ảnh 4:3).
  Khối 1 chữ bên trái, khối 2 ảnh bên trái, tự xen kẽ tiếp — thêm bao nhiêu khối cũng đúng bố cục.
- Tab **Kết quả**: repeater tiêu đề + mô tả, hiển thị lưới 2 cột (nên nhập 4 mục).
- Tab **Hình ảnh & liên kết**: Gallery (chọn 4 ảnh 16:10) + Relationship dịch vụ liên quan.
- Bật **Đưa lên showcase trang chủ** cho đúng 5 dự án, sắp thứ tự bằng Page Attributes → Order.

### 5.5 Liên hệ

```
[nlw_page_hero eyebrow="Liên hệ" title="Nói chuyện trực tiếp với tôi" desc="Cách nhanh nhất là nhắn Zalo hoặc Messenger — tôi thường trả lời trong ngày. Bạn cũng có thể để lại thông tin, tôi sẽ liên hệ lại."]

[nlw_contact_grid]
  [nlw_contact_channels]
  [nlw_contact_form][contact-form-7 id="123" title="Tư vấn website"][/nlw_contact_form]
[/nlw_contact_grid]

[nlw_map]

[nlw_cta]
```

Thay `id="123"` bằng ID form thật. Markup form nằm ở `docs/cf7-form.html`.

---

## 6. Bảng shortcode

| Shortcode | Dùng ở | Tham số |
|---|---|---|
| `[nlw_hero]` | Trang chủ | — (lấy từ Cấu hình) |
| `[nlw_intro]` | Trang chủ, Giới thiệu | `label`, `title`, `quote`; nội dung = các đoạn, mỗi dòng 1 đoạn |
| `[nlw_featured_services]` | Trang chủ | `eyebrow`, `title`, `lead`, `count` |
| `[nlw_projects_showcase]` | Trang chủ | `eyebrow`, `title`, `count` |
| `[nlw_values]` | Trang chủ | `eyebrow`, `title` |
| `[nlw_process]` | Trang chủ | `eyebrow`, `title`, `lead` |
| `[nlw_cta]` | Mọi trang | `title`, `desc` |
| `[nlw_page_hero]` | Trang trong | `eyebrow`, `title`, `desc`, `align` (left hoặc center) |
| `[nlw_services_grid]` | Dịch vụ | — |
| `[nlw_pricing]` | Trong `single-service.php` | `service` (ID hoặc slug) |
| `[nlw_related_projects]` | Dịch vụ, tuỳ trang | `service`, `ids`, `eyebrow`, `title` |
| `[nlw_contact_grid]` | Liên hệ | bọc 2 shortcode con |
| `[nlw_contact_channels]` | Liên hệ | `title` |
| `[nlw_contact_form]` | Liên hệ | `title`, `note`; nội dung = shortcode form |
| `[nlw_map]` | Liên hệ | `title` |
| `[nlw_vision]` | Giới thiệu | `eyebrow`, `by` |
| `[nlw_numbered_list]` | Giới thiệu | `eyebrow`, `title`, `alt` (1 hoặc 0); mỗi dòng “Tiêu đề & Mô tả” ngăn bởi dấu gạch đứng |
| `[nlw_ways]` | Giới thiệu | `eyebrow`, `title`, `alt`; mỗi dòng “Tiêu đề & Mô tả” ngăn bởi dấu gạch đứng |
| `[nlw_chips]` | Giới thiệu | `eyebrow`, `title`, `lead`; mỗi dòng 1 chip |
| `[nlw_footer]` | Flatsome Block | — |

---

## 7. Hai điểm bắt buộc của thương hiệu

1. **`border-radius: 0` toàn site.** `theme.css` ép `border-radius: 0 !important` cho mọi
   phần tử trong `body.nlw`, kể cả nút và input của Flatsome / CF7. Ngoại lệ duy nhất là
   chấm trạng thái `.nlw-dot` ở trang Liên hệ.
2. **Gradient thương hiệu chỉ dùng 1 lần** — hero trang chủ (`.nlw-hero`). Không đặt
   `--brand-gradient` cho bất kỳ section nào khác.

---

## 8. Checklist trước khi bàn giao

- [ ] Trang chủ: hero gradient, showcase 5 dự án chạy đúng theo cuộn, timeline 5 bước có node active nằm giữa màn hình.
- [ ] ≤900px: hai section pin đã trở về dạng xếp dọc / cuộn ngang tay; không còn khoảng trắng thừa.
- [ ] Mọi nút, ô nhập, ảnh, chip đều góc vuông.
- [ ] Nút Zalo và Messenger hoạt động ở: header, hero, CTA band, footer, nút nổi, trang Liên hệ.
- [ ] Form gửi được, có thông báo lỗi/thành công đúng màu (`#D93C3C` / `#1E9E57`).
- [ ] Archive dự án: lọc theo nhóm chạy bằng URL, phân trang đúng, có trạng thái rỗng.
- [ ] 4 trang dịch vụ hiển thị đủ 3 gói, gói giữa có ribbon "Gói phổ biến".
- [ ] Ảnh dự án thật đã thay hết placeholder gạch chéo.
- [ ] GA4 + GTM + Clarity đã gắn; có event cho Zalo / Messenger / gọi / submit form.
- [ ] Kiểm tra `prefers-reduced-motion` (bật "Giảm chuyển động" trong hệ điều hành).
- [ ] PageSpeed mobile: LCP < 2.5s.

---

## 9. Việc còn phụ thuộc nội dung khách cung cấp

Chưa đủ căn cứ để hoàn thiện, cần Nam cung cấp:

- Số Zalo, link Messenger, hotline, email chính thức (hiện là placeholder trong handoff).
- Screenshot các dự án thật + nội dung case study.
- Ảnh chân dung trang Giới thiệu.
- Xác nhận namtrandang.com dùng chung thương hiệu Nam làm WEB hay là thương hiệu cá nhân riêng.
- Nội dung thật cho hero/giới thiệu (bản hiện tại là placeholder đúng giọng thương hiệu).

Mọi nội dung liên quan tới giá, cam kết bảo hành và phạm vi công việc trong bảng giá đều lấy
nguyên văn từ bộ thiết kế — **cần Nam rà soát và duyệt lại trước khi công bố ra khách hàng.**
