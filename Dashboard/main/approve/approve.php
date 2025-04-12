<?php
session_start();
// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit;
}

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify CSV Data</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> -->
    
    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../components/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/bars/nav_sidebar/nav_side_bar.css">
    <style>
        /* Sidebar and Layout Styles */
        #sidebar {
            width: 280px;
            transition: all 0.3s;
        }

        .wrapper {
            margin-left: 310px;
            padding: 20px 30px;
            transition: all 0.3s;
        }

        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1050;
                height: 100vh;
            }

            #sidebar.active {
                transform: translateX(0);
            }

            .wrapper {
                margin-left: 0 !important;
                padding: 15px !important;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        /* Notification Styles */
        .notification-area {
            margin-top: 20px;
        }

        .alert-dismissible {
            padding-right: 4rem;
        }

        /* Approval Item Styles */
        .approval-item {
            border-left: 4px solid #0d6efd;
            margin-bottom: 15px;
        }

        /* Empty State Styles */
        .empty-state {
            opacity: 0.7;
        }
    </style>
</head>

<body class="d-flex">
    <?php
    // nav_bar
    include '../bars/nav_sidebar/nav_bar.php';
    // -- Sidebar --
    include '../bars/nav_sidebar/side_bar.php';
    ?>

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay"></div>

    <div class="wrapper d-flex flex-column flex-grow-1">
       
        <!-- Main Content -->
        <main class="container-fluid py-4 flex-grow-1">
            <div class="verification-container bg-white rounded-3 shadow-sm p-4">
                <h2 class="mb-4"><i class="fas fa-check-circle text-primary me-2"></i> Pending Approvals</h2>

                <!-- Notification Area -->
                <div class="notification-area">
                    <?php if (isset($_SESSION['notification'])): ?>
                        <div class="alert alert-dismissible alert-<?php echo $_SESSION['notification_type'] ?? 'success'; ?>">
                            <?php echo htmlspecialchars($_SESSION['notification']); ?>
                            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                        </div>
                        <?php unset($_SESSION['notification'], $_SESSION['notification_type']); ?>
                    <?php endif; ?>
                </div>

                <?php
                // Database connection
                include 'config.php';

                // Fetch pending approvals from database
                $query = "SELECT * FROM csv_approvals WHERE status = 'pending' ORDER BY submitted_at DESC";
                $result = $conn->query($query);
                ?>

                <div class="filter-controls mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select id="filter-table" class="form-select">
                                <option value="">All Tables</option>
                                <option value="staff">Staff</option>
                                <option value="publications">Publications</option>
                                <option value="grants">Grants</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="date" id="filter-date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-filter me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pending-approvals">
                    <?php if ($result->num_rows > 0): ?>
                        <div class="list-group">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="list-group-item approval-item mb-3 rounded" data-table="<?php echo htmlspecialchars($row['table_name']); ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="approval-info">
                                            <h5 class="mb-1"><?php echo htmlspecialchars(ucfirst($row['table_name'])); ?> Data Submission</h5>
                                            <small class="text-muted">
                                                Submitted by: <?php echo htmlspecialchars($row['submitted_by']); ?> |
                                                <?php echo htmlspecialchars($row['submitted_at']); ?> |
                                                Records: <?php echo htmlspecialchars($row['record_count']); ?>
                                            </small>
                                        </div>
                                        <div class="approval-actions btn-group">
                                            <button class="btn btn-outline-primary btn-sm" onclick="viewDetails(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" onclick="approveSubmission(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="rejectSubmission(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state text-center py-5">
                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                            <h3 class="mb-2">No Pending Approvals</h3>
                            <p class="text-muted">There are currently no submissions waiting for your approval.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../bars/nav_sidebar/nav_side_bar.js"></script>
    <script>
        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            if (hamburger && sidebar && overlay) {
                hamburger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });

        // Approval functions
        function approveSubmission(approvalId) {
            if (confirm('Are you sure you want to approve this submission?')) {
                fetch('process_approve.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=approve&id=' + approvalId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?notification=' +
                                encodeURIComponent('Submission approved successfully!') +
                                '&notification_type=success';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error);
                    });
            }
        }

        function rejectSubmission(approvalId) {
            const reason = prompt('Please enter reason for rejection:');
            if (reason !== null) {
                fetch('process_approve.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=reject&id=' + approvalId + '&reason=' + encodeURIComponent(reason)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '<?php echo $_SERVER['PHP_SELF']; ?>?notification=' +
                                encodeURIComponent('Submission rejected successfully!') +
                                '&notification_type=success';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Error: ' + error);
                    });
            }
        }

        function viewDetails(approvalId) {
            window.location.href = 'view_approval.php?id=' + approvalId;
        }

        function applyFilters() {
            const tableFilter = document.getElementById('filter-table').value;
            const dateFilter = document.getElementById('filter-date').value;

            document.querySelectorAll('.approval-item').forEach(item => {
                const itemTable = item.getAttribute('data-table');
                const itemDate = item.querySelector('small').textContent;

                const tableMatch = !tableFilter || itemTable === tableFilter;
                const dateMatch = !dateFilter || itemDate.includes(dateFilter);

                item.style.display = (tableMatch && dateMatch) ? 'block' : 'none';
            });
        }

        // Check for URL notification parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const notification = urlParams.get('notification');
            const notificationType = urlParams.get('notification_type');

            if (notification) {
                // Create notification element
                const notificationArea = document.querySelector('.notification-area');
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-dismissible alert-${notificationType || 'success'}`;
                alertDiv.innerHTML = `
                    ${decodeURIComponent(notification)}
                    <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                `;
                notificationArea.prepend(alertDiv);

                // Clean URL
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        });
    </script>
</body>

</html>