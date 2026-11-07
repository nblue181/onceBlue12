'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';

import ThemeToggle from './ThemeToggle';

/**
 * Menu lấy đúng theo namtrandang.com. Slug /dich-vu/ và /du-an/ khớp với slug
 * CPT đã đăng ký trong WordPress, nên đổi bên nào cũng phải đổi bên kia.
 */
const NAV = [
  { label: 'Trang chủ', href: '/' },
  { label: 'Giới thiệu', href: '/gioi-thieu' },
  { label: 'Dịch vụ', href: '/dich-vu' },
  { label: 'Dự án', href: '/du-an' },
  { label: 'Liên hệ', href: '/lien-he' },
];

export default function Header({ options = {} }) {
  const [open, setOpen] = useState(false);
  const [solid, setSolid] = useState(false);

  // Header trong suốt khi ở đầu trang để nhìn thấy nền Aura, chuyển sang đục
  // khi cuộn xuống — nếu để trong suốt suốt thì chữ menu chồng lên nội dung.
  useEffect(() => {
    const onScroll = () => setSolid(window.scrollY > 12);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    return () => window.removeEventListener('scroll', onScroll);
  }, []);

  // Mở menu mobile thì chặn cuộn phía sau, không thì cuộn xuyên qua lớp phủ.
  useEffect(() => {
    document.body.style.overflow = open ? 'hidden' : '';
    return () => {
      document.body.style.overflow = '';
    };
  }, [open]);

  const zalo = options.zalo_url || (options.zalo_phone ? `https://zalo.me/${options.zalo_phone}` : '');

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 border-b transition-colors duration-300 ${
        solid ? 'hairline backdrop-blur-md' : 'border-transparent'
      }`}
      style={solid ? { backgroundColor: 'hsl(var(--fx-bg) / 0.82)' } : undefined}
    >
      <div className="container flex h-16 items-center justify-between gap-4">
        <Link href="/" className="flex items-center gap-2" aria-label={options.brand_name || 'Anmitech'}>
          {/* Hai bản logo, ẩn/hiện theo giao diện. Dùng <img> chứ không dùng
              next/image: SVG không cần tối ưu kích cỡ, và next/image sẽ thêm
              một lớp wrapper làm lệch canh giữa. */}
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/logo.svg" alt={options.brand_name || 'Anmitech'} className="h-8 w-auto dark-hide" />
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src="/logo-white.svg" alt="" aria-hidden="true" className="h-8 w-auto light-hide" />
        </Link>

        <nav className="hidden items-center gap-1 md:flex" aria-label="Menu chính">
          {NAV.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="rounded-lg px-3 py-2 text-sm font-semibold muted transition hover:text-brand-400"
            >
              {item.label}
            </Link>
          ))}
        </nav>

        <div className="flex items-center gap-2">
          <ThemeToggle />

          {zalo && (
            <a
              href={zalo}
              target="_blank"
              rel="noopener noreferrer"
              className="btn fx-btn-brand hidden md:inline-flex"
            >
              Nhắn Zalo
            </a>
          )}

          <button
            type="button"
            onClick={() => setOpen((v) => !v)}
            aria-label={open ? 'Đóng menu' : 'Mở menu'}
            aria-expanded={open}
            className="grid h-10 w-10 place-items-center rounded-xl border hairline md:hidden"
          >
            <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
              {open ? (
                <path d="M6 6l12 12M18 6L6 18" strokeLinecap="round" />
              ) : (
                <path d="M4 7h16M4 12h16M4 17h16" strokeLinecap="round" />
              )}
            </svg>
          </button>
        </div>
      </div>

      {open && (
        <nav
          className="border-t hairline md:hidden"
          style={{ backgroundColor: 'hsl(var(--fx-bg))' }}
          aria-label="Menu chính"
        >
          <div className="container flex flex-col py-3">
            {NAV.map((item) => (
              <Link
                key={item.href}
                href={item.href}
                onClick={() => setOpen(false)}
                className="rounded-lg px-2 py-3 text-base font-semibold muted transition hover:text-brand-400"
              >
                {item.label}
              </Link>
            ))}
            {zalo && (
              <a href={zalo} target="_blank" rel="noopener noreferrer" className="btn fx-btn-brand mt-3">
                Nhắn Zalo
              </a>
            )}
          </div>
        </nav>
      )}
    </header>
  );
}
