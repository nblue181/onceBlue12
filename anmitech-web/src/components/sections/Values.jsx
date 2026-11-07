import { CountUp, Reveal } from '../../react/fx';

/**
 * Section 5 — Giá trị cốt lõi, kèm dải số liệu.
 *
 * Số liệu chỉ render khi trang Cấu hình có nhập. `CountUp` là hiệu ứng đếm nên
 * càng phải chắc số đó thật — chỉ dẫn trong WordPress đã ghi "không có thì để
 * trống, đừng bịa".
 */

// Bộ icon khớp đúng danh sách choices của trường select trong SCF.
const ICONS = {
  eye: (
    <>
      <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
      <circle cx="12" cy="12" r="2.6" />
    </>
  ),
  chat: <path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.4A8 8 0 1 1 21 12Z" />,
  heart: <path d="M12 20s-7-4.4-7-9.3A4 4 0 0 1 12 8a4 4 0 0 1 7 2.7c0 4.9-7 9.3-7 9.3Z" />,
  square: (
    <>
      <rect x="4" y="4" width="16" height="16" rx="3" />
      <path d="M8.5 12.5l2.5 2.5 4.5-5" />
    </>
  ),
  target: (
    <>
      <circle cx="12" cy="12" r="8" />
      <circle cx="12" cy="12" r="3.4" />
    </>
  ),
};

function Icon({ name }) {
  const path = ICONS[name];
  if (!path) return null;

  return (
    <span
      className="grid h-11 w-11 place-items-center rounded-xl"
      style={{ backgroundColor: 'hsl(var(--brand-400) / 0.12)' }}
    >
      <svg
        viewBox="0 0 24 24"
        className="h-6 w-6 text-brand-400"
        fill="none"
        stroke="currentColor"
        strokeWidth="1.7"
        strokeLinecap="round"
        strokeLinejoin="round"
        aria-hidden="true"
      >
        {path}
      </svg>
    </span>
  );
}

export default function Values({ options = {} }) {
  const values = options.values || [];
  const stats = options.stats || [];

  if (values.length === 0 && stats.length === 0) return null;

  return (
    <section className="py-24">
      <div className="container">
        {values.length > 0 && (
          <>
            <Reveal>
              <p className="eyebrow">Cách chúng tôi làm</p>
              <h2 className="section-title">Bốn điều giữ trong mọi dự án</h2>
            </Reveal>

            <div className="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
              {values.map((v, i) => (
                <Reveal key={`${v.title}-${i}`} delay={i} className="fx-card rounded-2xl p-6">
                  <Icon name={v.icon} />
                  <h3 className="mt-4 text-lg font-bold">{v.title}</h3>
                  {v.desc && <p className="mt-2 text-sm leading-relaxed muted">{v.desc}</p>}
                </Reveal>
              ))}
            </div>
          </>
        )}

        {stats.length > 0 && (
          <div className="mt-14 grid gap-6 rounded-2xl border hairline p-8 sm:grid-cols-3">
            {stats.map((s, i) => (
              <Reveal key={`${s.label}-${i}`} delay={i} className="text-center">
                <div className="text-4xl font-bold tracking-tightest text-brand-400">
                  <CountUp to={Number(s.number) || 0} suffix={s.suffix || ''} />
                </div>
                {s.label && <p className="mt-2 text-sm muted">{s.label}</p>}
              </Reveal>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
