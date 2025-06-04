<?php 
session_start();
// Database connection
include '../../head/approve/config.php';

// Check if user is logged in
if (!isset($_SESSION['staff_id'])) {
        header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
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

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"> -->

    <link rel="stylesheet" href="../../../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../components/src/bootstrap-icons-1.11.3/bootstrap-icons-1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style/style.css">
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
                        <i class="bi bi-info-circle"></i> Please add all your academic degrees, starting with the highest.
                    </div>

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
                                                <label for="degree_name_<?= $index ?>" class="form-label required-field">Degree Name</label>
                                                <input type="text" class="form-control degree-name"
                                                    id="degree_name_<?= $index ?>" name="degrees[<?= $index ?>][degree_name]"
                                                    value="<?= htmlspecialchars($degree['degree_name'] ?? '') ?>" required>
                                                <div class="invalid-feedback">Please provide the degree name.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="degree_classification_<?= $index ?>" class="form-label required-field">Classification</label>
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
                                                <label for="institution_<?= $index ?>" class="form-label required-field">Institution</label>
                                                <input type="text" class="form-control institution"
                                                    id="institution_<?= $index ?>" name="degrees[<?= $index ?>][institution]"
                                                    value="<?= htmlspecialchars($degree['institution'] ?? '') ?>" required>
                                                <div class="invalid-feedback">Please provide the institution name.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="year_obtained_<?= $index ?>" class="form-label required-field">Year Obtained</label>
                                                <input type="number" class="form-control year-obtained"
                                                    id="year_obtained_<?= $index ?>" name="degrees[<?= $index ?>][year_obtained]"
                                                    value="<?= htmlspecialchars($degree['year_obtained'] ?? '') ?>" min="1900"
                                                    max="<?= date('Y') ?>" required>
                                                <div class="invalid-feedback">Please provide a valid year (1900-<?= date('Y') ?>).</div>
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
                                            <label for="degree_classification_0" class="form-label required-field">Classification</label>
                                            <select class="form-select degree-classification" id="degree_classification_0"
                                                name="degrees[0][degree_classification]" required>
                                                <option value="" selected disabled>Select classification</option>
                                                <?php foreach ($classifications as $classification): ?>
                                                    <option value="<?= htmlspecialchars($classification) ?>"><?= htmlspecialchars($classification) ?></option>
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
                                            <label for="year_obtained_0" class="form-label required-field">Year Obtained</label>
                                            <input type="number" class="form-control year-obtained" id="year_obtained_0"
                                                name="degrees[0][year_obtained]" min="1900" max="<?= date('Y') ?>" required>
                                            <div class="invalid-feedback">Please provide a valid year (1900-<?= date('Y') ?>).</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="button" class="btn btn-warning add-entry-btn" id="add-degree">
                            <i class="bi bi-plus-circle"></i> Add Another Degree
                        </button>

                        <div class="nav-buttons">
                            <button type="button" class="btn btn-secondary" onclick="goToPreviousStep()">Previous</button>
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
                                            <span class="input-group-text">UGX</span>
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
                                            <span class="input-group-text">UGX</span>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

    <script src="../../../components/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="../../../components/alerts/sweetalert2.js"></script>
    <?php include_once('js/logic.php'); ?>
    
</body>

</html>