# Trang chủ — Content Revision V2 (theo brief mới)

Phần nằm trong theme đã sửa và deploy. Tài liệu này chỉ liệt kê phần nằm trong
**database**, chỉ sửa được trong wp-admin.

---

## 1. Nam làm WEB → Cấu hình → tab Trang chủ

| Trường | Dán vào |
|---|---|
| Hero — mô tả | `Mình xây dựng website cho doanh nghiệp nhỏ — rõ ràng, dễ hiểu và phù hợp với cách khách hàng tìm hiểu trước khi quyết định.` |

Bản hiện tại đang 2 câu ("Website không phải là điểm bắt đầu. Trước khi làm, mình cùng bạn…").
Brief mục 5.1 yêu cầu Hero nhiều khoảng thở và không thêm paragraph — nên rút về một câu.

**4 giá trị** — sửa cột Mô tả:

| Tiêu đề | Mô tả mới |
|---|---|
| Rõ ràng | Minh bạch về cách làm, phạm vi và những gì bạn nhận được. |
| Dễ hiểu | Giải thích những điều cần thiết bằng ngôn ngữ không quá kỹ thuật. |
| Tận tâm | Đồng hành từ những bước đầu tiên đến khi website được bàn giao. |
| Thực tế | Tập trung vào điều doanh nghiệp và khách hàng thực sự cần. |

## 2. Cấu hình → tab CTA band

| Trường | Dán vào |
|---|---|
| Tiêu đề | `Bạn đang muốn làm một website?` *(giữ nguyên)* |
| Mô tả | `Mình có thể cùng bạn làm rõ website cần gì trước khi bắt đầu xây dựng.` |

## 3. Cấu hình → tab SEO trang chủ

| Trường | Dán vào |
|---|---|
| Meta description | `Mình xây dựng website cho doanh nghiệp nhỏ — rõ ràng, dễ hiểu và phù hợp với cách khách hàng tìm hiểu trước khi quyết định.` |

Ô này đã có giá trị lưu trong database nên giá trị mặc định trong theme không còn tác dụng.

---

## 4. Trang chủ trong UX Builder

**Section 02 — Giới thiệu** (thay toàn bộ):

```
[nlw_intro label="Giới thiệu ngắn" title="Người xây dựng niềm tin cho doanh nghiệp qua website." cta="Tìm hiểu về mình"]
Mình là Nam. Mình giúp doanh nghiệp nhỏ xây dựng một website mà khách hàng có thể hiểu, tin tưởng và dễ dàng liên hệ.
[/nlw_intro]
```

Bỏ `size="small"` vì tiêu đề mới ngắn. Bỏ danh sách 3 câu hỏi — brief mục 5.2 yêu cầu
phần này chỉ là giới thiệu ngắn, không kể chuyện.

**Section 04 — Dự án:** xoá tham số `lead="..."` đang có, để shortcode trần:

```
[nlw_projects_showcase]
```

Nội dung mới đã nằm sẵn trong theme. Còn tham số thì bản cũ trong database vẫn thắng.

**Section 07 — CTA cuối:**

```
[nlw_cta btn2="Xem dịch vụ" btn2_url="https://namtrandang.com/dich-vu/"]
```

Brief mục 5.6 muốn nút phụ là "Xem dịch vụ" thay cho Messenger.

---

## 5. Dịch vụ → thứ tự và mô tả

Brief mục 5.3 xếp thứ tự: **Doanh nghiệp → Bán hàng → Theo yêu cầu**.
Hiện tại đang ngược: Theo yêu cầu → Bán hàng → Doanh nghiệp.

Sửa ở **Dịch vụ → từng mục → Page Attributes → Order**:

| Dịch vụ | Order | Mô tả ngắn mới |
|---|---|---|
| Website doanh nghiệp | 1 | Giúp khách hàng hiểu doanh nghiệp, dịch vụ và những điều tạo nên sự tin tưởng. |
| Website bán hàng | 2 | Giúp khách hàng tìm hiểu sản phẩm và thực hiện bước tiếp theo thuận tiện hơn. |
| Website theo yêu cầu | 3 | Xây dựng theo đặc thù doanh nghiệp khi một khuôn mẫu có sẵn không phù hợp. |
| Landing Page | 4 | *(không hiện ở trang chủ — trang chủ chỉ lấy 3 dịch vụ đầu)* |

Lưu ý: ô Mô tả ngắn dùng chung với trang Dịch vụ, sửa ở đây sẽ đổi cả hai nơi.
