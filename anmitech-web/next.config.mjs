/** @type {import('next').NextConfig} */

// Ảnh của WordPress nằm ở domain khác nên phải khai trước, nếu không next/image
// sẽ từ chối tải. Đọc từ biến môi trường để đổi domain mà không sửa code.
const wpHost = (() => {
  try {
    return process.env.NEXT_PUBLIC_WP_URL ? new URL(process.env.NEXT_PUBLIC_WP_URL).hostname : null;
  } catch {
    return null;
  }
})();

const nextConfig = {
  images: {
    remotePatterns: wpHost
      ? [{ protocol: 'https', hostname: wpHost }]
      : [],
  },
};

export default nextConfig;
