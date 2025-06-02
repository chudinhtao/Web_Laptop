function loadPage(url) {
  fetch(url)
    .then((res) => res.text())
    .then((html) => {
      const container = document.getElementById("app-content");
      container.innerHTML = html;

      // ❌ Xóa các script cũ đã được thêm từ lần load trước
      document
        .querySelectorAll("script[data-dynamic]")
        .forEach((script) => script.remove());

      // ✅ Thêm lại các script từ trang vừa load
      const scripts = container.querySelectorAll("script");
      scripts.forEach((oldScript) => {
        const newScript = document.createElement("script");

        if (oldScript.src) {
          newScript.src = oldScript.src;
        } else {
          newScript.textContent = oldScript.textContent;
        }

        newScript.setAttribute("data-dynamic", "true"); // đánh dấu script động
        document.body.appendChild(newScript);
      });
    })
    .catch((err) => {
      console.error("Failed to load page:", err);
      document.getElementById("app-content").innerHTML =
        '<p class="text-red-500">Failed to load content.</p>';
    });
}
// function loadPage(url) {
//   fetch(url)
//     .then((res) => {
//       if (!res.ok) throw new Error('Không thể tải trang: ' + res.status);
//       return res.text();
//     })
//     .then((html) => {
//       const container = document.getElementById("app-content");
//       if (!container) throw new Error('Không tìm thấy #app-content');
//       container.innerHTML = html;

//       // Xóa các script động cũ
//       document
//         .querySelectorAll("script[data-dynamic]")
//         .forEach((script) => script.remove());

//       // Thêm lại các script và thực thi
//       const scripts = container.querySelectorAll("script");
//       scripts.forEach((oldScript) => {
//         const newScript = document.createElement("script");
//         if (oldScript.src) {
//           newScript.src = oldScript.src;
//           newScript.async = true; // Đảm bảo script tải không chặn DOM
//         } else {
//           newScript.textContent = oldScript.textContent;
//         }
//         newScript.setAttribute("data-dynamic", "true");
//         document.body.appendChild(newScript);

//         // Kiểm tra và gọi fetchProducts nếu tồn tại
//         if (newScript.textContent.includes("fetchProducts")) {
//           setTimeout(() => {
//             if (typeof fetchProducts === "function") {
//               fetchProducts();
//             }
//           }, 0); // Chạy sau khi script được thêm vào
//         }
//       });
//     })
//     .catch((err) => {
//       console.error("Lỗi khi tải trang:", err);
//       const container = document.getElementById("app-content");
//       if (container) {
//         container.innerHTML = '<p class="text-red-500">Không thể tải nội dung.</p>';
//       }
//     });
// }