import { Source_Sans_3 } from 'next/font/google';

import AuraBackground from '../src/components/AuraBackground';
import MotionNotice from '../src/components/MotionNotice';

// Thứ tự ba file này quan trọng, đừng đổi:
//   1. globals.css   — Tailwind base/components/utilities
//   2. effects.css   — bộ hiệu ứng, phải nằm sau Tailwind (xem chú thích đầu file đó)
//   3. aura-theme.css — ghi đè biến Aura, phải nằm sau effects.css mới thắng
//
// Nếu gộp bước 3 vào globals.css thì effects.css nạp sau sẽ ghi đè lại và nền
// quay về hai màu xanh–cyan.
import './globals.css';
import '../src/styles/effects.css';
import './aura-theme.css';

/**
 * Font chốt của Anmitech. Dùng next/font thay vì thẻ <link> tới Google Fonts:
 * file font được tải về và phục vụ từ cùng domain, nên không có request sang
 * fonts.gstatic.com và không bị nháy chữ khi đổi font.
 *
 * `vietnamese` là subset bắt buộc — thiếu nó thì mọi chữ có dấu rơi sang font
 * dự phòng, nhìn ra ngay vì nét chữ đổi giữa câu.
 */
const sourceSans = Source_Sans_3({
  subsets: ['latin', 'latin-ext', 'vietnamese'],
  weight: ['400', '600', '700', '800'],
  display: 'swap',
  variable: '--font-sans',
});

export const metadata = {
  title: {
    default: 'Anmitech — Website & Web App cho doanh nghiệp',
    template: '%s — Anmitech',
  },
  description:
    'Anmitech xây dựng website và web app cho doanh nghiệp vừa và nhỏ. ' +
    'Bắt đầu từ việc hiểu khách hàng của bạn, rồi mới dựng website quanh điều đó.',
  icons: { icon: '/favicon.svg' },
};

/**
 * Script đặt giao diện TRƯỚC khi trang vẽ.
 *
 * Nếu đợi React chạy xong mới đặt data-theme thì khách thấy một nháy trắng rồi
 * mới chuyển sang tối. Đoạn này phải là script đồng bộ, nằm trong <head>, và
 * phải nằm TRƯỚC mọi CSS có màu.
 */
const themeScript = `
(function () {
  try {
    var saved = localStorage.getItem('anmitech-theme');
    var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
  } catch (e) {
    document.documentElement.setAttribute('data-theme', 'dark');
  }
})();
`;

export default function RootLayout({ children }) {
  return (
    <html lang="vi" suppressHydrationWarning>
      <head>
        <script dangerouslySetInnerHTML={{ __html: themeScript }} />
      </head>
      <body className={`${sourceSans.variable} font-sans antialiased`}>
        {/* Nền Aura của toàn site. Đặt ở đây nên mọi trang đều có, và chỉ tồn
            tại MỘT bộ lớp blur cho cả website. */}
        <AuraBackground />

        {children}

        {/* Chỉ có khi chạy npm run dev — cảnh báo nếu hệ điều hành đang tắt
            chuyển động, vì lúc đó nhìn vào sẽ tưởng hiệu ứng bị lỗi. */}
        {process.env.NODE_ENV !== 'production' && <MotionNotice />}
      </body>
    </html>
  );
}
