<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İmza Alanı</title>
    <style>
        body {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        .container {
            height: 90%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .canvas {
            border: 1px solid black;
            cursor: crosshair;
        }

        .tools {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
        }

        button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }

        .input-group {
            margin: 1rem 0;
            display: flex;
            flex-direction: column;
            width: 200px;
        }

        .input-group input {
            padding: 0.5rem;
            font-size: 1rem;
        }
    </style>
</head>
<body>


<div class="container">
    <!-- Form yapısı -->
    <form id="signature-form" action="/save-signature" method="POST">
        <!-- Ad Soyad formu -->
        <div class="input-group">
            <label for="first-name">Ad:</label>
            <input type="text" id="name" name="name" placeholder="Adınızı girin" required>
        </div>
        <div class="input-group">
            <label for="last-name">Soyad:</label>
            <input type="text" id="last-name" name="last_name" placeholder="Soyadınızı girin" required>
        </div>

        <!-- İmza alanı -->
        <canvas class="canvas" width="650" height="350"></canvas>

        <!-- Butonlar ve araçlar -->
        <div class="tools">
            <button type="submit" id="save-btn">Okudum Onaylıyorum</button>
        </div>
    </form>
</div>
<a href="{{route('index')}}">Formunuzu kontrol edin</a>
<script>
    const canvas = document.querySelector(".canvas");
    const ctx = canvas.getContext("2d");
    let isDrawing = false;
    let xStart = 0, yStart = 0;

    // Draw white background on canvas
    ctx.fillStyle = "white";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Set pen size and color (fixed)
    const penSize = 5;
    const penColor = "black";

    // Draw on canvas
    canvas.addEventListener("mousedown", (e) => {
        isDrawing = true;
        xStart = e.offsetX;
        yStart = e.offsetY;
    });

    canvas.addEventListener("mousemove", (e) => {
        if (isDrawing) {
            drawLine(xStart, yStart, e.offsetX, e.offsetY);
            xStart = e.offsetX;
            yStart = e.offsetY;
        }
    });

    canvas.addEventListener("mouseup", () => isDrawing = false);
    canvas.addEventListener("mouseout", () => isDrawing = false);

    const drawLine = (x1, y1, x2, y2) => {
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.strokeStyle = penColor;
        ctx.lineWidth = penSize;
        ctx.stroke();
    };

    // Form submit eventini yakala
    document.getElementById("signature-form").addEventListener("submit", function (e) {
        e.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

        const signatureData = canvas.toDataURL('image/png');
        const formData = new FormData(this);
        formData.append('signature', signatureData);

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF koruması
            },
            body: formData
        }).then(response => {
            if (response.ok) {
                alert('İmza başarıyla kaydedildi.');
            } else {
                alert('İmza kaydedilemedi.');
            }
        }).catch(error => {
            console.error('Hata:', error);
        });
    });
</script>

</body>
</html>
