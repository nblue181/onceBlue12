/**
 * ANMITECH — Dữ liệu mẫu để dựng và xem giao diện khi chưa có WordPress.
 *
 * Hình dạng của object này PHẢI giống hệt cái mà endpoint
 * /wp-json/anmitech/v1/home trả về, để khi cắm dữ liệu thật vào thì
 * không component nào phải sửa. Xem `anmi_rest_home()` trong
 * wp-import/snippet-rest.php.
 *
 * CẢNH BÁO: đây là số liệu BỊA để xem bố cục. Không được để lọt lên
 * production. `src/lib/api.js` chỉ dùng file này khi thiếu NEXT_PUBLIC_WP_URL,
 * và luôn in cảnh báo ra console khi dùng.
 */

export const mockHome = {
  options: {
    brand_name: 'Anmitech',
    tagline: 'Công nghệ cho doanh nghiệp Việt',
    domain: 'anmitech.vn',
    footer_desc:
      'Anmitech xây dựng website và web app cho doanh nghiệp vừa và nhỏ, ' +
      'tập trung vào thứ khách hàng của bạn thực sự cần thấy.',
    logo: '/logo.svg',
    logo_light: '/logo-white.svg',

    zalo_phone: '0867067306',
    zalo_url: 'https://zalo.me/0867067306',
    messenger_url: '',
    hotline: '0867067306',
    email: 'lienhe@anmitech.vn',
    working_hours: '8:00 – 18:00, Thứ Hai đến Thứ Bảy',
    address: 'TP. Hồ Chí Minh',

    hero_eyebrow: 'WEBSITE & WEB APP',
    hero_title: 'Website nói đúng điều doanh nghiệp bạn cần nói',
    hero_karaoke: 'đúng điều',
    hero_typewriter: [
      { text: 'website doanh nghiệp' },
      { text: 'website bán hàng' },
      { text: 'landing page' },
      { text: 'web app quản lý' },
    ],
    hero_desc:
      'Chúng tôi bắt đầu từ việc hiểu cách khách hàng của bạn ra quyết định, ' +
      'rồi mới dựng website quanh điều đó.',
    hero_note: 'Tư vấn miễn phí, không ràng buộc.',

    about_eyebrow: 'VỀ ANMITECH',
    about_title: 'Bạn sẽ hiểu website của mình trước khi sở hữu nó',
    about_desc:
      'Phần lớn doanh nghiệp nhận website xong vẫn không biết nó hoạt động thế nào. ' +
      'Chúng tôi làm ngược lại: giải thích từng quyết định, để đến lúc bàn giao bạn ' +
      'tự chỉnh được nội dung mà không phải gọi ai.',
    about_image: '',
    // Cụm chip trôi lơ lửng bên phải phần Giới thiệu. Có about_image thì ảnh
    // được ưu tiên, cụm này không hiện.
    about_chips: [
      { label: 'Next.js' },
      { label: 'WordPress' },
      { label: 'Tailwind' },
      { label: 'REST API' },
      { label: 'SEO' },
      { label: 'PageSpeed' },
    ],
    about_link_label: 'Tìm hiểu về Anmitech',
    about_link_url: '/gioi-thieu/',

    values: [
      { icon: 'eye', title: 'Rõ ràng', desc: 'Báo giá theo hạng mục, không có mục nào bạn không hiểu.' },
      { icon: 'chat', title: 'Dễ hiểu', desc: 'Giải thích bằng tiếng Việt thường, không dùng thuật ngữ để loè.' },
      { icon: 'heart', title: 'Tận tâm', desc: 'Trả lời trong giờ làm việc, không để câu hỏi treo qua ngày.' },
      { icon: 'target', title: 'Thực tế', desc: 'Chỉ làm tính năng bạn dùng tới, không nhồi cho dày báo giá.' },
    ],

    // Con số bịa cho bố cục. Xoá hết trước khi lên production nếu chưa có số thật.
    stats: [
      { number: 32, suffix: '+', label: 'Website đã bàn giao' },
      { number: 7, suffix: ' ngày', label: 'Thời gian bàn giao nhanh nhất' },
      { number: 98, suffix: '%', label: 'Điểm PageSpeed trung bình' },
    ],

    process_eyebrow: 'CÁCH LÀM VIỆC',
    process_title: 'Hiểu trước. Làm đúng. Đồng hành đến khi bạn làm chủ website',
    process: [
      { title: 'Hiểu doanh nghiệp', desc: 'Nghe bạn kể về khách hàng và cách họ tìm đến bạn.' },
      { title: 'Làm rõ website cần làm gì', desc: 'Chốt danh sách trang và việc mỗi trang phải làm được.' },
      { title: 'Xây dựng website', desc: 'Dựng giao diện, nội dung và phần quản trị.' },
      { title: 'Kiểm tra cùng bạn', desc: 'Bạn bấm thử từng nút trước khi chúng tôi gọi là xong.' },
      { title: 'Bàn giao & đồng hành', desc: 'Hướng dẫn tự cập nhật, hỗ trợ tiếp sau bàn giao.' },
    ],

    cta_title: 'Bạn đang muốn làm một website?',
    cta_desc: 'Nhắn cho chúng tôi vài dòng về doanh nghiệp của bạn. Không cần chuẩn bị gì trước.',
  },

  services: [
    {
      id: 1,
      slug: 'landing-page',
      title: 'Landing Page',
      excerpt: 'Một trang đích tập trung vào một mục tiêu duy nhất.',
      thumb: null,
      acf: {
        icon: 'layout',
        short_desc: 'Một trang, một mục tiêu, một nút bấm.',
        price_from: 1888000,
        cmp_show: true,
        cmp_recommended: false,
        cmp_badge: 'KHỞI ĐỘNG NHANH',
        cmp_price_prefix: '',
        cmp_price_original: 3000000,
        cmp_price_badge: '',
        cmp_price_note: 'Giá ưu đãi theo phạm vi',
        cmp_duration: '3–5 ngày',
        cmp_fit: 'Chiến dịch, giới thiệu dịch vụ, form tư vấn',
        cmp_design: '1 giao diện responsive',
        cmp_seo: 'SEO nền tảng',
        cmp_admin: 'Form liên hệ',
        cmp_analytics: 'GA4 + Pixel cơ bản',
        cmp_support: 'Bảo hành 36 tháng',
        cmp_highlight: 'Tặng hosting 1 năm',
        cmp_cta_label: 'Nhận báo giá Landing',
        cmp_extra_rows: [],
      },
    },
    {
      id: 2,
      slug: 'website-doanh-nghiep',
      title: 'Website Doanh Nghiệp',
      excerpt: 'Bộ mặt đầy đủ của công ty trên internet.',
      thumb: null,
      acf: {
        icon: 'building',
        short_desc: 'Giới thiệu công ty, dịch vụ và portfolio.',
        price_from: 5000000,
        cmp_show: true,
        cmp_recommended: true,
        cmp_badge: 'ĐỀ XUẤT',
        cmp_price_prefix: '',
        cmp_price_original: 8000000,
        cmp_price_badge: '',
        cmp_price_note: 'Giá ưu đãi theo phạm vi',
        cmp_duration: '7–14 ngày',
        cmp_fit: 'Công ty, portfolio, dịch vụ cần SEO bền',
        cmp_design: 'UI/UX theo thương hiệu',
        cmp_seo: 'SEO on-page nâng cao',
        cmp_admin: 'CMS dễ cập nhật',
        cmp_analytics: 'GA4, Search Console, sitemap',
        cmp_support: 'Bảo trì 1 năm',
        cmp_highlight: 'CMS quản trị nội dung',
        cmp_cta_label: 'Tư vấn gói doanh nghiệp',
        cmp_extra_rows: [],
      },
    },
    {
      id: 3,
      slug: 'website-ban-hang',
      title: 'Website Bán Hàng',
      excerpt: 'Catalog sản phẩm, giỏ hàng và thanh toán.',
      thumb: null,
      acf: {
        icon: 'cart',
        short_desc: 'Bán hàng online với giỏ hàng và thanh toán.',
        price_from: 10000000,
        cmp_show: true,
        cmp_recommended: false,
        cmp_badge: 'TĂNG TRƯỞNG',
        cmp_price_prefix: '',
        cmp_price_original: 15000000,
        cmp_price_badge: '',
        cmp_price_note: 'Giá ưu đãi theo phạm vi',
        cmp_duration: '3–4 tuần',
        cmp_fit: 'Shop, catalog sản phẩm, bán hàng online',
        cmp_design: 'Giao diện bán hàng riêng',
        cmp_seo: 'Schema sản phẩm',
        cmp_admin: 'Quản lý sản phẩm / đơn hàng',
        cmp_analytics: 'Theo dõi chuyển đổi',
        cmp_support: 'Bảo hành + training',
        cmp_highlight: 'Giỏ hàng + thanh toán',
        cmp_cta_label: 'Xây web bán hàng',
        cmp_extra_rows: [],
      },
    },
    {
      id: 4,
      slug: 'web-app-custom',
      title: 'Web App Custom',
      excerpt: 'Công cụ nội bộ dựng riêng theo quy trình của bạn.',
      thumb: null,
      acf: {
        icon: 'target',
        short_desc: 'Dashboard, đặt lịch, CRM mini, workflow riêng.',
        price_from: 10000000,
        cmp_show: true,
        cmp_recommended: false,
        cmp_badge: 'THEO YÊU CẦU',
        cmp_price_prefix: 'Từ',
        cmp_price_original: 20000000,
        cmp_price_badge: 'ƯU ĐÃI SCOPE ĐẦU',
        cmp_price_note: 'Giá ưu đãi theo phạm vi',
        cmp_duration: 'Theo phạm vi',
        cmp_fit: 'Dashboard, web app quản lý, đặt lịch, CRM mini',
        cmp_design: 'Prototype trước khi code',
        cmp_seo: 'Tối ưu theo sản phẩm',
        cmp_admin: 'Backend / API tùy chỉnh',
        cmp_analytics: 'Dashboard đo hiệu quả',
        cmp_support: 'SLA theo thỏa thuận',
        cmp_highlight: 'Phân tích giải pháp miễn phí',
        cmp_cta_label: 'Trao đổi giải pháp',
        cmp_extra_rows: [],
      },
    },
  ],

  projects: [
    {
      id: 11,
      slug: 'surface-shop',
      title: 'Surface Shop',
      excerpt: 'Website bán laptop Surface với catalog và tư vấn qua Zalo.',
      thumb: null,
      categories: [{ slug: 'ban-hang', name: 'Bán hàng' }],
      acf: { client: 'Surface Shop', industry: 'Bán lẻ', website_type: 'Website bán hàng', year: '2026', featured: true },
    },
    {
      id: 12,
      slug: 'cong-ty-xay-dung-minh-an',
      title: 'Xây dựng Minh An',
      excerpt: 'Website giới thiệu công ty xây dựng, có trang dự án theo nhóm.',
      thumb: null,
      categories: [{ slug: 'doanh-nghiep', name: 'Doanh nghiệp' }],
      acf: { client: 'Minh An', industry: 'Xây dựng', website_type: 'Website doanh nghiệp', year: '2026', featured: true },
    },
  ],
};
