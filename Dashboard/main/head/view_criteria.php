<?php
session_start();
require_once 'approve/config.php';

// Check authentication
// if (!isset($_SESSION['staff_id']) || $_SESSION['user_role'] !== 'hrm') {
//     header('Location: /EMPLOYEE-TRACKING-SYSTEM/registration/register.php');
//     exit();
// }

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_criteria'])) {
        // Add new criteria
        $stmt = $conn->prepare("INSERT INTO criteria (category, name, points) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $_POST['category'], $_POST['name'], $_POST['points']);

        if ($stmt->execute()) {
            $message = "Criteria added successfully!";
            $message_type = "success";
        } else {
            $message = "Error adding criteria: " . $conn->error;
            $message_type = "danger";
        }
    } elseif (isset($_POST['update_criteria'])) {
        // Update existing criteria
        $stmt = $conn->prepare("UPDATE criteria SET category = ?, name = ?, points = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $_POST['category'], $_POST['name'], $_POST['points'], $_POST['id']);

        if ($stmt->execute()) {
            $message = "Criteria updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating criteria: " . $conn->error;
            $message_type = "danger";
        }
    } elseif (isset($_POST['delete_criteria'])) {
        // Delete criteria
        $stmt = $conn->prepare("DELETE FROM criteria WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);

        if ($stmt->execute()) {
            $message = "Criteria deleted successfully!";
            $message_type = "success";
        } else {
            $message = "Error deleting criteria: " . $conn->error;
            $message_type = "danger";
        }
    }
}

// Get all criteria grouped by category
$criteria = [];
$result = $conn->query("SELECT * FROM criteria ORDER BY category");
while ($row = $result->fetch_assoc()) {
    $criteria[$row['category']][] = $row;
}

// Get unique categories for dropdown
$categories = array_keys($criteria);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Criteria Management - MUST HRM</title>
    <link rel="stylesheet" href="../../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../components/bootstrap/css/bootstrap.min.css">
    <style>
        :root {
            --must-primary: #2e3192;
            --must-secondary: #FFC107;
            --must-accent: #4CAF50;
        }

        .push-right-240 {
            margin-left: 10%;
        }

        .must-bg-primary {
            background-color: var(--must-primary) !important;
        }

        .must-bg-secondary {
            background-color: var(--must-secondary) !important;
        }

        .must-text-primary {
            color: var(--must-primary) !important;
        }

        .must-text-secondary {
            color: var(--must-secondary) !important;
        }

        .criteria-card {
            border-left: 4px solid var(--must-primary);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .criteria-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-header {
            background-color: rgba(46, 49, 146, 0.1);
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .points-badge {
            background-color: var(--must-accent);
            color: white;
            font-weight: bold;
        }

        .edit-btn {
            color: var(--must-primary);
            border-color: var(--must-primary);
        }

        .edit-btn:hover {
            background-color: var(--must-primary);
            color: white;
        }

        .delete-btn {
            color: #dc3545;
            border-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #dc3545;
            color: white;
        }

        .add-criteria-btn {
            background-color: var(--must-accent);
            color: white;
        }

        .add-criteria-btn:hover {
            background-color: #3e8e41;
            color: white;
        }

        .search-box {
            border: 2px solid var(--must-primary);
        }

        @media (max-width: 768px) {
            .criteria-card {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <?php include '../bars/nav_bar.php'; ?>

    <!-- Sidebar -->
    <?php include '../bars/side_bar.php';
    ?>

    <!-- Main Content -->
    <div class="content-wrapper w-75 mx-auto">
        <div class="container-fluid mt-5 py-4 push-right-240">
            <?php if ($message): ?>
                <div class="alert alert-<?= $message_type ?> alert-dismissible fade show">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="must-text-primary">
                        <i class="fas fa-tasks me-2"></i> Performance Criteria Management
                    </h2>
                    <p class="text-muted">Manage scoring criteria for the Employee Tracking System</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn add-criteria-btn" data-bs-toggle="modal" data-bs-target="#addCriteriaModal">
                        <i class="fas fa-plus me-2"></i> Add New Criteria
                    </button>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-text must-bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control search-box" placeholder="Search criteria...">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php if (empty($criteria)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No criteria found. Add new criteria to get started.
                        </div>
                    <?php else: ?>
                        <?php foreach ($criteria as $category => $items): ?>
                            <div class="category-header">
                                <h5 class="mb-0 must-text-primary">
                                    <i class="fas fa-folder-open me-2"></i> <?= htmlspecialchars($category) ?>
                                </h5>
                            </div>

                            <div class="row">
                                <?php foreach ($items as $item): ?>
                                    <div class="col-md-6 col-lg-4 criteria-item" data-category="<?= htmlspecialchars($category) ?>">
                                        <div class="card criteria-card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">

                                                    <span class="badge points-badge">
                                                        <?= $item['points'] ?> pts
                                                    </span>
                                                </div>
                                                <p class="card-text text-muted small mb-2">
                                                    <i class="fas fa-tag me-1"></i> <?= htmlspecialchars($item['name']) ?>
                                                </p>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-sm edit-btn me-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editCriteriaModal"
                                                        data-id="<?= $item['id'] ?>"
                                                        data-category="<?= htmlspecialchars($item['category']) ?>"
                                                        data-name="<?= htmlspecialchars($item['name']) ?>"
                                                        data-points="<?= $item['points'] ?>">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </button>

                                                    <button class="btn btn-sm delete-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteCriteriaModal"
                                                        data-id="<?= $item['id'] ?>">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Criteria Modal -->
    <div class="modal fade" id="addCriteriaModal" tabindex="-1" aria-labelledby="addCriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header must-bg-primary text-white">
                    <h5 class="modal-title" id="addCriteriaModalLabel">
                        <i class="fas fa-plus-circle me-2"></i> Add New Criteria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="addCategory" name="category" required>
                            <small class="text-muted">E.g., "Academic Qualifications", "Research Grants"</small>
                        </div>
                        <div class="mb-3">
                            <label for="addName" class="form-label">System Name</label>
                            <input type="text" class="form-control" id="addName" name="name" required>
                            <small class="text-muted">Internal identifier (no spaces, use underscores)</small>
                        </div>
                       
                        <div class="mb-3">
                            <label for="addPoints" class="form-label">Points</label>
                            <input type="number" step="0.1" class="form-control" id="addPoints" name="points" required>
                            <small class="text-muted">Points awarded for this criteria</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_criteria" class="btn add-criteria-btn">Add Criteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Criteria Modal -->
    <div class="modal fade" id="editCriteriaModal" tabindex="-1" aria-labelledby="editCriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header must-bg-primary text-white">
                    <h5 class="modal-title" id="editCriteriaModalLabel">
                        <i class="fas fa-edit me-2"></i> Edit Criteria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="editId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="editCategory" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="editName" class="form-label">System Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editPoints" class="form-label">Points</label>
                            <input type="number" step="0.1" class="form-control" id="editPoints" name="points" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_criteria" class="btn add-criteria-btn">Update Criteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Criteria Modal -->
    <div class="modal fade" id="deleteCriteriaModal" tabindex="-1" aria-labelledby="deleteCriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteCriteriaModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="deleteId">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the following criteria?</p>
                        <p class="fw-bold" id="deleteCriteriaName"></p>
                        <p class="text-danger">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_criteria" class="btn btn-danger">Delete Criteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../components/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit modal population
        document.getElementById('editCriteriaModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const modal = this;

            modal.querySelector('#editId').value = button.getAttribute('data-id');
            modal.querySelector('#editCategory').value = button.getAttribute('data-category');
            modal.querySelector('#editName').value = button.getAttribute('data-name');
            modal.querySelector('#editPoints').value = button.getAttribute('data-points');
        });

        // Delete modal population
        document.getElementById('deleteCriteriaModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const modal = this;

            modal.querySelector('#deleteId').value = button.getAttribute('data-id');
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const criteriaItems = document.querySelectorAll('.criteria-item');

            criteriaItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const category = item.getAttribute('data-category').toLowerCase();

                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide category headers based on visible items
            document.querySelectorAll('.category-header').forEach(header => {
                const category = header.textContent.toLowerCase();
                const categoryItems = document.querySelectorAll(`.criteria-item[data-category="${header.textContent}"]`);
                const hasVisibleItems = Array.from(categoryItems).some(item => item.style.display !== 'none');

                if (hasVisibleItems || category.includes(searchTerm)) {
                    header.style.display = 'block';
                } else {
                    header.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>