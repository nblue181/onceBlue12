import Link from 'next/link';

import { Reveal } from '../../react/fx';
import { comparableServices, discountBadge, formatPrice } from '../../lib/api';

/**
 * Section 3 — Bảng so sánh dịch vụ.
 *
 * MỘT CỘT = MỘT DỊCH VỤ (CPT service), không phải một gói. Repeater `packages`
 * là chuyện khác: bảng giá nhiều gói bên trong trang chi tiết một dịch vụ.
 *
 * Bảng dựng bằng CSS grid chứ không dùng <table>: cần mỗi cột là một khối liền
 * mạch để tô sáng cột được đề xuất và cho phép cuộn ngang trên mobile — với
 * <table> thì tô cả cột phải chạm vào từng <td>, và cột không thể có bóng đổ.
 *
 * Vẫn giữ role="table" để trình đọc màn hình hiểu đây là bảng so sánh.
 */

// Các hàng cố định, đúng bộ hàng của bảng gốc. Thứ tự này là thứ tự hiển thị.
const ROWS = [
  { key: 'cmp_fit', label: 'Phù hợp', check: false },
  { key: 'cmp_design', label: 'Giao diện', check: true },
  { key: 'cmp_seo', label: 'SEO', check: true },
  { key: 'cmp_admin', label: 'Quản trị', check: true },
  { key: 'cmp_analytics', label: 'Đo lường', check: true },
  { key: 'cmp_support', label: 'Hỗ trợ', check: true },
];

export default function ServicesTable({ services = [], options = {} }) {
  const cols = comparableServices(services);
  if (cols.length === 0) return null;

  // Hàng phát sinh: lấy hợp của mọi nhãn, giữ thứ tự lần đầu gặp. Cột nào
  // không có nhãn đó sẽ hiện dấu gạch — nhờ vậy bảng không bao giờ lệch hàng
  // dù các dịch vụ nhập không giống nhau.
  const extraLabels = [];
  for (const s of cols) {
    for (const r of s.acf?.cmp_extra_rows || []) {
      if (r?.label && !extraLabels.includes(r.label)) extraLabels.push(r.label);
    }
  }

  const zalo = options.zalo_url || (options.zalo_phone ? `https://zalo.me/${options.zalo_phone}` : '');

  // Một cột nhãn hàng + N cột dịch vụ. minmax để cột không co dưới mức đọc được.
  const gridCols = { gridTemplateColumns: `minmax(9rem, 12rem) repeat(${cols.length}, minmax(13rem, 1fr))` };

  // Section không có nền riêng: nền Aura của toàn site (app/layout.jsx) chạy
  // xuyên qua đây. Bỏ luôn data-fx-hover-bg — thuộc tính đó chỉ tác động lên hai
  // quầng .fx-wave của TechBackground, giờ không còn gì để tác động.
  return (
    <section id="bang-gia" className="py-24">
      <div className="container">
        <Reveal className="text-center">
          <p className="eyebrow">Bảng giá dịch vụ</p>
          <h2 className="section-title">Chọn theo việc bạn cần làm được</h2>
          <p className="mx-auto mt-4 max-w-2xl text-base muted">
            So sánh theo hạng mục: giá, thời gian, phạm vi và phần tối ưu SEO đều nhìn được ngay.
          </p>
        </Reveal>

        {/* Cuộn ngang nằm ở ĐÂY, không ở body — trang không bao giờ cuộn ngang. */}
        <Reveal delay={1} className="mt-12 -mx-4 overflow-x-auto px-4 pb-2">
          <div
            role="table"
            aria-label="So sánh các dịch vụ"
            className="min-w-[52rem] overflow-hidden rounded-2xl border hairline"
          >
            {/* ---------- Hàng đầu: tên dịch vụ ---------- */}
            <div role="row" className="grid border-b hairline" style={gridCols}>
              <Cell head className="text-xs font-bold uppercase tracking-[0.14em] muted">
                Hạng mục
              </Cell>

              {cols.map((s) => (
                <Cell key={s.id} head col={s} className="border-l hairline">
                  <div className="text-lg font-bold leading-tight">{s.title}</div>
                  {s.acf?.cmp_badge && (
                    <span
                      className={`mt-2 inline-block rounded-md px-2 py-1 text-[10px] font-bold uppercase tracking-wider ${
                        s.acf?.cmp_recommended
                          ? 'fx-btn-brand'
                          : 'border hairline muted'
                      }`}
                    >
                      {s.acf.cmp_badge}
                    </span>
                  )}
                </Cell>
              ))}
            </div>

            {/* ---------- Hàng giá ---------- */}
            <div role="row" className="grid border-b hairline" style={gridCols}>
              <Cell label>Giá từ</Cell>

              {cols.map((s) => {
                const a = s.acf || {};
                const badge = discountBadge({
                  original: a.cmp_price_original,
                  current: a.price_from,
                  override: a.cmp_price_badge,
                });
                const original = formatPrice(a.cmp_price_original);
                const price = formatPrice(a.price_from);

                return (
                  <Cell key={s.id} col={s} className="border-l hairline">
                    {(original || badge) && (
                      <div className="flex flex-wrap items-center gap-2">
                        {original && (
                          <span className="text-xs line-through muted">
                            {a.cmp_price_prefix ? `${a.cmp_price_prefix} ` : ''}
                            {original}
                          </span>
                        )}
                        {badge && (
                          <span className="rounded bg-brand-500/15 px-1.5 py-0.5 text-[10px] font-bold uppercase text-brand-400">
                            {badge}
                          </span>
                        )}
                      </div>
                    )}

                    {price && (
                      <div className="mt-1 text-2xl font-bold tracking-tightest">
                        {a.cmp_price_prefix ? <span className="text-base font-semibold">{a.cmp_price_prefix} </span> : null}
                        {price}
                      </div>
                    )}

                    {a.cmp_price_note && <div className="mt-1 text-xs muted">{a.cmp_price_note}</div>}
                  </Cell>
                );
              })}
            </div>

            {/* ---------- Hàng thời gian ---------- */}
            <Row label="Thời gian" cols={cols} gridCols={gridCols} render={(a) => a.cmp_duration} />

            {/* ---------- Các hàng cố định ---------- */}
            {ROWS.map((row) => (
              <Row
                key={row.key}
                label={row.label}
                cols={cols}
                gridCols={gridCols}
                check={row.check}
                render={(a) => a[row.key]}
              />
            ))}

            {/* ---------- Hàng phát sinh ---------- */}
            {extraLabels.map((label) => (
              <Row
                key={label}
                label={label}
                cols={cols}
                gridCols={gridCols}
                check
                render={(a) => (a.cmp_extra_rows || []).find((r) => r?.label === label)?.value}
              />
            ))}

            {/* ---------- Hàng cuối: điểm nổi bật + nút ---------- */}
            <div role="row" className="grid" style={gridCols}>
              <Cell label>Điểm nổi bật</Cell>

              {cols.map((s) => {
                const a = s.acf || {};
                return (
                  <Cell key={s.id} col={s} className="border-l hairline">
                    {a.cmp_highlight && (
                      <div className="flex items-start gap-2 text-sm font-semibold">
                        <span className="text-brand-400" aria-hidden="true">
                          ✦
                        </span>
                        <span>{a.cmp_highlight}</span>
                      </div>
                    )}

                    <Link
                      href={`/dich-vu/${s.slug}`}
                      className={`btn mt-4 w-full ${a.cmp_recommended ? 'fx-btn-brand' : 'btn-ghost'}`}
                    >
                      {a.cmp_cta_label || 'Nhận báo giá'}
                    </Link>
                  </Cell>
                );
              })}
            </div>
          </div>
        </Reveal>

        {zalo && (
          <Reveal delay={2} className="fx-card mt-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl p-5">
            <p className="text-sm muted">
              Cần phạm vi riêng, tích hợp hệ thống cũ hoặc SEO theo ngành? Anmitech bóc tách hạng mục trước khi báo giá.
            </p>
            <a href={zalo} target="_blank" rel="noopener noreferrer" className="btn fx-btn-brand shrink-0">
              Nhắn Zalo tư vấn miễn phí
            </a>
          </Reveal>
        )}
      </div>
    </section>
  );
}

/* ------------------------------------------------------------------ */

/** Một ô. `col` để tô sáng cột được đề xuất mà không phải lặp điều kiện. */
function Cell({ children, head = false, label = false, col = null, className = '' }) {
  const highlight = col?.acf?.cmp_recommended;

  return (
    <div
      role={head || label ? 'columnheader' : 'cell'}
      className={`px-4 ${head ? 'py-5' : 'py-4'} ${
        label ? 'text-sm font-semibold muted' : 'text-sm'
      } ${className}`}
      style={highlight ? { backgroundColor: 'hsl(var(--brand-400) / 0.06)' } : undefined}
    >
      {children}
    </div>
  );
}

/** Một hàng dữ liệu. Ô trống hiện dấu gạch để bảng không bị hụt hàng. */
function Row({ label, cols, gridCols, render, check = false }) {
  const values = cols.map((s) => render(s.acf || {}));

  // Cả hàng không có gì thì bỏ luôn hàng đó, không hiện một dải gạch ngang.
  if (values.every((v) => !v)) return null;

  return (
    <div role="row" className="grid border-b hairline" style={gridCols}>
      <Cell label>{label}</Cell>

      {cols.map((s, i) => (
        <Cell key={s.id} col={s} className="border-l hairline">
          {values[i] ? (
            <span className="flex items-start gap-2">
              {check && (
                <span className="mt-0.5 text-brand-400" aria-hidden="true">
                  ✓
                </span>
              )}
              <span>{values[i]}</span>
            </span>
          ) : (
            <span className="muted" aria-label="không có">
              —
            </span>
          )}
        </Cell>
      ))}
    </div>
  );
}
