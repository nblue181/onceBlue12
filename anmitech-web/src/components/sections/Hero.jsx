import { Karaoke, Reveal } from '../../react/fx';

/**
 * Section 1 — Hero.
 *
 * Không tự dựng nền: nền Aura nay là của cả website, đặt một lần ở
 * app/layout.jsx. Trước đây Hero tự dựng ba lớp nên chỉ banner đầu có Aura,
 * cuộn xuống là nền phẳng.
 *
 * min-h ở đây chỉ để Hero chiếm gần hết màn hình đầu tiên, không còn liên
 * quan tới chiều cao của lớp nền nữa.
 */
export default function Hero({ options = {} }) {
  // hero_karaoke là một cụm CON của hero_title. Tách tiêu đề tại đúng cụm đó
  // để bọc <Karaoke> quanh nó mà không phải nhập tiêu đề hai lần.
  const title = options.hero_title || '';
  const key = options.hero_karaoke || '';
  const at = key ? title.indexOf(key) : -1;

  const heading =
    at >= 0 ? (
      <>
        {title.slice(0, at)}
        <Karaoke>{key}</Karaoke>
        {title.slice(at + key.length)}
      </>
    ) : (
      title
    );

  // effects.js đọc data-fx-type dưới dạng JSON array các chuỗi.
  const typed = (options.hero_typewriter || []).map((r) => r?.text).filter(Boolean);

  const zalo = options.zalo_url || (options.zalo_phone ? `https://zalo.me/${options.zalo_phone}` : '');

  return (
    <section className="flex min-h-[92vh] items-center pt-16">
      <div className="container py-20">
        <div className="max-w-3xl">
          {options.hero_eyebrow && (
            <Reveal as="p" className="eyebrow">
              {options.hero_eyebrow}
            </Reveal>
          )}

          {title && (
            <Reveal as="h1" delay={1} className="mt-4 text-balance text-4xl font-bold leading-[1.1] tracking-tightest md:text-6xl">
              {heading}
            </Reveal>
          )}

          {typed.length > 0 && (
            <Reveal delay={2} className="mt-5 text-lg font-semibold md:text-xl">
              <span className="muted">Chúng tôi làm </span>
              <span className="fx-text-brand" data-fx-type={JSON.stringify(typed)} />
            </Reveal>
          )}

          {options.hero_desc && (
            <Reveal as="p" delay={3} className="mt-6 max-w-xl text-base leading-relaxed muted md:text-lg">
              {options.hero_desc}
            </Reveal>
          )}

          <Reveal delay={4} className="mt-9 flex flex-wrap items-center gap-3">
            {zalo && (
              <a href={zalo} target="_blank" rel="noopener noreferrer" className="btn fx-btn-brand">
                Nhắn Zalo tư vấn
              </a>
            )}
            <a href="#bang-gia" className="btn btn-ghost">
              Xem bảng giá
            </a>
          </Reveal>

          {options.hero_note && (
            <Reveal as="p" delay={5} className="mt-4 text-sm muted">
              {options.hero_note}
            </Reveal>
          )}
        </div>
      </div>
    </section>
  );
}
