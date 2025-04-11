<?php
session_start();

// Load the JSON data
$json_file_path = 'criteria/criteria_data.json'; // Change this to your actual path
$criteria_data = json_decode(file_get_contents($json_file_path), true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competence Scoring System - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        h3 {
            background-color: rgb(144, 238, 144);
            /* Light Green */
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.5);
            /* Increased shadow intensity */
            color: black;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin: 20px auto;
            /* Centers the button horizontally */
            display: block;
            /* Makes the button take up full width of the parent block */
        }

        button:hover {
            background-color: #45a049;
        }

        .success-message,
        .error-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            /* Rounded corners */
        }

        .success-message {
            background-color: white;
            color: green;
            text-align: center;
        }

        .error-message {
            background-color: white;
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

    <div>
        <?php //include '../nav.php'; 
        ?>

    </div>

    <div class="container">
        <h2>Competence Scoring System - Edit Criteria</h2>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); // Remove message after displaying it
                ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']); // Remove message after displaying it
                ?>
            </div>
        <?php endif; ?>

        <form action="criteria_process.php" method="POST">
            <button type="submit">Update Criteria</button>
            <h3>Academic and Professional Qualifications (Non-clinical Scholars)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PhD [Lecturer]</td>
                        <td><input type="number" name="criteria[PhD_Lecturer]" value="<?php echo htmlspecialchars($criteria_data['PhD track [Lecturer]'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Masters</td>
                        <td><input type="number" name="criteria[Masters]" value="<?php echo htmlspecialchars($criteria_data['Masters'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Bachelor's (First Class)</td>
                        <td><input type="number" name="criteria[Bachelor's (First Class)]" value="<?php echo htmlspecialchars($criteria_data["Bachelor's (First Class)"] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Bachelor's (Second Upper)</td>
                        <td><input type="number" name="criteria[Bachelor's (Second Upper)]" value="<?php echo htmlspecialchars($criteria_data["Bachelor's (Second Upper)"] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Other Qualifications</td>
                        <td><input type="number" name="criteria[Other Qualifications]" value="<?php echo htmlspecialchars($criteria_data['Other Qualifications'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Peer-reviewed Journal (First author)</td>
                        <td><input type="number" name="criteria[Peer-reviewed Journal (First author)]" value="<?php echo htmlspecialchars($criteria_data['Peer-reviewed Journal (First author)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Peer-reviewed Journal (Corresponding author)</td>
                        <td><input type="number" name="criteria[Peer-reviewed Journal (Corresponding author)]" value="<?php echo htmlspecialchars($criteria_data['Peer-reviewed Journal (Corresponding author)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Peer-reviewed Journal (Co-author)</td>
                        <td><input type="number" name="criteria[Peer-reviewed Journal (Co-author)]" value="<?php echo htmlspecialchars($criteria_data['Peer-reviewed Journal (Co-author)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>1 point per year</td>
                        <td><input type="number" name="criteria[1 point per year]" value="<?php echo htmlspecialchars($criteria_data['1 point per year'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <!-- New Section for Clinical Scholars -->
            <h3>Academic and Professional Qualifications (Clinical Scholars)</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PhD or being on PhD track (added advantage)</td>
                        <td><input type="number" name="criteria[PhD or being on PhD track]" value="<?php echo htmlspecialchars($criteria_data['PhD or being on PhD track'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Masters</td>
                        <td><input type="number" name="criteria[Masters]" value="<?php echo htmlspecialchars($criteria_data['Masters'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Bachelor’s degree: First class</td>
                        <td><input type="number" name="criteria[Bachelor’s degree (First class)]" value="<?php echo htmlspecialchars($criteria_data["Bachelor’s degree (First class)"] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Bachelor’s degree: Second upper</td>
                        <td><input type="number" name="criteria[Bachelor’s degree (Second upper)]" value="<?php echo htmlspecialchars($criteria_data["Bachelor’s degree (Second upper)"] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Other academic and professional qualifications</td>
                        <td><input type="number" name="criteria[Other academic and professional qualifications]" value="<?php echo htmlspecialchars($criteria_data['Other academic and professional qualifications'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <h3>Research Grants and Collaborations</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>More than UGX 1,000,000,000</td>
                        <td><input type="number" name="criteria[More than UGX 1,000,000,000]" value="<?php echo htmlspecialchars($criteria_data['More than UGX 1,000,000,000'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>UGX 500,000,000 - 1,000,000,000</td>
                        <td><input type="number" name="criteria[UGX 500,000,000 - 1,000,000,000]" value="<?php echo htmlspecialchars($criteria_data['UGX 500,000,000 - 1,000,000,000'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>UGX 100,000,000 - 500,000,000</td>
                        <td><input type="number" name="criteria[UGX 100,000,000 - 500,000,000]" value="<?php echo htmlspecialchars($criteria_data['UGX 100,000,000 - 500,000,000'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Less than UGX 100,000,000</td>
                        <td><input type="number" name="criteria[Less than UGX 100,000,000]" value="<?php echo htmlspecialchars($criteria_data['Less than UGX 100,000,000'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <h3>Supervision of Students</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PhD Candidates (max 10)</td>
                        <td><input type="number" name="criteria[PhD Candidates (max 10)]" value="<?php echo htmlspecialchars($criteria_data['PhD Candidates (max 10)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Masters Candidates (max 5)</td>
                        <td><input type="number" name="criteria[Masters Candidates (max 5)]" value="<?php echo htmlspecialchars($criteria_data['Masters Candidates (max 5)'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit">Update Criteria</button>
            <h3>Teaching Courses</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>More than 6 Courses</td>
                        <td><input type="number" name="criteria[More than 6 Courses]" value="<?php echo htmlspecialchars($criteria_data['More than 6 Courses'] ?? ''); ?>" required required></td>
                    </tr>
                    <tr>
                        <td>4 - 6 Courses</td>
                        <td><input type="number" name="criteria[4 - 6 Courses]" value="<?php echo htmlspecialchars($criteria_data['4 - 6 Courses'] ?? ''); ?>" required required></td>
                    </tr>
                    <tr>
                        <td>Less than 4 Courses</td>
                        <td><input type="number" name="criteria[Less than 4 Courses]" value="<?php echo htmlspecialchars($criteria_data['Less than 4 Courses'] ?? ''); ?>" required required></td>
                    </tr>
                </tbody>
            </table>

            <h3>Intellectual Property</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Patent</td>
                        <td><input type="number" name="criteria[Patent]" value="<?php echo htmlspecialchars($criteria_data['Patent'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Utility Model</td>
                        <td><input type="number" name="criteria[Utility Model]" value="<?php echo htmlspecialchars($criteria_data['Utility Model'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Copyright</td>
                        <td><input type="number" name="criteria[Copyright]" value="<?php echo htmlspecialchars($criteria_data['Copyright'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Product</td>
                        <td><input type="number" name="criteria[Product]" value="<?php echo htmlspecialchars($criteria_data['Product'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Trademark</td>
                        <td><input type="number" name="criteria[Trademark]" value="<?php echo htmlspecialchars($criteria_data['Trademark'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <h3>Administrative Roles</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dean / Director</td>
                        <td><input type="number" name="criteria[Dean / Director]" value="<?php echo htmlspecialchars($criteria_data['Dean / Director'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Deputy Dean/Director</td>
                        <td><input type="number" name="criteria[Deputy Dean/Director]" value="<?php echo htmlspecialchars($criteria_data['Deputy Dean/Director'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Head of Department</td>
                        <td><input type="number" name="criteria[Head of Department]" value="<?php echo htmlspecialchars($criteria_data['Head of Department'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Other</td>
                        <td><input type="number" name="criteria[Other]" value="<?php echo htmlspecialchars($criteria_data['Other'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit">Update Criteria</button>
            <h3>Supervision of Students</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PhD Candidates (max 10)</td>
                        <td><input type="number" name="criteria[PhD Candidates (max 10)]" value="<?php echo htmlspecialchars($criteria_data['PhD Candidates (max 10)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Masters Candidates (max 5)</td>
                        <td><input type="number" name="criteria[Masters Candidates (max 5)]" value="<?php echo htmlspecialchars($criteria_data['Masters Candidates (max 5)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Undergraduate (max 3)</td>
                        <td><input type="number" name="criteria[Undergraduate (max 3)]" value="<?php echo htmlspecialchars($criteria_data['Undergraduate (max 3)'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <h3>International Collaboration</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Collaborations with international organizations</td>
                        <td><input type="number" name="criteria[Collaborations with international organizations]" value="<?php echo htmlspecialchars($criteria_data['Collaborations with international organizations'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Exchange programs with international institutions</td>
                        <td><input type="number" name="criteria[Exchange programs with international institutions]" value="<?php echo htmlspecialchars($criteria_data['Exchange programs with international institutions'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Joint research initiatives</td>
                        <td><input type="number" name="criteria[Joint research initiatives]" value="<?php echo htmlspecialchars($criteria_data['Joint research initiatives'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <!-- New Section for Teaching Assistants -->
            <h3>Teaching Assistants</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Teaching experience (max 3 years)</td>
                        <td><input type="number" name="criteria[Teaching experience (max 3 years)]" value="<?php echo htmlspecialchars($criteria_data['Teaching experience (max 3 years)'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Research contributions</td>
                        <td><input type="number" name="criteria[Research contributions]" value="<?php echo htmlspecialchars($criteria_data['Research contributions'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Participation in workshops or seminars</td>
                        <td><input type="number" name="criteria[Participation in workshops or seminars]" value="<?php echo htmlspecialchars($criteria_data['Participation in workshops or seminars'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>
            <h3>Overall</h3>
            <table>
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Overall Points</td>
                        <td><input type="number" name="criteria[Overall]" value="<?php echo htmlspecialchars($criteria_data['Overall'] ?? ''); ?>" required></td>
                    </tr>
                </tbody>
            </table>

            <button type="submit">Update Criteria</button>
        </form>

    </div>
</body>

</html>