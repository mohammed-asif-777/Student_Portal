<!-- sidebar_student.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  #toggle-btn {
    position: fixed;
    top: 10px;
    left: 10px;
    z-index: 1000;
    background: #2c3e50;
    color: #fff;
    border: none;
    padding: 8px 10px;
    border-radius: 4px;
    cursor: pointer;
  }

  #sidebar {
    position: fixed;
    left: -220px;
    top: 0;
    bottom: 0;
    width: 220px;
    background: #2c3e50;
    transition: left 0.3s ease;
    padding-top: 20px;
    overflow-y: auto;
  }

  #sidebar.open { left: 0; }

  #sidebar .menu-item {
    color: #fff;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: background 0.2s;
  }

  #sidebar .menu-item:hover {
    background: #34495e;
  }

  #sidebar .menu-item i {
    margin-right: 10px;
    width: 18px;
    text-align: center;
    color: #fff;
  }

  #sidebar .logo-wrap {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  }

  #sidebar .logo-wrap i {
    color: #fff;
  }

  .content {
    margin-left: 0;
    transition: margin-left 0.3s ease;
  }

  .content.shift {
    margin-left: 220px;
  }

  #sidebar .menu-item.active {
    background: #1a252f;
  }
</style>

<!-- Toggle Button -->
<button id="toggle-btn"><i class="fas fa-bars"></i></button>

<!-- Sidebar -->
<nav id="sidebar">
  <div class="logo-wrap">
    <i class="fas fa-university fa-2x"></i>
  </div>
  <a href="dashboard.php" class="menu-item<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? ' active' : '' ?>"><i class="fas fa-home"></i>Dashboard</a>
  <a href="graph_student.php" class="menu-item<?= basename($_SERVER['PHP_SELF']) == 'graph_student.php' ? ' active' : '' ?>"><i class="fas fa-chart-bar"></i>Graph</a>
  <a href="mail_send_student.php" class="menu-item<?= basename($_SERVER['PHP_SELF']) == 'mail_send_student.php' ? ' active' : '' ?>"><i class="fas fa-paper-plane"></i>Send Mail</a>
  <a href="mail_inbox_student.php" class="menu-item<?= basename($_SERVER['PHP_SELF']) == 'mail_inbox_student.php' ? ' active' : '' ?>"><i class="fas fa-inbox"></i>Inbox</a>
  <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i>Logout</a>
</nav>

<!-- Script to toggle sidebar -->
<script>
  const btn = document.getElementById('toggle-btn');
  const sb = document.getElementById('sidebar');
  const ct = document.querySelector('.content');
  btn.addEventListener('click', () => {
    sb.classList.toggle('open');
    ct.classList.toggle('shift');
  });
</script>
