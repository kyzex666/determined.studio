<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Print Material Masuk</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            background: #0f172a; /* slate-900 */
            color: #e5e7eb; /* gray-200 */
            padding: 20px;
        }

        .card {
            background: #020617;
            padding: 24px;
            border-radius: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #334155;
            padding: 8px;
        }

        th {
            background: #1e293b;
        }

        .no-border td {
            border: none;
            padding: 4px 0;
        }

        .actions {
            margin-bottom: 16px;
            text-align: right;
        }

        button {
            padding: 8px 14px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        /* ================= PRINT MODE ================= */
        @media print {
            body {
                background: white;
                color: black;
                padding: 0;
            }

            .card {
                background: white;
                border-radius: 0;
            }

            th {
                background: #eee;
            }

            button,
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="actions">
    <button onclick="window.print()">Print</button>
</div>

<div class="card">

    <h1>BUKTI MATERIAL MASUK</h1>

    <table class="no-border">
        <tr>
            <td width="150">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($materialIn->date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Vendor</td>
            <td>: {{ $materialIn->vendor->name }}</td>
        </tr>
        <tr>
            <td>Material</td>
            <td>: {{ $materialIn->material->name }}</td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td>: {{ number_format($materialIn->qty, 2) }} {{ $materialIn->material->unit }}</td>
        </tr>
        <tr>
            <td>Diterima Oleh</td>
            <td>: {{ $materialIn->receivedBy->name }}</td>
        </tr>
        @if($materialIn->note)
        <tr>
            <td>Catatan</td>
            <td>: {{ $materialIn->note }}</td>
        </tr>
        @endif
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th>Material</th>
                <th>Qty</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $materialIn->material->name }}</td>
                <td>{{ number_format($materialIn->qty, 2) }}</td>
                <td>{{ $materialIn->material->unit }}</td>
            </tr>
        </tbody>
    </table>

</div>

</body>
</html>
