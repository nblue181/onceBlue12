/**
 * ANMITECH — Lớp đọc dữ liệu.
 *
 * Một chỗ duy nhất biết dữ liệu đến từ đâu. Component không bao giờ gọi fetch
 * trực tiếp, nên chuyển từ dữ liệu mẫu sang WordPress thật là đổi biến môi
 * trường, không sửa component nào.
 *
 *   Chưa có NEXT_PUBLIC_WP_URL  → dùng src/lib/mock-home.js
 *   Có rồi                      → gọi /wp-json/anmitech/v1/home
 *
 * Endpoint đó do wp-import/snippet-rest.php tạo ra. Nếu chưa dán snippet thì
 * WordPress trả 404 và hàm dưới đây quay về dữ liệu mẫu kèm cảnh báo, để trang
 * vẫn dựng được chứ không sập trắng.
 */

import { mockHome } from './mock-home';

const WP_URL = process.env.NEXT_PUBLIC_WP_URL?.replace(/\/+$/, '') || '';

/** Bao nhiêu giây thì Next.js coi dữ liệu là cũ và gọi lại WordPress. */
const REVALIDATE_SECONDS = 300;

export function isMockMode() {
  return !WP_URL;
}

/**
 * Dữ liệu cho trang chủ: options + services + projects trong một lượt gọi.
 *
 * Luôn trả về object đúng hình dạng, kể cả khi WordPress lỗi — trang chủ sập
 * vì một lần timeout là đánh đổi tệ hơn nhiều so với việc hiện bố cục trống.
 */
export async function getHome() {
  if (isMockMode()) {
    console.warn(
      '[anmitech] Đang chạy bằng DỮ LIỆU MẪU. Đặt NEXT_PUBLIC_WP_URL trong .env.local để đọc WordPress thật.'
    );
    return { data: mockHome, source: 'mock' };
  }

  const url = `${WP_URL}/wp-json/anmitech/v1/home`;

  try {
    const res = await fetch(url, { next: { revalidate: REVALIDATE_SECONDS } });

    if (!res.ok) {
      console.error(`[anmitech] ${url} trả HTTP ${res.status}. Quay về dữ liệu mẫu.`);
      return { data: mockHome, source: 'mock-fallback', error: `HTTP ${res.status}` };
    }

    const json = await res.json();

    // Endpoint đúng luôn có ba khoá này. Thiếu là dấu hiệu snippet chưa dán đủ
    // hoặc có plugin khác chiếm namespace — cần biết ngay, đừng render nửa vời.
    for (const key of ['options', 'services', 'projects']) {
      if (!(key in json)) {
        console.error(`[anmitech] Thiếu khoá "${key}" trong phản hồi. Quay về dữ liệu mẫu.`);
        return { data: mockHome, source: 'mock-fallback', error: `thiếu khoá ${key}` };
      }
    }

    return { data: json, source: 'wordpress' };
  } catch (err) {
    console.error(`[anmitech] Không gọi được ${url}: ${err.message}. Quay về dữ liệu mẫu.`);
    return { data: mockHome, source: 'mock-fallback', error: err.message };
  }
}

/* ------------------------------------------------------------------ *
 * Hàm tiện dụng dùng chung cho nhiều section
 * ------------------------------------------------------------------ */

/** 4.000.000 → "4.000.000đ". Trả về chuỗi rỗng nếu không phải số. */
export function formatPrice(value, { suffix = 'đ' } = {}) {
  const n = Number(value);
  if (!Number.isFinite(n) || n <= 0) return '';
  return new Intl.NumberFormat('vi-VN').format(n) + suffix;
}

/**
 * Tính nhãn giảm giá từ giá gốc và giá đang bán.
 *
 * Ưu tiên nhãn người dùng tự nhập (cmp_price_badge) — có trường hợp nhãn không
 * phải phần trăm, ví dụ "ƯU ĐÃI SCOPE ĐẦU".
 */
export function discountBadge({ original, current, override }) {
  if (override) return override;

  const o = Number(original);
  const c = Number(current);
  if (!Number.isFinite(o) || !Number.isFinite(c) || o <= 0 || c <= 0 || c >= o) return '';

  return `TIẾT KIỆM ${Math.round((1 - c / o) * 100)}%`;
}

/** Các dịch vụ được bật cmp_show, tối đa 4 cột — bảng rộng hơn sẽ vỡ trên mobile. */
export function comparableServices(services, limit = 4) {
  return (services || []).filter((s) => s?.acf?.cmp_show).slice(0, limit);
}
