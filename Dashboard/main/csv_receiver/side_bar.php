<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Sidebar Styles */
    .sidebar {
      width: 250px;
      background-color: #4CAF50;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      transition: all 0.3s ease;
      z-index: 1000;
      overflow-y: auto;
    }

    .sidebar-logo {
      padding: 20px;
      text-align: center;
    }

    .sidebar h2 {
      text-align: center;
      padding: 10px 0;
      margin: 0;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar li {
      padding: 15px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      transition: background 0.3s;
    }

    .sidebar li:hover {
      background-color: rgba(0,0,0,0.1);
    }

    .sidebar li a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .sidebar li a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar li.active {
      background-color: rgba(0,0,0,0.2);
    }

    /* Hamburger Menu */
    .hamburger {
      display: none;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1100;
      background: #4CAF50;
      color: white;
      border: none;
      font-size: 24px;
      cursor: pointer;
      border-radius: 4px;
      padding: 5px 10px;
    }

    /* Main Content */
    .content {
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }

    /* Collapsed State */
    .sidebar.collapsed {
      width: 80px;
      overflow: hidden;
    }

    .sidebar.collapsed .text {
      display: none;
    }

    .sidebar.collapsed h2 {
      font-size: 0;
      visibility: hidden;
    }

    .sidebar.collapsed li a {
      justify-content: center;
    }

    .sidebar.collapsed li a i {
      margin-right: 0;
      font-size: 20px;
    }

    .content.collapsed {
      margin-left: 80px;
    }

    /* Role Indicator */
    .role-indicator {
      position: fixed;
      top: 70px;
      right: 20px;
      background-color: #4CAF50;
      color: white;
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 14px;
      z-index: 1000;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .sidebar {
        left: -250px;
      }
      
      .sidebar.mobile-show {
        left: 0;
      }
      
      .sidebar.collapsed {
        left: -80px;
      }
      
      .sidebar.collapsed.mobile-show {
        left: 0;
        width: 80px;
      }
      
      .content {
        margin-left: 0;
      }
      
      .hamburger {
        display: block;
      }
      
      .role-indicator {
        top: 120px;
      }
    }

    @media (max-width: 768px) {
      .role-indicator {
        top: 70px;
        right: 10px;
        font-size: 12px;
      }
    }
  </style>
</head>
<body>

  <!-- Hamburger Menu -->
  <button class="hamburger" id="hamburger">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-logo"></div>
    <h2>Dashboard</h2>
    <ul>
      <li class="active">
        <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/upload_csv.php">
          <i class="fas fa-upload"></i>
          <span class="text">Upload</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="fas fa-check-circle"></i>
          <span class="text">Verify</span>
        </a>
      </li>
      <li>
        <a href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/modify_column.php">
          <i class="fas fa-edit"></i>
          <span class="text">Edit</span>
        </a>
      </li>
      <li>
        <a href="/EMPLOYEE-TRACKING-SYSTEM/register/logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span class="text">Logout</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Role Indicator -->
  <div class="role-indicator">
    Logged in as: <?php echo strtoupper(htmlspecialchars($_SESSION['user_role'] ?? 'guest')); ?>
  </div>

  <!-- Main Content -->
  <div class="content" id="content">
    <!-- Your content here -->
  </div>

  <script>
    // Toggle sidebar
    document.getElementById('hamburger').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebar');
      const content = document.getElementById('content');
      
      if (window.innerWidth <= 992) {
        // Mobile behavior - toggle visibility
        sidebar.classList.toggle('mobile-show');
      } else {
        // Desktop behavior - toggle collapsed state
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
      }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
      const sidebar = document.getElementById('sidebar');
      const hamburger = document.getElementById('hamburger');
      
      if (window.innerWidth <= 992 && 
          !sidebar.contains(e.target) && 
          !hamburger.contains(e.target) &&
          sidebar.classList.contains('mobile-show')) {
        sidebar.classList.remove('mobile-show');
      }
    });

    // Highlight active menu item
    document.addEventListener('DOMContentLoaded', function() {
      const currentPage = window.location.pathname;
      const menuItems = document.querySelectorAll('.sidebar li a');
      
      menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPage) {
          item.parentElement.classList.add('active');
        } else {
          item.parentElement.classList.remove('active');
        }
      });
    });
  </script>
</body>
</html>