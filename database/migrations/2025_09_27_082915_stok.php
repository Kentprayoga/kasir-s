<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Produk siap jual di toko
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0); // stok toko
            $table->timestamps();
        });

        // Paket (misal paket nasi + ayam goreng)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->decimal('harga', 12, 2);
            $table->timestamps();
        });

        // Detail paket (apa saja yang ada di paket)
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('qty'); // qty produk dalam paket
            $table->timestamps();
        });

        // Stok Gudang (bahan mentah)
        Schema::create('stok_gudangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->decimal('harga',12,2)->default(0);
            $table->integer('stok_tersedia')->default(0);
            $table->timestamps();
        });

        // Log stok gudang
        Schema::create('stok_gudang_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_gudang_id')->constrained()->cascadeOnDelete();
            $table->integer('stok_masuk')->default(0);
            $table->integer('stok_keluar')->default(0);
            $table->string('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });

        // Log stok toko
        Schema::create('stok_toko_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('stok_masuk')->default(0);
            $table->integer('stok_keluar')->default(0);
            $table->string('keterangan')->nullable();
            $table->date('tanggal');
            $table->timestamps();
        });

        // Shift kasir
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->decimal('modal_awal', 12, 2);
            $table->decimal('modal_akhir', 12, 2)->nullable();
            $table->decimal('total_penjualan', 12, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Transaksi
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_struk')->unique();
            $table->foreignId('shift_id')->nullable()->constrained()->cascadeOnDelete();
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total', 12, 2);
            $table->decimal('uang_dibayar', 12, 2);
            $table->decimal('kembalian', 12, 2);
            $table->timestamps();
        });

        // Detail item transaksi
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Detail paket transaksi (opsional)
        Schema::create('transaction_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Pengeluaran
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->decimal('jumlah', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('transaction_packages');
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('stok_toko_logs');
        Schema::dropIfExists('stok_gudang_logs');
        Schema::dropIfExists('stok_gudangs');
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('products');
    }
};