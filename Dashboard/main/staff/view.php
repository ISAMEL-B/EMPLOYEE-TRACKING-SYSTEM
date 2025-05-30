<?php
session_start();
require_once '../head/approve/config.php';
$current_pag = basename($_SERVER['PHP_SELF']);

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit;
}

// Get approval ID from URL
$approval_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch approval details
$approval = [];
$stmt = $conn->prepare("SELECT * FROM csv_approvals WHERE id = ?");
$stmt->bind_param("i", $approval_id);
$stmt->execute();
$result = $stmt->get_result();
$approval = $result->fetch_assoc();

// if (!$approval) {
//     $_SESSION['notification'] = "Approval request not found";
//     header('Location: verify.php');
//     exit;
// }

// Fetch submitter details
$submitter = [];
$stmt = $conn->prepare("SELECT employee_id, email, system_role FROM staff WHERE staff_id = ?");
$stmt->bind_param("i", $approval['submitted_by']);
$stmt->execute();
$result = $stmt->get_result();
$submitter = $result->fetch_assoc();

// Fetch CSV data
$csv_data = [];
if (file_exists($approval['file_path'])) {
    if (($handle = fopen($approval['file_path'], "r")) !== FALSE) {
        $header = fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            $csv_data[] = array_combine($header, $data);
        }
        fclose($handle);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Approval - MUST Employee Tracking</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Main Content Positioning */
        .content {
            margin-left: 240px;
            margin-top: 60px;
            padding: 25px;
            min-height: calc(100vh - 60px);
            transition: margin-left 0.3s ease;
        }

        /* When sidebar is collapsed */
        .sidebar.collapsed~.content {
            margin-left: 80px;
        }

        /* Approval Details Card */
        .approval-details {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        /* Detail Rows */
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
            width: 200px;
            flex-shrink: 0;
        }

        .detail-value {
            flex: 1;
            color: #34495e;
        }

        /* Data Preview Section */
        .data-preview {
            max-height: 500px;
            overflow-y: auto;
            margin-top: 20px;
            border: 1px solid #eee;
            border-radius: 4px;
            padding: 15px;
            background-color: #f9f9f9;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
            position: sticky;
            bottom: 25px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #f39c12;
            color: white;
        }

        .status-approved {
            background-color: #2ecc71;
            color: white;
        }

        .status-rejected {
            background-color: #e74c3c;
            color: white;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th, .data-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }

        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .content {
                margin-left: 0;
            }

            .sidebar.show~.content {
                margin-left: 240px;
            }

            .sidebar.collapsed.show~.content {
                margin-left: 80px;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .data-preview {
                max-height: 300px;
            }
        }

        /* Animation for notification */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }
    </style>
</head>

<body>
    <?php
    // -- Sidebar --
    include '../bars/side_bar.php';
    // nav_bar
    include '../bars/nav_bar.php';
    ?>

    <!-- Main Content -->
    <div class="content">
        <div class="approval-details">
            <h2><i class="fas fa-file-import"></i> Approval Request Details</h2>

            <div class="detail-row">
                <div class="detail-label">Request ID:</div>
                <div class="detail-value">#<?php echo htmlspecialchars($approval['id']); ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                    <span class="status-badge status-<?php echo htmlspecialchars($approval['status']); ?>">
                        <?php echo ucfirst(htmlspecialchars($approval['status'])); ?>
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Table Name:</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(ucfirst($approval['table_name'])); ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Submitted By:</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($submitter['employee_id'] ?? 'N/A'); ?>
                    (<?php echo htmlspecialchars($submitter['email'] ?? 'N/A'); ?>)
                    <br><small>Role: <?php echo htmlspecialchars($submitter['system_role'] ?? 'N/A'); ?></small>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Submitted At:</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(date('M d, Y H:i', strtotime($approval['submitted_at']))); ?>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Records Count:</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars($approval['record_count']); ?>
                </div>
            </div>

            <?php if ($approval['status'] === 'rejected'): ?>
                <div class="detail-row">
                    <div class="detail-label">Rejection Reason:</div>
                    <div class="detail-value">
                        <?php echo htmlspecialchars($approval['rejection_reason'] ?? 'Not specified'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="detail-row">
                <div class="detail-label">CSV File:</div>
                <div class="detail-value">
                    <?php echo htmlspecialchars(basename($approval['file_path'])); ?>
                    <a href="<?php echo htmlspecialchars($approval['file_path']); ?>" download class="btn btn-secondary" style="margin-left: 15px;">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h3><i class="fas fa-table"></i> Data Preview</h3>
            <div class="data-preview">
                <?php if (!empty($csv_data)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <?php foreach (array_keys($csv_data[0]) as $header): ?>
                                    <th><?php echo htmlspecialchars($header); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($csv_data as $row): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?php echo htmlspecialchars($value); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No data available for preview.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($approval['status'] === 'pending'): ?>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="approveSubmission(<?php echo $approval['id']; ?>)">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button class="btn btn-danger" onclick="showRejectModal(<?php echo $approval['id']; ?>)">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </div>
        <?php else: ?>
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Back to Approvals
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 500px;">
            <h3 style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> Reject Submission</h3>
            <p>Please provide a reason for rejecting this submission:</p>
            <textarea id="rejectionReason" style="width: 100%; height: 120px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;"></textarea>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button class="btn btn-secondary" onclick="document.getElementById('rejectModal').style.display = 'none'">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn btn-danger" id="confirmRejectBtn">
                    <i class="fas fa-check"></i> Confirm Rejection
                </button>
            </div>
        </div>
    </div>

    <script>
        // Current approval ID for modal use
        let currentApprovalId = 0;

        function showRejectModal(approvalId) {
            currentApprovalId = approvalId;
            document.getElementById('rejectModal').style.display = 'flex';
            document.getElementById('rejectionReason').value = '';
        }

        document.getElementById('confirmRejectBtn').addEventListener('click', function() {
            const reason = document.getElementById('rejectionReason').value.trim();
            if (reason === '') {
                alert('Please enter a rejection reason');
                return;
            }
            rejectSubmission(currentApprovalId, reason);
            document.getElementById('rejectModal').style.display = 'none';
        });

        function approveSubmission(id) {
            if (confirm('Are you sure you want to approve this submission?')) {
                fetch('process_approval.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/json'
                        },
                        body: 'action=approve&id=' + id
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                throw new Error(`Expected JSON but got: ${text.substr(0, 100)}...`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showNotification('Submission approved successfully!', 'success');
                            updateApprovalUI('approved');
                        } else {
                            showNotification('Error: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Error: ' + error.message, 'error');
                        console.error('Approval error:', error);
                    });
            }
        }

        function rejectSubmission(id, reason) {
            fetch('process_approval.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    },
                    body: 'action=reject&id=' + id + '&reason=' + encodeURIComponent(reason)
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            throw new Error(`Expected JSON but got: ${text.substr(0, 100)}...`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification('Submission rejected successfully!', 'success');
                        updateApprovalUI('rejected', reason);
                    } else {
                        showNotification('Error: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    showNotification('Error: ' + error.message, 'error');
                    console.error('Rejection error:', error);
                });
        }

        function updateApprovalUI(status, reason = null) {
            // Update status badge
            const statusBadge = document.querySelector('.status-badge');
            statusBadge.className = `status-badge status-${status}`;
            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            // Add rejection reason if provided
            if (status === 'rejected' && reason) {
                if (!document.querySelector('.rejection-reason')) {
                    const detailRow = document.createElement('div');
                    detailRow.className = 'detail-row rejection-reason';
                    detailRow.innerHTML = `
                        <div class="detail-label">Rejection Reason:</div>
                        <div class="detail-value">${reason}</div>
                    `;
                    document.querySelector('.approval-details').appendChild(detailRow);
                } else {
                    document.querySelector('.rejection-reason .detail-value').textContent = reason;
                }
            }

            // Hide action buttons after approval/rejection
            document.querySelector('.action-buttons').style.display = 'none';
        }

        function showNotification(message, type) {
            let notification = document.getElementById('action-notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'action-notification';
                notification.style.position = 'fixed';
                notification.style.top = '20px';
                notification.style.right = '20px';
                notification.style.padding = '15px';
                notification.style.borderRadius = '4px';
                notification.style.color = 'white';
                notification.style.zIndex = '10000';
                notification.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
                notification.style.animation = 'fadeIn 0.3s';
                document.body.appendChild(notification);
            }

            notification.textContent = message;
            notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#e74c3c';

            setTimeout(() => {
                notification.style.animation = 'fadeOut 0.5s';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }
    </script>
</body>

</html>