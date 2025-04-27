<?php
     // Database connection variables
     $dbuser = "root";
     $dbpass = "";
     $host = "localhost";
     $db = "hrm_db1";

     // Create connection using 'conn'
     $conn = new mysqli($host, $dbuser, $dbpass, $db);

     // Check connection
     if ($conn->connect_error) {
         die("❌ Connection failed: " . $conn->connect_error);
     } else {
         // echo "✅ Connected successfully.";
     }
?>