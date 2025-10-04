@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}

        @if(session('print_id'))
            <a href="{{ route('transactions.receipt', session('print_id')) }}" 
               target="_blank" 
               class="btn btn-sm btn-primary">
                Cetak Struk
            </a>
        @endif
    </div>
@endif

<div class="container">
    <h2 class="mb-4">Tambah Transaksi</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf

        <table class="table table-bordered" id="items-table">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="items[0][product_id]" class="form-control product-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-harga="{{ $product->harga }}">
                                    {{ $product->nama_barang }} (Rp {{ number_format($product->harga,0,',','.') }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[0][qty]" class="form-control qty-input" min="1" value="1" required>
                    </td>
                    <td class="harga">0</td>
                    <td class="subtotal">0</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-primary mb-3" id="add-row">+ Tambah Barang</button>

        <div class="mb-3">
            <label>Total Belanja</label>
            <input type="text" id="total" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Uang Dibayar</label>
            <select class="form-control mb-2" id="uang-select">
                <option value="">-- Pilih Nominal --</option>
                <option value="25000">25.000</option>
                <option value="30000">30.000</option>
                <option value="40000">40.000</option>
                <option value="50000">50.000</option>
                <option value="100000">100.000</option>
            </select>
            <!-- ubah ke text supaya bisa diformat -->
            <input type="text" name="uang_dibayar" id="uang_dibayar" class="form-control" placeholder="Atau isi manual" required>
        </div>

        <div class="mb-3">
            <label>Kembalian</label>
            <input type="text" id="kembalian" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success">Simpan Transaksi</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let rowIndex = 1;

    function formatRupiah(angka) {
        if (!angka) return "0";
        return angka.toLocaleString('id-ID');
    }

    function parseRupiah(str) {
        return parseInt(str.replace(/\./g, "").replace(/[^0-9]/g, "")) || 0;
    }

    function updateRow(row) {
        let harga = parseFloat(row.querySelector(".product-select")?.selectedOptions[0]?.dataset.harga || 0);
        let qty = parseInt(row.querySelector(".qty-input").value || 0);
        let subtotal = harga * qty;

        row.dataset.subtotal = subtotal;

        row.querySelector(".harga").textContent = formatRupiah(harga);
        row.querySelector(".subtotal").textContent = formatRupiah(subtotal);

        updateTotal();
    }

    function updateUangSelect(total) {
        let uangSelect = document.getElementById("uang-select");

        let existing = uangSelect.querySelector("option[data-total='true']");
        if (existing) existing.remove();

        if (total > 0) {
            let option = document.createElement("option");
            option.value = total;
            option.textContent = total.toLocaleString('id-ID') + " (Total Belanja)";
            option.dataset.total = "true";
            uangSelect.insertBefore(option, uangSelect.firstChild.nextSibling);
        }
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll("#items-table tbody tr").forEach(row => {
            let subtotal = parseFloat(row.dataset.subtotal || 0);
            total += subtotal;
        });

        document.getElementById("total").value = formatRupiah(total);

        updateUangSelect(total);

        let uang = parseRupiah(document.getElementById("uang_dibayar").value || "0");
        let kembalian = uang - total;
        document.getElementById("kembalian").value = formatRupiah(kembalian >= 0 ? kembalian : 0);
    }

    // Format input uang_dibayar saat ketik
    document.getElementById("uang_dibayar").addEventListener("input", function(e) {
        let value = parseRupiah(e.target.value);
        e.target.value = value > 0 ? formatRupiah(value) : "";
        updateTotal();
    });

    // Tambah baris
// Tambah baris
document.getElementById("add-row").addEventListener("click", function () {
    let newRow = document.querySelector("#items-table tbody tr").cloneNode(true);

    // Reset field
    newRow.querySelectorAll("input, select").forEach(input => {
        if (input.classList.contains("qty-input")) {
            input.value = 1; // ✅ qty default 1
        } else {
            input.value = "";
        }
        input.style.backgroundColor = ""; 
    });

    newRow.querySelector(".harga").textContent = "0";
    newRow.querySelector(".subtotal").textContent = "0";
    newRow.dataset.subtotal = 0;

    newRow.querySelector(".product-select").setAttribute("name", `items[${rowIndex}][product_id]`);
    newRow.querySelector(".qty-input").setAttribute("name", `items[${rowIndex}][qty]`);
    rowIndex++;

    document.querySelector("#items-table tbody").appendChild(newRow);
    updateRow(newRow);
});


    // Event delegation
    document.addEventListener("change", function (e) {
        if (e.target.classList.contains("product-select")) {
            updateRow(e.target.closest("tr"));
            if (e.target.value) {
                e.target.style.backgroundColor = "#d1ecf1"; 
                e.target.style.color = "#000"; 
            } else {
                e.target.style.backgroundColor = "";
                e.target.style.color = "";
            }
        }
        if (e.target.classList.contains("qty-input")) {
            updateRow(e.target.closest("tr"));
        }
        if (e.target.id === "uang-select") {
            let val = e.target.value;
            document.getElementById("uang_dibayar").value = val ? formatRupiah(parseInt(val)) : "";
            updateTotal();
        }
    });

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-row")) {
            e.target.closest("tr").remove();
            updateTotal();
        }
    });
});
</script>

@endsection
