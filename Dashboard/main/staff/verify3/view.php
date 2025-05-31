<?php 
session_start();
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Appraisal Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-green: #2e7d32;
        --light-green: #81c784;
        --primary-yellow: #ffd600;
        --light-yellow: #fff176;
        --primary-blue: #1976d2;
        --light-blue: #64b5f6;
    }

    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .progress-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
    }

    .progress {
        height: 20px;
        margin-bottom: 20px;
    }

    .progress-bar {
        background-color: var(--primary-green);
        transition: width 0.5s ease;
    }

    .form-section {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
        display: none;
    }

    .form-section.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    .form-header {
        color: var(--primary-blue);
        border-bottom: 2px solid var(--light-blue);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .btn-primary:hover {
        background-color: #1565c0;
        border-color: #1565c0;
    }

    .btn-success {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }

    .btn-success:hover {
        background-color: #1b5e20;
        border-color: #1b5e20;
    }

    .btn-warning {
        background-color: var(--primary-yellow);
        border-color: var(--primary-yellow);
        color: #000;
    }

    .btn-warning:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .progress-step {
        text-align: center;
        position: relative;
        padding-top: 30px;
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
        background-color: var(--primary-yellow);
        color: black;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e0e0e0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.9rem;
        color: #616161;
    }

    .progress-step.completed .step-label {
        color: var(--primary-green);
        font-weight: bold;
    }

    .progress-step.active .step-label {
        color: var(--primary-blue);
        font-weight: bold;
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

    .nav-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .form-control:focus {
        border-color: var(--light-blue);
        box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
    }

    .form-check-input:checked {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(25, 118, 210, 0.25);
    }

    .section-counter {
        color: var(--primary-blue);
        font-weight: bold;
    }

    /* Error and validation styles */
    .alert-danger {
        margin-bottom: 20px;
        animation: fadeIn 0.3s ease;
        position: relative;
    }

    .alert-close {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #721c24;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        animation: shake 0.5s ease;
    }

    .invalid-feedback {
        color: #dc3545;
        display: none;
        margin-top: 0.25rem;
        font-size: 0.875em;
    }

    .is-invalid~.invalid-feedback {
        display: block;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-5px);
        }

        40%,
        80% {
            transform: translateX(5px);
        }
    }

    /* Progress step connector lines */
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 20px;
        left: calc(50% + 20px);
        width: calc(100% - 40px);
        height: 2px;
        background-color: #ddd;
        z-index: -1;
    }

    .progress-step.completed:not(:last-child)::after {
        background-color: var(--primary-green);
    }
    </style>
    <style>
    /* Main container with spaces for sidebar and navbar */
    .main-wrapper {
        display: grid;
        grid-template-columns: var(--sidebar-width) 1fr;
        grid-template-rows: var(--navbar-height) 1fr;
        min-height: 100vh;
    }

    /* Empty sidebar space */
    .sidebar-space {
        grid-column: 1;
        grid-row: 1 / span 2;
        background-color: transparent;
    }

    /* Empty navbar space */
    .navbar-space {
        grid-column: 2;
        grid-row: 1;
        background-color: transparent;
    }

    /* Content area */
    .content-area {
        grid-column: 2;
        grid-row: 2;
        padding: 20px;
        overflow-y: auto;
    }

    /* Form container */
    .container.py-5 {
        max-width: 1200px;
        margin: 0 auto;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 30px;
    }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <!-- Empty space for navbar (10% height) -->
        <div class="navbar-space"></div>
        <?php  include '../../bars/nav_bar.php'; ?>

        <!-- // -- Sidebar -- -->
        <div class="sidebar-space"></div>
        <?php include '../../bars/side_bar.php';

        ?>
    <div class="content-area">
        <div class="container py-5 ">
            <h1 class="text-center mb-4" style="color: var(--primary-green);">Staff Appraisal Form</h1>

            <!-- Progress Container -->
            <div class="progress-container">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>

                <div class="row text-center">
                    <div class="col progress-step active" data-step="1" onclick="goToStep(1)">
                        <div class="step-number">1</div>
                        <div class="step-label">Biodata</div>
                    </div>
                    <div class="col progress-step" data-step="2" onclick="goToStep(2)">
                        <div class="step-number">2</div>
                        <div class="step-label">Degrees</div>
                    </div>
                    <div class="col progress-step" data-step="3" onclick="goToStep(3)">
                        <div class="step-number">3</div>
                        <div class="step-label">Publications</div>
                    </div>
                    <div class="col progress-step" data-step="4" onclick="goToStep(4)">
                        <div class="step-number">4</div>
                        <div class="step-label">Grants</div>
                    </div>
                    <div class="col progress-step" data-step="5" onclick="goToStep(5)">
                        <div class="step-number">5</div>
                        <div class="step-label">Activities</div>
                    </div>
                </div>
            </div>

            <!-- Form Sections -->
            <!-- Section 1: Biodata -->
            <div class="form-section active" id="section-1">
                <h2 class="form-header">1. Personal Biodata <span class="section-counter">(Step 1 of 5)</span></h2>

                <form id="biodata-form">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="invalid-feedback">Please provide your first name.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="invalid-feedback">Please provide your last name.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please provide a valid email address.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                            <div class="invalid-feedback">Please provide your phone number.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label">Employee ID</label>
                            <input type="text" class="form-control" id="employee_id" name="employee_id" required>
                            <div class="invalid-feedback">Please provide your employee ID.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="scholar_type" class="form-label">Scholar Type</label>
                            <select class="form-select" id="scholar_type" name="scholar_type" required>
                                <option value="" selected disabled>Select scholar type</option>
                                <option value="Professor">Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Lecturer">Lecturer</option>
                                <option value="Researcher">Researcher</option>
                            </select>
                            <div class="invalid-feedback">Please select your scholar type.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select" id="department_id" name="department_id" required>
                                <option value="" selected disabled>Select department</option>
                                <!-- Options will be populated dynamically -->
                            </select>
                            <div class="invalid-feedback">Please select your department.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="years_of_experience" class="form-label">Years of Experience</label>
                            <input type="number" class="form-control" id="years_of_experience"
                                name="years_of_experience" min="0" required>
                            <div class="invalid-feedback">Please provide your years of experience.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Profile Photo</label>
                        <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                    </div>

                    <div class="nav-buttons">
                        <button type="button" class="btn btn-secondary" disabled>Previous</button>
                        <button type="button" class="btn btn-primary" onclick="validateAndContinue()">Save &
                            Continue</button>
                    </div>
                </form>
            </div>

            <!-- Section 2: Degrees -->
            <div class="form-section" id="section-2">
                <h2 class="form-header">2. Academic Degrees <span class="section-counter">(Step 2 of 5)</span></h2>

                <div class="alert alert-info mb-4">
                    Please add all your academic degrees, starting with the highest.
                </div>

                <form id="degrees-form">
                    <div id="degrees-container">
                        <!-- Degree entries will be added here -->
                        <div class="degree-entry mb-4 p-3 border rounded">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="degree_name_1" class="form-label">Degree Name</label>
                                    <input type="text" class="form-control degree-name" id="degree_name_1"
                                        name="degrees[0][degree_name]" required>
                                    <div class="invalid-feedback">Please provide the degree name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="degree_classification_1" class="form-label">Classification</label>
                                    <select class="form-select degree-classification" id="degree_classification_1"
                                        name="degrees[0][degree_classification]" required>
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
                                    <label for="institution_1" class="form-label">Institution</label>
                                    <input type="text" class="form-control institution" id="institution_1"
                                        name="degrees[0][institution]" required>
                                    <div class="invalid-feedback">Please provide the institution name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="year_obtained_1" class="form-label">Year Obtained</label>
                                    <input type="number" class="form-control year-obtained" id="year_obtained_1"
                                        name="degrees[0][year_obtained]" min="1900" max="2099" required>
                                    <div class="invalid-feedback">Please provide a valid year (1900-2099).</div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-degree">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-warning mb-4" id="add-degree">
                        Add Another Degree
                    </button>

                    <div class="nav-buttons">
                        <button type="button" class="btn btn-secondary" onclick="goToPreviousStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="validateAndContinue()">Save &
                            Continue</button>
                    </div>
                </form>
            </div>

            <!-- Section 3: Publications -->
            <div class="form-section" id="section-3">
                <h2 class="form-header">3. Publications <span class="section-counter">(Step 3 of 5)</span></h2>

                <div class="alert alert-info mb-4">
                    Please add all your publications (journal articles, conference papers, books, etc.)
                </div>

                <form id="publications-form">
                    <div id="publications-container">
                        <!-- Publication entries will be added here -->
                        <div class="publication-entry mb-4 p-3 border rounded">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="publication_type_1" class="form-label">Publication Type</label>
                                    <select class="form-select publication-type" id="publication_type_1"
                                        name="publications[0][publication_type]" required>
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
                                    <label for="role_1" class="form-label">Your Role</label>
                                    <input type="text" class="form-control role" id="role_1"
                                        name="publications[0][role]" placeholder="Author, Co-author, Editor, etc."
                                        required>
                                    <div class="invalid-feedback">Please specify your role.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="title_1" class="form-label">Title</label>
                                    <input type="text" class="form-control title" id="title_1"
                                        name="publications[0][title]" required>
                                    <div class="invalid-feedback">Please provide the publication title.</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="publication_date_1" class="form-label">Publication Date</label>
                                    <input type="date" class="form-control publication-date" id="publication_date_1"
                                        name="publications[0][publication_date]" required>
                                    <div class="invalid-feedback">Please provide the publication date.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="journal_name_1" class="form-label">Journal/Publisher Name</label>
                                    <input type="text" class="form-control journal-name" id="journal_name_1"
                                        name="publications[0][journal_name]">
                                </div>
                                <div class="col-md-6">
                                    <label for="doi_1" class="form-label">DOI/ISBN (if available)</label>
                                    <input type="text" class="form-control doi" id="doi_1" name="publications[0][doi]"
                                        placeholder="e.g., 10.1234/abc123">
                                </div>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-publication">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-warning mb-4" id="add-publication">
                        Add Another Publication
                    </button>

                    <div class="nav-buttons">
                        <button type="button" class="btn btn-secondary" onclick="goToPreviousStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="validateAndContinue()">Save &
                            Continue</button>
                    </div>
                </form>
            </div>

            <!-- Section 4: Grants -->
            <div class="form-section" id="section-4">
                <h2 class="form-header">4. Research Grants <span class="section-counter">(Step 4 of 5)</span></h2>

                <div class="alert alert-info mb-4">
                    Please add all research grants you have received or participated in.
                </div>

                <form id="grants-form">
                    <div id="grants-container">
                        <!-- Grant entries will be added here -->
                        <div class="grant-entry mb-4 p-3 border rounded">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="grant_name_1" class="form-label">Grant Name</label>
                                    <input type="text" class="form-control grant-name" id="grant_name_1"
                                        name="grants[0][grant_name]" required>
                                    <div class="invalid-feedback">Please provide the grant name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="funding_agency_1" class="form-label">Funding Agency</label>
                                    <input type="text" class="form-control funding-agency" id="funding_agency_1"
                                        name="grants[0][funding_agency]" required>
                                    <div class="invalid-feedback">Please provide the funding agency.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="grant_amount_1" class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control grant-amount" id="grant_amount_1"
                                            name="grants[0][grant_amount]" min="0" step="0.01" required>
                                    </div>
                                    <div class="invalid-feedback">Please provide the grant amount.</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="grant_year_1" class="form-label">Year Awarded</label>
                                    <input type="number" class="form-control grant-year" id="grant_year_1"
                                        name="grants[0][grant_year]" min="1900" max="2099" required>
                                    <div class="invalid-feedback">Please provide a valid year (1900-2099).</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="role_1" class="form-label">Your Role</label>
                                    <input type="text" class="form-control role" id="role_1" name="grants[0][role]"
                                        placeholder="PI, Co-PI, Researcher, etc." required>
                                    <div class="invalid-feedback">Please specify your role.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description_1" class="form-label">Brief Description</label>
                                <textarea class="form-control description" id="description_1"
                                    name="grants[0][description]" rows="2"></textarea>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-grant">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-warning mb-4" id="add-grant">
                        Add Another Grant
                    </button>

                    <div class="nav-buttons">
                        <button type="button" class="btn btn-secondary" onclick="goToPreviousStep()">Previous</button>
                        <button type="button" class="btn btn-primary" onclick="validateAndContinue()">Save &
                            Continue</button>
                    </div>
                </form>
            </div>

            <!-- Section 5: Academic Activities -->
            <div class="form-section" id="section-5">
                <h2 class="form-header">5. Academic Activities <span class="section-counter">(Step 5 of 5)</span></h2>

                <div class="alert alert-info mb-4">
                    Please add your academic activities (conferences, workshops, etc.)
                </div>

                <form id="activities-form">
                    <div id="activities-container">
                        <!-- Activity entries will be added here -->
                        <div class="activity-entry mb-4 p-3 border rounded">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="activity_type_1" class="form-label">Activity Type</label>
                                    <select class="form-select activity-type" id="activity_type_1"
                                        name="activities[0][activity_type]" required>
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
                                    <label for="title_1" class="form-label">Title/Event Name</label>
                                    <input type="text" class="form-control title" id="title_1"
                                        name="activities[0][title]" required>
                                    <div class="invalid-feedback">Please provide the activity title.</div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="date_1" class="form-label">Date</label>
                                    <input type="date" class="form-control date" id="date_1" name="activities[0][date]"
                                        required>
                                    <div class="invalid-feedback">Please provide the activity date.</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="role_1" class="form-label">Your Role</label>
                                    <input type="text" class="form-control role" id="role_1" name="activities[0][role]"
                                        placeholder="Speaker, Organizer, Participant, etc." required>
                                    <div class="invalid-feedback">Please specify your role.</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="location_1" class="form-label">Location</label>
                                    <input type="text" class="form-control location" id="location_1"
                                        name="activities[0][location]" required>
                                    <div class="invalid-feedback">Please provide the activity location.</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description_1" class="form-label">Description</label>
                                <textarea class="form-control description" id="description_1"
                                    name="activities[0][description]" rows="2"></textarea>
                            </div>

                            <button type="button" class="btn btn-danger btn-sm remove-activity">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-warning mb-4" id="add-activity">
                        Add Another Activity
                    </button>

                    <div class="nav-buttons">
                        <button type="button" class="btn btn-secondary" onclick="goToPreviousStep()">Previous</button>
                        <button type="button" class="btn btn-success" onclick="submitForm()">Submit Portfolio</button>
                    </div>
                </form>
            </div>

            <!-- Confirmation Modal -->
            <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmationModalLabel">Submission Complete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                                        fill="var(--primary-green)" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                    </svg>
                                </div>
                                <h4 class="text-success">Thank You!</h4>
                                <p>Your academic portfolio has been successfully submitted.</p>
                                <p>You can review and update your information at any time.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Track current step and completed steps
    let currentStep = 1;
    const totalSteps = 5;
    const completedSteps = new Set([1]); // Start with step 1 accessible
    const formData = {};

    // Initialize the form
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize department dropdown (mock data)
        const departments = [{
                id: 1,
                name: "Computer Science"
            },
            {
                id: 2,
                name: "Mathematics"
            },
            {
                id: 3,
                name: "Physics"
            },
            {
                id: 4,
                name: "Chemistry"
            },
            {
                id: 5,
                name: "Biology"
            },
            {
                id: 6,
                name: "Engineering"
            },
            {
                id: 7,
                name: "Medicine"
            },
            {
                id: 8,
                name: "Business Administration"
            },
            {
                id: 9,
                name: "Economics"
            },
            {
                id: 10,
                name: "Law"
            }
        ];

        const departmentSelect = document.getElementById('department_id');
        departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.id;
            option.textContent = dept.name;
            departmentSelect.appendChild(option);
        });

        // Set up event listeners for adding/removing entries
        setupDynamicFormElements();

        // Show first section
        showFormSection(currentStep);
        updateProgressUI();
    });

    function goToStep(stepNumber) {
        // Allow backward navigation to any completed step
        if (stepNumber < currentStep) {
            navigateToStep(stepNumber);
            return;
        }

        // Only allow forward to immediate next step if current step is completed
        if (stepNumber === currentStep + 1) {
            if (validateCurrentStep()) {
                completedSteps.add(currentStep);
                navigateToStep(stepNumber);
            } else {
                showValidationError();
            }
        } else {
            showAlert(
                "Please complete the current section before jumping ahead. You can only proceed to the next step after completing the current one."
                );
        }
    }

    function navigateToStep(stepNumber) {
        // Save current section data before navigating
        saveCurrentSectionData();

        // Update current step
        currentStep = stepNumber;

        // Update UI
        updateProgressUI();
        showFormSection(stepNumber);

        // Scroll to top of section
        document.querySelector(`#section-${stepNumber}`).scrollIntoView({
            behavior: 'smooth'
        });
    }

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

    function clearValidationErrors() {
        // Remove all is-invalid classes
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Remove any existing error alerts
        const existingAlert = document.querySelector('.alert-danger');
        if (existingAlert) {
            existingAlert.remove();
        }
    }

    function showValidationError(missingFields) {
        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.innerHTML = `
            <strong>Missing Information!</strong>
            <button type="button" class="alert-close">&times;</button>
            <div class="mt-2">Please complete the following required fields:</div>
            <ul class="mb-0 mt-2">
                ${missingFields.map(field => `<li>${field}</li>`).join('')}
            </ul>
        `;

        // Add close button functionality
        errorDiv.querySelector('.alert-close').addEventListener('click', function() {
            errorDiv.remove();
        });

        // Insert before the navigation buttons
        const activeSection = document.querySelector('.form-section.active');
        const navButtons = activeSection.querySelector('.nav-buttons');

        if (navButtons) {
            activeSection.insertBefore(errorDiv, navButtons);
        } else {
            activeSection.appendChild(errorDiv);
        }

        // Scroll to the error message
        errorDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });

        // Add shake animation to highlight first missing field
        const firstInvalidField = activeSection.querySelector('.is-invalid');
        if (firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.classList.add('is-invalid');

            // Remove animation class after animation completes
            firstInvalidField.addEventListener('animationend', () => {
                firstInvalidField.classList.remove('is-invalid');
                // Re-add it without animation if still invalid
                setTimeout(() => {
                    if (!firstInvalidField.value.trim()) {
                        firstInvalidField.classList.add('is-invalid');
                    }
                }, 100);
            }, {
                once: true
            });
        }
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validateAndContinue() {
        if (validateCurrentStep()) {
            completedSteps.add(currentStep);
            const nextStep = currentStep + 1;
            if (nextStep <= totalSteps) {
                goToStep(nextStep);
            }
        }
    }

    function goToPreviousStep() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
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
        document.querySelector('.progress-bar').setAttribute('aria-valuenow', progressPercentage);
    }

    function showFormSection(stepNumber) {
        // Hide all sections
        document.querySelectorAll('.form-section').forEach(section => {
            section.classList.remove('active');
        });

        // Show current section
        const currentSection = document.querySelector(`#section-${stepNumber}`);
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

    function saveCurrentSectionData() {
        const form = document.querySelector(`#section-${currentStep} form`);
        if (!form) return;

        const formDataObj = new FormData(form);
        const data = {};

        formDataObj.forEach((value, key) => {
            // Handle array-like fields (degrees, publications, etc.)
            if (key.includes('[') && key.includes(']')) {
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

        formData[`section${currentStep}`] = data;
        console.log('Saved data for section', currentStep, data);
    }

    function submitForm() {
        // Validate last section first
        if (!validateCurrentStep()) {
            return;
        }

        // Save current section data
        saveCurrentSectionData();

        // Show confirmation dialog
        Swal.fire({
            title: 'Submit Portfolio?',
            text: 'Are you sure you want to submit your academic portfolio?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary-green)',
            cancelButtonColor: 'var(--primary-blue)',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // In a real app, this would submit all formData to the server
                console.log('Submitting all data:', formData);

                // Simulate submission
                setTimeout(() => {
                    // Show success modal
                    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                    modal.show();

                    // Reset form (optional)
                    // document.querySelectorAll('form').forEach(form => form.reset());
                    // formData = {};
                    // currentStep = 1;
                    // updateProgress();
                    // navigateToSection(1);
                }, 1500);
            }
        });
    }

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