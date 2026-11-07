/**
 * Nền Aura dùng cho TOÀN BỘ website.
 *
 * Đặt một lần ở app/layout.jsx, không đặt trong từng section. Trước đây mỗi
 * section tự dựng nền riêng nên chỉ banner đầu có Aura, còn cuộn xuống là nền
 * phẳng — và mỗi section lại tạo thêm 3-4 lớp blur, tốn GPU vô ích.
 *
 * Bốn lớp, tất cả đều pointer-events:none nên không chắn chuột:
 *   1  ma trận chấm 1px, trôi 24s
 *   2  quầng sáng lớn, trôi 20s
 *   3  quầng sáng phụ, trôi ngược 26s
 *   4  hai vệt sáng quét ngang, 19s và 31s ngược chiều
 *
 * Không có TechBackground (lưới kẻ ô kiểu XimiTech) ở đây — đó là chủ ý.
 */
export default function AuraBackground() {
  return (
    <div className="aura-fixed" aria-hidden="true">
      <div className="aura-layer-1" />
      <div className="aura-layer-2" />
      <div className="aura-layer-3" />
      <div className="aura-sweep" />
      <div className="aura-sweep aura-sweep-2" />
    </div>
  );
}
