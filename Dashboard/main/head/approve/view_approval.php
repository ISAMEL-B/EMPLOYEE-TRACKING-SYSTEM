<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
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

if (!$approval) {
    $_SESSION['notification'] = "Approval request not found";
    header('Location: verify.php');
    exit;
}

// Fetch submitter details
$submitter = [];
$stmt = $conn->prepare("SELECT employee_id, email, role FROM users WHERE user_id = ?");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    /* Main Content Positioning */
    .content {
        margin-left: 240px; /* Matches sidebar width */
        margin-top: 60px; /* Matches nav bar height */
        padding: 25px;
        min-height: calc(100vh - 60px);
        transition: margin-left 0.3s ease;
    }

    /* When sidebar is collapsed */
    .sidebar.collapsed ~ .content {
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
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
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

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .content {
            margin-left: 0;
        }
        
        .sidebar.show ~ .content {
            margin-left: 240px;
        }
        
        .sidebar.collapsed.show ~ .content {
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
</style>
</head>
<body>
<?php
    // -- Sidebar --
    include '../../bars/side_bar.php';
    // nav_bar
    include '../../bars/nav_bar.php';
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
                    <br><small>Role: <?php echo htmlspecialchars($submitter['role'] ?? 'N/A'); ?></small>
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
            <button class="btn btn-danger" onclick="rejectSubmission(<?php echo $approval['id']; ?>)">
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

    <script>
        function approveSubmission(id) {
    if (confirm('Are you sure you want to approve this submission?')) {
        fetch('process_approval.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Accept': 'application/json' // Explicitly ask for JSON
            },
            body: 'action=approve&id=' + id
        })
        .then(response => {
            // First check if the response is JSON
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
                // Update UI
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

// Similar update for rejectSubmission()

function rejectSubmission(id) {
    const reason = prompt('Please enter reason for rejection:');
    if (reason !== null && reason.trim() !== '') {
        fetch('process_approval.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=reject&id=' + id + '&reason=' + encodeURIComponent(reason)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Submission rejected successfully!', 'success');
                // Update UI immediately
                document.querySelector('.status-badge').className = 'status-badge status-rejected';
                document.querySelector('.status-badge').textContent = 'Rejected';
                // Add rejection reason to display
                if (!document.querySelector('.rejection-reason')) {
                    const detailRow = document.createElement('div');
                    detailRow.className = 'detail-row rejection-reason';
                    detailRow.innerHTML = `
                        <div class="detail-label">Rejection Reason:</div>
                        <div class="detail-value">${reason}</div>
                    `;
                    document.querySelector('.approval-details').appendChild(detailRow);
                }
                // Hide action buttons after rejection
                document.querySelector('.action-buttons').style.display = 'none';
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Network error: ' + error, 'error');
        });
    } else if (reason !== null) {
        alert('Rejection reason cannot be empty!');
    }
}

function showNotification(message, type) {
    // Create or use existing notification div
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

    // Set notification style based on type
    notification.textContent = message;
    notification.style.backgroundColor = type === 'success' ? '#4CAF50' : '#e74c3c';

    // Auto-hide after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'fadeOut 0.5s';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
}
    </script>
</body>
</html>