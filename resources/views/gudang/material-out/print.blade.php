<!DOCTYPE html>
<html>
<head>
    <title>Print Material Out</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td { padding: 6px; vertical-align: top; }
    </style>
</head>
<body onload="window.print()">

    <h2 style="text-align:center;">Material Keluar</h2>
    <hr>

    <table>
        <tr>
            <td width="30%">Tanggal</td>
            <td>{{ $materialOut->date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Production Order</td>
            <td>{{ $materialOut->productionOrder->order_code }}</td>
        </tr>
        <tr>
            <td>Produk</td>
            <td>{{ $materialOut->productionOrder->product->name }}</td>
        </tr>
        <tr>
            <td>Material</td>
            <td>{{ $materialOut->material->name }}</td>
        </tr>
        <tr>
            <td>Qty</td>
            <td>{{ number_format($materialOut->qty, 2) }} {{ $materialOut->material->unit }}</td>
        </tr>
        <tr>
            <td>Dikeluarkan Oleh</td>
            <td>{{ $materialOut->issuedBy->name }}</td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>{{ $materialOut->notes ?? '-' }}</td>
        </tr>
    </table>

</body>
</html>
