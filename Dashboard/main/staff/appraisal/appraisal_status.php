<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['staff_id'])) {
    header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
    exit;
}

$current_pag = basename($_SERVER['PHP_SELF']);

// DB connection
include '../../head/approve/config.php';

// Fetch staff list for dropdown
$staff_members = [];
$staff_sql = "SELECT staff_id, first_name, last_name FROM staff ORDER BY first_name";
if ($result = $conn->query($staff_sql)) {
    while ($row = $result->fetch_assoc()) {
        $staff_members[$row['staff_id']] = $row['first_name'] . ' ' . $row['last_name'];
    }
}

// Determine selected staff ID
$user_role = $_SESSION['user_role'] ?? '';
$session_staff_id = $_SESSION['staff_id'] ?? 0;

if ($user_role === 'staff') {
    $selected_staff_id = $session_staff_id;
} else {
    $selected_staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : 0;
}

// Initialize arrays
$staff_data = $degrees_data = $publications_data = $grants_data = $innovations_data = $community_service_data = $supervision_data = [];

if ($selected_staff_id) {

    // Helper function to fetch rows into array
    function fetch_all($conn, $query) {
        $data = [];
        $result = $conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Fetch all sections
    $staff_data_query = "SELECT * FROM staff WHERE staff_id = $selected_staff_id";
    $staff_result = $conn->query($staff_data_query);
    $staff_data = $staff_result ? $staff_result->fetch_assoc() : [];

    $degrees_data = fetch_all($conn, "SELECT * FROM degrees WHERE staff_id = $selected_staff_id");
    $publications_data = fetch_all($conn, "SELECT * FROM publications WHERE staff_id = $selected_staff_id");
    $grants_data = fetch_all($conn, "SELECT * FROM grants WHERE staff_id = $selected_staff_id");
    $innovations_data = fetch_all($conn, "SELECT * FROM innovations WHERE staff_id = $selected_staff_id");
    $community_service_data = fetch_all($conn, "SELECT * FROM communityservice WHERE staff_id = $selected_staff_id");
    $supervision_data = fetch_all($conn, "SELECT * FROM supervision WHERE staff_id = $selected_staff_id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Appraisal Form | MUST</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../components/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        margin: 0;
    }

    .wrapper {
        margin-left: 280px;
        padding: 20px;
        transition: all 0.3s;
    }

    #sidebar {
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        transition: all 0.3s;
    }

    .appraisal-container {
        background: #fff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .staff-selector {
        margin-bottom: 30px;
        padding: 15px;
        border: 1px solid #ddd;
        background: #f9f9f9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .section-title {
        font-size: 1.2em;
        font-weight: bold;
        margin: 25px 0 10px 0;
        padding-bottom: 5px;
        border-bottom: 1px solid #000;
    }

    .status-pending {
        font-weight: bold;
        color: #856404;
    }

    .status-approved {
        font-weight: bold;
        color: #155724;
    }

    .status-rejected {
        font-weight: bold;
        color: #721c24;
    }

    .print-actions {
        text-align: right;
        margin-bottom: 20px;
    }

    textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
    }

    .signature-area {
        margin-top: 40px;
    }

    .signature-line {
        display: inline-block;
        width: 250px;
        border-top: 1px solid #000;
        margin-top: 50px;
    }

    /* Print specific styles */
    @media print {
        body * {
            visibility: hidden;
        }

        #printable-area,
        #printable-area * {
            visibility: visible;
        }

        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        body {
            background: #fff;
            font-size: 12pt;
        }

        table {
            page-break-inside: avoid;
        }
    }

    /* Mobile responsive */
    @media (max-width: 991.98px) {
        #sidebar {
            transform: translateX(-100%);
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
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }
    }

    .header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .must-logo {
        height: 80px;
        margin-right: 10px;
    }

    .header-text {
        flex: 1;
        text-align: center;
    }
    </style>
</head>

<body class="d-flex">
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay"></div>

    <!-- Sidebar -->
    <?php include '../../bars/side_bar.php'; ?>

    <!-- Navbar -->
    <?php include '../../bars/nav_bar.php'; ?>

    <!-- Main Content -->
    <div class="wrapper d-flex flex-column flex-grow-1">
        <div class="appraisal-container">
            <div id="printable-area">
                <div class="header">
                    <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png" alt="MUST Logo"
                        class="must-logo">
                    <div class="header-text">
                        <h2>MBARARA UNIVERSITY OF SCIENCE AND TECHNOLOGY</h2>
                        <h3>STAFF PERFORMANCE APPRAISAL FORM</h3>
                        <p>P.O. Box 1410, Mbarara, Uganda | Tel: +256 414 668 971</p>
                    </div>
                </div>

                <div class="print-actions no-print">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i>Print Form
                    </button>
                    <button onclick="downloadPDF()" class="btn btn-outline-secondary">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </button>
                    <button onclick="location.reload()" class="btn btn-outline-info">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                    </button>
                </div>

                <div class="staff-selector no-print">
                    <form method="POST" action="">
                        <?php if ($_SESSION['user_role'] != 'staff'): ?>
                            <div class="row align-items-center"> 
                                <div class="col-md-8">
                                    <label for="staff_id"><strong>Select Staff Member:</strong></label>
                                    <select name="staff_id" id="staff_id" class="form-select" required>
                                        <option value="">-- Select Staff Member --</option>
                                        <?php foreach ($staff_members as $id => $name): ?>
                                        <option value="<?php echo $id; ?>"
                                            <?php echo ($selected_staff_id == $id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($name); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i> Load Data
                                </button>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="staff_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <?php endif; ?>
                        
                    </form>
                </div>

                <?php if ($selected_staff_id): ?>
                <!-- Staff Basic Information -->
                <table>
                    <tr>
                        <th colspan="4" style="text-align: center;">STAFF INFORMATION</th>
                    </tr>
                    <tr>
                        <th width="25%">Full Name</th>
                        <td width="25%">
                            <?php echo htmlspecialchars($staff_data['first_name'] . ' ' . $staff_data['last_name']); ?>
                        </td>
                        <th width="25%">Department</th>
                        <td width="25%">
                            <?php 
                            if ($staff_data['department_id']) {
                                $dept_query = "SELECT department_name FROM departments WHERE department_id = " . $staff_data['department_id'];
                                $dept_result = $conn->query($dept_query);
                                echo htmlspecialchars($dept_result->fetch_assoc()['department_name']);
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Position</th>
                        <td><?php echo htmlspecialchars($staff_data['position'] ?? 'N/A'); ?></td>
                        <th>Years of Service</th>
                        <td><?php echo htmlspecialchars($staff_data['years_of_experience'] ?? 'N/A'); ?></td>
                    </tr>
                </table>

                <!-- Academic Qualifications -->
                <div class="section-title">ACADEMIC QUALIFICATIONS</div>
                <table>
                    <tr>
                        <th width="25%">Degree</th>
                        <th width="25%">Institution</th>
                        <th width="15%">Year Awarded</th>
                        <th width="15%">Status</th>
                    </tr>
                    <?php if (!empty($degrees_data)): ?>
                    <?php foreach ($degrees_data as $degree): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($degree['degree_name']); ?></td>
                        <td><?php echo htmlspecialchars($degree['institution'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($degree['year_awarded'] ?? 'N/A'); ?></td>
                        <td class="status-<?php echo $degree['verification_status'] ?? 'pending'; ?>">
                            <?php echo strtoupper($degree['verification_status'] ?? 'PENDING'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No academic qualifications recorded</td>
                    </tr>
                    <?php endif; ?>
                </table>

                <!-- Research Publications -->
                <div class="section-title">RESEARCH PUBLICATIONS</div>
                <table>
                    <tr>
                        <th width="30%">Title</th>
                        <th width="15%">Type</th>
                        <th width="20%">Journal/Publisher</th>
                        <th width="15%">Date</th>
                        <th width="10%">Status</th>
                    </tr>
                    <?php if (!empty($publications_data)): ?>
                    <?php foreach ($publications_data as $pub): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pub['title'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($pub['publication_type']); ?></td>
                        <td><?php echo htmlspecialchars($pub['journal_name'] ?? $pub['publisher'] ?? 'N/A'); ?></td>
                        <td><?php echo date('M Y', strtotime($pub['publication_date'])); ?></td>
                        <td class="status-<?php echo $pub['verification_status'] ?? 'pending'; ?>">
                            <?php echo strtoupper($pub['verification_status'] ?? 'PENDING'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No research publications recorded</td>
                    </tr>
                    <?php endif; ?>
                </table>

                <!-- Research Grants -->
                <div class="section-title">RESEARCH GRANTS</div>
                <table>
                    <tr>
                        <th width="30%">Project Title</th>
                        <th width="20%">Funding Agency</th>
                        <th width="15%">Amount (UGX)</th>
                        <th width="15%">Year</th>
                        <th width="10%">Status</th>
                    </tr>
                    <?php if (!empty($grants_data)): ?>
                    <?php foreach ($grants_data as $grant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grant['grant_title'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($grant['funding_agency'] ?? 'N/A'); ?></td>
                        <td><?php echo number_format($grant['grant_amount'], 2); ?></td>
                        <td><?php echo date('Y', strtotime($grant['grant_year'])); ?></td>
                        <td class="status-<?php echo $grant['verification_status'] ?? 'pending'; ?>">
                            <?php echo strtoupper($grant['verification_status'] ?? 'PENDING'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No research grants recorded</td>
                    </tr>
                    <?php endif; ?>
                </table>

                <!-- Community Service -->
                <div class="section-title">COMMUNITY SERVICE</div>
                <table>
                    <tr>
                        <th width="30%">Activity</th>
                        <th width="20%">Organization</th>
                        <th width="20%">Role</th>
                        <th width="15%">Duration</th>
                        <th width="15%">Status</th>
                    </tr>
                    <?php if (!empty($community_service_data)): ?>
                    <?php foreach ($community_service_data as $service): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service['description']); ?></td>
                        <td><?php echo htmlspecialchars($service['organization'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($service['role'] ?? 'Participant'); ?></td>
                        <td><?php echo htmlspecialchars($service['duration'] ?? 'N/A'); ?></td>
                        <td class="status-<?php echo $service['verification_status'] ?? 'pending'; ?>">
                            <?php echo strtoupper($service['verification_status'] ?? 'PENDING'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No community service recorded</td>
                    </tr>
                    <?php endif; ?>
                </table>

                <!-- Verification Summary -->
                <div class="section-title">VERIFICATION SUMMARY</div>
                <table>
                    <tr>
                        <th width="20%">Verifier Notes:</th>
                        <td><textarea rows="5"></textarea></td>
                    </tr>
                    <tr>
                        <th>Overall Recommendation:</th>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recommendation" id="rec-approve"
                                    checked>
                                <label class="form-check-label" for="rec-approve">Approve All Verified</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recommendation" id="rec-reject">
                                <label class="form-check-label" for="rec-reject">Reject All Unverified</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recommendation" id="rec-partial">
                                <label class="form-check-label" for="rec-partial">Partial Approval</label>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Signature Area -->
                <div class="signature-area">
                    <table>
                        <tr>
                            <td width="50%">
                                <p>Staff Member's Signature:</p>
                                <div class="signature-line"></div>
                                <p>Date: ___________________</p>
                            </td>
                            <td width="50%">
                                <p>Verifier's Signature:</p>
                                <div class="signature-line"></div>
                                <p>Date: ___________________</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <?php else: ?>
                <div style="text-align: center; margin-top: 50px; font-style: italic;">
                    <p>Please select a staff member from the dropdown to view appraisal data</p>
                </div>
                <?php endif; ?>
            </div> <!-- End printable area -->
        </div>
    </div>

    <!-- Include JavaScript libraries -->
    <script src="../../components/jquery/jquery.min.js"></script>
    <script src="../../components/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/bars/nav_sidebar/nav_side_bar.js"></script>

    <script>
    // Sidebar toggle functionality
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

    // PDF Download functionality
    function downloadPDF() {
        const element = document.getElementById('printable-area');
        const opt = {
            margin: 10,
            filename: 'Staff_Appraisal_<?php echo $selected_staff_id ? $staff_data['first_name'].'_'.$staff_data['last_name'] : ''; ?>.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
    </script>
</body>

</html>