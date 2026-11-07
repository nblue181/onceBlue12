import Link from 'next/link';

import { Reveal } from '../../react/fx';

/** Section 7 — CTA cuối trang. */
export default function Cta({ options = {} }) {
  if (!options.cta_title) return null;

  const zalo = options.zalo_url || (options.zalo_phone ? `https://zalo.me/${options.zalo_phone}` : '');

  return (
    <section className="py-24">
      <div className="container text-center">
        <Reveal>
          <h2 className="section-title mx-auto max-w-2xl">{options.cta_title}</h2>
        </Reveal>

        {options.cta_desc && (
          <Reveal as="p" delay={1} className="mx-auto mt-4 max-w-xl text-base muted">
            {options.cta_desc}
          </Reveal>
        )}

        <Reveal delay={2} className="mt-9 flex flex-wrap justify-center gap-3">
          {zalo && (
            <a href={zalo} target="_blank" rel="noopener noreferrer" className="btn fx-btn-brand">
              Nhắn Zalo
            </a>
          )}
          <Link href="/lien-he" className="btn btn-ghost">
            Gửi yêu cầu qua form
          </Link>
        </Reveal>

        {options.hotline && (
          <Reveal as="p" delay={3} className="mt-6 text-sm muted">
            Hoặc gọi{' '}
            <a href={`tel:${options.hotline.replace(/\s/g, '')}`} className="font-semibold text-brand-400">
              {options.hotline}
            </a>
            {options.working_hours ? ` · ${options.working_hours}` : ''}
          </Reveal>
        )}
      </div>
    </section>
  );
}
