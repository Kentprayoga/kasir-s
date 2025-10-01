<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <!-- Profile section (optional) -->
    </li>
<li class="nav-item">
  <a class="nav-link" href="{{ route('dashboard') }}">
    <span class="menu-title">Dashboard</span>
    <i class="mdi mdi-home menu-icon"></i>
  </a>
</li>


    <!-- Stok Gudang Menu -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('expenses.create') }}">
        <span class="menu-title">pengeluaran</span>
        <i class="mdi mdi-cash-multiple menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">transaksi</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-currency-usd menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <!-- Sub-menu for Stok Gudang -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('kasir.create') }}">kasir</a>
          </li>
          <!-- Sub-menu for Stok Toko -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('transactions.index') }}">detai penjualan</a>
          </li>
        </ul>
      </div>
    </li>
    <!-- Stok Toko Menu -->


    <!-- Penjualan Menu -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('products.index') }}">
        <span class="menu-title">Menu</span>
        <i class="mdi mdi-food-fork-drink menu-icon"></i>
      </a>
    </li>

    <!-- Stok Collapse Menu -->
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <span class="menu-title">Stok</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-crosshairs-gps menu-icon"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <!-- Sub-menu for Stok Gudang -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('stok-gudang.index') }}">Stok Gudang</a>
          </li>
          <!-- Sub-menu for Stok Toko -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('stok-toko.index') }}">Stok Toko</a>
          </li>
        </ul>
      </div>
    </li>

    <!-- Shift Menu -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('shifts.index') }}">
        <span class="menu-title">Shift</span>
        <i class="mdi mdi-time menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>
