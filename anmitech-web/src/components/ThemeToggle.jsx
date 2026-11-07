'use client';

import { useEffect, useState } from 'react';

/**
 * Nút đổi giao diện sáng / tối.
 *
 * Giao diện đã được script trong <head> của layout đặt xong trước khi trang vẽ.
 * Component này chỉ ĐỌC lại trạng thái đó rồi cho phép đổi — nó không phải nơi
 * quyết định giao diện ban đầu.
 *
 * Trước khi mount xong thì render nút rỗng cùng kích thước, để không bị nhảy
 * layout và không bị lệch hydration (server không biết localStorage có gì).
 */
export default function ThemeToggle({ className = '' }) {
  const [theme, setTheme] = useState(null);

  useEffect(() => {
    setTheme(document.documentElement.getAttribute('data-theme') || 'dark');
  }, []);

  function toggle() {
    const next = theme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    try {
      localStorage.setItem('anmitech-theme', next);
    } catch {
      // Chặn cookie/storage thì vẫn đổi được cho phiên hiện tại, chỉ không nhớ.
    }
    setTheme(next);
  }

  const isDark = theme === 'dark';

  return (
    <button
      type="button"
      onClick={toggle}
      aria-label={isDark ? 'Chuyển sang giao diện sáng' : 'Chuyển sang giao diện tối'}
      title={isDark ? 'Giao diện sáng' : 'Giao diện tối'}
      className={`grid h-10 w-10 place-items-center rounded-xl border transition hairline
                  hover:border-brand-400/50 ${className}`}
    >
      {theme === null ? (
        <span className="h-5 w-5" aria-hidden="true" />
      ) : isDark ? (
        <SunIcon />
      ) : (
        <MoonIcon />
      )}
    </button>
  );
}

function SunIcon() {
  return (
    <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
      <circle cx="12" cy="12" r="4" />
      <path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4" strokeLinecap="round" />
    </svg>
  );
}

function MoonIcon() {
  return (
    <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="1.8" aria-hidden="true">
      <path d="M21 12.8A8.5 8.5 0 1 1 11.2 3a6.5 6.5 0 0 0 9.8 9.8Z" strokeLinecap="round" />
    </svg>
  );
}
