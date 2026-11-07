'use client';

import { useEffect, useState } from 'react';

import { forceMotion, initEffects } from '../lib/effects';
import { MOTION_EVENT } from '../react/motion';

/**
 * Dải cảnh báo khi máy đang bật "giảm chuyển động".
 *
 * Đây là nguyên nhân số một của triệu chứng "code có hiệu ứng mà không thấy gì
 * chạy": Windows 11 → Settings → Accessibility → Visual effects → Animation
 * effects, hoặc macOS → Accessibility → Display → Reduce motion. Bật thiết lập
 * đó thì toàn bộ nền đứng yên, chữ hiện luôn, marquee không chạy — đúng như
 * thiết kế, không phải lỗi.
 *
 * Nút "Bật hiệu ứng" thêm .fx-force-motion vào <html> để xem bất chấp thiết
 * lập hệ điều hành. CHỈ dùng khi kiểm tra — người bật giảm chuyển động thường
 * vì chóng mặt hoặc đau nửa đầu, không nên ép họ xem.
 *
 * Component chỉ render khi chạy `npm run dev`. Bản production không có.
 */
export default function MotionNotice() {
  const [reduced, setReduced] = useState(false);
  const [forced, setForced] = useState(false);
  const [hidden, setHidden] = useState(false);

  useEffect(() => {
    const mq = window.matchMedia('(prefers-reduced-motion: reduce)');
    setReduced(mq.matches);

    const onChange = (e) => setReduced(e.matches);
    mq.addEventListener('change', onChange);
    return () => mq.removeEventListener('change', onChange);
  }, []);

  if (!reduced || hidden) return null;

  function toggle() {
    const next = !forced;
    forceMotion(next);
    setForced(next);

    // Phải gắn lại hiệu ứng, không chỉ đổi class.
    //
    // Lúc tải trang, vì hệ điều hành đang giảm chuyển động, initReveal đã gán
    // is-in cho MỌI phần tử luôn. Nếu giờ chỉ bật class rồi thôi thì tất cả
    // animation nổ một lượt, kể cả những khối nằm ngoài khung nhìn — đúng cái
    // lỗi mà thay đổi này đang đi sửa.
    //
    // Xoá is-in rồi chạy lại: lúc này REDUCED() đã thấy .fx-force-motion nên nó
    // dùng IntersectionObserver, hiệu ứng quay về đúng cơ chế chờ cuộn tới.
    document
      .querySelectorAll('[data-fx-reveal].is-in')
      .forEach((el) => el.classList.remove('is-in'));

    initEffects(document);

    // Các section gắn với cuộn (Dự án, Quy trình) dùng hook riêng, không đi qua
    // initEffects. Bắn sự kiện để chúng gắn lại listener, nếu không hai section
    // đó vẫn đứng im trong khi phần còn lại đã chạy.
    window.dispatchEvent(new Event(MOTION_EVENT));
  }

  return (
    <div className="fixed bottom-4 left-1/2 z-[70] w-[min(34rem,calc(100vw-2rem))] -translate-x-1/2 rounded-xl border border-amber-500/40 bg-amber-950/95 p-4 text-xs text-amber-50 shadow-2xl backdrop-blur">
      <p className="font-bold">Máy đang bật &ldquo;giảm chuyển động&rdquo;</p>
      <p className="mt-1 leading-relaxed text-amber-100/80">
        Nền Aura, vệt sáng và hiệu ứng chữ đang bị hệ điều hành tắt — không phải lỗi code. Tắt ở{' '}
        <span className="font-semibold">
          Settings → Accessibility → Visual effects → Animation effects
        </span>
        , hoặc bấm nút dưới để xem tạm.
      </p>

      <div className="mt-3 flex gap-2">
        <button
          type="button"
          onClick={toggle}
          className="rounded-lg bg-amber-400 px-3 py-1.5 font-bold text-amber-950 transition hover:bg-amber-300"
        >
          {forced ? 'Trả về mặc định' : 'Bật hiệu ứng để xem'}
        </button>
        <button
          type="button"
          onClick={() => setHidden(true)}
          className="rounded-lg border border-amber-500/40 px-3 py-1.5 font-semibold transition hover:bg-amber-900"
        >
          Ẩn
        </button>
      </div>
    </div>
  );
}
