<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Bilgileri ve İmza</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }
        img {
            width: 150px;
            height: auto;
        }
        .form-header {
            font-weight: bold;
            padding: 10px;
        }
        .section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="section">
    <div class="form-header">Adı ve Soyadı:</div>
    <div>{{ $signature->name }} {{ $signature->last_name }}</div>
</div>
<div class="section">
    <div class="form-header">T.C. Kimlik No:</div>
    <div>{{ $signature->kimlik }}</div>
</div>
<div class="section">
    <div class="form-header">Cinsiyeti</div>
    <div>{{$signature->cinsiyet}}</div>
</div>

<div class="section">
    <div class="form-header">Mezun Olduğu (Üniversite, Fakülte ve Bölüm):</div>
    <div>{{ $signature->universite }}</div>
</div>

<div class="section">
    <div class="form-header">Başvuru Sahibi - Tarih - İmza:</div>
    <div>{{ $signature->created_at->format('d/m/Y') }}</div>
    <img src="{{ public_path('storage/signatures/' . $signature->image) }}" alt="İmza">
</div>
</body>
</html>
