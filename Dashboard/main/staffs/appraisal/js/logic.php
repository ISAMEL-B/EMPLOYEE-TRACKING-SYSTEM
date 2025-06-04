<script>
    // Global variables
    let currentStep = <?= $current_page ?>;
    const totalSteps = 5;
    const userId = <?= $user_id ?>;
    const progressData = <?= json_encode($progress_data) ?>;
    const userData = <?= json_encode($user_data) ?>;
    const departments = <?= json_encode($departments) ?>;

    // Initialize the form when DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        const photoInput = document.getElementById('photo');

        if (photoInput) {
            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        let preview = document.getElementById('photo-preview');

                        // Create preview if it doesn't exist
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.id = 'photo-preview';
                            preview.className = 'photo-preview';
                            photoInput.parentNode.appendChild(preview);
                        }

                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };

                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid image file.');
                }
            });
        }

        // Initialize other UI components
        setupDynamicFormElements?.();
        showFormSection?.(currentStep);
        updateProgressUI?.();
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
                    window.location.href = `appraisal_form.php?page=${nextStep}`;
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
            window.location.href = `appraisal_form.php?page=${stepNumber}`;
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
            window.location.href = 'appraisal_form.php?page=1';
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
    const entries = container.querySelectorAll(`.${entryClass}`);

    // Always allow removal if there's more than one entry
    if (entries.length > 1) {
        entry.remove();

        // Re-index remaining entries
        const updatedEntries = container.querySelectorAll(`.${entryClass}`);
        updatedEntries.forEach((entry, index) => {
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