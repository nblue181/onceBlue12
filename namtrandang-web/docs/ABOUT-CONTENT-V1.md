# Trang Giới thiệu — Content Revision V1.0

Toàn bộ nội dung trang này nằm trong shortcode ở UX Builder (database), nên phải dán tay.
Phần theme đã được bổ sung để đỡ được các nội dung mới (v1.3.0) — xem mục cuối.

Mở **Trang → Giới thiệu → UX Builder**, thay từng element Text bằng khối tương ứng.
Giữ nguyên thứ tự 7 section hiện tại.

---

## Section 01 — Hero

```
[nlw_page_hero eyebrow="Giới thiệu" title="Xây dựng niềm tin cho doanh nghiệp qua website" desc="Mình là Nam. Mình làm website cho các doanh nghiệp vừa và nhỏ — những người giỏi trong công việc của mình nhưng chưa quen với công nghệ."]
Mình tin rằng một website tốt không bắt đầu từ giao diện hay công nghệ. Nó bắt đầu từ việc hiểu doanh nghiệp, hiểu khách hàng và hiểu website cần làm gì cho công việc kinh doanh.
[/nlw_page_hero]
```

Đoạn thứ hai đặt trong thân shortcode — đây là tính năng mới, trước đây hero chỉ nhận được một đoạn.

---

## Section 02 — Câu chuyện

```
[nlw_intro label="Câu chuyện" title="Bắt đầu từ một điều đơn giản:|Khách hàng cần được hiểu" bg="alt"]
Trong quá trình làm website, mình gặp không ít chủ doanh nghiệp nhỏ từng làm web nhưng không hiểu mình đang có gì, website đang phục vụ ai và vì sao nó lại được xây dựng như vậy.
Một website có thể rất đẹp, rất nhiều chức năng, nhưng nếu khách hàng không hiểu doanh nghiệp đang làm gì, website vẫn chưa thực sự làm tốt công việc của nó.
Từ đó, mình dần hình thành một cách làm đơn giản hơn: trước khi nói về giao diện hay công nghệ, hãy nói về **doanh nghiệp** và **khách hàng** trước.
[/nlw_intro]
```

Ba thay đổi trong khối này:

- `|` trong `title` tách thành hai tầng — "Bắt đầu từ một điều đơn giản:" nhỏ, ở trên;
  "Khách hàng cần được hiểu" to, là trọng tâm.
- Đã bỏ `quote=` nên câu "Bạn sẽ hiểu website của mình trước khi sở hữu nó." không còn.
- `**doanh nghiệp**` và `**khách hàng**` được tô màu brand và in đậm.
- `bg="alt"` cho nền xám nhạt — Hero ngay phía trên đang nền trắng, để trắng cả hai
  thì hai section dính vào nhau thành một mảng.

Đã đổi **"Trong nhiều năm làm website"** thành **"Trong quá trình làm website"** theo mục 16
của spec — chưa có xác nhận số năm nên không giữ cách nói ám chỉ thâm niên.
Nếu Nam muốn ghi số năm thật, sửa lại dòng này.

---

## Section 03 — Tầm nhìn

```
[nlw_vision eyebrow="Tầm nhìn" desc="Mình muốn website trở thành một công cụ mà chủ doanh nghiệp có thể hiểu, sử dụng và tiếp tục phát triển — chứ không phải một thứ được bàn giao rồi để đó." by="— Định hướng của Nam làm WEB"]
Mỗi doanh nghiệp
đều xứng đáng có một website
mà họ thật sự hiểu và làm chủ.
[/nlw_vision]
```

Mỗi dòng trong thân shortcode giờ là **đúng một dòng hiển thị** — chỗ ngắt do bạn quyết định,
không phải do độ rộng khung. Đoạn mô tả bên dưới đã được nới rộng và hiện đúng 2 dòng.

---

## Section 04 — Giá trị cốt lõi

```
[nlw_numbered_list eyebrow="Giá trị cốt lõi" title="Bốn điều mình luôn giữ trong mỗi dự án"]
Rõ ràng | Bạn biết mình đang làm gì, vì sao cần làm và website đang phục vụ điều gì.
Dễ hiểu | Không cần rành công nghệ. Mình giải thích những điều phức tạp bằng ngôn ngữ dễ hiểu.
Tận tâm | Mình đồng hành từ những bước đầu tiên đến khi website được bàn giao và bạn có thể sử dụng nó.
Thực tế | Không cố làm thật nhiều. Mình tập trung vào những gì thực sự cần cho doanh nghiệp và khách hàng của bạn.
[/nlw_numbered_list]
```

---

## Section 05 — Lĩnh vực

Có hai cách, cần Nam chọn — xem phần cảnh báo bên dưới.

**Cách A — tự động lấy từ dữ liệu dự án thật:**

```
[nlw_chips title="Mình đã có cơ hội làm việc với nhiều doanh nghiệp" lead="Mỗi ngành có một cách kinh doanh khác nhau, khách hàng khác nhau và những điều khách hàng cần biết cũng khác nhau. Một số lĩnh vực mình đã có cơ hội làm việc gồm:" source="projects"]
```

**Cách B — nhập tay, chỉ những ngành Nam thực sự đã làm:**

```
[nlw_chips title="Mình đã có cơ hội làm việc với nhiều doanh nghiệp" lead="Mỗi ngành có một cách kinh doanh khác nhau, khách hàng khác nhau và những điều khách hàng cần biết cũng khác nhau. Một số lĩnh vực mình đã có cơ hội làm việc gồm:"]
Nhà hàng & F&B
Xây dựng
Spa & Làm đẹp
[/nlw_chips]
```

---

## Section 06 — Cách làm việc

```
[nlw_ways title="Cách mình làm việc" lead="Mình không muốn bạn phải biết sẵn mình cần gì trước khi bắt đầu."]
Hỏi trước khi làm | Mình muốn hiểu công việc kinh doanh, khách hàng và điều bạn đang thực sự cần trước khi bắt tay vào xây dựng website.
Giải thích từng bước | Nếu có điều gì chưa rõ, mình sẽ giải thích lại bằng ngôn ngữ dễ hiểu — không bắt bạn phải hiểu công nghệ trước mới có thể làm website.
Bàn giao để bạn làm chủ | Website không kết thúc ở lúc bàn giao. Mình muốn bạn hiểu cách sử dụng và có thể tiếp tục quản lý website của mình sau đó.
[/nlw_ways]
```

Thứ tự 3 bước này chính là triết lý **Khách hàng → Doanh nghiệp → Công nghệ**:
hỏi về khách hàng và việc kinh doanh trước, công nghệ nằm ở cuối chứ không mở đầu.

---

## Section 07 — CTA

```
[nlw_cta title="Nếu bạn đang muốn làm một website,|mình có thể cùng bạn bắt đầu từ điều cần thiết nhất." desc="Bạn chưa cần biết chính xác mình cần website như thế nào.|Chỉ cần kể mình nghe một chút về doanh nghiệp và điều bạn đang muốn làm.|Mình sẽ cùng bạn làm rõ website có thể giúp gì và nên bắt đầu từ đâu." btn2="Xem dự án"]
```

Dấu `|` trong `title` và `desc` là chỗ ngắt dòng — dùng được vì thuộc tính shortcode
không chứa được ký tự xuống dòng thật.

`btn2="Xem dự án"` đổi nút phụ từ Messenger sang nút dẫn tới trang Dự án, tự trỏ về `/du-an/`.
Microcopy "Tư vấn miễn phí · Không ràng buộc" đã có sẵn, không cần khai báo.

---

## Cảnh báo về phần Lĩnh vực

Trang đang liệt kê 8 ngành: Nhà hàng & F&B, Xây dựng, Spa & làm đẹp, Thời trang,
Giáo dục, Nội thất, Phòng khám, Sản xuất.

**Trong hệ thống hiện chỉ có 1 dự án** — "Surface Shop", lĩnh vực **"Máy tính"** —
và không có ngành nào trong 8 ngành trên. Danh sách đó vốn là nội dung mẫu tôi đưa vào
tài liệu hướng dẫn lúc dựng trang, không phải dữ liệu thật.

Mục 17 của spec ghi rõ: ngành chưa có dự án hoặc trải nghiệm thật thì phải xoá.

- Chọn **Cách A** nếu muốn danh sách luôn khớp dữ liệu thật — hiện sẽ chỉ ra đúng
  một chip "Máy tính", và tự dài ra khi Nam nhập thêm dự án.
- Chọn **Cách B** nếu Nam có kinh nghiệm thật ở những ngành chưa kịp đưa lên website —
  nhưng phải tự rà lại từng ngành, chỉ giữ ngành có thật.

Đây là thông tin tạo niềm tin, không nên để danh sách mẫu chạy tiếp trên site thật.

---

## Phần theme đã bổ sung (v1.3.0)

| Shortcode | Tính năng mới |
|---|---|
| `[nlw_page_hero]` | Nhận thêm đoạn văn trong thân shortcode |
| `[nlw_vision]` | Thêm `desc=` — đoạn diễn giải giữa câu tầm nhìn và dòng ký tên |
| `[nlw_ways]` | Thêm `lead=`; đổi tiêu đề mặc định "Cách tôi làm việc" → "Cách mình làm việc" |
| `[nlw_chips]` | Thêm `source="projects"` — lấy lĩnh vực từ dự án thật, không trùng lặp, sắp xếp A→Z |
| `[nlw_cta]` | Thêm `btn2=` và `btn2_url=` để đổi nút phụ |
| `[nlw_intro]`, `[nlw_page_hero]` | Dòng bắt đầu bằng `-` tự thành danh sách; sửa lỗi các câu dính liền nhau |
