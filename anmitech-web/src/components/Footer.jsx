import Link from 'next/link';

/**
 * Footer đọc toàn bộ thông tin từ trang Cấu hình của WordPress.
 * Trường nào để trống thì dòng đó không render — thà thiếu một dòng còn hơn
 * hiện ra nhãn "Hotline:" rồi bỏ trống.
 */
export default function Footer({ options = {} }) {
  const year = 2026; // Cố định để build không sinh HTML khác nhau giữa các lần.

  const contacts = [
    { label: 'Hotline', value: options.hotline, href: options.hotline ? `tel:${options.hotline.replace(/\s/g, '')}` : null },
    { label: 'Email', value: options.email, href: options.email ? `mailto:${options.email}` : null },
    { label: 'Zalo', value: options.zalo_phone, href: options.zalo_url || null },
    { label: 'Giờ làm việc', value: options.working_hours, href: null },
    { label: 'Khu vực', value: options.address, href: null },
  ].filter((c) => c.value);

  return (
    <footer className="border-t hairline pb-10 pt-14">
      <div className="container grid gap-10 md:grid-cols-[1.4fr_1fr_1fr]">
        <div>
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/logo.svg" alt={options.brand_name || 'Anmitech'} className="h-9 w-auto dark-hide" />
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/logo-white.svg" alt="" aria-hidden="true" className="h-9 w-auto light-hide" />

          {options.footer_desc && (
            <p className="mt-4 max-w-sm text-sm leading-relaxed muted">{options.footer_desc}</p>
          )}
        </div>

        <div>
          <h2 className="text-sm font-bold uppercase tracking-[0.14em]">Liên kết</h2>
          <ul className="mt-4 space-y-2 text-sm">
            {[
              { label: 'Giới thiệu', href: '/gioi-thieu' },
              { label: 'Dịch vụ', href: '/dich-vu' },
              { label: 'Dự án', href: '/du-an' },
              { label: 'Liên hệ', href: '/lien-he' },
            ].map((l) => (
              <li key={l.href}>
                <Link href={l.href} className="muted transition hover:text-brand-400">
                  {l.label}
                </Link>
              </li>
            ))}
          </ul>
        </div>

        {contacts.length > 0 && (
          <div>
            <h2 className="text-sm font-bold uppercase tracking-[0.14em]">Liên hệ</h2>
            <ul className="mt-4 space-y-2 text-sm">
              {contacts.map((c) => (
                <li key={c.label} className="muted">
                  <span className="opacity-70">{c.label}: </span>
                  {c.href ? (
                    <a href={c.href} className="transition hover:text-brand-400">
                      {c.value}
                    </a>
                  ) : (
                    c.value
                  )}
                </li>
              ))}
            </ul>
          </div>
        )}
      </div>

      <div className="container mt-12 border-t hairline pt-6 text-xs muted">
        © {year} {options.brand_name || 'Anmitech'}
        {options.domain ? ` · ${options.domain}` : ''}
      </div>
    </footer>
  );
}
