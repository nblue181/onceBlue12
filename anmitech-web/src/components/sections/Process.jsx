'use client';

import { useRef } from 'react';

import { Reveal } from '../../react/fx';
import { useReducedMotion, useScrollProgress } from '../../react/motion';

/**
 * Section 6 — Quy trình, băng thẻ trượt trong lúc màn hình bị ghim.
 *
 * Bốn điều làm nên section này:
 *
 *   1. GHIM MÀN HÌNH. Khối ngoài cao hơn khung nhìn nhiều lần, khối trong
 *      `sticky top-0 h-screen`. Cuộn tới đây thì nội dung đứng yên giữa màn
 *      hình, quãng cuộn được tiêu vào việc chạy băng thẻ. Hết bước cuối mới
 *      nhả ra cho trang chạy tiếp.
 *
 *   2. Chạy theo CUỘN CHUỘT, không tự động. Dừng tay là dừng, cuộn ngược là lùi.
 *
 *   3. Số mốc và thẻ nằm trong CÙNG MỘT ô của băng nên luôn thẳng cột. Để số ở
 *      một lưới riêng còn thẻ thì trượt là hai hệ toạ độ khác nhau — số 1 sẽ ở
 *      mép trái trong khi thẻ 1 nằm giữa màn hình.
 *
 *   4. Thẻ đang xem to bằng kích thước thật, các thẻ khác thu còn 86% và mờ đi.
 *      Dùng `transform: scale` chứ không đổi width/height — scale không kích
 *      hoạt tính lại bố cục, nên 5 thẻ co giãn liên tục vẫn mượt.
 *
 * Mỗi bước chiếm đúng 1/n quãng cuộn, kể cả bước đầu và bước cuối. Nếu chia
 * theo n-1 khoảng thì hai bước ngoài cùng chỉ được nửa thời gian của các bước
 * giữa, và bước 5 vừa hiện ra đã bị nhả ghim.
 *
 * Đường nối chạy MƯỢT theo giá trị cuộn, còn băng thẻ thì NHẢY theo từng bước.
 * Cố ý: đường nối cho cảm giác tay mình đang kéo, thẻ nhảy dứt khoát mới ra
 * chất "slide" chứ không lửng lơ hai thẻ cùng lúc.
 *
 * Trên mobile và khi giảm chuyển động: danh sách dọc, hiện đủ mọi bước, không
 * ghim gì cả. Ghim màn hình trên điện thoại là cách nhanh nhất để khách thoát
 * trang, còn người đã tắt chuyển động thì không nên bị giữ lại.
 */

/** Bề rộng một thẻ và khoảng cách giữa hai thẻ. Dùng trong calc() của transform. */
const CARD_W = '21rem';
const CARD_GAP = '1.5rem';

/** Khoảng cách giữa tâm hai mốc liền nhau — cũng là quãng băng trượt mỗi bước. */
const STRIDE = `(${CARD_W} + ${CARD_GAP})`;

/** Đường kính mốc số. Đường nối phải đặt ở đúng nửa giá trị này mới trúng tâm. */
const NODE = '3.5rem';

/** Cuộn bao nhiêu phần khung nhìn thì sang một bước. Càng lớn càng phải cuộn lâu. */
const SCROLL_PER_STEP = 40;

export default function Process({ options = {} }) {
  const ref = useRef(null);
  const reduced = useReducedMotion();
  const progress = useScrollProgress(ref, { mode: 'track' });

  const steps = options.process || [];
  const n = steps.length;
  if (n === 0) return null;

  // Chia đều quãng cuộn cho n bước. Bước i giữ màn hình trong khoảng [i/n, (i+1)/n).
  const current = Math.min(n - 1, Math.max(0, Math.floor(progress * n)));

  // Đường nối vẽ tới tâm mốc cuối đúng lúc bước cuối bắt đầu, rồi đứng yên
  // suốt lượt dừng của bước đó.
  const lineStrides = Math.min(n - 1, progress * n);

  // Đưa tâm ô thứ `current` về đúng giữa: băng bắt đầu ở mốc 50% của khối cha,
  // rồi lùi lại đúng khoảng cách từ đầu băng tới tâm ô đó.
  const shift = `calc(-1 * (${current} * ${STRIDE} + ${CARD_W} / 2))`;

  const header = (
    <>
      {options.process_eyebrow && <p className="eyebrow">{options.process_eyebrow}</p>}
      {options.process_title && <h2 className="section-title max-w-3xl">{options.process_title}</h2>}
    </>
  );

  return (
    <>
      {/* ---------- Bản desktop: ghim màn hình ----------
          Chiều cao khối ngoài = một khung nhìn để hiện nội dung, cộng thêm phần
          quãng cuộn bị tiêu cho việc chạy băng. */}
      {!reduced && (
        <section
          ref={ref}
          className="relative hidden md:block"
          style={{ height: `calc(100vh + ${n * SCROLL_PER_STEP}vh)` }}
        >
          <div className="sticky top-0 flex h-screen flex-col justify-center overflow-hidden">
            <div className="container">
              <Reveal>{header}</Reveal>
            </div>

            {/* Băng đặt ngoài .container để thẻ hai bên ló ra tới mép màn hình.
                Nằm trong container thì băng bị cắt cụt ngay ở lề nội dung,
                trông như lỗi tràn chứ không phải như một băng đang trượt. */}
            <div
              className="relative mt-16 overflow-hidden"
              style={{
                // Mờ dần hai mép để thẻ bị cắt không kết thúc bằng một đường thẳng đứng.
                WebkitMaskImage:
                  'linear-gradient(to right, transparent, #000 10%, #000 90%, transparent)',
                maskImage:
                  'linear-gradient(to right, transparent, #000 10%, #000 90%, transparent)',
              }}
            >
              <div
                className="relative will-change-transform"
                style={{
                  marginLeft: '50%',
                  transform: `translateX(${shift})`,
                  transition: 'transform 700ms cubic-bezier(0.22, 0.61, 0.36, 1)',
                }}
              >
                {/* Đường nối chạy từ TÂM mốc đầu tới TÂM mốc cuối. Nằm ngoài
                    <ol> vì con trực tiếp của <ol> chỉ được là <li>. */}
                <div
                  className="absolute h-px"
                  style={{
                    top: `calc(${NODE} / 2)`,
                    left: `calc(${CARD_W} / 2)`,
                    width: `calc(${n - 1} * ${STRIDE})`,
                    backgroundColor: 'hsl(var(--ink-line) / var(--ink-line-alpha))',
                  }}
                  aria-hidden="true"
                />
                <div
                  className="absolute h-px bg-brand-400"
                  style={{
                    top: `calc(${NODE} / 2)`,
                    left: `calc(${CARD_W} / 2)`,
                    width: `calc(${lineStrides.toFixed(3)} * ${STRIDE})`,
                  }}
                  aria-hidden="true"
                />

                <ol className="flex items-stretch" style={{ gap: CARD_GAP }}>
                  {steps.map((s, i) => {
                    const active = i === current;

                    return (
                      <li
                        key={`${s.title}-${i}`}
                        className="flex shrink-0 flex-col"
                        style={{ width: CARD_W }}
                        aria-current={active ? 'step' : undefined}
                      >
                        {/* Mốc số nằm trong cùng ô với thẻ, canh giữa ô — nên
                            tâm mốc luôn trùng tâm thẻ, không thể lệch cột. */}
                        <div className="flex justify-center">
                          <Node
                            index={i}
                            size={NODE}
                            state={i < current ? 'done' : active ? 'current' : 'todo'}
                          />
                        </div>

                        <div
                          className="fx-card mt-10 flex-1 rounded-2xl p-6"
                          style={{
                            transform: active ? 'scale(1)' : 'scale(0.86)',
                            opacity: active ? 1 : 0.4,
                            borderColor: active ? 'hsl(var(--brand-400) / 0.5)' : undefined,
                            transition:
                              'transform 700ms cubic-bezier(0.22, 0.61, 0.36, 1), opacity 500ms ease, border-color 300ms ease',
                          }}
                        >
                          <h3 className="text-lg font-bold leading-snug">{s.title}</h3>
                          <p className="mt-1 text-xs font-bold tabular-nums text-brand-400">
                            {String(i + 1).padStart(2, '0')}
                          </p>
                          {s.desc && (
                            <p className="mt-3 text-sm leading-relaxed muted">{s.desc}</p>
                          )}
                        </div>
                      </li>
                    );
                  })}
                </ol>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* ---------- Bản mobile: đường mốc dọc ----------
          Đường kẻ nằm ngoài <ol>: con trực tiếp của <ol> chỉ được là <li>. */}
      <section className={`py-24 ${reduced ? '' : 'md:hidden'}`}>
        <div className="container">
          <Reveal>{header}</Reveal>

          <div className="relative mt-12">
            <div
              className="absolute bottom-6 left-6 top-6 w-px"
              style={{ backgroundColor: 'hsl(var(--ink-line) / var(--ink-line-alpha))' }}
              aria-hidden="true"
            />

            <ol className="relative space-y-6">
              {steps.map((s, i) => (
                <Reveal as="li" key={`m-${s.title}-${i}`} delay={i} className="flex gap-4">
                  <Node index={i} state="plain" />
                  <div className="fx-card flex-1 rounded-2xl p-5">
                    <h3 className="text-base font-bold">{s.title}</h3>
                    {s.desc && <p className="mt-2 text-sm leading-relaxed muted">{s.desc}</p>}
                  </div>
                </Reveal>
              ))}
            </ol>
          </div>
        </div>
      </section>
    </>
  );
}

/* ------------------------------------------------------------------ */

/**
 * Một mốc trên đường.
 *   done    — đã cuộn qua, hiện dấu tích
 *   current — đang xem, viền sáng và có quầng
 *   todo    — chưa tới, chỉ hiện số mờ
 *   plain   — bản mobile: không có trạng thái theo cuộn, mọi mốc như nhau
 *
 * Kích thước truyền vào chứ không cứng trong class: đường nối phải đặt ở đúng
 * nửa đường kính mốc, để hai chỗ tự do đổi là đường sẽ trượt khỏi tâm.
 */
function Node({ index, state, size = '3rem' }) {
  const base =
    'relative z-10 grid shrink-0 place-items-center rounded-2xl border font-bold tabular-nums transition-all duration-300';

  const box = { width: size, height: size, fontSize: `calc(${size} * 0.36)` };

  if (state === 'done') {
    return (
      <span
        className={`${base} border-transparent bg-brand-500 text-white`}
        style={box}
        aria-label={`Bước ${index + 1}, đã xem`}
      >
        <svg
          viewBox="0 0 24 24"
          style={{ width: `calc(${size} * 0.5)`, height: `calc(${size} * 0.5)` }}
          fill="none"
          stroke="currentColor"
          strokeWidth="2.4"
          aria-hidden="true"
        >
          <path d="M5 13l4 4L19 7" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </span>
    );
  }

  if (state === 'current') {
    return (
      <span
        className={`${base} text-brand-400`}
        style={{
          ...box,
          borderColor: 'hsl(var(--brand-400) / 0.7)',
          backgroundColor: 'hsl(var(--brand-400) / 0.10)',
          boxShadow: '0 0 0 8px hsl(var(--brand-400) / 0.10)',
        }}
      >
        {index + 1}
      </span>
    );
  }

  if (state === 'plain') {
    return (
      <span
        className={`${base} text-brand-400`}
        style={{
          ...box,
          borderColor: 'hsl(var(--brand-400) / 0.35)',
          backgroundColor: 'var(--aura-base)',
        }}
      >
        {index + 1}
      </span>
    );
  }

  return (
    <span
      className={`${base} muted`}
      style={{
        ...box,
        borderColor: 'hsl(var(--ink-line) / var(--ink-line-alpha))',
        // Nền đục để đường kẻ không xuyên qua giữa con số.
        backgroundColor: 'var(--aura-base)',
      }}
    >
      {index + 1}
    </span>
  );
}
