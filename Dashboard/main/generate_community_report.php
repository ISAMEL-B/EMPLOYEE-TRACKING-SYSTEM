<?php
require_once __DIR__ . '/../../vendor/autoload.php';

session_start();
require_once __DIR__ . '/head/approve/pdo.php';

// Check if user is logged in
if (!isset($_SESSION['user_role'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Get activity ID
$activity_id = $_GET['id'] ?? 0;

// Fetch activity details - only existing columns
$stmt = $pdo->prepare("
    SELECT 
        cs.activity_id,
        cs.activity_name,
        cs.location,
        cs.activity_date,
        cs.beneficiaries,
        cs.points_earned,
        cs.verification_status,
        CONCAT(s.first_name, ' ', s.last_name) AS staff_name,
        s.email,
        d.department_name,
        f.faculty_name
    FROM community_service_activities cs
    JOIN staff s ON cs.staff_id = s.staff_id
    JOIN departments d ON s.department_id = d.department_id
    JOIN faculties f ON d.faculty_id = f.faculty_id
    WHERE cs.activity_id = ?
");
$stmt->execute([$activity_id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$activity) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('MUST HR System');
$pdf->SetAuthor('Mbarara University of Science and Technology');
$pdf->SetTitle('Community Service Report - ' . $activity['activity_name']);

// Add a page
$pdf->AddPage();

// Add MUST logo with absolute path
$pdf->Image(__DIR__ . '/logo/mustlogo.jpg', 10, 10, 30);

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Community Service Activity Report', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Mbarara University of Science and Technology', 0, 1, 'C');
$pdf->Ln(10);

// Activity Details
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Activity Details', 0, 1);
$pdf->SetFont('helvetica', '', 12);

// Build the HTML content safely
$html = '<table border="0" cellpadding="5">';
$html .= '<tr><td width="30%"><b>Activity Name:</b></td><td width="70%">'.htmlspecialchars($activity['activity_name']).'</td></tr>';
$html .= '<tr><td><b>Staff Member:</b></td><td>'.htmlspecialchars($activity['staff_name']).'</td></tr>';
$html .= '<tr><td><b>Department:</b></td><td>'.htmlspecialchars($activity['department_name']).' ('.htmlspecialchars($activity['faculty_name']).')</td></tr>';
$html .= '<tr><td><b>Email:</b></td><td>'.htmlspecialchars($activity['email']).'</td></tr>';
$html .= '<tr><td><b>Location:</b></td><td>'.htmlspecialchars($activity['location']).'</td></tr>';
$html .= '<tr><td><b>Date:</b></td><td>'.htmlspecialchars($activity['activity_date']).'</td></tr>';

// Only show duration if it exists in the database
if (isset($activity['duration_hours'])) {
    $html .= '<tr><td><b>Duration:</b></td><td>'.htmlspecialchars($activity['duration_hours']).' hours</td></tr>';
}

$html .= '<tr><td><b>Beneficiaries:</b></td><td>'.htmlspecialchars($activity['beneficiaries'] ?? 'N/A').'</td></tr>';
$html .= '<tr><td><b>Points Earned:</b></td><td>'.htmlspecialchars($activity['points_earned']).'</td></tr>';
$html .= '<tr><td><b>Status:</b></td><td>'.htmlspecialchars($activity['verification_status']).'</td></tr>';
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Only show description if it exists
if (isset($activity['description'])) {
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Activity Description', 0, 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, $activity['description'], 0, 'L');
}

// Clear any previous output
ob_clean();

// Output the PDF
$pdf->Output('community_service_report_'.$activity_id.'.pdf', 'I');
exit;
?>