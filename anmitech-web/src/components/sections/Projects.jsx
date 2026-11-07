'use client';

import { useRef } from 'react';
import Link from 'next/link';

import { Reveal } from '../../react/fx';
import { useReducedMotion, useScrollProgress } from '../../react/motion';

/**
 * Section 4 — Dự án. Cuộn dọc để chạy ngang, giống namtrandang.com.
 *
 * Cách hoạt động: khối ngoài cao `n × 100vh`, khối trong `position: sticky` cao
 * đúng một khung nhìn nên nó bị ghim lại khi cuộn qua. Tiến độ cuộn 0 → 1 được
 * đổi thành `translateX` của dải thẻ. Kết quả: bánh xe chuột đẩy các dự án đi
 * ngang, mỗi vòng cuộn ăn đúng một dự án.
 *
 * Vì sao không dùng `overflow-x: scroll` với scroll-snap: cách đó buộc khách
 * phải cuộn ngang (trackpad hai ngón, hoặc shift+wheel) — chuột thường không
 * làm được, và trên trang dài thì họ cuộn dọc qua luôn mà không biết có gì.
 *
 * Ba đường rút gọn, đều có chủ ý:
 *   · một dự án        → không ghim, hiện luôn một thẻ
 *   · giảm chuyển động → BỎ HẲN khối ghim, chỉ dựng lưới dọc. Nếu vẫn dựng khối
 *                        ghim thì khách phải cuộn qua n×100vh mà nội dung không
 *                        đổi — trông như trang bị treo.
 *   · mobile           → cũng lưới dọc, ghim màn hình trên điện thoại rất khó chịu
 */
export default function Projects({ projects = [] }) {
  const trackRef = useRef(null);
  const reduced = useReducedMotion();
  const progress = useScrollProgress(trackRef, { mode: 'track' });

  if (projects.length === 0) return null;

  const n = projects.length;
  const shift = n > 1 ? progress * (n - 1) * 100 : 0;

  return (
    <section className="py-24">
      <div className="container">
        <Reveal>
          <p className="eyebrow">Dự án tiêu biểu</p>
          <h2 className="section-title">Một số website đã thực hiện</h2>
          <p className="mt-4 max-w-xl text-base muted">
            Mỗi dự án bắt đầu từ một nhu cầu khác nhau. Cuộn xuống để xem từng bài toán.
          </p>
        </Reveal>
      </div>

      {/* ---------- Bản mobile và bản giảm chuyển động: lưới dọc ---------- */}
      <div className={`container mt-12 grid gap-6 ${reduced ? '' : 'md:hidden'}`}>
        {projects.map((p, i) => (
          <Reveal key={p.id} delay={i}>
            <Card project={p} index={i} total={n} />
          </Reveal>
        ))}
      </div>

      {/* ---------- Bản desktop: ghim và chạy ngang ---------- */}
      {!reduced && (
      <div
        ref={trackRef}
        className="relative mt-12 hidden md:block"
        style={{ height: n > 1 ? `${n * 100}vh` : 'auto' }}
      >
        <div className="sticky top-16 flex h-[calc(100vh-4rem)] items-center overflow-hidden">
          <div className="container">
            <div
              className="flex gap-6 transition-transform duration-100 ease-out"
              style={{ transform: `translateX(-${shift}%)` }}
            >
              {projects.map((p, i) => (
                <div key={p.id} className="w-full shrink-0">
                  <Card project={p} index={i} total={n} active={Math.round(progress * (n - 1)) === i} />
                </div>
              ))}
            </div>

            {/* Thanh tiến độ — cho khách biết còn bao nhiêu dự án nữa, nếu không
                họ sẽ tưởng trang bị kẹt khi cuộn mà nội dung không đi xuống. */}
            {n > 1 && (
              <div className="mt-8 flex items-center gap-4">
                <div
                  className="h-0.5 flex-1 overflow-hidden rounded-full"
                  style={{ backgroundColor: 'hsl(var(--ink-line) / var(--ink-line-alpha))' }}
                >
                  <div
                    className="h-full rounded-full bg-brand-400 transition-[width] duration-100"
                    style={{ width: `${((Math.round(progress * (n - 1)) + 1) / n) * 100}%` }}
                  />
                </div>
                <span className="text-sm font-bold tabular-nums muted">
                  {String(Math.round(progress * (n - 1)) + 1).padStart(2, '0')} / {String(n).padStart(2, '0')}
                </span>
              </div>
            )}
          </div>
        </div>
      </div>
      )}

      <div className="container mt-10">
        <Link href="/du-an" className="btn btn-ghost">
          Xem tất cả dự án <span aria-hidden="true">→</span>
        </Link>
      </div>
    </section>
  );
}

/* ------------------------------------------------------------------ */

function Card({ project: p, index, total, active = true }) {
  return (
    <article
      className={`fx-card overflow-hidden rounded-2xl transition-opacity duration-300 ${
        active ? 'opacity-100' : 'opacity-40'
      }`}
    >
      <div className="relative">
        {p.thumb?.url ? (
          /* eslint-disable-next-line @next/next/no-img-element */
          <img
            src={p.thumb.url}
            alt={p.thumb.alt || p.title}
            className="aspect-[16/9] w-full object-cover"
            loading="lazy"
          />
        ) : (
          <div
            className="grid aspect-[16/9] w-full place-items-center text-xs font-bold uppercase tracking-[0.2em] muted"
            style={{ backgroundColor: 'hsl(var(--brand-400) / 0.06)' }}
          >
            Ảnh dự án
          </div>
        )}

        <span
          className="absolute right-5 top-5 text-sm font-bold tabular-nums text-brand-400"
          aria-hidden="true"
        >
          {String(index + 1).padStart(2, '0')} / {String(total).padStart(2, '0')}
        </span>
      </div>

      <div className="p-6">
        {p.categories?.length > 0 && (
          <div className="flex flex-wrap gap-2">
            {p.categories.map((c) => (
              <span
                key={c.slug}
                className="rounded-md px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-brand-400"
                style={{ backgroundColor: 'hsl(var(--brand-400) / 0.10)' }}
              >
                {c.name}
              </span>
            ))}
          </div>
        )}

        <h3 className="mt-3 text-2xl font-bold tracking-tightest">
          <Link href={`/du-an/${p.slug}`} className="transition hover:text-brand-400">
            {p.title}
          </Link>
        </h3>

        {p.excerpt && <p className="mt-2 max-w-xl text-sm leading-relaxed muted">{p.excerpt}</p>}

        <dl className="mt-5 flex flex-wrap gap-x-8 gap-y-2 text-xs">
          {[
            ['Khách hàng', p.acf?.client],
            ['Lĩnh vực', p.acf?.industry],
            ['Loại website', p.acf?.website_type],
            ['Năm', p.acf?.year],
          ]
            .filter(([, v]) => v)
            .map(([k, v]) => (
              <div key={k}>
                <dt className="muted">{k}</dt>
                <dd className="mt-0.5 font-semibold">{v}</dd>
              </div>
            ))}
        </dl>
      </div>
    </article>
  );
}
