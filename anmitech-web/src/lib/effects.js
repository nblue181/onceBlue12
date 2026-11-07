/**
 * ANMITECH — Bộ hiệu ứng (JavaScript thuần)
 *
 * Không phụ thuộc thư viện nào. Dùng được cho React, hoặc HTML tĩnh.
 * Điều khiển bằng thuộc tính data-* trên HTML:
 *
 *   data-fx-reveal            hiện dần khi cuộn vào khung nhìn
 *   data-fx-spotlight         vệt sáng đi theo con trỏ trong khối này
 *   data-fx-hover-bg          rê chuột thì quầng sáng nền đậm lên
 *   data-fx-marquee           tự nhân đôi danh sách con cho chạy vô hạn
 *   data-fx-count="1200"      đếm số tăng dần khi lọt vào khung nhìn
 *   data-fx-accordion         nhóm accordion (con: [data-fx-acc-item])
 *   data-fx-words             tách chữ thành từng từ để hiện so le
 *
 * Cách dùng:
 *   import { initEffects } from './lib/effects';
 *   const cleanup = initEffects();     // gọi cleanup() khi unmount
 */

/**
 * Người xem có đang muốn ít chuyển động không.
 *
 * Phải kiểm tra `.fx-force-motion` y như CSS làm (`html:not(.fx-force-motion)`).
 * Thiếu điều kiện này thì JS và CSS nói hai chuyện khác nhau: CSS bật lại
 * animation, còn JS vẫn tưởng đang giảm chuyển động nên gán is-in cho mọi phần
 * tử ngay khi tải — hậu quả là toàn bộ hiệu ứng chạy một lượt ngoài khung nhìn,
 * khách cuộn tới thì đã hết.
 */
export const prefersReducedMotion = () =>
  typeof window !== 'undefined' &&
  !document.documentElement.classList.contains('fx-force-motion') &&
  window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const REDUCED = prefersReducedMotion;

/* -------------------------------------------------------------------------
 * 1. Hiện dần khi cuộn
 * ---------------------------------------------------------------------- */
function initReveal(root = document) {
  const items = root.querySelectorAll('[data-fx-reveal]');
  if (!items.length) return () => {};

  items.forEach((el) => el.classList.add('fx-reveal'));

  // Không có IntersectionObserver, hoặc người dùng tắt chuyển động: hiện ngay.
  //
  // REDUCED() phải nằm ở đây chứ không chỉ dựa vào CSS. Hiện tại effects.css có
  // bỏ opacity:0 khi giảm chuyển động nên nội dung vẫn thấy được, nhưng nếu chỉ
  // trông vào đó thì một lần sửa CSS là nội dung bị ẩn vĩnh viễn ở những máy mà
  // IntersectionObserver không kịp chạy (tab mở ở chế độ ẩn, trình duyệt tiết
  // kiệm pin). Gán is-in ngay là đường an toàn.
  if (!('IntersectionObserver' in window) || REDUCED()) {
    items.forEach((el) => el.classList.add('is-in'));
    return () => {};
  }

  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-in');
        io.unobserve(e.target); // chỉ chạy một lần
      });
    },
    { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
  );

  items.forEach((el) => io.observe(el));
  return () => io.disconnect();
}

/* -------------------------------------------------------------------------
 * 2. Vệt sáng theo con trỏ
 * ---------------------------------------------------------------------- */
function initSpotlight(root = document) {
  const zones = root.querySelectorAll('[data-fx-spotlight]');
  if (!zones.length || REDUCED()) return () => {};

  const offs = [];

  zones.forEach((zone) => {
    let glow = zone.querySelector('.fx-spotlight');
    if (!glow) {
      glow = document.createElement('div');
      glow.className = 'fx-spotlight';
      zone.appendChild(glow);
    }
    if (getComputedStyle(zone).position === 'static') zone.style.position = 'relative';

    let raf = 0;
    let x = 0;
    let y = 0;

    const move = (e) => {
      const r = zone.getBoundingClientRect();
      x = e.clientX - r.left;
      y = e.clientY - r.top;
      // Gom cập nhật vào 1 khung hình, tránh ghi style hàng trăm lần mỗi giây
      if (!raf) {
        raf = requestAnimationFrame(() => {
          glow.style.setProperty('--fx-x', x + 'px');
          glow.style.setProperty('--fx-y', y + 'px');
          raf = 0;
        });
      }
    };
    const enter = () => glow.classList.add('is-on');
    const leave = () => {
      glow.classList.remove('is-on');
      if (raf) { cancelAnimationFrame(raf); raf = 0; }
    };

    zone.addEventListener('mousemove', move);
    zone.addEventListener('mouseenter', enter);
    zone.addEventListener('mouseleave', leave);

    offs.push(() => {
      zone.removeEventListener('mousemove', move);
      zone.removeEventListener('mouseenter', enter);
      zone.removeEventListener('mouseleave', leave);
      if (raf) cancelAnimationFrame(raf);
    });
  });

  return () => offs.forEach((f) => f());
}

/* -------------------------------------------------------------------------
 * 3. Rê chuột làm đậm quầng sáng nền
 * ---------------------------------------------------------------------- */
function initHoverBg(root = document) {
  const zones = root.querySelectorAll('[data-fx-hover-bg]');
  if (!zones.length || REDUCED()) return () => {};

  const offs = [];
  zones.forEach((zone) => {
    const bg = zone.querySelector('.fx-bg');
    if (!bg) return;
    const on = () => bg.classList.add('is-hovering');
    const off = () => bg.classList.remove('is-hovering');
    zone.addEventListener('mouseenter', on);
    zone.addEventListener('mouseleave', off);
    offs.push(() => {
      zone.removeEventListener('mouseenter', on);
      zone.removeEventListener('mouseleave', off);
    });
  });
  return () => offs.forEach((f) => f());
}

/* -------------------------------------------------------------------------
 * 4. Marquee — nhân đôi nội dung để chạy nối liền
 * ---------------------------------------------------------------------- */
function initMarquee(root = document) {
  root.querySelectorAll('[data-fx-marquee]').forEach((el) => {
    if (el.dataset.fxMarqueeReady) return;

    const track = el.querySelector('.fx-marquee-track');
    if (!track) return;

    // Nhân đôi đúng một lần: keyframe chạy tới -50% nên cần 2 bản giống nhau.
    const clone = track.cloneNode(true);
    clone.setAttribute('aria-hidden', 'true');
    [...clone.children].forEach((c) => c.setAttribute('aria-hidden', 'true'));
    while (clone.firstChild) track.appendChild(clone.firstChild);

    const speed = el.dataset.fxMarquee;
    if (speed) track.style.setProperty('--fx-marquee-speed', speed);
    if (el.dataset.fxMarqueeReverse !== undefined) track.classList.add('is-reverse');

    el.dataset.fxMarqueeReady = '1';
  });
  return () => {};
}

/* -------------------------------------------------------------------------
 * 5. Đếm số tăng dần
 * ---------------------------------------------------------------------- */
function initCount(root = document) {
  const items = root.querySelectorAll('[data-fx-count]');
  if (!items.length) return () => {};

  const fmt = new Intl.NumberFormat('vi-VN');

  const run = (el) => {
    const to = parseFloat(el.dataset.fxCount) || 0;
    const suffix = el.dataset.fxCountSuffix || '';
    const dur = parseInt(el.dataset.fxCountDuration || '1400', 10);

    if (REDUCED()) { el.textContent = fmt.format(to) + suffix; return; }

    const t0 = performance.now();
    const tick = (now) => {
      const p = Math.min(1, (now - t0) / dur);
      const eased = 1 - Math.pow(1 - p, 3); // ease-out cubic
      el.textContent = fmt.format(Math.round(to * eased)) + suffix;
      if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  };

  if (!('IntersectionObserver' in window)) {
    items.forEach(run);
    return () => {};
  }

  const io = new IntersectionObserver(
    (entries) => entries.forEach((e) => {
      if (!e.isIntersecting) return;
      run(e.target);
      io.unobserve(e.target);
    }),
    { threshold: 0.4 }
  );
  items.forEach((el) => io.observe(el));
  return () => io.disconnect();
}

/* -------------------------------------------------------------------------
 * 6. Accordion
 *
 * <div data-fx-accordion>
 *   <div data-fx-acc-item>
 *     <button class="fx-acc-trigger" aria-expanded="false">
 *       Câu hỏi <svg class="fx-acc-icon">…</svg>
 *     </button>
 *     <div class="fx-acc-panel"><div>Nội dung</div></div>
 *   </div>
 * </div>
 * ---------------------------------------------------------------------- */
function initAccordion(root = document) {
  const groups = root.querySelectorAll('[data-fx-accordion]');
  if (!groups.length) return () => {};

  const offs = [];

  groups.forEach((group) => {
    // Cho phép mở nhiều mục cùng lúc: data-fx-accordion="multi"
    const multi = group.dataset.fxAccordion === 'multi';
    const items = [...group.querySelectorAll('[data-fx-acc-item]')];

    items.forEach((item) => {
      const btn = item.querySelector('.fx-acc-trigger');
      const panel = item.querySelector('.fx-acc-panel');
      if (!btn || !panel) return;

      btn.setAttribute('aria-expanded', 'false');

      const setOpen = (open) => {
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
          // Đo chiều cao thật rồi gán, để transition chạy được (auto không animate)
          panel.style.setProperty('--fx-h', panel.scrollHeight + 'px');
          panel.classList.add('is-open');
        } else {
          panel.classList.remove('is-open');
        }
      };

      const onClick = () => {
        const willOpen = btn.getAttribute('aria-expanded') !== 'true';
        if (!multi) items.forEach((other) => {
          if (other === item) return;
          const b = other.querySelector('.fx-acc-trigger');
          const p = other.querySelector('.fx-acc-panel');
          if (b && p) { b.setAttribute('aria-expanded', 'false'); p.classList.remove('is-open'); }
        });
        setOpen(willOpen);
      };

      btn.addEventListener('click', onClick);
      offs.push(() => btn.removeEventListener('click', onClick));
    });
  });

  // Cửa sổ đổi kích thước thì chiều cao panel đang mở cũng đổi theo
  const onResize = () => {
    root.querySelectorAll('.fx-acc-panel.is-open').forEach((p) => {
      p.style.setProperty('--fx-h', p.scrollHeight + 'px');
    });
  };
  window.addEventListener('resize', onResize);
  offs.push(() => window.removeEventListener('resize', onResize));

  return () => offs.forEach((f) => f());
}

/* -------------------------------------------------------------------------
 * 7. Tách chữ thành từng từ để hiện so le
 * ---------------------------------------------------------------------- */
function initWords(root = document) {
  root.querySelectorAll('[data-fx-words]').forEach((el) => {
    if (el.dataset.fxWordsReady) return;

    const words = (el.textContent || '').trim().split(/\s+/);
    el.textContent = '';
    words.forEach((w, i) => {
      const span = document.createElement('span');
      span.className = 'fx-word';
      span.style.setProperty('--i', String(i));
      span.textContent = w;
      el.appendChild(span);
      if (i < words.length - 1) el.appendChild(document.createTextNode(' '));
    });

    el.dataset.fxWordsReady = '1';
  });
  return () => {};
}

/* -------------------------------------------------------------------------
 * 8. Đánh chữ (typewriter)
 *
 * <span data-fx-type='["Website doanh nghiệp","Website bán hàng","Web App"]'
 *       data-fx-type-speed="70"      tốc độ gõ (ms/ký tự)
 *       data-fx-type-pause="1600">   dừng bao lâu trước khi xoá
 * </span>
 *
 * Gõ từng ký tự, dừng lại cho đọc, xoá ngược rồi sang cụm tiếp theo. Lặp vô hạn.
 * ---------------------------------------------------------------------- */
function initType(root = document) {
  const items = root.querySelectorAll('[data-fx-type]');
  if (!items.length) return () => {};

  const timers = [];

  items.forEach((el) => {
    let phrases;
    try {
      phrases = JSON.parse(el.dataset.fxType);
    } catch (e) {
      phrases = [el.dataset.fxType];
    }
    if (!Array.isArray(phrases) || !phrases.length) return;

    // Chừa sẵn chiều rộng bằng cụm dài nhất để dòng chữ không nhảy khi gõ
    const longest = phrases.reduce((a, b) => (b.length > a.length ? b : a), '');
    el.style.minWidth = longest.length + 'ch';
    el.style.display = 'inline-block';

    let out = el.querySelector('.fx-type-text');
    if (!out) {
      out = document.createElement('span');
      out.className = 'fx-type-text';
      el.textContent = '';
      el.appendChild(out);
      const caret = document.createElement('span');
      caret.className = 'fx-type-caret';
      caret.setAttribute('aria-hidden', 'true');
      el.appendChild(caret);
    }

    // Tắt chuyển động: hiện thẳng cụm đầu tiên, không gõ.
    if (REDUCED() && !document.documentElement.classList.contains('fx-force-motion')) {
      out.textContent = phrases[0];
      return;
    }

    const speed = parseInt(el.dataset.fxTypeSpeed || '70', 10);
    const pause = parseInt(el.dataset.fxTypePause || '1600', 10);
    let pi = 0;
    let ci = 0;
    let deleting = false;

    const step = () => {
      const text = phrases[pi];
      ci += deleting ? -1 : 1;
      out.textContent = text.slice(0, ci);

      let wait = deleting ? speed / 2 : speed;

      if (!deleting && ci === text.length) {
        deleting = true;
        wait = pause;
      } else if (deleting && ci === 0) {
        deleting = false;
        pi = (pi + 1) % phrases.length;
        wait = speed * 3;
      }

      timers.push(setTimeout(step, wait));
    };

    timers.push(setTimeout(step, 400));
  });

  return () => timers.forEach(clearTimeout);
}

/* -------------------------------------------------------------------------
 * Khởi tạo tất cả
 * ---------------------------------------------------------------------- */
export function initEffects(root = document) {
  const cleanups = [
    initWords(root),
    initReveal(root),
    initSpotlight(root),
    initHoverBg(root),
    initMarquee(root),
    initCount(root),
    initAccordion(root),
    initType(root),
  ];
  return () => cleanups.forEach((fn) => typeof fn === 'function' && fn());
}

/**
 * Bật cưỡng bức chuyển động, bỏ qua thiết lập "giảm chuyển động" của hệ điều hành.
 * Chỉ dùng khi người xem chủ động yêu cầu — ví dụ nút trong trang demo.
 */
export function forceMotion(on = true) {
  document.documentElement.classList.toggle('fx-force-motion', !!on);
}

export {
  initReveal,
  initSpotlight,
  initHoverBg,
  initMarquee,
  initCount,
  initAccordion,
  initWords,
  initType,
  REDUCED as prefersReducedMotion,
};
