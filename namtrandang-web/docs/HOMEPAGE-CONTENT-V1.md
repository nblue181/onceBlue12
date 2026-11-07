# Homepage Content Revision V1.0 — phần Founder cần dán trong wp-admin

Phần nội dung nằm trong theme đã được sửa và deploy (v1.1.0). Tài liệu này chỉ liệt kê
những chỗ nằm trong **database**, chỉ sửa được từ wp-admin.

Thứ tự làm: (1) Options Page → (2) Nội dung trang chủ trong UX Builder → (3) Dịch vụ → (4) Customizer.

---

## 1. Nam làm WEB → Cấu hình

### Tab "Trang chủ"

| Trường | Dán vào |
|---|---|
| Hero — eyebrow | `Nam làm WEB` |
| Hero — tiêu đề H1 | `Bạn sẽ hiểu website của mình trước khi sở hữu nó.` *(giữ nguyên, không sửa)* |
| Hero — microcopy dưới nút | `Tư vấn miễn phí · Không ràng buộc` |

**Hero — mô tả** (giữ nguyên chỗ xuống dòng, hệ thống sẽ hiển thị đúng):

```
Website không phải là điểm bắt đầu.
Trước khi làm, mình cùng bạn làm rõ doanh nghiệp đang cần gì, khách hàng cần biết gì và website cần làm gì cho việc kinh doanh.
```

**3 lời hứa (cột phải hero)** — Content Spec không đề cập phần này. Nội dung hiện tại
(Rõ ràng / Dễ hiểu / Đồng hành) không vi phạm quy tắc xưng hô nên **giữ nguyên**,
chờ Founder quyết định có viết lại không.

**4 giá trị cốt lõi** — xoá hết dòng cũ, nhập lại:

| Tiêu đề | Mô tả |
|---|---|
| Rõ ràng | Bạn biết mình đang làm gì, vì sao cần làm và mình sẽ nhận được gì. |
| Dễ hiểu | Không cần rành công nghệ. Mình giải thích website bằng ngôn ngữ dễ hiểu. |
| Tận tâm | Không chỉ làm xong rồi bàn giao. Mình đồng hành để bạn có thể làm chủ website của mình. |
| Thực tế | Không cố làm thật nhiều. Mình chỉ làm những gì thực sự cần cho doanh nghiệp của bạn. |

**Quy trình 5 bước** — xoá hết bước cũ, nhập lại. **Ô "Nhãn phụ" để trống** ở cả 5 bước
(Content Spec không cung cấp, không tự bịa):

| Tên bước | Mô tả |
|---|---|
| Hiểu doanh nghiệp | Mình tìm hiểu công việc kinh doanh, khách hàng và điều bạn muốn website giúp mình đạt được. |
| Làm rõ website cần làm gì | Cùng xác định cấu trúc, nội dung và những điều khách hàng cần biết trước khi đưa ra quyết định. |
| Xây dựng website | Từ những gì đã thống nhất, mình thiết kế và phát triển website phù hợp với doanh nghiệp. |
| Kiểm tra cùng bạn | Cùng xem lại nội dung, trải nghiệm và các chức năng để đảm bảo website hoạt động đúng như mong muốn. |
| Bàn giao & đồng hành | Bạn nhận website, tài khoản và những hướng dẫn cần thiết để có thể tự mình quản lý và sử dụng website. |

### Tab "CTA band"

| Trường | Dán vào |
|---|---|
| Tiêu đề mặc định | `Bạn đang muốn làm một website?` |

Mô tả mặc định:

```
Không cần chuẩn bị sẵn mọi thứ. Hãy kể mình nghe một chút về doanh nghiệp của bạn.
Mình sẽ cùng bạn làm rõ website có thể giúp gì và nên bắt đầu từ đâu.
```

### Tab "SEO trang chủ"

Các ô này **đã có sẵn nội dung đúng** và đang chạy trên site. Chỉ cần mở trang Cấu hình
và bấm **Cập nhật** một lần để lưu vào database.

---

## 2. Trang chủ trong UX Builder

Mở trang chủ → UX Builder. Thay nội dung của các element Text bằng đúng các khối dưới đây.

**Section 02 — Giới thiệu:** thay toàn bộ shortcode `[nlw_intro]` hiện tại bằng:

```
[nlw_intro label="Giới thiệu" title="Có thể bạn đang nghĩ mình cần một website!" size="small" cta="Tìm hiểu về Nam"]
Nhưng trước khi bắt tay vào làm, có vài câu hỏi quan trọng hơn:
- Khách hàng của mình là ai?
- Họ cần biết điều gì?
- Website sẽ giúp việc kinh doanh như thế nào?
Mình bắt đầu từ những câu hỏi đó.
[/nlw_intro]
```

Ba điểm trong khối này:

- `size="small"` hạ tiêu đề từ 36px xuống 28px.
- Ba dòng bắt đầu bằng `-` thành danh sách nổi bật: viền trái brand, chữ 20px đậm màu đậm,
  tách hẳn khỏi các đoạn văn xung quanh.
- Không còn `quote=` nên pullquote "Hiểu đúng trước. Làm đúng sau." bị bỏ.

Nút "Tìm hiểu về Nam →" tự trỏ tới trang Giới thiệu đã chọn trong Cấu hình.
Muốn trỏ chỗ khác thì thêm `cta_url="..."`.

**Section 03 — Dịch vụ:** thay bằng shortcode trần (nội dung mới đã nằm sẵn trong theme):

```
[nlw_featured_services]
```

**Section 04 — Dự án:**

```
[nlw_projects_showcase]
```

**Section 05 — Giá trị:**

```
[nlw_values]
```

**Section 06 — Quy trình:**

```
[nlw_process]
```

**Section 07 — CTA cuối trang:**

```
[nlw_cta]
```

> Quan trọng: nếu shortcode đang có tham số `eyebrow=`, `title=`, `lead=` thì **xoá các tham số đó đi**.
> Còn tham số thì nội dung cũ trong database sẽ tiếp tục thắng nội dung mới trong theme.

---

## 3. Dịch vụ → sửa mô tả ngắn

Vào **Dịch vụ**, mở từng mục, sửa ô **Mô tả ngắn**:

| Dịch vụ | Mô tả ngắn mới |
|---|---|
| Website theo yêu cầu | Một website được xây dựng riêng cho doanh nghiệp, dựa trên khách hàng, mục tiêu và cách bạn đang kinh doanh. |
| Website bán hàng | Giúp khách hàng dễ tìm hiểu sản phẩm, dễ lựa chọn và dễ thực hiện bước tiếp theo. |
| Website doanh nghiệp | Một nơi để khách hàng hiểu doanh nghiệp của bạn là ai, đang làm gì và vì sao họ có thể tin tưởng. |

Ba mô tả cũ chứa "chuẩn Premium", "Tăng doanh thu", "Khẳng định vị thế", "phát triển bền vững" —
đều nằm trong danh sách cấm ở mục 26 của Content Spec.

**Lưu ý phạm vi:** ô Mô tả ngắn này cũng hiển thị ở trang Dịch vụ. Sửa ở đây sẽ ảnh hưởng
sang trang đó. Content Spec giới hạn ở Homepage, nhưng không thể tách riêng vì cùng một trường dữ liệu.

**Landing Page** (dịch vụ thứ 4) không xuất hiện trên Homepage nên giữ nguyên, chờ đợt nội dung trang Dịch vụ.

---

## 4. Customizer

| Việc | Đường dẫn | Nội dung |
|---|---|---|
| Nhãn nút header đang là `Nhắn zalo` (chữ z thường) | Customize → Header → Buttons | Sửa thành `Nhắn Zalo` |
| Dòng `Copyright 2026 © Flatsome Theme` ở đáy trang | Customize → Footer → Absolute Footer | Tắt, hoặc sửa nội dung thành `© 2026 Nam làm WEB` |

Footer của theme đã tự hiển thị `© 2026 Nam làm WEB`, nên bật Absolute Footer sẽ thành hiển thị hai dòng bản quyền.

---

## 5. Chưa làm được, cần Founder quyết định

**Đoạn intro của section Dự án.** Content Spec mục 15 có đoạn:

> Mỗi doanh nghiệp có một câu chuyện khác nhau. Vì vậy, website cũng không nên được làm theo một khuôn mẫu giống nhau.

Chưa đưa lên được. Phần đầu của section này đang `position:absolute`, đo thực tế cho thấy
nó **đã đè lên khung ảnh dự án 34px ngay cả khi chưa có đoạn intro**; thêm đoạn này thì
mức đè tăng lên 93px. Sửa được nhưng phải đụng layout, mà Content Spec mục 29 cấm.

Khi Founder cho phép chỉnh layout, bật lại bằng:

```
[nlw_projects_showcase lead="Mỗi doanh nghiệp có một câu chuyện khác nhau. Vì vậy, website cũng không nên được làm theo một khuôn mẫu giống nhau."]
```

**Form liên hệ.** Mục 21–23 của Content Spec (microcopy form, thông báo thành công/lỗi)
chưa áp dụng được vì Homepage hiện không có form. Sẽ dùng khi dựng trang Liên hệ.
