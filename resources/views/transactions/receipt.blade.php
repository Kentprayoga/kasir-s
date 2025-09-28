<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Belanja</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 250px; /* untuk kertas 58mm, kalau 80mm bisa 350px */
            margin: 0 auto;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="center">
        <h3>TOKO MAKMUR</h3>
        <p>Jl. Raya No.123<br>Telp: 0812-3456-7890</p>
    </div>

    <div class="line"></div>

    <p>
        Tanggal : {{ $transaction->tanggal_transaksi->format('d/m/Y H:i') }}<br>
        Nomor Struk : {{ $transaction->nomor_struk }} <br>
        No. Struk : {{ $transaction->id }}
    </p>

    <div class="line"></div>

    <table>
        @foreach($transaction->items as $item)
            <tr>
                <td colspan="2">{{ $item->product->nama_barang }}</td>
            </tr>
            <tr>
                <td>{{ $item->qty }} x {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td>Total</td>
            <td class="right">{{ number_format($transaction->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Dibayar</td>
            <td class="right">{{ number_format($transaction->uang_dibayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td class="right">{{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>Terima Kasih<br>Selamat Berbelanja Kembali</p>
    </div>

</body>
</html>
