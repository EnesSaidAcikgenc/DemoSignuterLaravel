<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>İmza Kayıtları</title>
    <style>
        *{
            font-family: 'DejaVu Sans', sans-serif;

        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>

<h2>Form Kayıtları</h2>

<table>
    <thead>
    <tr>
        <th>Ad</th>
        <th>Soyad</th>
        <th>İmza</th>
    </tr>
    </thead>
    <tbody>
    @foreach($signatures as $signature)
        <tr>
            <td>{{ $signature->name }}</td>
            <td>{{ $signature->last_name }}</td>
            <td>
                <img src="{{ public_path('storage/signatures/' . $signature->image) }}" alt="İmza Görseli">
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
