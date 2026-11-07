'use client';

import { useEffects } from '../react/fx';

/**
 * Kích hoạt bộ hiệu ứng một lần cho cả trang.
 *
 * Vì sao cần component riêng: `useEffects` là hook nên chỉ chạy trong client
 * component, còn app/page.jsx là server component (nó await dữ liệu). Đây là
 * lớp mỏng nhất để nối hai thế giới đó, không render ra thẻ nào.
 *
 * `deps` truyền vào để hiệu ứng gắn lại khi nội dung đổi — cần thiết khi dữ
 * liệu từ WordPress về sau lần render đầu.
 */
export default function FxRoot({ deps = [] }) {
  useEffects(deps);
  return null;
}
