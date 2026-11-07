'use client';

// Bắt buộc với App Router của Next.js: file này dùng useEffect, useRef và
// IntersectionObserver — tất cả chỉ tồn tại ở trình duyệt. Thiếu dòng trên thì
// Next.js cố render ở server và báo lỗi ngay khi build.

/**
 * ANMITECH — Lớp bọc React cho bộ hiệu ứng
 *
 * effects.js là JavaScript thuần nên dùng được ở bất kỳ đâu. File này chỉ gói
 * lại thành hook và component cho tiện trong React.
 *
 *   import { useEffects, TechBackground, Reveal, Karaoke, Marquee, CountUp } from './react/fx';
 */

import { Fragment, useEffect, useRef } from 'react';
import { initEffects } from '../lib/effects';

/**
 * Gọi một lần ở component gốc của trang.
 * Truyền deps để chạy lại khi nội dung đổi (ví dụ khi dữ liệu từ WordPress về).
 */
export function useEffects(deps = []) {
  useEffect(() => {
    const cleanup = initEffects(document);
    return cleanup;
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, deps);
}

/**
 * Nền động 4 lớp. Đặt trong khối cha có `relative`.
 *
 *   <section className="relative overflow-hidden" data-fx-hover-bg>
 *     <TechBackground />
 *     <div className="relative z-10">…nội dung…</div>
 *   </section>
 *
 * Nội dung PHẢI có z-10 (hoặc relative) để nằm trên các lớp nền.
 */
export function TechBackground({ grid = true, waves = true, scanline = true, className = '' }) {
  return (
    <div className={`fx-bg ${className}`} aria-hidden="true">
      {grid && <div className="fx-grid" />}
      {waves && (
        <>
          <div className="fx-wave fx-wave-a" />
          <div className="fx-wave fx-wave-b" />
        </>
      )}
      {scanline && <div className="fx-scanline" />}
    </div>
  );
}

/**
 * Hiện dần khi cuộn vào khung nhìn.
 * `delay` là số thứ tự, không phải mili giây — mỗi bậc cách nhau 80ms.
 */
export function Reveal({ as: Tag = 'div', delay = 0, variant, className = '', children, ...rest }) {
  const variantClass = variant ? `fx-reveal-${variant}` : '';
  return (
    <Tag
      data-fx-reveal=""
      style={{ '--i': delay }}
      className={`${variantClass} ${className}`.trim()}
      {...rest}
    >
      {children}
    </Tag>
  );
}

/**
 * Chữ hiện dần từ trái sang. Dùng cho ĐÚNG MỘT cụm từ trọng tâm ở H1.
 *
 *   <h1>Website <Karaoke>đúng nhu cầu</Karaoke> cho doanh nghiệp</h1>
 */
export function Karaoke({ children, className = '' }) {
  return <span className={`fx-karaoke ${className}`.trim()}>{children}</span>;
}

/**
 * Từng từ hiện lên so le. Chỉ nhận chuỗi thuần.
 *
 * Dấu cách phải nằm NGOÀI <span>, không được nhét vào trong.
 * `.fx-word` là `display: inline-block`, mà khoảng trắng ở cuối một inline-block
 * bị trình duyệt thu gọn mất — kết quả là cả câu dính liền thành một khối chữ
 * không đọc được. Đặt ra ngoài thì nó là text node của thẻ cha, giữ nguyên.
 */
export function Words({ text, as: Tag = 'span', className = '' }) {
  const words = String(text).trim().split(/\s+/);
  return (
    <Tag className={className}>
      {words.map((w, i) => (
        <Fragment key={i}>
          <span className="fx-word" style={{ '--i': i }}>
            {w}
          </span>
          {i < words.length - 1 ? ' ' : null}
        </Fragment>
      ))}
    </Tag>
  );
}

/**
 * Băng chạy vô hạn. Component tự nhân đôi nội dung nên không cần JS ngoài.
 * Rê chuột vào thì dừng lại để người xem đọc kịp.
 */
export function Marquee({ speed = '30s', reverse = false, className = '', children }) {
  return (
    <div className={`fx-marquee ${className}`.trim()}>
      <div
        className={`fx-marquee-track ${reverse ? 'is-reverse' : ''}`.trim()}
        style={{ '--fx-marquee-speed': speed }}
      >
        {children}
        <span aria-hidden="true" className="contents">
          {children}
        </span>
      </div>
    </div>
  );
}

/**
 * Đếm số tăng dần khi lọt vào khung nhìn.
 * Chỉ dùng cho số liệu THẬT. Không bịa "100+ dự án" khi chưa có.
 */
export function CountUp({ to, suffix = '', duration = 1400, className = '' }) {
  const ref = useRef(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const fmt = new Intl.NumberFormat('vi-VN');

    if (reduced || !('IntersectionObserver' in window)) {
      el.textContent = fmt.format(to) + suffix;
      return;
    }

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (!e.isIntersecting) return;
          io.unobserve(e.target);
          const t0 = performance.now();
          const tick = (now) => {
            const p = Math.min(1, (now - t0) / duration);
            const eased = 1 - Math.pow(1 - p, 3);
            el.textContent = fmt.format(Math.round(to * eased)) + suffix;
            if (p < 1) requestAnimationFrame(tick);
          };
          requestAnimationFrame(tick);
        });
      },
      { threshold: 0.4 }
    );

    io.observe(el);
    return () => io.disconnect();
  }, [to, suffix, duration]);

  return <span ref={ref} className={className}>0{suffix}</span>;
}

/**
 * Vùng có vệt sáng đi theo con trỏ.
 */
export function Spotlight({ className = '', children }) {
  return (
    <div data-fx-spotlight="" className={`relative ${className}`.trim()}>
      {children}
    </div>
  );
}
