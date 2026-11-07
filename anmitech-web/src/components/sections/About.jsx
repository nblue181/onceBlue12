import Link from 'next/link';

import { Reveal, Words } from '../../react/fx';

/**
 * Section 2 — Giới thiệu. Dữ liệu từ nhóm about_* ở trang Cấu hình.
 *
 * `Words` chỉ nhận chuỗi thuần và tách theo khoảng trắng, nên đoạn văn dài sẽ
 * sinh rất nhiều <span>. Vì thế nó chỉ dùng cho tiêu đề, còn đoạn nội dung
 * dùng Reveal thường.
 */
export default function About({ options = {} }) {
  if (!options.about_title && !options.about_desc) return null;

  const hasImage = Boolean(options.about_image);
  const chips = Array.isArray(options.about_chips) ? options.about_chips.filter((c) => c?.label) : [];

  // Ảnh được ưu tiên hơn cụm chip: ảnh thật của khách nói được nhiều hơn
  // một cụm nhãn trang trí. Không có cả hai thì thu về một cột cho gọn.
  const aside = hasImage ? 'image' : chips.length > 0 ? 'chips' : null;

  return (
    <section className="py-24">
      <div
        className={`container grid items-center gap-12 ${aside ? 'md:grid-cols-2' : 'max-w-3xl'}`}
      >
        <div>
          {options.about_eyebrow && (
            <Reveal as="p" className="eyebrow">
              {options.about_eyebrow}
            </Reveal>
          )}

          {options.about_title && (
            <Reveal delay={1}>
              <Words as="h2" text={options.about_title} className="section-title" />
            </Reveal>
          )}

          {options.about_desc && (
            <Reveal as="p" delay={2} className="mt-6 text-base leading-relaxed muted md:text-lg">
              {options.about_desc}
            </Reveal>
          )}

          {options.about_link_label && options.about_link_url && (
            <Reveal delay={3} className="mt-8">
              <Link href={options.about_link_url} className="btn btn-ghost">
                {options.about_link_label}
                <span aria-hidden="true">→</span>
              </Link>
            </Reveal>
          )}
        </div>

        {aside === 'image' && (
          <Reveal delay={2} variant="right">
            {/* eslint-disable-next-line @next/next/no-img-element */}
            <img
              src={options.about_image}
              alt=""
              className="w-full rounded-2xl border hairline object-cover"
              loading="lazy"
            />
          </Reveal>
        )}

        {aside === 'chips' && (
          <Reveal delay={2} variant="right">
            {/* Cụm chip trôi lơ lửng — hiệu ứng `fx-float-*` trong effects.css.
                Năm quỹ đạo khác nhau, chu kỳ 12–18 giây, cộng độ trễ so le nên
                không bao giờ trùng pha. Nếu cả cụm cùng nhịp thì nó đập lên
                đập xuống như một khối, mất hẳn cảm giác lơ lửng.

                aria-hidden vì đây là trang trí: nội dung thật đã nằm ở đoạn văn
                bên trái, đọc lại một cụm từ khoá rời rạc chỉ làm rối. */}
            <ul
              className="flex flex-wrap justify-center gap-3 md:gap-4"
              aria-hidden="true"
            >
              {chips.map((c, i) => (
                <li
                  key={`${c.label}-${i}`}
                  className={`chip fx-float-${(i % 5) + 1}`}
                  style={{ animationDelay: `${(i % 5) * -2.4}s` }}
                >
                  {c.label}
                </li>
              ))}
            </ul>
          </Reveal>
        )}
      </div>
    </section>
  );
}
