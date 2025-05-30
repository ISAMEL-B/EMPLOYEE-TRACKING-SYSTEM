<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Portfolio Form</title>
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
    </style>

    <style>
    .progress-step {
        position: relative;
        cursor: pointer;
        padding: 0 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .step-button {
        background: none;
        border: none;
        padding: 0;
        width: 100%;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .step-number {
        width: 30px;
        height: 30px;
        line-height: 30px;
        border-radius: 50%;
        background-color: #ddd;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2px;
        /* Reduced space between number and label */
        font-weight: bold;
        position: relative;
        top: -5px;
        /* Move number up slightly */
    }

    .step-label {
        font-size: 12px;
        color: #666;
        position: relative;
        top: 5px;
        /* Move label down slightly */
        text-align: center;
        white-space: nowrap;
    }

    .progress-step.completed .step-number {
        background-color: #28a745;
        color: white;
    }

    .progress-step.active .step-number {
        background-color: #007bff;
        color: white;
    }

    .progress-step.active .step-label {
        color: #007bff;
        font-weight: bold;
    }

    /* Optional: Add a connecting line between steps */
    .progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 15px;
        left: calc(50% + 20px);
        width: calc(100% - 40px);
        height: 2px;
        background-color: #ddd;
        z-index: -1;
    }

    .progress-step.completed:not(:last-child)::after {
        background-color: #28a745;
    }
    </style>
    <!-- css for unfinished fields and pressing continue -->
<style>
    .alert-danger {
    margin-bottom: 20px;
    animation: fadeIn 0.3s ease;
}

.is-invalid {
    border-color: #dc3545 !important;
    animation: shake 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-5px); }
    40%, 80% { transform: translateX(5px); }
}
</style>
</head>

<body>
    <div class="container py-5">
        <h1 class="text-center mb-4" style="color: var(--primary-green);">Academic Portfolio Form</h1>

        <!-- Progress Container -->
        <div class="progress-container">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>

            <div class="row text-center">
                <div class="col progress-step active" data-step="1">
                    <button class="step-button" onclick="goToStep(1)">
                        <div class="step-number">1</div>
                        <div class="step-label">Biodata</div>
                    </button>
                </div>
                <div class="col progress-step" data-step="2">
                    <button class="step-button" onclick="goToStep(2)">
                        <div class="step-number">2</div>
                        <div class="step-label">Degrees</div>
                    </button>
                </div>
                <div class="col progress-step" data-step="3">
                    <button class="step-button" onclick="goToStep(3)">
                        <div class="step-number">3</div>
                        <div class="step-label">Publications</div>
                    </button>
                </div>
                <div class="col progress-step" data-step="4">
                    <button class="step-button" onclick="goToStep(4)">
                        <div class="step-number">4</div>
                        <div class="step-label">Grants</div>
                    </button>
                </div>
                <div class="col progress-step" data-step="5">
                    <button class="step-button" onclick="goToStep(5)">
                        <div class="step-number">5</div>
                        <div class="step-label">Activities</div>
                    </button>
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
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="employee_id" name="employee_id" required>
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
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="" selected disabled>Select department</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="years_of_experience" class="form-label">Years of Experience</label>
                        <input type="number" class="form-control" id="years_of_experience" name="years_of_experience"
                            min="0" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Profile Photo</label>
                    <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                </div>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary prev-button"
                        onclick="goToPreviousStep()">Previous</button>
                    <button type="button" class="btn btn-primary next-button" onclick="validateAndContinue()">Save &
                        Continue</button>
                </div>
            </form>
        </div>

        <!-- Section 2: Degrees -->
        <div class="form-section" id="section-2">
            <h2 class="form-header">2. Academic Degrees <span class="section-counter">(Step 2 of 5)</span></h2>

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle-fill"></i> Please add all your academic degrees, starting with the highest.
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
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="institution_1" class="form-label">Institution</label>
                                <input type="text" class="form-control institution" id="institution_1"
                                    name="degrees[0][institution]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="year_obtained_1" class="form-label">Year Obtained</label>
                                <input type="number" class="form-control year-obtained" id="year_obtained_1"
                                    name="degrees[0][year_obtained]" min="1900" max="2099" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger btn-sm remove-degree">Remove</button>
                    </div>
                </div>

                <button type="button" class="btn btn-warning mb-4" id="add-degree">
                    <i class="bi bi-plus-circle"></i> Add Another Degree
                </button>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="1">Previous</button>
                    <button type="button" class="btn btn-primary next-section" data-next="3">Save & Continue</button>
                </div>
            </form>
        </div>

        <!-- Section 3: Publications -->
        <div class="form-section" id="section-3">
            <h2 class="form-header">3. Publications <span class="section-counter">(Step 3 of 5)</span></h2>

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle-fill"></i> Please add all your publications (journal articles, conference
                papers, books, etc.)
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
                            </div>
                            <div class="col-md-6">
                                <label for="role_1" class="form-label">Your Role</label>
                                <input type="text" class="form-control role" id="role_1" name="publications[0][role]"
                                    placeholder="Author, Co-author, Editor, etc." required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title_1" class="form-label">Title</label>
                                <input type="text" class="form-control title" id="title_1" name="publications[0][title]"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="publication_date_1" class="form-label">Publication Date</label>
                                <input type="date" class="form-control publication-date" id="publication_date_1"
                                    name="publications[0][publication_date]" required>
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
                    <i class="bi bi-plus-circle"></i> Add Another Publication
                </button>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="2">Previous</button>
                    <button type="button" class="btn btn-primary next-section" data-next="4">Save & Continue</button>
                </div>
            </form>
        </div>

        <!-- Section 4: Grants -->
        <div class="form-section" id="section-4">
            <h2 class="form-header">4. Research Grants <span class="section-counter">(Step 4 of 5)</span></h2>

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle-fill"></i> Please add all research grants you have received or participated
                in.
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
                            </div>
                            <div class="col-md-6">
                                <label for="funding_agency_1" class="form-label">Funding Agency</label>
                                <input type="text" class="form-control funding-agency" id="funding_agency_1"
                                    name="grants[0][funding_agency]" required>
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
                            </div>
                            <div class="col-md-4">
                                <label for="grant_year_1" class="form-label">Year Awarded</label>
                                <input type="number" class="form-control grant-year" id="grant_year_1"
                                    name="grants[0][grant_year]" min="1900" max="2099" required>
                            </div>
                            <div class="col-md-4">
                                <label for="role_1" class="form-label">Your Role</label>
                                <input type="text" class="form-control role" id="role_1" name="grants[0][role]"
                                    placeholder="PI, Co-PI, Researcher, etc." required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description_1" class="form-label">Brief Description</label>
                            <textarea class="form-control description" id="description_1" name="grants[0][description]"
                                rows="2"></textarea>
                        </div>

                        <button type="button" class="btn btn-danger btn-sm remove-grant">Remove</button>
                    </div>
                </div>

                <button type="button" class="btn btn-warning mb-4" id="add-grant">
                    <i class="bi bi-plus-circle"></i> Add Another Grant
                </button>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="3">Previous</button>
                    <button type="button" class="btn btn-primary next-section" data-next="5">Save & Continue</button>
                </div>
            </form>
        </div>

        <!-- Section 5: Academic Activities -->
        <div class="form-section" id="section-5">
            <h2 class="form-header">5. Academic Activities <span class="section-counter">(Step 5 of 5)</span></h2>

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle-fill"></i> Please add your academic activities (conferences, workshops,
                etc.)
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
                            </div>
                            <div class="col-md-6">
                                <label for="title_1" class="form-label">Title/Event Name</label>
                                <input type="text" class="form-control title" id="title_1" name="activities[0][title]"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date_1" class="form-label">Date</label>
                                <input type="date" class="form-control date" id="date_1" name="activities[0][date]"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label for="role_1" class="form-label">Your Role</label>
                                <input type="text" class="form-control role" id="role_1" name="activities[0][role]"
                                    placeholder="Speaker, Organizer, Participant, etc." required>
                            </div>
                            <div class="col-md-4">
                                <label for="location_1" class="form-label">Location</label>
                                <input type="text" class="form-control location" id="location_1"
                                    name="activities[0][location]" required>
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
                    <i class="bi bi-plus-circle"></i> Add Another Activity
                </button>

                <div class="nav-buttons">
                    <button type="button" class="btn btn-secondary prev-section" data-prev="4">Previous</button>
                    <button type="button" class="btn btn-success" id="submit-all">Submit Portfolio</button>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form navigation
            let currentSection = 1;
            const totalSections = 5;
            const formData = {};

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

            // Update progress bar and steps
            function updateProgress() {
                const progressPercentage = ((currentSection - 1) / (totalSections - 1)) * 100;
                document.querySelector('.progress-bar').style.width = `${progressPercentage}%`;
                document.querySelector('.progress-bar').setAttribute('aria-valuenow', progressPercentage);

                // Update step indicators
                document.querySelectorAll('.progress-step').forEach(step => {
                    const stepNum = parseInt(step.dataset.step);
                    step.classList.remove('completed', 'active', 'pending');

                    if (stepNum < currentSection) {
                        step.classList.add('completed');
                    } else if (stepNum === currentSection) {
                        step.classList.add('active');
                    } else {
                        step.classList.add('pending');
                    }
                });
            }

            // Navigation between sections
            function navigateToSection(sectionNum) {
                if (sectionNum < 1 || sectionNum > totalSections) return;

                // Save current section data before navigating away
                saveCurrentSectionData();

                // Hide current section
                document.querySelector(`#section-${currentSection}`).classList.remove('active');

                // Show new section
                document.querySelector(`#section-${sectionNum}`).classList.add('active');
                currentSection = sectionNum;

                // Update progress
                updateProgress();

                // Scroll to top of section
                document.querySelector(`#section-${sectionNum}`).scrollIntoView({
                    behavior: 'smooth'
                });
            }

            // Save data from current section
            function saveCurrentSectionData() {
                const form = document.querySelector(`#section-${currentSection} form`);
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

                formData[`section${currentSection}`] = data;
                console.log('Saved data for section', currentSection, data);

                // Simulate saving to user_progress table
                saveProgressToServer(currentSection, data);
            }

            // Simulate saving progress to server
            function saveProgressToServer(sectionNum, data) {
                // In a real app, this would be an AJAX call to your backend
                console.log(`Saving progress for section ${sectionNum} to server`, data);

                // Mock API response
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve({
                            success: true
                        });
                    }, 500);
                });
            }

            // Next section button click
            document.querySelectorAll('.next-section').forEach(button => {
                button.addEventListener('click', function() {
                    const nextSection = parseInt(this.dataset.next);

                    // Validate current section before proceeding
                    if (validateCurrentSection()) {
                        navigateToSection(nextSection);
                    }
                });
            });

            // Previous section button click
            document.querySelectorAll('.prev-section').forEach(button => {
                button.addEventListener('click', function() {
                    const prevSection = parseInt(this.dataset.prev);
                    navigateToSection(prevSection);
                });
            });

            // Validate current section
            function validateCurrentSection() {
                const form = document.querySelector(`#section-${currentSection} form`);
                if (!form) return true;

                // Check required fields
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;

                        // Scroll to first invalid field
                        if (isValid === false) {
                            field.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            field.focus();
                            isValid = true; // Prevent multiple scrolls
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please fill in all required fields before proceeding.',
                        confirmButtonColor: 'var(--primary-blue)'
                    });
                    return false;
                }

                return true;
            }

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
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="institution_${index}" class="form-label">Institution</label>
                                <input type="text" class="form-control institution" id="institution_${index}" name="degrees[${index}][institution]" required>
                            </div>
                            <div class="col-md-6">
                                <label for="year_obtained_${index}" class="form-label">Year Obtained</label>
                                <input type="number" class="form-control year-obtained" id="year_obtained_${index}" name="degrees[${index}][year_obtained]" min="1900" max="2099" required>
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

            // Remove degree entry
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-degree')) {
                    const entry = e.target.closest('.degree-entry');
                    if (document.querySelectorAll('.degree-entry').length > 1) {
                        entry.remove();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cannot Remove',
                            text: 'You must have at least one degree entry.',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                }
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
                            </div>
                            <div class="col-md-6">
                                <label for="role_${index}" class="form-label">Your Role</label>
                                <input type="text" class="form-control role" id="role_${index}" name="publications[${index}][role]" placeholder="Author, Co-author, Editor, etc." required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="title_${index}" class="form-label">Title</label>
                                <input type="text" class="form-control title" id="title_${index}" name="publications[${index}][title]" required>
                            </div>
                            <div class="col-md-4">
                                <label for="publication_date_${index}" class="form-label">Publication Date</label>
                                <input type="date" class="form-control publication-date" id="publication_date_${index}" name="publications[${index}][publication_date]" required>
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

            // Remove publication entry
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-publication')) {
                    const entry = e.target.closest('.publication-entry');
                    if (document.querySelectorAll('.publication-entry').length > 1) {
                        entry.remove();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cannot Remove',
                            text: 'You must have at least one publication entry.',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                }
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
                            </div>
                            <div class="col-md-6">
                                <label for="funding_agency_${index}" class="form-label">Funding Agency</label>
                                <input type="text" class="form-control funding-agency" id="funding_agency_${index}" name="grants[${index}][funding_agency]" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="grant_amount_${index}" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control grant-amount" id="grant_amount_${index}" name="grants[${index}][grant_amount]" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="grant_year_${index}" class="form-label">Year Awarded</label>
                                <input type="number" class="form-control grant-year" id="grant_year_${index}" name="grants[${index}][grant_year]" min="1900" max="2099" required>
                            </div>
                            <div class="col-md-4">
                                <label for="role_${index}" class="form-label">Your Role</label>
                                <input type="text" class="form-control role" id="role_${index}" name="grants[${index}][role]" placeholder="PI, Co-PI, Researcher, etc." required>
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

            // Remove grant entry
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-grant')) {
                    const entry = e.target.closest('.grant-entry');
                    if (document.querySelectorAll('.grant-entry').length > 1) {
                        entry.remove();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cannot Remove',
                            text: 'You must have at least one grant entry.',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                }
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
                            </div>
                            <div class="col-md-6">
                                <label for="title_${index}" class="form-label">Title/Event Name</label>
                                <input type="text" class="form-control title" id="title_${index}" name="activities[${index}][title]" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date_${index}" class="form-label">Date</label>
                                <input type="date" class="form-control date" id="date_${index}" name="activities[${index}][date]" required>
                            </div>
                            <div class="col-md-4">
                                <label for="role_${index}" class="form-label">Your Role</label>
                                <input type="text" class="form-control role" id="role_${index}" name="activities[${index}][role]" placeholder="Speaker, Organizer, Participant, etc." required>
                            </div>
                            <div class="col-md-4">
                                <label for="location_${index}" class="form-label">Location</label>
                                <input type="text" class="form-control location" id="location_${index}" name="activities[${index}][location]" required>
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

            // Remove activity entry
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-activity')) {
                    const entry = e.target.closest('.activity-entry');
                    if (document.querySelectorAll('.activity-entry').length > 1) {
                        entry.remove();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cannot Remove',
                            text: 'You must have at least one activity entry.',
                            confirmButtonColor: 'var(--primary-blue)'
                        });
                    }
                }
            });

            // Submit all data
            document.getElementById('submit-all').addEventListener('click', function() {
                // Save current section data first
                saveCurrentSectionData();

                // Validate last section
                if (!validateCurrentSection()) {
                    return;
                }

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
                            const modal = new bootstrap.Modal(document.getElementById(
                                'confirmationModal'));
                            modal.show();

                            // Reset form (optional)
                            // document.querySelectorAll('form').forEach(form => form.reset());
                            // formData = {};
                            // currentSection = 1;
                            // updateProgress();
                            // navigateToSection(1);
                        }, 1500);
                    }
                });
            });

            // Initialize progress
            updateProgress();
        });
    </script>


<!-- // Track current step and completed steps -->
    <script>
// Track current step and completed steps
let currentStep = 1;
const totalSteps = 5;
const completedSteps = new Set([1]); // Start with step 1 accessible

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
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
        alert("Please complete the current section before jumping ahead. You can only proceed to the next step after completing the current one.");
    }
}

function navigateToStep(stepNumber) {
    // Update current step
    currentStep = stepNumber;
    
    // Update UI
    updateProgressUI();
    showFormSection(stepNumber);
    
    // Update button states
    document.querySelector('.prev-button').disabled = (stepNumber === 1);
}

function validateCurrentStep() {
    let isValid = true;
    let missingFields = [];

    switch(currentStep) {
        case 1: // Biodata
            if (!document.getElementById('first_name').value.trim()) {
                missingFields.push('First Name');
            }
            if (!document.getElementById('last_name').value.trim()) {
                missingFields.push('Last Name');
            }
            if (!document.getElementById('email').value.trim()) {
                missingFields.push('Email');
            }
            if (!document.getElementById('employee_id').value.trim()) {
                missingFields.push('Employee ID');
            }
            break;

        case 2: // Degrees
            const degreeNames = document.querySelectorAll('.degree-name');
            if (degreeNames.length === 0) {
                missingFields.push('At least one degree');
            } else {
                degreeNames.forEach((input, index) => {
                    if (!input.value.trim()) {
                        missingFields.push(`Degree #${index + 1} name`);
                    }
                });
            }
            break;

        case 3: // Publications
            const publicationTitles = document.querySelectorAll('.title');
            if (publicationTitles.length === 0) {
                missingFields.push('At least one publication');
            } else {
                publicationTitles.forEach((input, index) => {
                    if (!input.value.trim()) {
                        missingFields.push(`Publication #${index + 1} title`);
                    }
                });
            }
            break;

        case 4: // Grants
            const grantTitles = document.querySelectorAll('.grant-name');
            if (grantTitles.length === 0) {
                missingFields.push('At least one grant');
            } else {
                grantTitles.forEach((input, index) => {
                    if (!input.value.trim()) {
                        missingFields.push(`Grant #${index + 1} name`);
                    }
                });
            }
            break;

        case 5: // Activities
            const activityTitles = document.querySelectorAll('.activity-entry .title');
            if (activityTitles.length === 0) {
                missingFields.push('At least one activity');
            } else {
                activityTitles.forEach((input, index) => {
                    if (!input.value.trim()) {
                        missingFields.push(`Activity #${index + 1} title`);
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

function showValidationError(missingFields) {
    const errorMessage = `Please complete the following required fields:\n\n• ${missingFields.join('\n• ')}`;
    
    // Create a more user-friendly notification
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger';
    errorDiv.innerHTML = `
        <strong>Missing Information!</strong>
        <ul class="mb-0">
            ${missingFields.map(field => `<li>${field}</li>`).join('')}
        </ul>
    `;
    
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-danger');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Insert before the navigation buttons
    const navButtons = document.querySelector('.nav-buttons');
    if (navButtons) {
        navButtons.parentNode.insertBefore(errorDiv, navButtons);
    } else {
        document.querySelector('.form-section.active').appendChild(errorDiv);
    }
    
    // Scroll to the error message
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Add shake animation to highlight missing fields
    missingFields.forEach(fieldName => {
        const fieldElement = getFieldElement(fieldName);
        if (fieldElement) {
            fieldElement.classList.add('is-invalid');
            fieldElement.addEventListener('animationend', () => {
                fieldElement.classList.remove('is-invalid');
            }, { once: true });
        }
    });
}

// Helper function to map field names to elements
function getFieldElement(fieldName) {
    if (fieldName.includes('Degree #')) {
        const index = parseInt(fieldName.replace('Degree #', '')) - 1;
        return document.querySelectorAll('.degree-name')[index];
    }
    if (fieldName.includes('Publication #')) {
        const index = parseInt(fieldName.replace('Publication #', '')) - 1;
        return document.querySelectorAll('.publication-entry .title')[index];
    }
    if (fieldName.includes('Grant #')) {
        const index = parseInt(fieldName.replace('Grant #', '')) - 1;
        return document.querySelectorAll('.grant-name')[index];
    }
    if (fieldName.includes('Activity #')) {
        const index = parseInt(fieldName.replace('Activity #', '')) - 1;
        return document.querySelectorAll('.activity-entry .title')[index];
    }
    
    switch(fieldName) {
        case 'First Name': return document.getElementById('first_name');
        case 'Last Name': return document.getElementById('last_name');
        case 'Email': return document.getElementById('email');
        case 'Employee ID': return document.getElementById('employee_id');
        default: return null;
    }
}

function validateAndContinue() {
    if (validateCurrentStep()) {
        completedSteps.add(currentStep);
        const nextStep = currentStep + 1;
        if (nextStep <= totalSteps) {
            goToStep(nextStep);
        } else {
            // Submit form if we're on the last step
            submitForm();
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
        step.classList.toggle('active', stepNum === currentStep);
        step.classList.toggle('completed', completedSteps.has(stepNum));
    });
    
    // Update progress bar
    document.querySelector('.progress-bar').style.width = `${((currentStep - 1) / (totalSteps - 1)) * 100}%`;
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
}

function submitForm() {
    // Here you would submit all the form data
    console.log('Form submitted!');
    
    // Show success modal
    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    modal.show();
}
</script>
</body>

</html>