<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF İmza Alanı</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
        }
        .signature-area {
            width: 100%;
            height: 200px;
            border: 1px solid black;
            position: relative;
        }
        canvas {
            width: 100%;
            height: 100%;
            border: 1px solid black;
        }
    </style>
</head>
<body>
<h1>İmza Alanı</h1>
<div class="signature-area">
    <canvas id="signature-canvas"></canvas>
</div>
<button id="save-btn">İmza Kaydet ve İndir</button>

<script>
    const canvas = document.getElementById("signature-canvas");
    const ctx = canvas.getContext("2d");
    let isDrawing = false;
    let x = 0;
    let y = 0;

    // Canvas boyutlarını ayarla
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;

    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        x = e.offsetX;
        y = e.offsetY;
    });

    canvas.addEventListener('mousemove', (e) => {
        if (isDrawing) {
            ctx.beginPath();
            ctx.moveTo(x, y);
            ctx.lineTo(e.offsetX, e.offsetY);
            ctx.stroke();
            x = e.offsetX;
            y = e.offsetY;
        }
    });

    canvas.addEventListener('mouseup', () => {
        isDrawing = false;
        ctx.closePath();
    });

    canvas.addEventListener('mouseout', () => {
        isDrawing = false;
        ctx.closePath();
    });

    document.getElementById("save-btn").addEventListener("click", () => {
        const signatureData = canvas.toDataURL('image/png');
        saveSignature(signatureData);
    });

    const saveSignature = (signatureData) => {
        fetch('/save-signature', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ signature: signatureData })
        })
            .then(response => {
                if (response.ok) {
                    return response.blob(); // PDF döndürecek
                }
                throw new Error('PDF oluşturma başarısız!');
            })
            .then(blob => {
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'signed-form.pdf';
                link.click();
            })
            .catch(error => {
                console.error('Hata:', error);
            });
    };
    const saveSignature = (signatureData) => {
        fetch('/save-signature', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ signature: signatureData })
        })
            .then(response => {
                if (response.ok) {
                    return response.blob(); // PDF döndürecek
                }
                throw new Error('PDF oluşturma başarısız!');
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                window.open(url); // PDF'yi yeni bir sekmede aç
            })
            .catch(error => {
                console.error('Hata:', error);
            });
    };
</script>
</body>
</html>
