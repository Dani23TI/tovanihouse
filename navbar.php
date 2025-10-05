<nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4" 
     style="background: linear-gradient(135deg, #1e3a8a, #2563eb);">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="index.php">
      <img src="https://cdn-icons-png.flaticon.com/512/706/706164.png" 
           alt="Logo" width="32" height="32" class="me-2">
      Kos Dashboard
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active-link' : '' ?>" href="index.php">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'data-kos.php' ? 'active-link' : '' ?>" href="data-kos.php">
            <i class="bi bi-building me-1"></i> Data Kos
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'kamar.php' ? 'active-link' : '' ?>" href="kamar.php">
            <i class="bi bi-door-open me-1"></i> Data Kamar
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'penghuni.php' ? 'active-link' : '' ?>" href="penghuni.php">
            <i class="bi bi-people me-1"></i> Data Penghuni
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'transaksi.php' ? 'active-link' : '' ?>" href="transaksi.php">
            <i class="bi bi-receipt me-1"></i> Transaksi
          </a>
        </li>
      </ul>

      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link logout-btn d-flex align-items-center" href="logout.php">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Tambahkan ini di <head> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .navbar .nav-link {
    color: rgba(255,255,255,0.85);
    font-weight: 500;
    transition: all 0.2s ease;
  }

  .navbar .nav-link:hover {
    color: #fff;
    transform: translateY(-1px);
  }

  .navbar .active-link {
    color: #fff !important;
    font-weight: 600;
    border-bottom: 2px solid #fff;
  }

  .logout-btn {
    color: #fee2e2 !important;
    background: rgba(255,255,255,0.1);
    padding: 6px 14px;
    border-radius: 8px;
    transition: all .2s;
  }

  .logout-btn:hover {
    background: rgba(239,68,68,0.2);
    color: #fff !important;
  }
</style>
