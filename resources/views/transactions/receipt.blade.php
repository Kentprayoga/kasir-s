<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk</title>

<style>
@page { size: 58mm auto; margin: 0; }

* { box-sizing: border-box; font-family: monospace; }

body {
  margin: 0;
  padding: 0;
  font-size: 9px;
  line-height: 1.1;
}

/* KUNCI: lebar aman + geser kanan */
#receipt {
  width: 48mm;          /* jangan dibesarin */
  padding-left: 6mm;    /* <<< GESER KANAN (FIX KIRI KEPOTONG) */
  padding-right: 1mm;   /* kanan tetap aman */
}

.center { text-align: center; }
.right { text-align: right; }
.nowrap { white-space: nowrap; }

h3, p { margin: 0; padding: 0; word-break: break-word; }
h3 { font-size: 11px; }

.line { border-top: 1px dashed #000; margin: 3px 0; }

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

td { padding: 1px 0; vertical-align: top; font-size: 9px; }

/* kolom */
.col-left { width: 68%; }
.col-right { width: 32%; }
</style>
</head>

<body onload="window.print()">

<div id="receipt">

  <div class="center">
    <br>

    <h2>RM Sasalero</h2>
    <h2>Masakan Padang</h2>
    <p>
      Dusun Rawagede 1 Rt 004 Rw 002<br>
      Kel. Balongsari, Kec. Rawamerta<br>
      Kab. Karawang<br>
      Telp: 0852-1851-8191
    </p>
  </div>

  <div class="line"></div>

  <p>
    Tanggal : {{ $transaction->tanggal_transaksi->format('d/m/Y H:i') }}<br>
    No Struk : {{ $transaction->nomor_struk }}<br>
  </p>

  <div class="line"></div>

  <table>
    @foreach($transaction->items as $item)
      <tr>
        <td colspan="2">{{ $item->product->nama_barang }}</td>
      </tr>
      <tr>
        <td class="col-left">
          {{ $item->qty }} x {{ number_format($item->harga, 0, ',', '.') }}
        </td>
        <td class="col-right right nowrap">
          {{ number_format($item->subtotal, 0, ',', '.') }}
        </td>
      </tr>
    @endforeach
  </table>

  <div class="line"></div>

  <table>
    <tr>
      <td class="col-left">TOTAL</td>
      <td class="col-right right nowrap">{{ number_format($transaction->total, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td class="col-left">BAYAR</td>
      <td class="col-right right nowrap">{{ number_format($transaction->uang_dibayar, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td class="col-left">KEMBALI</td>
      <td class="col-right right nowrap">{{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
    </tr>
  </table>

  <div class="line"></div>

  <div class="center">
    <p>Terima Kasih<br>Selamat Berbelanja Kembali</p>
  </div>

</div>

</body>
</html>
