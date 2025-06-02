<!DOCTYPE html>
<html>
<head>
    <title>Upload Ảnh với Cloudinary</title>
</head>
<body>
    <h2>Upload ảnh lên Cloudinaryaaaaaaa</h2>
    <form id="uploadForm" action="{{ url('/api/upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch('{{ url('/api/upload') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result.error || 'Lỗi khi upload ảnh');
                }

                // Gửi URL ảnh về trang product.html
                window.opener.postMessage({ imageUrl: result.url }, '*');
                alert('Upload ảnh thành công! URL: ' + result.url);
                setTimeout(() => window.close(), 1000); // Đóng popup sau 1 giây
            } catch (error) {
                alert('Lỗi: ' + error.message);
            }
        });
    </script>
</body>
</html>