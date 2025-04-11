<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competence Scoring Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        select, input[type="text"],input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Competence Scoring System Form</h2>
        <form action="" method="POST">
            <label for="qualification">Academic Qualification:</label>
            <select name="qualification" id="qualification">
                <option value="PhD">PhD</option>
                <option value="Masters">Masters</option>
                <option value="First Class Bachelor's">First Class Bachelor's</option>
                <option value="Second Upper Bachelor's">Second Upper Bachelor's</option>
                <option value="Other Qualification">Other Qualification</option>
            </select>

            <label for="publications">Publications:</label>
            <select name="publications" id="publications">
                <option value="Peer-reviewed journal article (First author)">Peer-reviewed journal article (First author)</option>
                <option value="Peer-reviewed journal article (Corresponding author)">Peer-reviewed journal article (Corresponding author)</option>
                <option value="Peer-reviewed journal article (Co-author)">Peer-reviewed journal article (Co-author)</option>
                <option value="Published book with ISBN">Published book with ISBN</option>
                <option value="Book chapter">Book chapter</option>
            </select>

            <label for="teaching_exp">Teaching Experience (years):</label>
            <input type="number" name="teaching_exp" id="teaching_exp" placeholder="Enter number of years" min="0" required>

            <label for="research_grants">Research Grants (UGX):</label>
            <input type="text" name="research_grants" id="research_grants" placeholder="Enter total grant amount">

            <label for="supervision">Supervision (Postgraduate Students):</label>
            <select name="supervision" id="supervision">
                <option value="PhD student">PhD student</option>
                <option value="Masters student">Masters student</option>
            </select>

            <label for="innovations">Innovations:</label>
            <select name="innovations" id="innovations">
                <option value="Patent">Patent</option>
                <option value="Utility model">Utility model</option>
                <option value="Copyright">Copyright</option>
                <option value="Product">Product</option>
                <option value="Trademark">Trademark</option>
            </select>

            <label for="community_service">Service to Community:</label>
            <input type="text" name="community_service" id="community_service" placeholder="Enter details of community service">

            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        // Associative array to store form data
        $criteria = [
            'Academic_Qualification' => $_POST['qualification'],
            'Publications' => $_POST['publications'],
            'Teaching_Experience_Years' => $_POST['teaching_exp'],
            'Research_Grants' => $_POST['research_grants'],
            'Supervision' => $_POST['supervision'],
            'Innovations' => $_POST['innovations'],
            'Community_Service' => $_POST['community_service']
        ];

        // Convert the associative array to JSON
        $json_data = json_encode($criteria, JSON_PRETTY_PRINT);

        // Write the JSON data to a file
        file_put_contents('criteria_json_file.json', $json_data);

        echo "<p>Data saved successfully!</p>";
    }
    ?>
</body>
</html>
