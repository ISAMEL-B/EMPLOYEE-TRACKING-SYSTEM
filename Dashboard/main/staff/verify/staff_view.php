<?php 
session_start();
// Database connection
include '../../head/approve/config.php';

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: ../../login.php");
    exit();
}

// Get the current page filename
$current_pag = basename($_SERVER['PHP_SELF']);

// Get current page from query string or default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1 || $current_page > 5) {
    $current_page = 1;
}

// Get user data using prepared statement
$user_id = $_SESSION['staff_id'];
$user_sql = "SELECT * FROM staff WHERE staff_id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$stmt->close();

// Get departments
$dept_sql = "SELECT * FROM departments";
$dept_result = $conn->query($dept_sql);
$departments = [];
while ($row = $dept_result->fetch_assoc()) {
    $departments[] = $row;
}

// Get saved progress using prepared statement
$progress_sql = "SELECT page_number, data FROM user_progress WHERE user_id = ? ORDER BY page_number";
$stmt = $conn->prepare($progress_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$progress_result = $stmt->get_result();
$progress_data = [];
while ($row = $progress_result->fetch_assoc()) {
    $decoded_data = json_decode($row['data'], true);
    if ($decoded_data !== null) {
        $progress_data[$row['page_number']] = $decoded_data;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Appraisal Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../verify3/style/style.css">
    <style>
    :root {
        --primary-green: #28a745;
        --primary-blue: #007bff;
        --danger-red: #dc3545;
        --warning-yellow: #ffc107;
    }

    .progress-step {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .progress-step.completed .step-number {
        background-color: var(--primary-green);
        color: white;
    }

    .progress-step.active .step-number {
        background-color: var(--primary-blue);
        color: white;
        transform: scale(1.1);
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
        transition: all 0.3s ease;
    }

    .form-section {
        display: none;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .form-section.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .is-invalid {
        border-color: var(--danger-red);
        animation: shake 0.5s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    .form-header {
        color: var(--primary-blue);
        border-bottom: 2px solid var(--primary-green);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .section-counter {
        font-size: 0.8em;
        color: #6c757d;
    }

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #dee2e6;
    }

    .entry-container {
        margin-bottom: 30px;
    }

    .entry-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8f9fa;
        position: relative;
    }

    .remove-entry {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .add-entry-btn {
        margin-bottom: 20px;
    }

    .progress-container {
        margin-bottom: 40px;
    }

    .progress {
        height: 8px;
        margin-bottom: 20px;
    }

    .progress-bar {
        background-color: var(--primary-green);
        transition: width 0.5s ease;
    }

    .step-label {
        font-size: 0.9rem;
        color: #495057;
    }

    .alert-danger {
        animation: fadeIn 0.3s ease;
    }

    .photo-preview {
        max-width: 150px;
        max-height: 150px;
        display: block;
        margin: 10px 0;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }

    .required-field::after {
        content: " *";
        color: var(--danger-red);
    }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <!-- Empty space for navbar (10% height) -->
        <div class="navbar-space"></div>
        <?php include '../../bars/nav_bar.php'; ?>

        <!-- Sidebar -->
        <div class="sidebar-space"></div>
        <?php include '../../bars/side_bar.php'; ?>

        <div class="content-area">
            <div class="container py-5">
                <h1 class="text-center mb-4" style="color: var(--primary-green);">Staff Appraisal Form</h1>

                <!-- Progress Container -->
                <div class="progress-container">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                            style="width: <?= (($current_page - 1) / 4) * 100 ?>%;"
                            aria-valuenow="<?= (($current_page - 1) / 4) * 100 ?>" aria-valuemin="0"
                            aria-valuemax="100"></div>
                    </div>

                    <div class="row text-center">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <div class="col progress-step <?= $i < $current_page ? 'completed' : ($i == $current_page ? 'active' : 'pending') ?>"
                            data-step="<?= $i ?>" onclick="goToStep(<?= $i ?>)">
                            <div class="step-number"><?= $i ?></div>
                            <div class="step-label">
                                <?= 
                                    $i == 1 ? 'Biodata' : 
                                    ($i == 2 ? 'Degrees' : 
                                    ($i == 3 ? 'Publications' : 
                                    ($i == 4 ? 'Grants' : 'Activities')))
                                ?>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Section 1: Biodata -->
                <div class="form-section <?= $current_page == 1 ? 'active' : '' ?>" id="section-1">
                    <h2 class="form-header">1. Personal Biodata <span class="section-counter">(Step 1 of 5)</span></h2>

                    <form id="biodata-form" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label required-field">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="<?= htmlspecialchars($progress_data[1]['first_name'] ?? $user_data['first_name'] ?? '') ?>"
                                    required>
                                <div class="invalid-feedback">Please provide your first name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label required-field">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="<?= htmlspecialchars($progress_data[1]['last_name'] ?? $user_data['last_name'] ?? '') ?>"
                                    required>
                                <div class="invalid-feedback">Please provide your last name.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($progress_data[1]['email'] ?? $user_data['email'] ?? '') ?>"
                                    required>
                                <div class="invalid-feedback">Please provide a valid email address.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label required-field">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                    value="<?= htmlspecialchars($progress_data[1]['phone_number'] ?? $user_data['phone_number'] ?? '') ?>"
                                    required>
                                <div class="invalid-feedback">Please provide your phone number.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label required-field">Employee ID</label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id"
                                    value="<?= htmlspecialchars($progress_data[1]['employee_id'] ?? $user_data['employee_id'] ?? '') ?>"
                                    required>
                                <div class="invalid-feedback">Please provide your employee ID.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="scholar_type" class="form-label required-field">Scholar Type</label>
                                <select class="form-select" id="scholar_type" name="scholar_type" required>
                                    <option value="" selected disabled>Select scholar type</option>
                                    <option value="Professor"
                                        <?= ($progress_data[1]['scholar_type'] ?? $user_data['scholar_type'] ?? '') == 'Professor' ? 'selected' : '' ?>>
                                        Professor</option>
                                    <option value="Associate Professor"
                                        <?= ($progress_data[1]['scholar_type'] ?? $user_data['scholar_type'] ?? '') == 'Associate Professor' ? 'selected' : '' ?>>
                                        Associate Professor</option>
                                    <option value="Assistant Professor"
                                        <?= ($progress_data[1]['scholar_type'] ?? $user_data['scholar_type'] ?? '') == 'Assistant Professor' ? 'selected' : '' ?>>
                                        Assistant Professor</option>
                                    <option value="Lecturer"
                                        <?= ($progress_data[1]['scholar_type'] ?? $user_data['scholar_type'] ?? '') == 'Lecturer' ? 'selected' : '' ?>>
                                        Lecturer</option>
                                    <option value="Researcher"
                                        <?= ($progress_data[1]['scholar_type'] ?? $user_data['scholar_type'] ?? '') == 'Researcher' ? 'selected' : '' ?>>
                                        Researcher</option>
                                </select>
                                <div class="invalid-feedback">Please select your scholar type.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="department_id" class="form-label required-field">Department</label>
                                <select class="form-select" id="department_id" name="department_id" required>
                                    <option value="" selected disabled>Select department</option>
                                    <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['department_id'] ?>"
                                        <?= ($progress_data[1]['department_id'] ?? $user_data['department_id'] ?? '') == $dept['department_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['department_name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select your department.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="years_of_experience" class="form-label required-field">Years of
                                    Experience</label>
                                <input type="number" class="form-control" id="years_of_experience"
                                    name="years_of_experience"
                                    value="<?= htmlspecialchars($progress_data[1]['years_of_experience'] ?? $user_data['years_of_experience'] ?? '') ?>"
                                    min="0" required>
                                <div class="invalid-feedback">Please provide your years of experience.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Profile Photo</label>
                            <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                            <?php if (!empty($progress_data[1]['photo_path']) || !empty($user_data['photo_path'])): ?>
                            <div class="mt-2">
                                <img src="<?= htmlspecialchars($progress_data[1]['photo_path'] ?? $user_data['photo_path']) ?>"
                                    class="photo-preview" id="photo-preview">
                                <small class="text-muted">Current photo:
                                    <?= htmlspecialchars(basename($progress_data[1]['photo_path'] ?? $user_data['photo_path'])) ?>
                                </small>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary" disabled>Previous</button>
                            <button type="button" class="btn btn-primary" onclick="saveForm(1)">Save & Continue</button>
                        </div>
                    </form>
                </div>

                <!-- Section 2: Degrees -->
                <div class="form-section <?= $current_page == 2 ? 'active' : '' ?>" id="section-2">
                    <h2 class="form-header">2. Academic Degrees <span class="section-counter">(Step 2 of 5)</span></h2>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Please add all your academic degrees, starting with the
                        highest.
                    </div>

                    <form id="degrees-form">
                        <div id="degrees-container" class="entry-container">
                            <?php if (!empty($progress_data[2]['degrees'])): ?>
                            <?php foreach ($progress_data[2]['degrees'] as $index => $degree): ?>
                            <div class="degree-entry entry-card">
                                <button type="button" class="btn btn-danger btn-sm remove-degree remove-entry">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="degree_name_<?= $index ?>" class="form-label required-field">Degree
                                            Name</label>
                                        <input type="text" class="form-control degree-name"
                                            id="degree_name_<?= $index ?>" name="degrees[<?= $index ?>][degree_name]"
                                            value="<?= htmlspecialchars($degree['degree_name'] ?? '') ?>" required>
                                        <div class="invalid-feedback">Please provide the degree name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="degree_classification_<?= $index ?>"
                                            class="form-label required-field">Classification</label>

                                        <?php
                                                // Fetch classifications from database
                                                $classQuery = "SELECT DISTINCT degree_classification FROM degrees WHERE degree_classification IS NOT NULL AND degree_classification != '' ORDER BY degree_classification";
                                                $classResult = $conn->query($classQuery);

                                                // Use database values or fallback to defaults
                                                $classifications = [];
                                                if ($classResult && $classResult->num_rows > 0) {
                                                    while ($row = $classResult->fetch_assoc()) {
                                                        $classifications[] = $row['degree_classification'];
                                                    }
                                                } else {
                                                    $classifications = ['First Class', 'Second Class Upper', 'Second Class Lower', 'Third Class', 'Pass', 'Distinction', 'Merit', 'N/A'];
                                                }
                                        ?>

                                        <select class="form-select degree-classification"
                                            id="degree_classification_<?= $index ?>"
                                            name="degrees[<?= $index ?>][degree_classification]" required>
                                            <option value="" disabled>Select classification</option>
                                            <?php foreach ($classifications as $classification): ?>
                                            <option value="<?= htmlspecialchars($classification) ?>"
                                                <?= ($degree['degree_classification'] ?? '') == $classification ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($classification) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select the degree classification.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="institution_<?= $index ?>"
                                            class="form-label required-field">Institution</label>
                                        <input type="text" class="form-control institution"
                                            id="institution_<?= $index ?>" name="degrees[<?= $index ?>][institution]"
                                            value="<?= htmlspecialchars($degree['institution'] ?? '') ?>" required>
                                        <div class="invalid-feedback">Please provide the institution name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="year_obtained_<?= $index ?>" class="form-label required-field">Year
                                            Obtained</label>
                                        <input type="number" class="form-control year-obtained"
                                            id="year_obtained_<?= $index ?>"
                                            name="degrees[<?= $index ?>][year_obtained]"
                                            value="<?= htmlspecialchars($degree['year_obtained'] ?? '') ?>" min="1900"
                                            max="<?= date('Y') ?>" required>
                                        <div class="invalid-feedback">Please provide a valid year
                                            (1900-<?= date('Y') ?>).</div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <!-- Default empty degree entry -->
                            <div class="degree-entry entry-card">
                                <button type="button" class="btn btn-danger btn-sm remove-degree remove-entry">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="degree_name_0" class="form-label required-field">Degree Name</label>
                                        <input type="text" class="form-control degree-name" id="degree_name_0"
                                            name="degrees[0][degree_name]" required>
                                        <div class="invalid-feedback">Please provide the degree name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="degree_classification_0"
                                            class="form-label required-field">Classification</label>
                                        <select class="form-select degree-classification" id="degree_classification_0"
                                            name="degrees[0][degree_classification]" required>
                                            <option value="" selected disabled>Select classification</option>
                                            <?php foreach ($classifications as $classification): ?>
                                            <option value="<?= $classification ?>"><?= $classification ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select the degree classification.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="institution_0" class="form-label required-field">Institution</label>
                                        <input type="text" class="form-control institution" id="institution_0"
                                            name="degrees[0][institution]" required>
                                        <div class="invalid-feedback">Please provide the institution name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="year_obtained_0" class="form-label required-field">Year
                                            Obtained</label>
                                        <input type="number" class="form-control year-obtained" id="year_obtained_0"
                                            name="degrees[0][year_obtained]" min="1900" max="<?= date('Y') ?>" required>
                                        <div class="invalid-feedback">Please provide a valid year
                                            (1900-<?= date('Y') ?>).</div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-warning add-entry-btn" id="add-degree">
                            <i class="bi bi-plus-circle"></i> Add Another Degree
                        </button>

                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary"
                                onclick="goToPreviousStep()">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="saveForm(2)">Save & Continue</button>
                        </div>
                    </form>
                </div>

                <!-- Section 3: Publications -->
                <div class="form-section <?= $current_page == 3 ? 'active' : '' ?>" id="section-3">
                    <h2 class="form-header">3. Publications <span class="section-counter">(Step 3 of 5)</span></h2>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Please add all your publications (journal articles, conference
                        papers, books, etc.)
                    </div>

                    <form id="publications-form">
                        <?php
                            // Fetch publication types from database (do this once at the top of your file)
                            $pub_types = [];
                            $typeQuery = $conn->query("SELECT DISTINCT publication_type FROM publications 
                                                    WHERE publication_type IS NOT NULL 
                                                    AND publication_type != '' 
                                                    ORDER BY publication_type");

                            if ($typeQuery && $typeQuery->num_rows > 0) {
                                while ($row = $typeQuery->fetch_assoc()) {
                                    $pub_types[] = $row['publication_type'];
                                }
                            } else {
                                // Fallback to default values if query fails
                                $pub_types = ['Journal Article', 'Conference Paper', 'Book', 'Book Chapter', 'Technical Report', 'Other'];
                            }
                        ?>

                        <div id="publications-container" class="entry-container">
                            <?php if (!empty($progress_data[3]['publications'])): ?>
                            <?php foreach ($progress_data[3]['publications'] as $index => $publication): ?>
                            <div class="publication-entry entry-card">
                                <button type="button" class="btn btn-danger btn-sm remove-publication remove-entry">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="publication_type_<?= $index ?>"
                                            class="form-label required-field">Publication Type</label>
                                        <select class="form-select publication-type" id="publication_type_<?= $index ?>"
                                            name="publications[<?= $index ?>][publication_type]" required>
                                            <option value="" disabled>Select type</option>
                                            <?php foreach ($pub_types as $type): ?>
                                            <option value="<?= htmlspecialchars($type) ?>"
                                                <?= ($publication['publication_type'] ?? '') == $type ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($type) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select the publication type.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="role_<?= $index ?>" class="form-label required-field">Your
                                            Role</label>
                                        <input type="text" class="form-control role" id="role_<?= $index ?>"
                                            name="publications[<?= $index ?>][role]"
                                            value="<?= htmlspecialchars($publication['role'] ?? '') ?>"
                                            placeholder="Author, Co-author, Editor, etc." required>
                                        <div class="invalid-feedback">Please specify your role.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="title_<?= $index ?>" class="form-label required-field">Title</label>
                                        <input type="text" class="form-control title" id="title_<?= $index ?>"
                                            name="publications[<?= $index ?>][title]"
                                            value="<?= htmlspecialchars($publication['title'] ?? '') ?>" required>
                                        <div class="invalid-feedback">Please provide the publication title.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="publication_date_<?= $index ?>"
                                            class="form-label required-field">Publication Date</label>
                                        <input type="date" class="form-control publication-date"
                                            id="publication_date_<?= $index ?>"
                                            name="publications[<?= $index ?>][publication_date]"
                                            value="<?= htmlspecialchars($publication['publication_date'] ?? '') ?>"
                                            required>
                                        <div class="invalid-feedback">Please provide the publication date.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="journal_name_<?= $index ?>" class="form-label">Journal/Publisher
                                            Name</label>
                                        <input type="text" class="form-control journal-name"
                                            id="journal_name_<?= $index ?>"
                                            name="publications[<?= $index ?>][journal_name]"
                                            value="<?= htmlspecialchars($publication['journal_name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="doi_<?= $index ?>" class="form-label">DOI/ISBN (if
                                            available)</label>
                                        <input type="text" class="form-control doi" id="doi_<?= $index ?>"
                                            name="publications[<?= $index ?>][doi]"
                                            value="<?= htmlspecialchars($publication['doi'] ?? '') ?>"
                                            placeholder="e.g., 10.1234/abc123">
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <!-- Default empty publication entry -->
                            <div class="publication-entry entry-card">
                                <button type="button" class="btn btn-danger btn-sm remove-publication remove-entry">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="publication_type_0" class="form-label required-field">Publication
                                            Type</label>
                                        <select class="form-select publication-type" id="publication_type_0"
                                            name="publications[0][publication_type]" required>
                                            <option value="" selected disabled>Select type</option>
                                            <?php foreach ($pub_types as $type): ?>
                                            <option value="<?= htmlspecialchars($type) ?>">
                                                <?= htmlspecialchars($type) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select the publication type.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="role_0" class="form-label required-field">Your Role</label>
                                        <input type="text" class="form-control role" id="role_0"
                                            name="publications[0][role]" placeholder="Author, Co-author, Editor, etc."
                                            required>
                                        <div class="invalid-feedback">Please specify your role.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="title_0" class="form-label required-field">Title</label>
                                        <input type="text" class="form-control title" id="title_0"
                                            name="publications[0][title]" required>
                                        <div class="invalid-feedback">Please provide the publication title.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="publication_date_0" class="form-label required-field">Publication
                                            Date</label>
                                        <input type="date" class="form-control publication-date" id="publication_date_0"
                                            name="publications[0][publication_date]" required>
                                        <div class="invalid-feedback">Please provide the publication date.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="journal_name_0" class="form-label">Journal/Publisher Name</label>
                                        <input type="text" class="form-control journal-name" id="journal_name_0"
                                            name="publications[0][journal_name]">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="doi_0" class="form-label">DOI/ISBN (if available)</label>
                                        <input type="text" class="form-control doi" id="doi_0"
                                            name="publications[0][doi]" placeholder="e.g., 10.1234/abc123">
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-warning add-entry-btn" id="add-publication">
                            <i class="bi bi-plus-circle"></i> Add Another Publication
                        </button>

                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary"
                                onclick="goToPreviousStep()">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="saveForm(3)">Save & Continue</button>
                        </div>
                    </form>
                </div>

                <!-- Section 4: Grants -->
                <div class="form-section <?= $current_page == 4 ? 'active' : '' ?>" id="section-4">
                    <h2 class="form-header">4. Research Grants <span class="section-counter">(Step 4 of 5)</span></h2>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Please add all research grants you have received or
                        participated in.
                    </div>

                    <form id="grants-form">
                        <div id="grants-container" class="entry-container">
                            <?php if (!empty($progress_data[4]['grants'])): ?>
                            <?php foreach ($progress_data[4]['grants'] as $index => $grant): ?>
                            <div class="grant-entry entry-card">
                                <button type="button" class="btn btn-danger btn-sm remove-grant remove-entry">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="grant_name_<?= $index ?>" class="form-label required-field">Grant
                                            Name</label>
                                        <input type="text" class="form-control grant-name" id="grant_name_<?= $index ?>"
                                            name="grants[<?= $index ?>][grant_name]"
                                            value="<?= htmlspecialchars($grant['grant_name'] ?? '') ?>" required>
                                        <div class="invalid-feedback">Please provide the grant name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="funding_agency_<?= $index ?>"
                                            class="form-label required-field">Funding Agency</label>
                                        <input type="text" class="form-control funding-agency"
                                            id="funding_agency_<?= $index ?>"
                                            name="grants[<?= $index ?>][funding_agency]"
                                            value="<?= htmlspecialchars($grant['funding_agency'] ?? '') ?>" required>
                                        <div class="invalid-feedback">Please provide the funding agency.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="grant_amount_<?= $index ?>"
                                            class="form-label required-field">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control grant-amount"
                                                id="grant_amount_<?= $index ?>"
                                                name="grants[<?= $index ?>][grant_amount]"
                                                value="<?= htmlspecialchars($grant['grant_amount'] ?? '') ?>" min="0"
                                                step="0.01" required>
                                        </div>
                                        <div class="invalid-feedback">Please provide the grant amount.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="grant_year_<?= $index ?>" class="form-label required-field">Year
                                            Awarded</label>
                                        <input type="number" class="form-control grant-year"
                                            id="grant_year_<?= $index ?>" name="grants[<?= $index ?>][grant_year]"
                                            value="<?= htmlspecialchars($grant['grant_year'] ?? '') ?>" min="1900"
                                            max="<?= date('Y') ?>" required>
                                        <div class="invalid-feedback">Please provide a valid year
                                            (1900-<?= date('Y') ?>).</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="role_<?= $index ?>" class="form-label required-field">Your
                                            Role</label>
                                        <input type="text" class="form-control role" id="role_<?= $index ?>"
                                            name="grants[<?= $index ?>][role]"
                                            value="<?= htmlspecialchars($grant['role'] ?? '') ?>"
                                            placeholder="PI, Co-PI, Researcher, etc." required>
                                        <div class="invalid-feedback">Please specify your role.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description_<?= $index ?>" class="form-label">Brief Description</label>
                                    <textarea class="form-control description" id="description_<?= $index ?>"
                                        name="grants[<?= $index ?>][description]"
                                        rows="2"><?= htmlspecialchars($grant['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <!-- Default empty grant entry -->
                            <div class="grant-entry entry-card">
                                <button type="button" class="btn btn-danger btn-sm remove-grant remove-entry">
                                    <i class="bi bi-trash"></i>
                                </button>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="grant_name_0" class="form-label required-field">Grant Name</label>
                                        <input type="text" class="form-control grant-name" id="grant_name_0"
                                            name="grants[0][grant_name]" required>
                                        <div class="invalid-feedback">Please provide the grant name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="funding_agency_0" class="form-label required-field">Funding
                                            Agency</label>
                                        <input type="text" class="form-control funding-agency" id="funding_agency_0"
                                            name="grants[0][funding_agency]" required>
                                        <div class="invalid-feedback">Please provide the funding agency.</div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="grant_amount_0" class="form-label required-field">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control grant-amount" id="grant_amount_0"
                                                name="grants[0][grant_amount]" min="0" step="0.01" required>
                                        </div>
                                        <div class="invalid-feedback">Please provide the grant amount.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="grant_year_0" class="form-label required-field">Year Awarded</label>
                                        <input type="number" class="form-control grant-year" id="grant_year_0"
                                            name="grants[0][grant_year]" min="1900" max="<?= date('Y') ?>" required>
                                        <div class="invalid-feedback">Please provide a valid year
                                            (1900-<?= date('Y') ?>).</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="role_0" class="form-label required-field">Your Role</label>
                                        <input type="text" class="form-control role" id="role_0" name="grants[0][role]"
                                            placeholder="PI, Co-PI, Researcher, etc." required>
                                        <div class="invalid-feedback">Please specify your role.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description_0" class="form-label">Brief Description</label>
                                    <textarea class="form-control description" id="description_0"
                                        name="grants[0][description]" rows="2"></textarea>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-warning add-entry-btn" id="add-grant">
                            <i class="bi bi-plus-circle"></i> Add Another Grant
                        </button>

                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary"
                                onclick="goToPreviousStep()">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="saveForm(4)">Save & Continue</button>
                        </div>
                    </form>
                </div>

                <!-- Section 5: Activities -->
                <div class="form-section <?= $current_page == 5 ? 'active' : '' ?>" id="section-5">
                    <h2 class="form-header">5. Academic Activities <span class="section-counter">(Step 5 of 5)</span>
                    </h2>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Please add your academic activities (conferences, workshops,
                        etc.)
                    </div>

                    <form id="activities-form">
                        <?php

// Fetch activity types from database
$activity_types = [];
$query = "SELECT type_id, type_name FROM activity_types ";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $activity_types[$row['type_id']] = $row['type_name'];
    }
}
mysqli_close($conn);
?>

<div id="activities-container" class="entry-container">
    <?php if (!empty($progress_data[5]['activities'])): ?>
    <?php foreach ($progress_data[5]['activities'] as $index => $activity): ?>
    <div class="activity-entry entry-card">
        <button type="button" class="btn btn-danger btn-sm remove-activity remove-entry">
            <i class="bi bi-trash"></i>
        </button>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="activity_type_<?= $index ?>"
                    class="form-label required-field">Activity Type Name</label>
                <select class="form-select activity-type" id="activity_type_<?= $index ?>"
                    name="activities[<?= $index ?>][activity_type]" required>
                    <option value="" disabled>Select type name</option>
                    <?php foreach ($activity_types as $id => $name): ?>
                    <option value="<?= $id ?>"
                        <?= (isset($activity['activity_type']) && $activity['activity_type'] == $id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($name) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select the activity type.</div>
            </div>
            <div class="col-md-6">
                <label for="title_<?= $index ?>" class="form-label required-field">Title/Event
                    Name</label>
                <input type="text" class="form-control title" id="title_<?= $index ?>"
                    name="activities[<?= $index ?>][title]"
                    value="<?= htmlspecialchars($activity['title'] ?? '') ?>" required>
                <div class="invalid-feedback">Please provide the activity title.</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date_<?= $index ?>" class="form-label required-field">Date</label>
                <input type="date" class="form-control date" id="date_<?= $index ?>"
                    name="activities[<?= $index ?>][date]"
                    value="<?= htmlspecialchars($activity['date'] ?? '') ?>" required>
                <div class="invalid-feedback">Please provide the activity date.</div>
            </div>
            <div class="col-md-4">
                <label for="role_<?= $index ?>" class="form-label required-field">Your
                    Role</label>
                <input type="text" class="form-control role" id="role_<?= $index ?>"
                    name="activities[<?= $index ?>][role]"
                    value="<?= htmlspecialchars($activity['role'] ?? '') ?>"
                    placeholder="Speaker, Organizer, Participant, etc." required>
                <div class="invalid-feedback">Please specify your role.</div>
            </div>
            <div class="col-md-4">
                <label for="location_<?= $index ?>"
                    class="form-label required-field">Location</label>
                <input type="text" class="form-control location" id="location_<?= $index ?>"
                    name="activities[<?= $index ?>][location]"
                    value="<?= htmlspecialchars($activity['location'] ?? '') ?>" required>
                <div class="invalid-feedback">Please provide the activity location.</div>
            </div>
        </div>

        <div class="mb-3">
            <label for="description_<?= $index ?>" class="form-label">Description</label>
            <textarea class="form-control description" id="description_<?= $index ?>"
                name="activities[<?= $index ?>][description]"
                rows="2"><?= htmlspecialchars($activity['description'] ?? '') ?></textarea>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <!-- Default empty activity entry -->
    <div class="activity-entry entry-card">
        <button type="button" class="btn btn-danger btn-sm remove-activity remove-entry">
            <i class="bi bi-trash"></i>
        </button>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="activity_type_0" class="form-label required-field">Activity
                    Type</label>
                <select class="form-select activity-type" id="activity_type_0"
                    name="activities[0][activity_type]" required>
                    <option value="" selected disabled>Select type</option>
                    <?php foreach ($activity_types as $id => $name): ?>
                    <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select the activity type.</div>
            </div>
            <div class="col-md-6">
                <label for="title_0" class="form-label required-field">Title/Event Name</label>
                <input type="text" class="form-control title" id="title_0"
                    name="activities[0][title]" required>
                <div class="invalid-feedback">Please provide the activity title.</div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date_0" class="form-label required-field">Date</label>
                <input type="date" class="form-control date" id="date_0"
                    name="activities[0][date]" required>
                <div class="invalid-feedback">Please provide the activity date.</div>
            </div>
            <div class="col-md-4">
                <label for="role_0" class="form-label required-field">Your Role</label>
                <input type="text" class="form-control role" id="role_0"
                    name="activities[0][role]"
                    placeholder="Speaker, Organizer, Participant, etc." required>
                <div class="invalid-feedback">Please specify your role.</div>
            </div>
            <div class="col-md-4">
                <label for="location_0" class="form-label required-field">Location</label>
                <input type="text" class="form-control location" id="location_0"
                    name="activities[0][location]" required>
                <div class="invalid-feedback">Please provide the activity location.</div>
            </div>
        </div>

        <div class="mb-3">
            <label for="description_0" class="form-label">Description</label>
            <textarea class="form-control description" id="description_0"
                name="activities[0][description]" rows="2"></textarea>
        </div>
    </div>
    <?php endif; ?>
</div>

                        <button type="button" class="btn btn-warning add-entry-btn" id="add-activity">
                            <i class="bi bi-plus-circle"></i> Add Another Activity
                        </button>

                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary"
                                onclick="goToPreviousStep()">Previous</button>
                            <button type="button" class="btn btn-success" onclick="submitForm()">
                                <i class="bi bi-check-circle"></i> Submit Details
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Confirmation Modal -->
                <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="confirmationModalLabel">Submission Complete</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-4">
                                    <div class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                    </div>
                                    <h4 class="text-success">Thank You!</h4>
                                    <p>Your academic details have been successfully submitted.</p>
                                    <p>You can review and update your information at any time.</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Global variables
    let currentStep = <?= $current_page ?>;
    const totalSteps = 5;
    const userId = <?= $user_id ?>;
    const progressData = <?= json_encode($progress_data) ?>;
    const userData = <?= json_encode($user_data) ?>;
    const departments = <?= json_encode($departments) ?>;

    // Initialize the form when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Set up photo preview
        const photoInput = document.getElementById('photo');
        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let preview = document.getElementById('photo-preview');
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.id = 'photo-preview';
                            preview.className = 'photo-preview';
                            photoInput.parentNode.appendChild(preview);
                        }
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Set up event listeners for adding/removing entries
        setupDynamicFormElements();

        // Show current section
        showFormSection(currentStep);
        updateProgressUI();
    });

    async function saveForm(page, isFinal = false) {
        if (!validateCurrentStep()) {
            return;
        }

        try {
            const formData = collectFormData(page);
            const payload = {
                page: page,
                data: formData,
                final_submit: isFinal
            };

            // Show loading indicator
            const saveBtn = document.querySelector(`#section-${page} .btn-primary, #section-${page} .btn-success`);
            const originalBtnText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

            const response = await fetch('appraisal_backend.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            });

            const result = await response.json();
            console.log('Server response:', result); // Add this line

            if (!response.ok) {
                throw new Error(result.error || `HTTP error! status: ${response.status}`);
            }

            if (result.success) {
                if (isFinal) {
                    showSuccessModal();
                } else {
                    const nextStep = Math.min(page + 1, totalSteps);
                    window.location.href = `staff_view.php?page=${nextStep}`;
                }
            } else {
                throw new Error(result.error || 'Server returned unsuccessful response');
            }
        } catch (error) {
            console.error('Error details:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error Details',
                text: error.message,
                footer: 'Please check console for more details'
            });
        } finally {
            // Restore button state
            const saveBtn = document.querySelector(`#section-${page} .btn-primary, #section-${page} .btn-success`);
            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnText;
            }
        }
    }

    // Function to collect form data
    function collectFormData(page) {
        const form = document.querySelector(`#section-${page} form`);
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};

        // Process simple fields
        formData.forEach((value, key) => {
            if (!key.includes('[')) {
                data[key] = value;
            }
        });

        // Process array fields (degrees, publications, etc.)
        if (page === 2) {
            data.degrees = collectArrayFields('degree-entry');
        } else if (page === 3) {
            data.publications = collectArrayFields('publication-entry');
        } else if (page === 4) {
            data.grants = collectArrayFields('grant-entry');
        } else if (page === 5) {
            data.activities = collectArrayFields('activity-entry');
        }

        // Handle file upload
        if (page === 1 && document.getElementById('photo').files.length > 0) {
            // In a real implementation, you would upload the file separately
            // and store the path. For this example, we'll just note that a file was selected
            data.photo_uploaded = true;
        }

        return data;
    }

    // Function to collect data from dynamic array fields
    function collectArrayFields(entryClass) {
        const entries = [];
        const containers = document.querySelectorAll(`.${entryClass}`);

        containers.forEach((container, index) => {
            const entry = {};
            const inputs = container.querySelectorAll('input, select, textarea');

            inputs.forEach(input => {
                const matches = input.name.match(/\[(\d+)\]\[(\w+)\]/);
                if (matches) {
                    const field = matches[2];
                    entry[field] = input.value;
                }
            });

            entries.push(entry);
        });

        return entries;
    }

    // Function to validate current step
    function validateCurrentStep() {
        let isValid = true;
        let missingFields = [];

        // Clear previous validation errors
        clearValidationErrors();

        switch (currentStep) {
            case 1: // Biodata
                if (!document.getElementById('first_name').value.trim()) {
                    missingFields.push('First Name');
                    document.getElementById('first_name').classList.add('is-invalid');
                }
                if (!document.getElementById('last_name').value.trim()) {
                    missingFields.push('Last Name');
                    document.getElementById('last_name').classList.add('is-invalid');
                }
                if (!document.getElementById('email').value.trim()) {
                    missingFields.push('Email');
                    document.getElementById('email').classList.add('is-invalid');
                } else if (!isValidEmail(document.getElementById('email').value)) {
                    missingFields.push('Valid Email');
                    document.getElementById('email').classList.add('is-invalid');
                }
                if (!document.getElementById('phone_number').value.trim()) {
                    missingFields.push('Phone Number');
                    document.getElementById('phone_number').classList.add('is-invalid');
                }
                if (!document.getElementById('employee_id').value.trim()) {
                    missingFields.push('Employee ID');
                    document.getElementById('employee_id').classList.add('is-invalid');
                }
                if (!document.getElementById('scholar_type').value) {
                    missingFields.push('Scholar Type');
                    document.getElementById('scholar_type').classList.add('is-invalid');
                }
                if (!document.getElementById('department_id').value) {
                    missingFields.push('Department');
                    document.getElementById('department_id').classList.add('is-invalid');
                }
                if (!document.getElementById('years_of_experience').value.trim()) {
                    missingFields.push('Years of Experience');
                    document.getElementById('years_of_experience').classList.add('is-invalid');
                }
                break;

            case 2: // Degrees
                const degreeNames = document.querySelectorAll('.degree-name');
                const degreeClassifications = document.querySelectorAll('.degree-classification');
                const institutions = document.querySelectorAll('.institution');
                const yearsObtained = document.querySelectorAll('.year-obtained');

                if (degreeNames.length === 0) {
                    missingFields.push('At least one degree');
                } else {
                    degreeNames.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Degree #${index + 1} name`);
                            input.classList.add('is-invalid');
                        }
                    });

                    degreeClassifications.forEach((select, index) => {
                        if (!select.value) {
                            missingFields.push(`Degree #${index + 1} classification`);
                            select.classList.add('is-invalid');
                        }
                    });

                    institutions.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Degree #${index + 1} institution`);
                            input.classList.add('is-invalid');
                        }
                    });

                    yearsObtained.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Degree #${index + 1} year obtained`);
                            input.classList.add('is-invalid');
                        } else if (input.value < 1900 || input.value > 2099) {
                            missingFields.push(`Degree #${index + 1} valid year (1900-2099)`);
                            input.classList.add('is-invalid');
                        }
                    });
                }
                break;

            case 3: // Publications
                const publicationTypes = document.querySelectorAll('.publication-type');
                const publicationRoles = document.querySelectorAll('.publication-entry .role');
                const publicationTitles = document.querySelectorAll('.publication-entry .title');
                const publicationDates = document.querySelectorAll('.publication-date');

                if (publicationTypes.length === 0) {
                    missingFields.push('At least one publication');
                } else {
                    publicationTypes.forEach((select, index) => {
                        if (!select.value) {
                            missingFields.push(`Publication #${index + 1} type`);
                            select.classList.add('is-invalid');
                        }
                    });

                    publicationRoles.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Publication #${index + 1} role`);
                            input.classList.add('is-invalid');
                        }
                    });

                    publicationTitles.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Publication #${index + 1} title`);
                            input.classList.add('is-invalid');
                        }
                    });

                    publicationDates.forEach((input, index) => {
                        if (!input.value) {
                            missingFields.push(`Publication #${index + 1} date`);
                            input.classList.add('is-invalid');
                        }
                    });
                }
                break;

            case 4: // Grants
                const grantNames = document.querySelectorAll('.grant-name');
                const fundingAgencies = document.querySelectorAll('.funding-agency');
                const grantAmounts = document.querySelectorAll('.grant-amount');
                const grantYears = document.querySelectorAll('.grant-year');
                const grantRoles = document.querySelectorAll('.grant-entry .role');

                if (grantNames.length === 0) {
                    missingFields.push('At least one grant');
                } else {
                    grantNames.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Grant #${index + 1} name`);
                            input.classList.add('is-invalid');
                        }
                    });

                    fundingAgencies.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Grant #${index + 1} funding agency`);
                            input.classList.add('is-invalid');
                        }
                    });

                    grantAmounts.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Grant #${index + 1} amount`);
                            input.classList.add('is-invalid');
                        }
                    });

                    grantYears.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Grant #${index + 1} year awarded`);
                            input.classList.add('is-invalid');
                        } else if (input.value < 1900 || input.value > 2099) {
                            missingFields.push(`Grant #${index + 1} valid year (1900-2099)`);
                            input.classList.add('is-invalid');
                        }
                    });

                    grantRoles.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Grant #${index + 1} role`);
                            input.classList.add('is-invalid');
                        }
                    });
                }
                break;

            case 5: // Activities
                const activityTypes = document.querySelectorAll('.activity-type');
                const activityTitles = document.querySelectorAll('.activity-entry .title');
                const activityDates = document.querySelectorAll('.activity-entry .date');
                const activityRoles = document.querySelectorAll('.activity-entry .role');
                const activityLocations = document.querySelectorAll('.activity-entry .location');

                if (activityTypes.length === 0) {
                    missingFields.push('At least one activity');
                } else {
                    activityTypes.forEach((select, index) => {
                        if (!select.value) {
                            missingFields.push(`Activity #${index + 1} type`);
                            select.classList.add('is-invalid');
                        }
                    });

                    activityTitles.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Activity #${index + 1} title`);
                            input.classList.add('is-invalid');
                        }
                    });

                    activityDates.forEach((input, index) => {
                        if (!input.value) {
                            missingFields.push(`Activity #${index + 1} date`);
                            input.classList.add('is-invalid');
                        }
                    });

                    activityRoles.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Activity #${index + 1} role`);
                            input.classList.add('is-invalid');
                        }
                    });

                    activityLocations.forEach((input, index) => {
                        if (!input.value.trim()) {
                            missingFields.push(`Activity #${index + 1} location`);
                            input.classList.add('is-invalid');
                        }
                    });
                }
                break;
        }

        if (missingFields.length > 0) {
            showValidationError(missingFields);
            return false;
        }
        return true;
    }

    // Function to clear validation errors
    function clearValidationErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        const existingAlert = document.querySelector('.alert-danger');
        if (existingAlert) {
            existingAlert.remove();
        }
    }

    // Function to show validation errors
    function showValidationError(missingFields) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.innerHTML = `
            <strong><i class="bi bi-exclamation-triangle"></i> Missing Information!</strong>
            <button type="button" class="btn-close alert-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="mt-2">Please complete the following required fields:</div>
            <ul class="mb-0 mt-2">
                ${missingFields.map(field => `<li>${field}</li>`).join('')}
            </ul>
        `;

        const activeSection = document.querySelector('.form-section.active');
        const navButtons = activeSection.querySelector('.nav-buttons');

        if (navButtons) {
            activeSection.insertBefore(errorDiv, navButtons);
        } else {
            activeSection.appendChild(errorDiv);
        }

        errorDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });

        const firstInvalidField = activeSection.querySelector('.is-invalid');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
    }

    // Function to navigate to a specific step
    function goToStep(stepNumber) {
        if (stepNumber < currentStep) {
            // Always allow going back
            window.location.href = `staff_view.php?page=${stepNumber}`;
        } else if (stepNumber === currentStep + 1) {
            // Only allow going forward after validation and saving
            saveForm(currentStep);
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: 'Please complete the current section before proceeding.',
                confirmButtonColor: 'var(--primary-blue)'
            });
        }
    }

    // Function to go to previous step
    function goToPreviousStep() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    }

    // Function to update progress UI
    function updateProgressUI() {
        // Update progress bar
        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.querySelector('.progress-bar').style.width = `${progressPercentage}%`;

        // Update step indicators
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
    }

    // Function to show current form section
    function showFormSection(stepNumber) {
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });

        const currentSection = document.getElementById(`section-${stepNumber}`);
        if (currentSection) {
            currentSection.classList.add('active');
        }

        // Update section counter
        document.querySelectorAll('.section-counter').forEach(counter => {
            counter.textContent = `(Step ${stepNumber} of ${totalSteps})`;
        });

        // Update nav buttons
        const prevButtons = document.querySelectorAll('.nav-buttons button.btn-secondary');
        prevButtons.forEach(btn => {
            btn.disabled = (stepNumber === 1);
        });
    }

    // Function to submit final form
    function submitForm() {
        Swal.fire({
            title: 'Submit Appraisal?',
            text: 'Are you sure you want to submit your appraisal?',
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

    // Function to show success modal
    function showSuccessModal() {
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        modal.show();

        // Redirect after modal is closed
        document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function() {
            window.location.href = 'staff_view.php?page=1';
        });
    }

    // Function to setup dynamic form elements
    function setupDynamicFormElements() {
        // Add degree entry
        document.getElementById('add-degree').addEventListener('click', function() {
            addDynamicField('degrees', 'degree');
        });

        // Add publication entry
        document.getElementById('add-publication').addEventListener('click', function() {
            addDynamicField('publications', 'publication');
        });

        // Add grant entry
        document.getElementById('add-grant').addEventListener('click', function() {
            addDynamicField('grants', 'grant');
        });

        // Add activity entry
        document.getElementById('add-activity').addEventListener('click', function() {
            addDynamicField('activities', 'activity');
        });

        // Remove entry handlers
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-degree') || e.target.closest('.remove-degree')) {
                handleRemoveEntry('degree-entry', 'degree');
            } else if (e.target.classList.contains('remove-publication') || e.target.closest(
                    '.remove-publication')) {
                handleRemoveEntry('publication-entry', 'publication');
            } else if (e.target.classList.contains('remove-grant') || e.target.closest('.remove-grant')) {
                handleRemoveEntry('grant-entry', 'grant');
            } else if (e.target.classList.contains('remove-activity') || e.target.closest('.remove-activity')) {
                handleRemoveEntry('activity-entry', 'activity');
            } else if (e.target.classList.contains('alert-close') || e.target.closest('.alert-close')) {
                e.target.closest('.alert').remove();
            }
        });
    }

    // Function to add a dynamic field
    function addDynamicField(containerId, fieldType) {
        const container = document.getElementById(`${containerId}-container`);
        if (!container) return;

        const index = container.children.length;
        let entryHtml = '';

        // Generate HTML based on field type
        if (fieldType === 'degree') {
            entryHtml = `
                <div class="degree-entry entry-card">
                    <button type="button" class="btn btn-danger btn-sm remove-degree remove-entry">
                        <i class="bi bi-trash"></i>
                    </button>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="degree_name_${index}" class="form-label required-field">Degree Name</label>
                            <input type="text" class="form-control degree-name" id="degree_name_${index}" name="degrees[${index}][degree_name]" required>
                            <div class="invalid-feedback">Please provide the degree name.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="degree_classification_${index}" class="form-label required-field">Classification</label>
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
                            <label for="institution_${index}" class="form-label required-field">Institution</label>
                            <input type="text" class="form-control institution" id="institution_${index}" name="degrees[${index}][institution]" required>
                            <div class="invalid-feedback">Please provide the institution name.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="year_obtained_${index}" class="form-label required-field">Year Obtained</label>
                            <input type="number" class="form-control year-obtained" id="year_obtained_${index}" name="degrees[${index}][year_obtained]" min="1900" max="<?= date('Y') ?>" required>
                            <div class="invalid-feedback">Please provide a valid year (1900-<?= date('Y') ?>).</div>
                        </div>
                    </div>
                </div>
            `;
        } else if (fieldType === 'publication') {
            entryHtml = `
                <div class="publication-entry entry-card">
                    <button type="button" class="btn btn-danger btn-sm remove-publication remove-entry">
                        <i class="bi bi-trash"></i>
                    </button>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="publication_type_${index}" class="form-label required-field">Publication Type</label>
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
                            <label for="role_${index}" class="form-label required-field">Your Role</label>
                            <input type="text" class="form-control role" id="role_${index}" name="publications[${index}][role]"
                                placeholder="Author, Co-author, Editor, etc." required>
                            <div class="invalid-feedback">Please specify your role.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="title_${index}" class="form-label required-field">Title</label>
                            <input type="text" class="form-control title" id="title_${index}" name="publications[${index}][title]" required>
                            <div class="invalid-feedback">Please provide the publication title.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="publication_date_${index}" class="form-label required-field">Publication Date</label>
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
                </div>
            `;
        } else if (fieldType === 'grant') {
            entryHtml = `
                <div class="grant-entry entry-card">
                    <button type="button" class="btn btn-danger btn-sm remove-grant remove-entry">
                        <i class="bi bi-trash"></i>
                    </button>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="grant_name_${index}" class="form-label required-field">Grant Name</label>
                            <input type="text" class="form-control grant-name" id="grant_name_${index}" name="grants[${index}][grant_name]" required>
                            <div class="invalid-feedback">Please provide the grant name.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="funding_agency_${index}" class="form-label required-field">Funding Agency</label>
                            <input type="text" class="form-control funding-agency" id="funding_agency_${index}" name="grants[${index}][funding_agency]" required>
                            <div class="invalid-feedback">Please provide the funding agency.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="grant_amount_${index}" class="form-label required-field">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control grant-amount" id="grant_amount_${index}"
                                    name="grants[${index}][grant_amount]" min="0" step="0.01" required>
                            </div>
                            <div class="invalid-feedback">Please provide the grant amount.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="grant_year_${index}" class="form-label required-field">Year Awarded</label>
                            <input type="number" class="form-control grant-year" id="grant_year_${index}"
                                name="grants[${index}][grant_year]" min="1900" max="<?= date('Y') ?>" required>
                            <div class="invalid-feedback">Please provide a valid year (1900-<?= date('Y') ?>).</div>
                        </div>
                        <div class="col-md-4">
                            <label for="role_${index}" class="form-label required-field">Your Role</label>
                            <input type="text" class="form-control role" id="role_${index}"
                                name="grants[${index}][role]" placeholder="PI, Co-PI, Researcher, etc." required>
                            <div class="invalid-feedback">Please specify your role.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description_${index}" class="form-label">Brief Description</label>
                        <textarea class="form-control description" id="description_${index}"
                            name="grants[${index}][description]" rows="2"></textarea>
                    </div>
                </div>
            `;
        } else if (fieldType === 'activity') {
            entryHtml = `
                <div class="activity-entry entry-card">
                    <button type="button" class="btn btn-danger btn-sm remove-activity remove-entry">
                        <i class="bi bi-trash"></i>
                    </button>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="activity_type_${index}" class="form-label required-field">Activity Type</label>
                            <select class="form-select activity-type" id="activity_type_${index}"
                                name="activities[${index}][activity_type]" required>
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
                            <label for="title_${index}" class="form-label required-field">Title/Event Name</label>
                            <input type="text" class="form-control title" id="title_${index}"
                                name="activities[${index}][title]" required>
                            <div class="invalid-feedback">Please provide the activity title.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="date_${index}" class="form-label required-field">Date</label>
                            <input type="date" class="form-control date" id="date_${index}"
                                name="activities[${index}][date]" required>
                            <div class="invalid-feedback">Please provide the activity date.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="role_${index}" class="form-label required-field">Your Role</label>
                            <input type="text" class="form-control role" id="role_${index}"
                                name="activities[${index}][role]"
                                placeholder="Speaker, Organizer, Participant, etc." required>
                            <div class="invalid-feedback">Please specify your role.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="location_${index}" class="form-label required-field">Location</label>
                            <input type="text" class="form-control location" id="location_${index}"
                                name="activities[${index}][location]" required>
                            <div class="invalid-feedback">Please provide the activity location.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description_${index}" class="form-label">Description</label>
                        <textarea class="form-control description" id="description_${index}"
                            name="activities[${index}][description]" rows="2"></textarea>
                    </div>
                </div>
            `;
        }

        const entry = document.createElement('div');
        entry.className = `${fieldType}-entry entry-card`;
        entry.innerHTML = entryHtml;

        container.appendChild(entry);

        // Scroll to new entry
        setTimeout(() => {
            entry.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }, 100);
    }

    // Function to handle removing an entry
    function handleRemoveEntry(entryClass, fieldName) {
        const entry = event.target.closest(`.${entryClass}`);
        const container = entry.parentElement;

        if (container.children.length > 1) {
            entry.remove();

            // Re-index remaining entries
            const entries = container.querySelectorAll(`.${entryClass}`);
            entries.forEach((entry, index) => {
                entry.querySelectorAll('input, select, textarea').forEach(input => {
                    const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    input.name = name;
                    input.id = input.id.replace(/\d+$/, index);
                });
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: `You must have at least one ${fieldName} entry.`,
                confirmButtonColor: 'var(--primary-blue)'
            });
        }
    }

    // Function to validate email format
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    </script>
</body>

</html>