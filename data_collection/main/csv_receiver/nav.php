<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    /* Styling the navigation container */
    .nav-container {
      display: flex;
      justify-content: center;
      position: fixed;
      top: 0;
      width: 100%;
      background-color: #fff;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Adds a shadow */
      z-index: 1000;
    }

    .nav-tabs {
      display: flex;
      border-bottom: 2px solid #ddd;
      justify-content: center;
    }

    /* Styling each button in the navigation */
    .nav-tabs button {
      background-color: #f1f1f1;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      border-bottom: 2px solid transparent;
      font-size: 16px;
    }

    /* Active and hover styles */
    .nav-tabs button.active {
      background-color: #ddd;
      border-bottom: 2px solid #4CAF50; /* Active tab bottom border color */
    }

    .nav-tabs button:hover {
      background-color: #e0e0e0;
    }

    /* Content sections */
    .tab-content {
      padding: 20px;
      margin-top: 60px; /* To avoid overlap with the fixed nav bar */
    }

  </style>
</head>
<body>

  <div class="nav-container">
    <div class="nav-tabs" id="nav-tab" role="tablist">
      <button class="nav-link active" id="nav-home-tab" type="button" aria-controls="nav-home" aria-selected="true" onclick="goToPage('upload.php')">Home</button>
      <button class="nav-link" id="nav-profile-tab" type="button" aria-controls="nav-profile" aria-selected="false" onclick="goToPage('../modify/column.php')">Modify CSV Receiver</button>
      <button class="nav-link" id="nav-contact-tab" type="button" aria-controls="nav-contact" aria-selected="false" onclick="goToPage('criteria/criteria_upload.php')">modify Criteria</button>
      <button class="nav-link" id="nav-about-tab" type="button" aria-controls="nav-about" aria-selected="false" onclick="goToPage('#')">About</button>
      <button class="nav-link" id="nav-services-tab" type="button" aria-controls="nav-services" aria-selected="false" onclick="goToPage('#')">Services</button>
      <button class="nav-link" id="nav-blog-tab" type="button" style="color:red; border-radius:10px;" aria-controls="nav-blog" aria-selected="false" onclick="goToPage('../../register/logout.php')">Logout</button>
    </div>
  </div>

  <script>
    // Function to redirect to a different page
    function goToPage(pageUrl) {
      window.location.href = pageUrl;
    }
  </script>

</body>
</html>
