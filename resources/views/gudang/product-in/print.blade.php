<!DOCTYPE html>
<html>
<head>
    <title>Cetak Product In</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #333; padding: 8px; }
    </style>
</head>
<body>

<h2>Laporan Barang Jadi Masuk</h2>

<table>
    <tr>
        <th>Produk</th>
        <td>{{ $productIn->product->name }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $productIn->date }}</td>
    </tr>
    <tr>
        <th>Qty</th>
        <td>{{ $productIn->qty }}</td>
    </tr>
    <tr>
        <th>Kondisi</th>
        <td>{{ ucfirst($productIn->condition) }}</td>
    </tr>
    <tr>
        <th>Order Produksi</th>
        <td>{{ $productIn->productionOrder->order_code ?? '-' }}</td>
    </tr>
    <tr>
        <th>Diterima Oleh</th>
        <td>{{ $productIn->receivedBy->name }}</td>
    </tr>
</table>

<script>
    window.print();
</script>

</body>
</html>
