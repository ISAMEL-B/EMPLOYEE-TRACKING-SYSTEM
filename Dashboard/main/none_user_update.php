<?php include 'processes/none_user_update_process.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Profile Management - MUST HRM</title>
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
        
        .required-field::after {
            content: " *";
            color: red;
        }
        
        .profile-photo-container {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 20px;
            border: 3px solid #ddd;
            background-color: #f8f9fa;
        }
        
        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #0d6efd;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .btn-must-primary {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
        }
        
        .btn-must-primary:hover {
            background-color: #0b5ed7;
            color: white;
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
                <h2>Staff Profile Management</h2>
                <p>Manage staff profiles and personal information</p>
            </div>

            <div class="employee-select-container mb-4">
                <label for="employeeSelect" class="form-label">Select Staff</label>
                <div class="input-group">
                    <select id="employeeSelect" class="form-select">
                        <option value="">Search for a Staff Member</option>
                        <?php foreach ($staffs as $staff_member): ?>
                            <option value="<?= $staff_member['staff_id'] ?>" 
                                <?= (isset($_GET['staff_id']) && $_GET['staff_id'] == $staff_member['staff_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($staff_member['staff_id'] . ' - ' . $staff_member['first_name'] . ' ' . $staff_member['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($_GET['staff_id'])): ?>
                        <button class="btn btn-outline-secondary" type="button" id="resetSelection">
                            <i class="fas fa-times"></i> Reset
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!isset($_GET['staff_id'])): ?>
                <div class="placeholder-container">
                    <div class="placeholder-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h4 class="placeholder-title">Select a staff member to update their profile</h4>
                    <p class="placeholder-text">Use the dropdown above to search and select a staff member to view and edit their profile information.</p>
                </div>
            <?php elseif (isset($staff) && $staff): ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="staff_id" value="<?= $staff['staff_id'] ?>">

                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="profile-photo-container">
                                <?php if (!empty($staff['photo_path'])): ?>
                                    <img src="<?= htmlspecialchars($staff['photo_path']) ?>" class="profile-photo" id="profilePhotoPreview">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user fa-4x text-muted"></i>
                                    </div>
                                    <img src="" class="profile-photo d-none" id="profilePhotoPreview">
                                <?php endif; ?>
                                <!-- camera icon for uploading the pic // but removed since non user dont have UI Account of this system -->
                                <!-- <div class="photo-upload-btn" onclick="document.getElementById('photoInput').click()">
                                    <i class="fas fa-camera"></i>
                                </div> -->
                                <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none" onchange="previewPhoto(event)">
                            </div>
                            <small class="text-muted">Upload a clear passport photo (JPG/PNG, max 2MB)</small>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label required-field">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                           value="<?= htmlspecialchars($staff['first_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label required-field">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                           value="<?= htmlspecialchars($staff['last_name'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="scholar_type" class="form-label required-field">Scholar Type</label>
                                <input type="text" class="form-control" id="scholar_type" name="scholar_type"
                                           value="<?= htmlspecialchars($staff['scholar_type'] ?? '') ?>" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-must-primary">
                                    <i class="fas fa-save me-2"></i> Save Changes
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
            // Initialize Select2
            $('#employeeSelect').select2({
                placeholder: "Search for a staff member",
                allowClear: true,
                width: '100%'
            });

            // Handle staff selection change
            $('#employeeSelect').on('change', function () {
                const staffId = $(this).val();
                if (staffId) {
                    // Add staff_id to URL
                    const url = new URL(window.location.href);
                    url.searchParams.set('staff_id', staffId);
                    window.location.href = url.toString();
                } else {
                    // Remove staff_id from URL
                    const url = new URL(window.location.href);
                    url.searchParams.delete('staff_id');
                    window.location.href = url.toString();
                }
            });

            // Reset button functionality
            $('#resetSelection').on('click', function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('staff_id');
                window.location.href = url.toString();
            });
        });

        // Photo preview functionality
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            // Validate file type and size
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!validTypes.includes(file.type)) {
                alert('Please upload a JPG or PNG image file');
                return;
            }
            
            if (file.size > maxSize) {
                alert('Image size should not exceed 2MB');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePhotoPreview');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                
                // Hide the default icon
                const icon = preview.parentElement.querySelector('.fa-user');
                if (icon) icon.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }

        // Form validation
        document.querySelector('form')?.addEventListener('submit', function (e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    </script>
</body>
</html>