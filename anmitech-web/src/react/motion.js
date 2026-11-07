'use client';

import { useEffect, useRef, useState } from 'react';

import { prefersReducedMotion } from '../lib/effects';

/** Tên sự kiện MotionNotice bắn ra khi bấm bật/tắt cưỡng chế chuyển động. */
export const MOTION_EVENT = 'anmitech:motionchange';

/**
 * Người xem có đang muốn ít chuyển động không, dạng hook.
 *
 * Luôn khởi tạo `false` rồi mới đọc thật trong useEffect. Đọc ngay lúc render
 * sẽ lệch hydration: server không biết thiết lập của máy khách nên render ra
 * một đằng, trình duyệt tính ra một nẻo.
 */
export function useReducedMotion() {
  const [reduced, setReduced] = useState(false);

  useEffect(() => {
    const read = () => setReduced(prefersReducedMotion());
    read();

    const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
    mq.addEventListener('change', read);
    window.addEventListener(MOTION_EVENT, read);

    return () => {
      mq.removeEventListener('change', read);
      window.removeEventListener(MOTION_EVENT, read);
    };
  }, []);

  return reduced;
}

/**
 * Đo tiến độ cuộn của một khối, trả về số 0 → 1.
 *
 * Dùng cho hiệu ứng gắn với thao tác cuộn chuột (scroll-linked), khác hẳn với
 * `Reveal` — Reveal chỉ bật một lần khi phần tử lọt vào khung nhìn rồi thôi,
 * còn cái này cập nhật liên tục theo vị trí cuộn nên chuyển động đi theo tay
 * người xem, cuộn lên là nó lùi lại.
 *
 * Hai chế độ:
 *
 *   mode: 'track'  — cho khối được ghim (có `position: sticky` bên trong).
 *                    0 khi đỉnh khối chạm đỉnh khung nhìn,
 *                    1 khi đáy khối chạm đáy khung nhìn.
 *                    Đây là cách làm "cuộn dọc để chạy ngang".
 *
 *   mode: 'enter'  — cho khối cao bình thường.
 *                    0 khi đỉnh khối còn ở dưới mốc `start`,
 *                    1 khi đáy khối lên tới mốc `end`.
 *                    start/end tính theo phần trăm chiều cao khung nhìn.
 *
 * Không dùng `animation-timeline: scroll()` của CSS: tới giờ vẫn chưa đủ trình
 * duyệt hỗ trợ, mà rơi về không có hiệu ứng thì đúng phần khách hay nhìn nhất
 * lại là phần chết.
 *
 * Giảm chuyển động: trả về 0 và không gắn listener nào. Trả 0 chứ không phải 1 —
 * component sẽ tự đổi sang bố cục tĩnh, nên giá trị này không được dùng tới; để
 * 1 thì lỡ có chỗ nào còn dùng sẽ hiện ra trạng thái cuối, trông như bị kẹt.
 */
export function useScrollProgress(ref, { mode = 'enter', start = 0.85, end = 0.5 } = {}) {
  const [progress, setProgress] = useState(0);
  const [motionKey, setMotionKey] = useState(0);
  const frame = useRef(0);

  // Bấm nút bật hiệu ứng thì phải gắn lại listener, nếu không section vẫn đứng.
  useEffect(() => {
    const bump = () => setMotionKey((k) => k + 1);
    const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
    mq.addEventListener('change', bump);
    window.addEventListener(MOTION_EVENT, bump);
    return () => {
      mq.removeEventListener('change', bump);
      window.removeEventListener(MOTION_EVENT, bump);
    };
  }, []);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    if (prefersReducedMotion()) {
      setProgress(0);
      return;
    }

    const measure = () => {
      const r = el.getBoundingClientRect();
      const vh = window.innerHeight || 1;
      let p;

      if (mode === 'track') {
        // Khoảng cuộn thực sự = chiều cao khối trừ một khung nhìn (phần bị ghim).
        const span = r.height - vh;
        p = span <= 0 ? 0 : -r.top / span;
      } else {
        // Bắt đầu khi đỉnh khối ở mốc `from`, kết thúc khi đáy khối lên mốc `to`.
        //   lúc bắt đầu  r.top = from
        //   lúc kết thúc r.top = to - r.height
        // nên khoảng di chuyển của r.top là: from - to + r.height
        const from = vh * start;
        const to = vh * end;
        const span = from - to + r.height;
        p = span <= 0 ? 1 : (from - r.top) / span;
      }

      setProgress(Math.min(1, Math.max(0, p)));
    };

    const onScroll = () => {
      // Gộp vào một frame: sự kiện scroll bắn hàng chục lần mỗi giây, đặt state
      // trực tiếp sẽ render lại nhiều hơn số frame màn hình vẽ được.
      cancelAnimationFrame(frame.current);
      frame.current = requestAnimationFrame(measure);
    };

    measure();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);

    return () => {
      cancelAnimationFrame(frame.current);
      window.removeEventListener('scroll', onScroll);
      window.removeEventListener('resize', onScroll);
    };
  }, [ref, mode, start, end, motionKey]);

  return progress;
}
