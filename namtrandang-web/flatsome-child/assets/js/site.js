/**
 * Nam làm WEB — hành vi phía client.
 *
 * 1. Reveal khi cuộn (IntersectionObserver, chạy 1 lần).
 * 2. Showcase dự án: section cao 440vh, sticky 100vh, đổi card theo tiến độ cuộn.
 * 3. Timeline quy trình: kéo track ngang sao cho bước active nằm giữa màn hình.
 *
 * ≤900px: cả 2 hiệu ứng scroll-driven bị tắt (CSS đã chuyển layout về dạng xếp dọc).
 */
(function () {
  'use strict';

  var MOBILE_BREAKPOINT = 900;

  function isMobile() {
    return window.innerWidth <= MOBILE_BREAKPOINT;
  }

  /* ---------------------------------------------------------------------
   * 1. Reveal
   * ------------------------------------------------------------------ */
  function initReveal() {
    var items = document.querySelectorAll('.nlw-rv');

    if (!items.length) {
      return;
    }

    if (!('IntersectionObserver' in window)) {
      items.forEach(function (el) {
        el.classList.add('is-in');
      });
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-in');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );

    items.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* ---------------------------------------------------------------------
   * Khung chung cho section pin theo cuộn
   * ------------------------------------------------------------------ */
  function bindScrollProgress(section, onUpdate) {
    var ticking = false;

    function compute() {
      ticking = false;

      if (isMobile()) {
        onUpdate(0, true);
        return;
      }

      var rect = section.getBoundingClientRect();
      var total = section.offsetHeight - window.innerHeight;
      var progress = total > 0 ? -rect.top / total : 0;

      onUpdate(Math.max(0, Math.min(1, progress)), false);
    }

    function request() {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(compute);
      }
    }

    window.addEventListener('scroll', request, { passive: true });
    window.addEventListener('resize', request);
    compute();
  }

  function scrollToIndex(section, index, total) {
    var range = section.offsetHeight - window.innerHeight;

    window.scrollTo({
      top: section.offsetTop + range * ((index + 0.5) / total),
      behavior: 'smooth'
    });
  }

  /* ---------------------------------------------------------------------
   * 2. Showcase dự án
   * ------------------------------------------------------------------ */
  function initShowcase() {
    var section = document.querySelector('[data-nlw-showcase]');

    if (!section) {
      return;
    }

    var cards = Array.prototype.slice.call(section.querySelectorAll('.nlw-pjx-card'));
    var rail = section.querySelector('.nlw-pjx-rail');
    var dots = section.querySelector('.nlw-pjx-dots');
    var counter = section.querySelector('.nlw-pjx-count');
    var total = cards.length;

    if (!total) {
      return;
    }

    cards.forEach(function (card, i) {
      var number = document.createElement('button');
      number.type = 'button';
      number.textContent = String(i + 1).padStart(2, '0');
      number.setAttribute('aria-label', 'Dự án ' + (i + 1));
      number.addEventListener('click', function () {
        scrollToIndex(section, i, total);
      });
      rail.appendChild(number);

      var dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('aria-label', 'Dự án ' + (i + 1));
      dot.addEventListener('click', function () {
        scrollToIndex(section, i, total);
      });
      dots.appendChild(dot);
    });

    var numbers = Array.prototype.slice.call(rail.children);
    var dotList = Array.prototype.slice.call(dots.children);

    bindScrollProgress(section, function (progress, mobile) {
      if (mobile) {
        cards.forEach(function (card) {
          card.classList.add('is-on');
        });
        return;
      }

      var index = Math.min(total - 1, Math.floor(progress * total));

      cards.forEach(function (card, i) {
        card.classList.toggle('is-on', i === index);
      });
      numbers.forEach(function (button, i) {
        button.classList.toggle('is-on', i === index);
      });
      dotList.forEach(function (button, i) {
        button.classList.toggle('is-on', i === index);
      });

      if (counter) {
        counter.textContent = index + 1 + ' / ' + total;
      }
    });
  }

  /* ---------------------------------------------------------------------
   * 3. Timeline quy trình
   * ------------------------------------------------------------------ */
  function initProcess() {
    var section = document.querySelector('[data-nlw-process]');

    if (!section) {
      return;
    }

    var items = Array.prototype.slice.call(section.querySelectorAll('.nlw-tl-item'));
    var track = section.querySelector('.nlw-tl-track');
    var fill = section.querySelector('.nlw-tl-fill');
    var view = section.querySelector('.nlw-tl-view');
    var total = items.length;

    if (!total || !track) {
      return;
    }

    bindScrollProgress(section, function (progress, mobile) {
      if (mobile) {
        track.style.transform = '';
        items.forEach(function (item) {
          item.classList.add('is-done');
          item.classList.remove('is-active');
        });
        if (fill) {
          fill.style.width = '100%';
        }
        return;
      }

      var width = items[0].offsetWidth;
      var pitch = total > 1 ? items[1].offsetLeft - items[0].offsetLeft : width;
      var base = items[0].offsetLeft;
      var position = progress * (total - 1);
      var activeCenter = base + position * pitch + width / 2;

      track.style.transform = 'translateX(' + (view.clientWidth / 2 - activeCenter) + 'px)';

      var active = Math.round(position);

      items.forEach(function (item, i) {
        item.classList.toggle('is-done', i < active);
        item.classList.toggle('is-active', i === active);
      });

      if (fill) {
        fill.style.width = Math.max(0, activeCenter - base) + 'px';
      }
    });
  }

  /* ---------------------------------------------------------------------
   * 4. Chuyển giao diện Sáng / Tối
   *
   * Giao diện ban đầu đã được script nội tuyến trong <head> đặt xong trước
   * khi trang vẽ. Ở đây chỉ xử lý cú bấm và ghi nhớ lựa chọn.
   * ------------------------------------------------------------------ */
  function initThemeToggle() {
    var buttons = document.querySelectorAll('[data-nlw-theme-toggle]');

    if (!buttons.length) {
      return;
    }

    var root = document.documentElement;

    function sync() {
      var isDark = root.getAttribute('data-theme') === 'dark';
      buttons.forEach(function (b) {
        b.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      });
    }

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        try {
          localStorage.setItem('nlw-theme', next);
        } catch (e) {
          /* Chế độ riêng tư chặn localStorage — vẫn đổi được, chỉ không nhớ. */
        }
        sync();
      });
    });

    sync();
  }

  function init() {
    initReveal();
    initShowcase();
    initProcess();
    initThemeToggle();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
