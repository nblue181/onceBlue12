# Anmitech — Import cấu trúc dữ liệu vào WordPress

Bốn file JSON, import qua **Custom Fields → Tools → Import**.
Không cần plugin, không cần sửa code.

## Thứ tự import — bắt buộc đúng thứ tự này

| # | File | Import cái gì |
|---|---|---|
| 1 | `01-post-types.json` | CPT **Dịch vụ**, CPT **Dự án**, taxonomy **Nhóm dự án** |
| 2 | `02-options-page.json` | Trang **Anmitech — Cấu hình** trong menu admin |
| 3 | `03-field-groups.json` | Trường cho Dịch vụ và Dự án |
| 4 | `04-options-fields.json` | Trường cho trang Cấu hình |

Vì sao phải theo thứ tự: field group ở file 3 và 4 có **location rule** trỏ tới
post type `service`, `project` và options page `anmitech-options`. Import ngược lại thì
field group vẫn vào được, nhưng sẽ không hiện ở đâu cả cho tới khi có nơi để gắn.

## Các bước

1. Vào `https://anmitech.namtrandang.com/wp-admin/`
2. **Custom Fields → Tools**
3. Cột **Import** → chọn file → **Import File**
4. Lặp lại cho cả 4 file, đúng thứ tự trên
5. Vào **Settings → Permalinks** → bấm **Save Changes**
   (bước này bắt buộc, nếu bỏ qua thì `/dich-vu/…` và `/du-an/…` trả về 404)

## Kiểm tra sau khi import

| Kiểm tra | Kết quả đúng |
|---|---|
| Menu trái admin | Có **Dịch vụ**, **Dự án**, **Anmitech** |
| Thêm một Dịch vụ thử | Thấy 3 tab: Giới thiệu / Bảng giá / Liên kết |
| Thêm một Dự án thử | Thấy 2 tab: Tổng quan / Nội dung case study |
| `…/wp-json/wp/v2/services` | Trả JSON, mỗi bài có khoá `acf` |
| `…/wp-json/wp/v2/projects` | Trả JSON |
| `…/wp-json/wp/v2/project-cats` | Trả JSON |

Nếu khoá `acf` **rỗng** trong REST: mở field group đó → tab **Settings** →
bật **Show in REST API**. Trong file đã bật sẵn (`show_in_rest: 1`), nhưng
một vài bản SCF cũ bỏ qua giá trị này khi import.

## Nếu file 02 báo lỗi khi import

Có nghĩa là bản SCF đang dùng chưa có giao diện Options Pages. Tạo tay thay thế:

- **Custom Fields → Options Pages → Add New**
- Menu slug: `anmitech-options`  ← phải đúng chính xác chuỗi này
- Menu title: `Anmitech`
- Capability: `manage_options`

Sau đó import file 04 như bình thường.

## Còn thiếu: dữ liệu Options không có trong REST API

Đây là hạn chế của WordPress, không phải lỗi cấu hình: **REST API không trả về
giá trị của Options Page**. Frontend Next.js sẽ đọc được Dịch vụ và Dự án,
nhưng không đọc được thông tin liên hệ, nội dung hero, số liệu.

Cách khắc phục — dán đoạn trong `snippet-rest.php` vào một trong hai chỗ:

- Plugin **Code Snippets** (khuyến nghị — không mất khi cập nhật theme)
- Hoặc `functions.php` của child theme

Sau đó có thêm hai endpoint:

- `…/wp-json/anmitech/v1/options` — toàn bộ trường Cấu hình
- `…/wp-json/anmitech/v1/home` — gộp Cấu hình + Dịch vụ + Dự án nổi bật trong một lượt gọi

Chưa dán cũng không sao — CPT và trường vẫn chạy đầy đủ trong admin.
Chỉ là khi dựng frontend sẽ cần.

## SEO và Schema — do Rank Math lo

Các trường SEO đã được **bỏ khỏi file JSON** để tránh hai nguồn cùng khai một thứ.
Toàn bộ title, description, OG image, schema quản trong Rank Math.

Dán `snippet-rankmath.php` (cùng chỗ với `snippet-rest.php`) để đưa dữ liệu đó ra REST.
Mỗi bài sẽ có thêm khoá `seo`:

```json
"seo": {
  "title": "…", "description": "…", "canonical": "…",
  "og_image": "…", "noindex": false,
  "schema": [ { "@context": "https://schema.org", "@type": "Service", … } ]
}
```

`schema` lấy qua endpoint `rankmath/v1/getHead` của chính Rank Math, vì schema lưu
trong database còn nguyên biến `%title%` `%date%` chưa thay — đọc thẳng postmeta ra
sẽ vô dụng.

**Trang chủ:** tạo một Page tên *Trang chủ*, vào **Settings → Reading → A static page**
chọn nó. Không có bước này thì Rank Math không có chỗ để gắn SEO trang chủ.

### Ba việc bắt buộc khi headless

1. **Chặn index domain WordPress.** Rank Math → Titles & Meta → bật `noindex` toàn site.
   Không làm thì `anmitech.namtrandang.com` và `anmitech.vn` trùng nội dung, cả hai cùng tụt hạng.

2. **Canonical phải trỏ về domain frontend.** Trong `wp-config.php`:

   ```php
   define( 'WP_HOME', 'https://anmitech.vn' );
   define( 'WP_SITEURL', 'https://anmitech.namtrandang.com' );
   ```

   Sau đó `get_permalink()` trả về URL frontend, nên schema và canonical đều đúng.
   Đổi lại: nút *View post* trong admin sẽ trỏ ra frontend — đúng như mong muốn khi headless.

3. **Sitemap sinh từ Next.js**, không dùng của Rank Math. Sitemap Rank Math liệt kê URL
   domain WordPress; nộp lên Search Console là hỏng.

## Đổi cấu trúc đường dẫn

Mặc định WordPress đang để `/2026/08/18/hello-world/`. Đổi sang:

**Settings → Permalinks → Post name**

Nếu không đổi, đường dẫn dự án sẽ dài và xấu trên kết quả tìm kiếm.

## Import lại file 03 và 04 — bản bổ sung ngày 18/08/2026

Hai file này đã được **thêm trường**, cần import lại. Import đúng như lần đầu:
**Custom Fields → Tools → Import**, chọn file, **Import File**.

| File | Thêm gì | Vì sao |
|---|---|---|
| `03-field-groups.json` | Tab **So sánh trang chủ** trên Dịch vụ — 17 trường `cmp_*` | Bảng so sánh nhiều cột ở trang chủ cần mỗi cột có cùng bộ khoá hàng. Repeater `features` cũ là danh sách tự do nên không xếp thẳng cột được. |
| `04-options-fields.json` | 6 trường `about_*` và 3 trường `process_*` | Bố cục trang chủ có section Giới thiệu và section Quy trình, trước đó không có nơi nhập. |

Import lại **an toàn**: cả hai giữ nguyên `key` của mọi trường cũ, nên WordPress ghi đè
đúng chỗ, không tạo bản trùng và không mất dữ liệu đã nhập.

Không cần import lại file 01 và 02 — CPT, taxonomy và Options Page không đổi.

### Bảng so sánh hoạt động thế nào

Một **cột = một Dịch vụ**, không phải một gói. Bật `cmp_show` ở dịch vụ nào thì dịch vụ
đó thành một cột; bảng đọc tối đa 4 cột theo thứ tự **Page Attributes → Order**.

Repeater `packages` **giữ nguyên vai trò cũ**: bảng giá 3 gói nằm trong *trang chi tiết*
của một dịch vụ. Hai thứ khác nhau, đừng nhập trùng.

Giá đang bán lấy từ `price_from` ở tab Giới thiệu — **không nhập lại** ở tab So sánh.
Bỏ trống `cmp_price_badge` thì frontend tự tính phần trăm từ `cmp_price_original`
và `price_from`.

## Sửa cấu trúc sau này

Import lại file đã sửa với **cùng `key`** thì WordPress ghi đè, không tạo bản trùng.
Nội dung đã nhập không bị mất — trường được nhận diện theo `key`, không theo `name`.

Đổi `name` của một trường (ví dụ `price_from` → `gia_khoi_diem`) thì **dữ liệu cũ mất**,
vì `name` chính là meta key trong database. Muốn đổi thì phải đổi trong database luôn.
