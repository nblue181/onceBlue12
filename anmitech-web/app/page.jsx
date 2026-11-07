import { getHome } from '../src/lib/api';

import FxRoot from '../src/components/FxRoot';
import Header from '../src/components/Header';
import Footer from '../src/components/Footer';

import Hero from '../src/components/sections/Hero';
import About from '../src/components/sections/About';
import ServicesTable from '../src/components/sections/ServicesTable';
import Projects from '../src/components/sections/Projects';
import Values from '../src/components/sections/Values';
import Process from '../src/components/sections/Process';
import Cta from '../src/components/sections/Cta';

/**
 * Trang chủ. Bảy section, thứ tự theo namtrandang.com — xem bảng trong
 * docs/HUONG-DAN-HIEU-UNG.md.
 *
 * Đây là server component: dữ liệu được await ngay tại đây nên HTML gửi về
 * trình duyệt đã có sẵn nội dung. Đó là lý do chọn Next.js thay vì SPA —
 * Google đọc được nội dung mà không cần chạy JavaScript.
 */
export default async function HomePage() {
  const { data, source, error } = await getHome();
  const { options = {}, services = [], projects = [] } = data;

  return (
    <>
      <FxRoot deps={[source]} />

      {source !== 'wordpress' && <MockBanner source={source} error={error} />}

      <Header options={options} />

      {/* Nền đan xen: section lẻ khoác thêm dải `.section-alt`, section chẵn để
          nguyên nền Aura. Trang dài bảy khối mà cùng một nền thì cuộn mãi không
          biết mình đang ở đâu — dải xen kẽ cho mắt cái mốc để bám.

          Bọc bằng <div> thay vì thêm class vào từng component: dải nền là việc
          của bố cục trang, không phải của section. Đổi thứ tự section sau này
          chỉ cần đổi ở đây, không phải sửa bảy file. */}
      <main>
        <Hero options={options} />

        <div className="section-alt">
          <About options={options} />
        </div>

        <ServicesTable services={services} options={options} />

        <div className="section-alt">
          <Projects projects={projects} />
        </div>

        <Values options={options} />

        <div className="section-alt">
          <Process options={options} />
        </div>

        <Cta options={options} />
      </main>

      <Footer options={options} />
    </>
  );
}

/**
 * Dải cảnh báo khi trang đang dựng bằng dữ liệu mẫu.
 *
 * Cố ý để nó xấu và nằm trên cùng: nội dung bên dưới là số liệu BỊA, không được
 * để lọt lên production mà không ai nhận ra. Dải này tự biến mất ngay khi
 * NEXT_PUBLIC_WP_URL trỏ tới WordPress có endpoint hoạt động.
 */
function MockBanner({ source, error }) {
  const fellBack = source === 'mock-fallback';

  return (
    <div className="sticky top-0 z-[60] bg-amber-400 px-4 py-2 text-center text-xs font-bold text-amber-950">
      {fellBack ? (
        <>
          Không đọc được WordPress ({error}) — đang hiện DỮ LIỆU MẪU. Kiểm tra
          <code className="mx-1 rounded bg-amber-950/10 px-1">NEXT_PUBLIC_WP_URL</code>
          và endpoint <code className="rounded bg-amber-950/10 px-1">/wp-json/anmitech/v1/home</code>.
        </>
      ) : (
        <>
          Đang chạy DỮ LIỆU MẪU để xem giao diện. Số liệu và nội dung là bịa. Tạo
          <code className="mx-1 rounded bg-amber-950/10 px-1">.env.local</code>
          với <code className="rounded bg-amber-950/10 px-1">NEXT_PUBLIC_WP_URL</code> để đọc dữ liệu thật.
        </>
      )}
    </div>
  );
}
