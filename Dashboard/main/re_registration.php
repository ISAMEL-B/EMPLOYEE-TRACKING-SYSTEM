<?php 
include "processes/re_registration_process.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_hrm ? 'Employee Profile Management' : 'Complete Your Profile' ?> - MUST HRM</title>
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="styles/re_registration.css">
    <style>
        .placeholder-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 3rem;
            text-align: center;
            margin-top: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px dashed #dee2e6;
        }
        
        .placeholder-icon {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        
        .placeholder-title {
            color: #343a40;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .placeholder-text {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .select2-container--default .select2-selection--single {
            height: 46px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 46px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }
        
        #resetSelection {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-left: none;
        }

        .input-group .form-select {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .form-select:focus {
            z-index: 1;
        }
    </style>
</head>

<body>
    <?php include 'bars/nav_bar.php'; ?>
    <?php include 'bars/side_bar.php'; ?>

    <div class="container">
        <div class="profile-completion-container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="completion-header">
                <h2><?= $is_hrm ? 'Employee Profile Management' : 'Update Your Profile' ?></h2>
                <p><?= $is_hrm ? 'Manage employee profiles' : 'Manage your personal information' ?></p>
            </div>

            <?php if ($is_hrm): ?>
                <div class="employee-select-container mb-4">
                    <label for="employeeSelect" class="form-label">Select Employee</label>
                    <div class="input-group">
                        <select id="employeeSelect" class="form-select">
                            <option value="">Search for an employee</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['user_id'] ?>" <?= isset($_GET['user_id']) && $_GET['user_id'] == $employee['user_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($employee['employee_id'] . ' - ' . $employee['first_name'] . ' ' . $employee['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($user): ?>
                            <button class="btn btn-outline-secondary" type="button" id="resetSelection">
                                <i class="fas fa-times"></i> Reset
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($is_hrm && !$user): ?>
                <div class="placeholder-container">
                    <div class="placeholder-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h4 class="placeholder-title">Select a staff member to update their profile</h4>
                    <p class="placeholder-text">Use the dropdown above to search and select a staff member to view and edit their profile information.</p>
                </div>
            <?php elseif ($user): ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="profile-photo-container">
                                <?php if (!empty($user['photo_path'])): ?>
                                    <img src="<?= htmlspecialchars($user['photo_path']) ?>" class="profile-photo" id="profilePhotoPreview">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                    <img src="" class="profile-photo d-none" id="profilePhotoPreview">
                                <?php endif; ?>
                                <div class="photo-upload-btn" onclick="document.getElementById('photoInput').click()">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(event)">
                            </div>
                            <small class="text-muted">Upload a clear passport photo</small>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="email" class="form-label">Institutional Email</label>
                                <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label required-field">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label required-field">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label required-field">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="required format 0701234567" pattern="^07[0-9]{8}$"
                                       value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="personal_email" class="form-label">Personal Email</label>
                                <input type="email" class="form-control" id="personal_email" name="personal_email"
                                       value="<?= htmlspecialchars($user['personal_email'] ?? '') ?>">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-must-primary">
                                    <i class="fas fa-save ms-2"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="../components/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#employeeSelect').select2({
                placeholder: "Search for an employee",
                allowClear: true,
                width: '100%'
            });

            $('#employeeSelect').on('change', function () {
                const userId = $(this).val();
                if (userId) {
                    window.location.href = '?user_id=' + userId;
                } else {
                    window.location.href = window.location.pathname;
                }
            });

            // Reset button functionality
            $('#resetSelection').on('click', function() {
                $('#employeeSelect').val('').trigger('change');
            });
        });

        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('profilePhotoPreview');
                preview.src = reader.result;
                preview.classList.remove('d-none');
                preview.parentElement.querySelector('.fa-user')?.classList.add('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        document.querySelector('form')?.addEventListener('submit', function (e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    valid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>