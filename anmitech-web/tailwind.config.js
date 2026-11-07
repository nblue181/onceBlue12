/** @type {import('tailwindcss').Config} */

// ANMITECH — cấu hình Tailwind
// Màu đọc trực tiếp từ logo_anmitech_ngang.svg (bản logo chốt).

export default {
  darkMode: ['class'],
  content: ['./app/**/*.{js,jsx,ts,tsx}', './src/**/*.{js,jsx,ts,tsx}'],
  theme: {
    container: {
      center: true,
      padding: { DEFAULT: '1rem', lg: '2rem' },
      screens: { '2xl': '1280px' },
    },
    extend: {
      colors: {
        brand: {
          50: '#F0F7FF',
          100: '#DBEDFF',
          200: '#A3D0FF',
          300: '#5CABFF',
          400: '#248EFF',
          500: '#0073EA', // ← màu logo, đọc từ SVG chính thức
          600: '#005EC2',
          700: '#004A99',
          800: '#003B7A',
          900: '#002C5C',
          950: '#001B38',
        },
        // Đầu sáng của gradient logo. Dùng cho điểm nhấn, quầng sáng,
        // trạng thái hover — không dùng làm màu chữ trên nền trắng
        // (tương phản 1.8:1, dưới xa ngưỡng đọc được).
        cyan: {
          brand: '#03D0FF',
        },
        // Nền tối của trang
        ink: {
          DEFAULT: '#08131F',
          soft: '#0B1B2B',
          card: '#0E2136',
        },
      },

      fontFamily: {
        // Font chốt: Source Sans 3 (Adobe, qua Google Fonts) — biến thiên 200–900,
        // có subset vietnamese đầy đủ dấu.
        //
        // `var(--font-sans)` đứng đầu vì next/font tự sinh một tên họ font riêng
        // (kiểu __Source_Sans_3_abc123) rồi gán vào biến này — viết thẳng
        // "Source Sans 3" sẽ KHÔNG khớp và font im lặng không áp dụng.
        // Tên chữ đứng thứ hai để trang nào nạp font bằng thẻ <link> vẫn đúng.
        sans: ['var(--font-sans)', '"Source Sans 3"', 'Segoe UI', 'system-ui', 'sans-serif'],
        display: ['var(--font-sans)', '"Source Sans 3"', 'Segoe UI', 'system-ui', 'sans-serif'],
      },

      // Bó chữ tiêu đề — thứ khiến heading trông "gọn" chứ không lỏng lẻo
      letterSpacing: {
        tightest: '-0.045em',
      },

      keyframes: {
        'fx-float-1': {
          '0%, 100%': { transform: 'translate(0, 0)' },
          '25%': { transform: 'translate(10px, -15px)' },
          '50%': { transform: 'translate(-5px, -25px)' },
          '75%': { transform: 'translate(-15px, -10px)' },
        },
        'fx-float-soft': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-15px)' },
        },
        'fx-scroll-left': {
          from: { transform: 'translateX(0)' },
          to: { transform: 'translateX(-50%)' },
        },
        'fx-scroll-right': {
          from: { transform: 'translateX(-50%)' },
          to: { transform: 'translateX(0)' },
        },
        'fx-caret': { '50%': { opacity: '0' } },
      },

      animation: {
        'fx-float-1': 'fx-float-1 12s ease-in-out infinite',
        'fx-float-soft': 'fx-float-soft 4s ease-in-out infinite',
        'fx-marquee': 'fx-scroll-left 30s linear infinite',
        'fx-marquee-rev': 'fx-scroll-right 30s linear infinite',
        'fx-caret': 'fx-caret 1.1s steps(2, start) infinite',
      },

      backgroundImage: {
        // Gradient của logo, 6 điểm dừng đúng như file SVG gốc.
        // Dùng cho nút chính và chữ nhấn để giao diện khớp logo.
        'brand-gradient':
          'linear-gradient(135deg, #03D0FF 0%, #02B0F8 18%, #0195F2 38%, #0082ED 58%, #0077EB 78%, #0073EA 100%)',
        // Bản rút gọn hai điểm dừng, cho nền lớn — ít băng màu hơn khi trải rộng.
        'brand-fade': 'linear-gradient(135deg, #03D0FF 0%, #0073EA 100%)',
      },
    },
  },
  plugins: [],
};
