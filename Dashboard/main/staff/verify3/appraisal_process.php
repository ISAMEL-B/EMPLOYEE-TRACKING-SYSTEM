<?php
session_start();
require_once '../../head/approve/config.php';

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../../login.php");
    exit();
}

$user_id = $_SESSION['staff_id'];
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Initialize database connection
// $db = new Database();
// $conn = $db->getConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Save form data
        $page = (int)$_POST['page'];
        $data = json_encode($_POST['data']);
        
        // Check if progress record exists
        $stmt = $conn->prepare("SELECT progress_id FROM user_progress WHERE user_id = ? AND page_number = ?");
        $stmt->execute([$user_id, $page]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE user_progress SET data = ?, updated_at = NOW() WHERE progress_id = ?");
            $stmt->execute([$data, $exists['progress_id']]);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO user_progress (user_id, page_number, data) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $page, $data]);
        }
        
        // If this is the final submission (page 5)
        if ($page === 5 && isset($_POST['final_submit'])) {
            // Mark all pages as completed
            $stmt = $conn->prepare("UPDATE user_progress SET status = 'completed' WHERE user_id = ?");
            $stmt->execute([$user_id]);
            
            // Process the full submission (save to respective tables)
            processFullSubmission($user_id, $conn);
            
            $_SESSION['success_message'] = "Your appraisal has been successfully submitted!";
            header("Location: appraisal_process.php?page=5");
            exit();
        }
        
        // Return success response for AJAX
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        }
        
        // Redirect to next page
        $next_page = min($page + 1, 5);
        header("Location: appraisal_process.php?page=$next_page");
        exit();
        
    } catch (PDOException $e) {
        // Handle database errors
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit();
        }
        $_SESSION['error_message'] = "Error saving your data: " . $e->getMessage();
    }
}

// Function to process full submission
function processFullSubmission($user_id, $conn) {
    // Get all saved data
    $stmt = $conn->prepare("SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number");
    $stmt->execute([$user_id]);
    $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $full_data = [];
    foreach ($pages as $page) {
        $full_data[$page['page_number']] = json_decode($page['data'], true);
    }
    
    // Process biodata (page 1)
    if (isset($full_data[1])) {
        $biodata = $full_data[1];
        // Update staff table
        $stmt = $conn->prepare("UPDATE staff SET 
            first_name = ?, last_name = ?, email = ?, phone_number = ?, 
            scholar_type = ?, department_id = ?, years_of_experience = ?
            WHERE staff_id = ?");
        $stmt->execute([
            $biodata['first_name'], $biodata['last_name'], $biodata['email'], 
            $biodata['phone_number'], $biodata['scholar_type'], $biodata['department_id'], 
            $biodata['years_of_experience'], $user_id
        ]);
        
        // Handle photo upload if exists
        if (!empty($biodata['photo_path'])) {
            $stmt = $conn->prepare("UPDATE staff SET photo_path = ? WHERE staff_id = ?");
            $stmt->execute([$biodata['photo_path'], $user_id]);
        }
    }
    
    // Process degrees (page 2)
    if (isset($full_data[2]['degrees'])) {
        // First delete existing degrees for this user
        $conn->prepare("DELETE FROM degrees WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new degrees
        $stmt = $conn->prepare("INSERT INTO degrees 
            (staff_id, degree_name, degree_classification, institution, year_obtained, verification_status) 
            VALUES (?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[2]['degrees'] as $degree) {
            $stmt->execute([
                $user_id, $degree['degree_name'], $degree['degree_classification'], 
                $degree['institution'], $degree['year_obtained']
            ]);
        }
    }
    
    // Process publications (page 3)
    if (isset($full_data[3]['publications'])) {
        // Delete existing publications
        $conn->prepare("DELETE FROM publications WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new publications
        $stmt = $conn->prepare("INSERT INTO publications 
            (staff_id, publication_type, role, title, journal_name, publication_date, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[3]['publications'] as $pub) {
            $pub_date = date('Y-m-d', strtotime($pub['publication_date']));
            $stmt->execute([
                $user_id, $pub['publication_type'], $pub['role'], 
                $pub['title'], $pub['journal_name'], $pub_date
            ]);
        }
    }
    
    // Process grants (page 4)
    if (isset($full_data[4]['grants'])) {
        // Delete existing grants
        $conn->prepare("DELETE FROM grants WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new grants
        $stmt = $conn->prepare("INSERT INTO grants 
            (staff_id, grant_name, funding_agency, grant_amount, grant_year, role, description, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[4]['grants'] as $grant) {
            $stmt->execute([
                $user_id, $grant['grant_name'], $grant['funding_agency'], 
                $grant['grant_amount'], $grant['grant_year'], $grant['role'], 
                $grant['description']
            ]);
        }
    }
    
    // Process activities (page 5)
    if (isset($full_data[5]['activities'])) {
        // Delete existing activities
        $conn->prepare("DELETE FROM academicactivities WHERE staff_id = ?")->execute([$user_id]);
        
        // Insert new activities
        $stmt = $conn->prepare("INSERT INTO academicactivities 
            (staff_id, activity_type, title, role, location, description, verification_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'approved')");
        
        foreach ($full_data[5]['activities'] as $activity) {
            $stmt->execute([
                $user_id, $activity['activity_type'], $activity['title'], 
                $activity['role'], $activity['location'], $activity['description']
            ]);
        }
    }
}

// Get user's current progress
$progress = [];
$stmt = $conn->prepare("SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number");
$stmt->execute([$user_id]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $progress[$row['page_number']] = json_decode($row['data'], true);
}

// Get user data from staff table
$stmt = $conn->prepare("SELECT * FROM staff WHERE staff_id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Get departments for dropdown
$departments = [];
$stmt = $conn->query("SELECT * FROM departments");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $departments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Appraisal Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <style>
        .progress-step {
            cursor: pointer;
        }
        .progress-step.completed .step-number {
            background-color: var(--primary-green);
            color: white;
        }
        .progress-step.active .step-number {
            background-color: var(--primary-blue);
            color: white;
        }
        .progress-step.pending .step-number {
            background-color: #e9ecef;
            color: #6c757d;
        }
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 5px;
            font-weight: bold;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .is-invalid {
            border-color: #dc3545;
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <!-- Your existing HTML structure remains the same until the form sections -->
    
    <!-- Section 1: Biodata -->
    <div class="form-section <?= $current_page === 1 ? 'active' : '' ?>" id="section-1">
        <h2 class="form-header">1. Personal Biodata <span class="section-counter">(Step 1 of 5)</span></h2>
        <form id="biodata-form" onsubmit="return saveForm(1)">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                           value="<?= htmlspecialchars($user_data['first_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                           value="<?= htmlspecialchars($user_data['last_name'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Rest of your form fields with values populated from $user_data or $progress -->
            
            <div class="nav-buttons">
                <button type="button" class="btn btn-secondary" disabled>Previous</button>
                <button type="submit" class="btn btn-primary">Save & Continue</button>
            </div>
        </form>
    </div>

    <!-- Similar structure for other sections (2-5) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Global variables
    let currentStep = <?= $current_page ?>;
    const totalSteps = 5;
    const userId = <?= $user_id ?>;
    
    // Initialize form with saved data
    document.addEventListener('DOMContentLoaded', function() {
        // Populate form fields from PHP $progress data
        <?php if (isset($progress[1])): ?>
        populateForm('biodata-form', <?= json_encode($progress[1]) ?>);
        <?php endif; ?>
        
        // Similar for other sections...
        
        // Initialize progress UI
        updateProgressUI();
    });
    
    // Function to save form data via AJAX
    async function saveForm(page, isFinal = false) {
        try {
            const form = document.querySelector(`#section-${page} form`);
            const formData = new FormData(form);
            const data = {};
            
            formData.forEach((value, key) => {
                // Handle array fields (degrees, publications, etc.)
                if (key.includes('[')) {
                    const matches = key.match(/(\w+)\[(\d+)\]\[(\w+)\]/);
                    if (matches) {
                        const arrayName = matches[1];
                        const index = matches[2];
                        const fieldName = matches[3];
                        
                        if (!data[arrayName]) data[arrayName] = [];
                        if (!data[arrayName][index]) data[arrayName][index] = {};
                        data[arrayName][index][fieldName] = value;
                    }
                } else {
                    data[key] = value;
                }
            });
            
            // Add photo if uploaded
            const photoInput = document.getElementById('photo');
            if (photoInput.files.length > 0) {
                const photoFile = photoInput.files[0];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    data.photo_path = e.target.result;
                    sendData(page, data, isFinal);
                };
                reader.readAsDataURL(photoFile);
            } else {
                await sendData(page, data, isFinal);
            }
            
            return false;
        } catch (error) {
            console.error('Error saving form:', error);
            Swal.fire('Error', 'An error occurred while saving your data.', 'error');
            return false;
        }
    }
    
    async function sendData(page, data, isFinal) {
        const payload = {
            page: page,
            data: data,
            ajax: true
        };
        
        if (isFinal) {
            payload.final_submit = true;
        }
        
        const response = await fetch('appraisal_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(payload)
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (isFinal) {
                // Show success modal
                const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                modal.show();
            } else {
                // Go to next step
                const nextStep = Math.min(page + 1, totalSteps);
                window.location.href = `appraisal_process.php?page=${nextStep}`;
            }
        } else {
            Swal.fire('Error', result.error || 'Failed to save data', 'error');
        }
    }
    
    function populateForm(formId, data) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        // Handle simple fields
        for (const key in data) {
            if (Array.isArray(data[key])) continue;
            
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = data[key];
                } else {
                    input.value = data[key];
                }
            }
        }
        
        // Handle array fields (degrees, publications, etc.)
        for (const arrayName in data) {
            if (Array.isArray(data[arrayName])) {
                // Remove all but the first entry (template)
                const container = document.getElementById(`${arrayName}-container`);
                if (container) {
                    while (container.children.length > 1) {
                        container.lastChild.remove();
                    }
                    
                    // Add entries for each item in the array
                    for (let i = 0; i < data[arrayName].length; i++) {
                        if (i > 0) {
                            // Click the "Add" button to create new entries
                            document.getElementById(`add-${arrayName}`).click();
                        }
                        
                        // Populate the entry
                        const entry = container.children[i];
                        for (const field in data[arrayName][i]) {
                            const input = entry.querySelector(`[name="${arrayName}[${i}][${field}]"]`);
                            if (input) {
                                input.value = data[arrayName][i][field];
                            }
                        }
                    }
                }
            }
        }
    }
    
    function updateProgressUI() {
        // Update progress steps
        document.querySelectorAll('.progress-step').forEach(step => {
            const stepNum = parseInt(step.dataset.step);
            step.classList.remove('active', 'completed', 'pending');
            
            if (stepNum < currentStep) {
                step.classList.add('completed');
            } else if (stepNum === currentStep) {
                step.classList.add('active');
            } else {
                step.classList.add('pending');
            }
        });
        
        // Update progress bar
        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.querySelector('.progress-bar').style.width = `${progressPercentage}%`;
    }
    
    function goToStep(stepNumber) {
        if (stepNumber < currentStep) {
            // Allow going back to any previous step
            window.location.href = `appraisal_process.php?page=${stepNumber}`;
        } else if (stepNumber === currentStep + 1) {
            // Only allow going forward one step at a time after validation
            document.querySelector(`#section-${currentStep} form`).dispatchEvent(new Event('submit'));
        } else {
            Swal.fire('Info', 'Please complete the current section before proceeding.', 'info');
        }
    }
    
    function goToPreviousStep() {
        if (currentStep > 1) {
            window.location.href = `appraisal_process.php?page=${currentStep - 1}`;
        }
    }
    
    function submitFinalForm() {
        Swal.fire({
            title: 'Submit Appraisal?',
            text: 'Are you sure you want to submit your appraisal? You won\'t be able to make changes after submission.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary-green)',
            cancelButtonColor: 'var(--primary-blue)',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                saveForm(5, true);
            }
        });
    }
    
    // Your existing dynamic form element setup functions...

    function showAlert(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Attention',
            text: message,
            confirmButtonColor: 'var(--primary-blue)'
        });
    }

    function setupDynamicFormElements() {
        // Add degree entry
        document.getElementById('add-degree').addEventListener('click', function() {
            const container = document.getElementById('degrees-container');
            const index = container.children.length;

            const degreeEntry = document.createElement('div');
            degreeEntry.className = 'degree-entry mb-4 p-3 border rounded';
            degreeEntry.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="degree_name_${index}" class="form-label">Degree Name</label>
                        <input type="text" class="form-control degree-name" id="degree_name_${index}" name="degrees[${index}][degree_name]" required>
                        <div class="invalid-feedback">Please provide the degree name.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="degree_classification_${index}" class="form-label">Classification</label>
                        <select class="form-select degree-classification" id="degree_classification_${index}" name="degrees[${index}][degree_classification]" required>
                            <option value="" selected disabled>Select classification</option>
                            <option value="First Class">First Class</option>
                            <option value="Second Class Upper">Second Class Upper</option>
                            <option value="Second Class Lower">Second Class Lower</option>
                            <option value="Third Class">Third Class</option>
                            <option value="Pass">Pass</option>
                            <option value="Distinction">Distinction</option>
                            <option value="Merit">Merit</option>
                            <option value="N/A">Not Applicable</option>
                        </select>
                        <div class="invalid-feedback">Please select the degree classification.</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="institution_${index}" class="form-label">Institution</label>
                        <input type="text" class="form-control institution" id="institution_${index}" name="degrees[${index}][institution]" required>
                        <div class="invalid-feedback">Please provide the institution name.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="year_obtained_${index}" class="form-label">Year Obtained</label>
                        <input type="number" class="form-control year-obtained" id="year_obtained_${index}" name="degrees[${index}][year_obtained]" min="1900" max="2099" required>
                        <div class="invalid-feedback">Please provide a valid year (1900-2099).</div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-danger btn-sm remove-degree">Remove</button>
            `;

            container.appendChild(degreeEntry);

            // Scroll to new entry
            setTimeout(() => {
                degreeEntry.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 100);
        });

        // Add publication entry
        document.getElementById('add-publication').addEventListener('click', function() {
            const container = document.getElementById('publications-container');
            const index = container.children.length;

            const publicationEntry = document.createElement('div');
            publicationEntry.className = 'publication-entry mb-4 p-3 border rounded';
            publicationEntry.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="publication_type_${index}" class="form-label">Publication Type</label>
                        <select class="form-select publication-type" id="publication_type_${index}" name="publications[${index}][publication_type]" required>
                            <option value="" selected disabled>Select type</option>
                            <option value="Journal Article">Journal Article</option>
                            <option value="Conference Paper">Conference Paper</option>
                            <option value="Book">Book</option>
                            <option value="Book Chapter">Book Chapter</option>
                            <option value="Technical Report">Technical Report</option>
                            <option value="Other">Other</option>
                        </select>
                        <div class="invalid-feedback">Please select the publication type.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="role_${index}" class="form-label">Your Role</label>
                        <input type="text" class="form-control role" id="role_${index}" name="publications[${index}][role]" placeholder="Author, Co-author, Editor, etc." required>
                        <div class="invalid-feedback">Please specify your role.</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label for="title_${index}" class="form-label">Title</label>
                        <input type="text" class="form-control title" id="title_${index}" name="publications[${index}][title]" required>
                        <div class="invalid-feedback">Please provide the publication title.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="publication_date_${index}" class="form-label">Publication Date</label>
                        <input type="date" class="form-control publication-date" id="publication_date_${index}" name="publications[${index}][publication_date]" required>
                        <div class="invalid-feedback">Please provide the publication date.</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="journal_name_${index}" class="form-label">Journal/Publisher Name</label>
                        <input type="text" class="form-control journal-name" id="journal_name_${index}" name="publications[${index}][journal_name]">
                    </div>
                    <div class="col-md-6">
                        <label for="doi_${index}" class="form-label">DOI/ISBN (if available)</label>
                        <input type="text" class="form-control doi" id="doi_${index}" name="publications[${index}][doi]" placeholder="e.g., 10.1234/abc123">
                    </div>
                </div>
                
                <button type="button" class="btn btn-danger btn-sm remove-publication">Remove</button>
            `;

            container.appendChild(publicationEntry);

            // Scroll to new entry
            setTimeout(() => {
                publicationEntry.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 100);
        });

        // Add grant entry
        document.getElementById('add-grant').addEventListener('click', function() {
            const container = document.getElementById('grants-container');
            const index = container.children.length;

            const grantEntry = document.createElement('div');
            grantEntry.className = 'grant-entry mb-4 p-3 border rounded';
            grantEntry.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="grant_name_${index}" class="form-label">Grant Name</label>
                        <input type="text" class="form-control grant-name" id="grant_name_${index}" name="grants[${index}][grant_name]" required>
                        <div class="invalid-feedback">Please provide the grant name.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="funding_agency_${index}" class="form-label">Funding Agency</label>
                        <input type="text" class="form-control funding-agency" id="funding_agency_${index}" name="grants[${index}][funding_agency]" required>
                        <div class="invalid-feedback">Please provide the funding agency.</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="grant_amount_${index}" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control grant-amount" id="grant_amount_${index}" name="grants[${index}][grant_amount]" min="0" step="0.01" required>
                        </div>
                        <div class="invalid-feedback">Please provide the grant amount.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="grant_year_${index}" class="form-label">Year Awarded</label>
                        <input type="number" class="form-control grant-year" id="grant_year_${index}" name="grants[${index}][grant_year]" min="1900" max="2099" required>
                        <div class="invalid-feedback">Please provide a valid year (1900-2099).</div>
                    </div>
                    <div class="col-md-4">
                        <label for="role_${index}" class="form-label">Your Role</label>
                        <input type="text" class="form-control role" id="role_${index}" name="grants[${index}][role]" placeholder="PI, Co-PI, Researcher, etc." required>
                        <div class="invalid-feedback">Please specify your role.</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description_${index}" class="form-label">Brief Description</label>
                    <textarea class="form-control description" id="description_${index}" name="grants[${index}][description]" rows="2"></textarea>
                </div>
                
                <button type="button" class="btn btn-danger btn-sm remove-grant">Remove</button>
            `;

            container.appendChild(grantEntry);

            // Scroll to new entry
            setTimeout(() => {
                grantEntry.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 100);
        });

        // Add activity entry
        document.getElementById('add-activity').addEventListener('click', function() {
            const container = document.getElementById('activities-container');
            const index = container.children.length;

            const activityEntry = document.createElement('div');
            activityEntry.className = 'activity-entry mb-4 p-3 border rounded';
            activityEntry.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="activity_type_${index}" class="form-label">Activity Type</label>
                        <select class="form-select activity-type" id="activity_type_${index}" name="activities[${index}][activity_type]" required>
                            <option value="" selected disabled>Select type</option>
                            <option value="Conference">Conference</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Symposium">Symposium</option>
                            <option value="Invited Talk">Invited Talk</option>
                            <option value="Panel Discussion">Panel Discussion</option>
                            <option value="Other">Other</option>
                        </select>
                        <div class="invalid-feedback">Please select the activity type.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="title_${index}" class="form-label">Title/Event Name</label>
                        <input type="text" class="form-control title" id="title_${index}" name="activities[${index}][title]" required>
                        <div class="invalid-feedback">Please provide the activity title.</div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="date_${index}" class="form-label">Date</label>
                        <input type="date" class="form-control date" id="date_${index}" name="activities[${index}][date]" required>
                        <div class="invalid-feedback">Please provide the activity date.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="role_${index}" class="form-label">Your Role</label>
                        <input type="text" class="form-control role" id="role_${index}" name="activities[${index}][role]" placeholder="Speaker, Organizer, Participant, etc." required>
                        <div class="invalid-feedback">Please specify your role.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="location_${index}" class="form-label">Location</label>
                        <input type="text" class="form-control location" id="location_${index}" name="activities[${index}][location]" required>
                        <div class="invalid-feedback">Please provide the activity location.</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description_${index}" class="form-label">Description</label>
                    <textarea class="form-control description" id="description_${index}" name="activities[${index}][description]" rows="2"></textarea>
                </div>
                
                <button type="button" class="btn btn-danger btn-sm remove-activity">Remove</button>
            `;

            container.appendChild(activityEntry);

            // Scroll to new entry
            setTimeout(() => {
                activityEntry.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 100);
        });

        // Remove entry handlers
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-degree')) {
                const entry = e.target.closest('.degree-entry');
                if (document.querySelectorAll('.degree-entry').length > 1) {
                    entry.remove();
                } else {
                    showAlert('You must have at least one degree entry.');
                }
            }

            if (e.target.classList.contains('remove-publication')) {
                const entry = e.target.closest('.publication-entry');
                if (document.querySelectorAll('.publication-entry').length > 1) {
                    entry.remove();
                } else {
                    showAlert('You must have at least one publication entry.');
                }
            }

            if (e.target.classList.contains('remove-grant')) {
                const entry = e.target.closest('.grant-entry');
                if (document.querySelectorAll('.grant-entry').length > 1) {
                    entry.remove();
                } else {
                    showAlert('You must have at least one grant entry.');
                }
            }

            if (e.target.classList.contains('remove-activity')) {
                const entry = e.target.closest('.activity-entry');
                if (document.querySelectorAll('.activity-entry').length > 1) {
                    entry.remove();
                } else {
                    showAlert('You must have at least one activity entry.');
                }
            }
        });
    }
    </script>
</body>
</html>