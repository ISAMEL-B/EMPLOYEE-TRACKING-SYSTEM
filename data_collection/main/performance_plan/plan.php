<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Plan Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .form-table th, .form-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .form-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .section-header {
            background-color: #e2e2e2;
            font-weight: bold;
            text-align: left;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: #fff;
        }
        .accept-btn {
            background-color: #008CBA;
            color: #fff;
        }
        .reject-btn {
            background-color: #f44336;
            color: #fff;
        }
        .hidden {
            display: none;
        }
        .showButton {
            background-color: lightgreen;
            margin-left: 5px;     
            color: black;         
            padding: 5px 10px;   
            border: none;         
            border-radius: 5px;   
            cursor: pointer;      
        }
        .msgdiv {
            text-align: center; /* Center align text */
            color: green; /* Set text color to green */
            margin: 20px; /* Optional: Add some margin */
            padding: 10px; /* Optional: Add some padding */
            border: 1px solid green; /* Optional: Add a green border */
            display: none; /* Hide it initially */
            background-color: #f0f0f0; /* Optional: Light background color */
            border-radius: 5px; /* Optional: Rounded corners */
        }
    </style>
    <script>
        function toggleAdditionalFields(section) {
            const additionalFields = document.querySelectorAll(`.${section}-more`);
            additionalFields.forEach(field => {
                field.classList.toggle('hidden');
            });
        }

        function validateForm(event) {
            const requiredFields = [
                ...document.querySelectorAll('input[type="text"]')
            ];
            const filledCount = requiredFields.filter(input => input.value.trim() !== '').length;

            if (filledCount < 15) {
                alert('Please fill in at least 15 columns.'); // Changed to 15 as per your validation logic
                event.preventDefault();
            }
        }

        // Function to show the message div
        function showMessage() {
            const messageDiv = document.getElementById('messageDiv');
            if (messageDiv.innerHTML.trim() !== '') { // Check if there's a message
                messageDiv.style.display = 'block'; // Show the message div
                // Set a timer to hide the div after 10 seconds
                setTimeout(() => {
                    messageDiv.style.display = 'none'; // Hide the message div
                }, 10000); // 10000 milliseconds = 10 seconds
            }
        }

        // Call the function to show the message after the DOM is loaded
        document.addEventListener('DOMContentLoaded', showMessage);
    </script>
</head>
<body>

<div class="form-container">
    <h2>Performance Plan Form</h2>
    <div class="msgdiv" id="messageDiv">
        <?php
            // Check if there's a success message in the session
            if (isset($_SESSION['success_message'])) {
                echo "<p>{$_SESSION['success_message']}</p>"; // Display the message
                unset($_SESSION['success_message']); // Clear the message from the session
            }
        ?>
    </div>
    
    <form action="process_form.php" method="post" onsubmit="validateForm(event)">
        <table class="form-table">
        <div>
            <label for="username">Name:</label>
            <input type="text" id="username" name="username" required>
        </div>
            <!-- Section: Teaching and Learning -->
            <tr>
                <td colspan="3" class="section-header">
                    Teaching and Learning 
                    <button class="showButton" type="button" onclick="toggleAdditionalFields('teaching')" style="float: right;">Show More</button>
                </td>
            </tr>
            <tr>
                <th>Key Performance Outputs</th>
                <th>Performance Indicator</th>
                <th>Performance Targets</th>
            </tr>
            <tr>
                <td><input type="text" name="teaching_outputs[]" ></td>
                <td><input type="text" name="teaching_indicators[]" ></td>
                <td><input type="text" name="teaching_targets[]" ></td>
            </tr>
            <tr class="teaching-more hidden">
                <td><input type="text" name="teaching_outputs[]"></td>
                <td><input type="text" name="teaching_indicators[]"></td>
                <td><input type="text" name="teaching_targets[]"></td>
            </tr>
            <tr class="teaching-more hidden">
                <td><input type="text" name="teaching_outputs[]"></td>
                <td><input type="text" name="teaching_indicators[]"></td>
                <td><input type="text" name="teaching_targets[]"></td>
            </tr>
            <tr class="teaching-more hidden">
                <td><input type="text" name="teaching_outputs[]"></td>
                <td><input type="text" name="teaching_indicators[]"></td>
                <td><input type="text" name="teaching_targets[]"></td>
            </tr>
            <tr class="teaching-more hidden">
                <td><input type="text" name="teaching_outputs[]"></td>
                <td><input type="text" name="teaching_indicators[]"></td>
                <td><input type="text" name="teaching_targets[]"></td>
            </tr>

            <!-- Section: Research, Innovations and Publications -->
            <tr>
                <td colspan="3" class="section-header">
                    Research, Innovations and Publications 
                    <button type="button" class="showButton" onclick="toggleAdditionalFields('research')" style="float: right;">Show More</button>
                </td>
            </tr>
            <tr>
                <th>Key Performance Outputs</th>
                <th>Performance Indicator</th>
                <th>Performance Targets</th>
            </tr>
            <tr>
                <td><input type="text" name="research_outputs[]" ></td>
                <td><input type="text" name="research_indicators[]" ></td>
                <td><input type="text" name="research_targets[]" ></td>
            </tr>
            <tr class="research-more hidden">
                <td><input type="text" name="research_outputs[]"></td>
                <td><input type="text" name="research_indicators[]"></td>
                <td><input type="text" name="research_targets[]"></td>
            </tr>
            <tr class="research-more hidden">
                <td><input type="text" name="research_outputs[]"></td>
                <td><input type="text" name="research_indicators[]"></td>
                <td><input type="text" name="research_targets[]"></td>
            </tr>
            <tr class="research-more hidden">
                <td><input type="text" name="research_outputs[]"></td>
                <td><input type="text" name="research_indicators[]"></td>
                <td><input type="text" name="research_targets[]"></td>
            </tr>
            <tr class="research-more hidden">
                <td><input type="text" name="research_outputs[]"></td>
                <td><input type="text" name="research_indicators[]"></td>
                <td><input type="text" name="research_targets[]"></td>
            </tr>

            <!-- Section: Community Engagement -->
            <tr>
                <td colspan="3" class="section-header">
                    Community Engagement 
                    <button type="button" class="showButton" onclick="toggleAdditionalFields('community')" style="float: right;">Show More</button>
                </td>
            </tr>
            <tr>
                <th>Key Performance Outputs</th>
                <th>Performance Indicator</th>
                <th>Performance Targets</th>
            </tr>
            <tr>
                <td><input type="text" name="community_outputs[]" ></td>
                <td><input type="text" name="community_indicators[]" ></td>
                <td><input type="text" name="community_targets[]" ></td>
            </tr>
            <tr class="community-more hidden">
                <td><input type="text" name="community_outputs[]"></td>
                <td><input type="text" name="community_indicators[]"></td>
                <td><input type="text" name="community_targets[]"></td>
            </tr>
            <tr class="community-more hidden">
                <td><input type="text" name="community_outputs[]"></td>
                <td><input type="text" name="community_indicators[]"></td>
                <td><input type="text" name="community_targets[]"></td>
            </tr>
            <tr class="community-more hidden">
                <td><input type="text" name="community_outputs[]"></td>
                <td><input type="text" name="community_indicators[]"></td>
                <td><input type="text" name="community_targets[]"></td>
            </tr>
            <tr class="community-more hidden">
                <td><input type="text" name="community_outputs[]"></td>
                <td><input type="text" name="community_indicators[]"></td>
                <td><input type="text" name="community_targets[]"></td>
            </tr>
        </table>

        <div class="button-group">
            <button type="submit" class="submit-btn">Submit</button>
            <button type="button" class="accept-btn">Accept</button>
            <button type="button" class="reject-btn">Reject</button>
        </div>
    </form>
</div>

</body>
</html>
