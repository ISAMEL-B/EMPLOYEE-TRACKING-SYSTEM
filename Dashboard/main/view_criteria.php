<?php
session_start();
include 'criteria/config.php';

// Load criteria from database
$categories = [];
$criteria_data = [];

$query = "SELECT * FROM criteria";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category']][$row['name']] = $row['name'];
        $criteria_data[$row['name']] = $row['points'];
    }
} else {
    $_SESSION['error_message'] = 'No criteria found in database';
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competence Scoring System - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="bars/nav_sidebar/nav_side_bar.css">
    <link rel="stylesheet" href="criteria/criteria.css">
    <style>
        /* Sidebar and Navigation Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 240px;
            background-color: #4CAF50;
            color: white;
            z-index: 1000;
            transition: transform 0.3s ease;
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        
        .hamburger {
            position: fixed;
            left: 15px;
            top: 15px;
            color: white;
            font-size: 20px;
            cursor: pointer;
            z-index: 1001;
        }
        
        .nav-container {
            left: 240px;
        }
        
        .main-content {
            margin-left: 240px;
        }
        
        /* Responsive styles */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .nav-container {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Role indicator */
        .role-indicator {
            position: fixed;
            top: 70px;
            right: 20px;
            background-color: rgb(69, 188, 45);
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            z-index: 1000;
        }
        
        /* Rest of your existing styles */
        .debug-info {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 15px;
        }
        
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .error-message {
            background-color: #f2dede;
            color: #a94442;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .category-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .category-header {
            background-color:rgb(202, 251, 187);
            color:rgb(46, 46, 46);
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .category-content {
            padding: 15px;
            display: none;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .editable-criteria {
            cursor: pointer;
            padding: 5px;
            display: inline-block;
        }
        
        .editable-criteria:hover {
            background-color: #f0f0f0;
        }
        
        .criteria-input {
            width: 100%;
            padding: 5px;
        }
        
        .add-criteria-input {
            width: 100%;
            padding: 8px;
        }
        
        .add-criteria-btn, .remove-criteria-btn {
            padding: 6px 12px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .remove-criteria-btn {
            background-color: #d9534f;
        }
        
        .save-button {
            padding: 10px 20px;
            background-color: #337ab7;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .container {
                padding: 10px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            .category-section {
                margin-bottom: 15px;
            }
            
            .category-header {
                padding: 10px;
            }
            
            .category-content {
                padding: 5px;
            }
            
            .add-criteria-row td {
                padding: 5px;
            }
            
            .save-button {
                width: 100%;
                padding: 10px;
            }
            
            .role-indicator {
                top: 60px;
                right: 10px;
                font-size: 0.8em;
                padding: 3px 8px;
            }
            
            .action-text {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php
    include 'bars/nav_sidebar/side_bar.php';
    include 'bars/nav_sidebar/nav_bar.php';
    ?>

    <div class="role-indicator">
        Logged in as: <?php echo strtoupper(htmlspecialchars($_SESSION['user_role'] ?? 'guest')); ?>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Competence Scoring System - Edit Criteria</h2>

            <!-- Debug info -->
            <div class="debug-info">
                Database Records: <?php
                                    include 'criteria/config.php';
                                    $result = $conn->query("SELECT COUNT(*) AS total FROM criteria");
                                    $row = $result->fetch_assoc();
                                    echo htmlspecialchars($row['total']);
                                    ?><br>
                Last Updated: <?php
                                $result = $conn->query("SELECT MAX(updated_at) AS last_updated FROM criteria");
                                $row = $result->fetch_assoc();
                                echo $row['last_updated'] ? htmlspecialchars($row['last_updated']) : 'Never';
                                $conn->close();
                                ?>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form id="criteria-form" action="criteria/criteria_process.php" method="POST">
                <?php foreach ($categories as $category => $items): ?>
                    <div class="category-section">
                        <div class="category-header" onclick="toggleCategory(this)">
                            <h3><?= htmlspecialchars($category) ?></h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <div class="category-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Points</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $key => $label): ?>
                                        <tr>
                                            <td>
                                                <span class="editable-criteria"
                                                    onclick="makeEditable(this)"
                                                    data-original="<?= htmlspecialchars($label) ?>">
                                                    <?= htmlspecialchars($label) ?>
                                                </span>
                                                <input type="hidden" name="criteria_names[<?= htmlspecialchars($key) ?>]"
                                                    value="<?= htmlspecialchars($label) ?>">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="criteria_values[<?= htmlspecialchars($key) ?>]"
                                                    value="<?= htmlspecialchars($criteria_data[$key] ?? '0') ?>"
                                                    required
                                                    min="0"
                                                    step="0.1">
                                            </td>
                                            <td>
                                                <button type="button" class="remove-criteria-btn"
                                                    onclick="removeCriteria(this)">
                                                    <i class="fas fa-trash"></i> <span class="action-text">Remove</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="add-criteria-row">
                                        <td colspan="2">
                                            <input type="text" class="add-criteria-input"
                                                placeholder="New criterion name">
                                        </td>
                                        <td>
                                            <button type="button" class="add-criteria-btn"
                                                onclick="addCriteria(this, '<?= htmlspecialchars($category) ?>')">
                                                <i class="fas fa-plus"></i> <span class="action-text">Add</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="save-button">
                    <i class="fas fa-save"></i> Save All Changes
                </button>
            </form>
        </div>
    </div>

    <script>
        // Sidebar functionality
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            
            if (sidebar.classList.contains('show')) {
                if (!overlay) {
                    const newOverlay = document.createElement('div');
                    newOverlay.className = 'sidebar-overlay';
                    newOverlay.onclick = toggleSidebar;
                    document.body.appendChild(newOverlay);
                }
                document.querySelector('.sidebar-overlay').style.display = 'block';
            } else {
                if (overlay) overlay.style.display = 'none';
            }
        }
        
        // Close sidebar when clicking outside on large screens
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const hamburger = document.querySelector('.hamburger');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth > 992 && sidebar.classList.contains('show')) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnHamburger = hamburger && hamburger.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnHamburger) {
                    toggleSidebar();
                }
            }
        });
        
        // Category toggling
        function toggleCategory(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector('i');
            
            if (content.style.display === 'block') {
                content.style.display = 'none';
                icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
            } else {
                content.style.display = 'block';
                icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
            }
        }
        
        // Editable criteria
        function makeEditable(element) {
            const originalValue = element.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'criteria-input';
            input.value = originalValue;
            
            function saveEdit() {
                const newValue = input.value.trim();
                const span = document.createElement('span');
                span.className = 'editable-criteria';
                span.textContent = newValue || originalValue;
                span.onclick = () => makeEditable(span);
                span.setAttribute('data-original', originalValue);
                
                input.replaceWith(span);
                
                // Update hidden input
                const hiddenInput = input.closest('tr').querySelector('input[type="hidden"]');
                if (hiddenInput) hiddenInput.value = newValue || originalValue;
            }
            
            input.addEventListener('blur', saveEdit);
            input.addEventListener('keypress', (e) => e.key === 'Enter' && saveEdit());
            
            element.replaceWith(input);
            input.focus();
        }
        
        // Add new criterion
        function addCriteria(button, category) {
            const row = button.closest('tr');
            const input = row.querySelector('.add-criteria-input');
            const criteriaName = input.value.trim();
            
            if (!criteriaName) return alert('Please enter a criterion name');
            
            const newKey = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td>
                    <span class="editable-criteria" onclick="makeEditable(this)" data-original="${criteriaName}">
                        ${criteriaName}
                    </span>
                    <input type="hidden" name="criteria_names[${newKey}]" value="${criteriaName}">
                </td>
                <td>
                    <input type="number" name="criteria_values[${newKey}]" value="0" required min="0" step="0.1">
                </td>
                <td>
                    <button type="button" class="remove-criteria-btn" onclick="removeCriteria(this)">
                        <i class="fas fa-trash"></i> <span class="action-text">Remove</span>
                    </button>
                </td>
            `;
            
            row.parentNode.insertBefore(newRow, row);
            input.value = '';
        }
        
        // Remove criterion
        function removeCriteria(button) {
            if (confirm('Are you sure you want to remove this criterion?')) {
                button.closest('tr').remove();
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Show first category by default
            const firstCategory = document.querySelector('.category-content');
            if (firstCategory) {
                firstCategory.style.display = 'block';
                firstCategory.previousElementSibling.querySelector('i')
                    .classList.replace('fa-chevron-down', 'fa-chevron-up');
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                // Hide/show action text based on screen size
                document.querySelectorAll('.action-text').forEach(text => {
                    text.style.display = window.innerWidth <= 768 ? 'none' : 'inline';
                });
                
                // Ensure sidebar is visible on large screens
                if (window.innerWidth > 992) {
                    document.querySelector('.sidebar').classList.remove('show');
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) overlay.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>