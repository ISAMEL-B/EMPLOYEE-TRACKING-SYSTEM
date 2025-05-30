<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST University - Academic Audit Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --must-green: #006837;
            --must-yellow: #FFD700;
            --must-blue: #005BAA;
            --must-light-gray: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--must-light-gray);
        }
        
        .header {
            background-color: var(--must-green);
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .progress-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .progress {
            height: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        
        .progress-bar {
            background-color: var(--must-blue);
            transition: width 0.5s ease;
        }
        
        .form-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            display: none;
        }
        
        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--must-green);
        }
        
        .nav-pills .nav-link {
            color: var(--must-green);
        }
        
        .btn-primary {
            background-color: var(--must-blue);
            border-color: var(--must-blue);
        }
        
        .btn-primary:hover {
            background-color: #004a8f;
            border-color: #004a8f;
        }
        
        .btn-success {
            background-color: var(--must-green);
            border-color: var(--must-green);
        }
        
        .btn-success:hover {
            background-color: #005629;
            border-color: #005629;
        }
        
        .form-control:focus {
            border-color: var(--must-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 91, 170, 0.25);
        }
        
        .section-title {
            color: var(--must-green);
            border-bottom: 2px solid var(--must-yellow);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .activity-card {
            border-left: 4px solid var(--must-blue);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .activity-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .activity-title {
            color: var(--must-blue);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .required-field::after {
            content: " *";
            color: red;
        }
        
        .tab-content {
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="container">
            <h1>MUST University</h1>
            <h2>Academic Audit Form</h2>
        </div>
    </div>
    
    <div class="container">
        <!-- Progress Bar and Navigation -->
        <div class="progress-container">
            <h4 class="text-center mb-4">Form Completion Progress</h4>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
            
            <ul class="nav nav-pills justify-content-center" id="progressTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="biodata-tab" data-bs-toggle="pill" data-bs-target="#biodata" type="button" role="tab">1. Biodata</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="position-tab" data-bs-toggle="pill" data-bs-target="#position" type="button" role="tab">2. Position</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="performance-tab" data-bs-toggle="pill" data-bs-target="#performance" type="button" role="tab">3. Performance</button>
                </li>
            </ul>
        </div>
        
        <!-- Form Sections -->
        <div class="tab-content" id="formContent">
            <!-- Section 1: Biodata -->
            <div class="tab-pane fade show active" id="biodata" role="tabpanel" aria-labelledby="biodata-tab">
                <div class="form-section active" id="section1">
                    <h3 class="section-title">1. Biodata</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label required-field">First Name</label>
                            <input type="text" class="form-control" id="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="surname" class="form-label required-field">Surname</label>
                            <input type="text" class="form-control" id="surname" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label required-field">Gender</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Academic Qualifications</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" id="degreesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Degree Obtained</th>
                                    <th>Institution & Location</th>
                                    <th>Completion Date</th>
                                    <th>Field of Study</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="degree[]"></td>
                                    <td><input type="text" class="form-control" name="institution[]"></td>
                                    <td><input type="month" class="form-control" name="completionDate[]"></td>
                                    <td><input type="text" class="form-control" name="field[]"></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-primary" id="addDegree">Add Degree</button>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Professional Training and Fellowships</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" id="trainingTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Training/Fellowship</th>
                                    <th>Institution</th>
                                    <th>Completion Date</th>
                                    <th>Field/Certification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="training[]"></td>
                                    <td><input type="text" class="form-control" name="trainingInstitution[]"></td>
                                    <td><input type="month" class="form-control" name="trainingCompletion[]"></td>
                                    <td><input type="text" class="form-control" name="trainingField[]"></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-primary" id="addTraining">Add Training</button>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Are you currently pursuing any academic degree?</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pursuingDegree" id="pursuingYes" value="yes">
                                <label class="form-check-label" for="pursuingYes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pursuingDegree" id="pursuingNo" value="no" checked>
                                <label class="form-check-label" for="pursuingNo">No</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="currentDegreeInfo" style="display: none;">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Level of Course</label>
                                <select class="form-select" id="degreeLevel">
                                    <option value="">Select Level</option>
                                    <option value="bachelor">Bachelor's</option>
                                    <option value="masters">Master's</option>
                                    <option value="phd">PhD</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="commencementYear" class="form-label">Commencement Year</label>
                                <input type="number" class="form-control" id="commencementYear" min="1900" max="2099">
                            </div>
                            <div class="col-md-4">
                                <label for="sponsor" class="form-label">Sponsor (if any)</label>
                                <input type="text" class="form-control" id="sponsor">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="studyProgress" class="form-label">Comment on your study progress thus far</label>
                            <textarea class="form-control" id="studyProgress" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" disabled>Previous</button>
                        <button type="button" class="btn btn-primary next-section" data-next="section2">Save & Continue</button>
                    </div>
                </div>
            </div>
            
            <!-- Section 2: Position -->
            <div class="tab-pane fade" id="position" role="tabpanel" aria-labelledby="position-tab">
                <div class="form-section" id="section2">
                    <h3 class="section-title">2. Position Information</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="currentPosition" class="form-label required-field">Current Position</label>
                            <input type="text" class="form-control" id="currentPosition" required>
                        </div>
                        <div class="col-md-6">
                            <label for="facultyInstitute" class="form-label required-field">Faculty/Institute</label>
                            <input type="text" class="form-control" id="facultyInstitute" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="department" class="form-label required-field">Department</label>
                            <input type="text" class="form-control" id="department" required>
                        </div>
                        <div class="col-md-6">
                            <label for="firstAppointment" class="form-label required-field">Date of First Appointment to MUST</label>
                            <input type="month" class="form-control" id="firstAppointment" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="currentAppointment" class="form-label required-field">Date of Appointment to Current Position</label>
                            <input type="month" class="form-control" id="currentAppointment" required>
                        </div>
                        <div class="col-md-6">
                            <label for="yearsInPosition" class="form-label">Number of Years in Current Position</label>
                            <input type="number" class="form-control" id="yearsInPosition" min="0">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="lastPromotion" class="form-label">Date of Last Promotion</label>
                            <input type="month" class="form-control" id="lastPromotion">
                        </div>
                        <div class="col-md-6">
                            <label for="lastAppraisal" class="form-label">Academic Year of Last Appraisal</label>
                            <input type="text" class="form-control" id="lastAppraisal" placeholder="e.g., 2021/2022">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="appraisalRecommendation" class="form-label">Recommendation from Most Recent Appraisal</label>
                        <textarea class="form-control" id="appraisalRecommendation" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="positionComment" class="form-label">Comment on Your Current Position</label>
                        <textarea class="form-control" id="positionComment" rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary prev-section" data-prev="section1">Previous</button>
                        <button type="button" class="btn btn-primary next-section" data-next="section3">Save & Continue</button>
                    </div>
                </div>
            </div>
            
            <!-- Section 3: Performance Indicators -->
            <div class="tab-pane fade" id="performance" role="tabpanel" aria-labelledby="performance-tab">
                <div class="form-section" id="section3">
                    <h3 class="section-title">3. Performance Indicators</h3>
                    
                    <div class="accordion" id="performanceAccordion">
                        <!-- Activity 1: Workload Analysis -->
                        <div class="card activity-card mb-3">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0 activity-title">
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#activity1" aria-expanded="true" aria-controls="activity1">
                                        Activity 1: Workload Analysis
                                    </button>
                                </h5>
                            </div>
                            
                            <div id="activity1" class="collapse show" aria-labelledby="headingOne" data-bs-parent="#performanceAccordion">
                                <div class="card-body">
                                    <h6 class="mb-3">a) Weekly Workload for 2021/2022 Academic Year</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="workloadTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Semester</th>
                                                    <th>Courses/Activities</th>
                                                    <th>Lectures</th>
                                                    <th>Practical/Clinical</th>
                                                    <th>Tutorials/Seminars</th>
                                                    <th>Preparation</th>
                                                    <th>Marking</th>
                                                    <th>Others</th>
                                                    <th>Total Hours</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select class="form-select" name="semester[]">
                                                            <option value="sem1">Semester I</option>
                                                            <option value="sem2">Semester II</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="activity[]" placeholder="Course code/Activity"></td>
                                                    <td><input type="number" class="form-control" name="lectures[]" min="0" step="0.5"></td>
                                                    <td><input type="number" class="form-control" name="practical[]" min="0" step="0.5"></td>
                                                    <td><input type="number" class="form-control" name="tutorials[]" min="0" step="0.5"></td>
                                                    <td><input type="number" class="form-control" name="preparation[]" min="0" step="0.5"></td>
                                                    <td><input type="number" class="form-control" name="marking[]" min="0" step="0.5"></td>
                                                    <td><input type="number" class="form-control" name="others[]" min="0" step="0.5"></td>
                                                    <td><input type="number" class="form-control" name="total[]" min="0" step="0.5" readonly></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addWorkload">Add Row</button>
                                    </div>
                                    
                                    <h6 class="mb-3">b) Challenges in Carrying Out Your Workload</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="workloadChallenges" rows="3"></textarea>
                                    </div>
                                    
                                    <h6 class="mb-3">c) Potential Solutions to Address Challenges</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="workloadSolutions" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity 2: Research & Publications -->
                        <div class="card activity-card mb-3">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0 activity-title">
                                    <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#activity2" aria-expanded="false" aria-controls="activity2">
                                        Activity 2: Research & Publications
                                    </button>
                                </h5>
                            </div>
                            <div id="activity2" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#performanceAccordion">
                                <div class="card-body">
                                    <!-- Content for Activity 2 -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label for="firstAuthor" class="form-label">First-Author Publications</label>
                                            <input type="number" class="form-control" id="firstAuthor" min="0">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="coAuthor" class="form-label">Co-Authored Publications</label>
                                            <input type="number" class="form-control" id="coAuthor" min="0">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="totalPublications" class="form-label">Total Publications</label>
                                            <input type="number" class="form-control" id="totalPublications" min="0" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="totalCitations" class="form-label">Total Citations</label>
                                            <input type="number" class="form-control" id="totalCitations" min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="uploadedPublications" class="form-label">Publications Uploaded to MUST Repository</label>
                                        <input type="number" class="form-control" id="uploadedPublications" min="0">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Subscribed Publication Platforms</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="googleScholar">
                                            <label class="form-check-label" for="googleScholar">Google Scholar</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="researchGate">
                                            <label class="form-check-label" for="researchGate">ResearchGate</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="academia">
                                            <label class="form-check-label" for="academia">Academia</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="otherPlatform">
                                            <label class="form-check-label" for="otherPlatform">Other (specify)</label>
                                            <input type="text" class="form-control mt-1" id="otherPlatformSpecify" style="display: none;">
                                        </div>
                                    </div>
                                    
                                    <h6 class="mb-3">Publication Details</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="publicationsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Authors, Year</th>
                                                    <th>Title</th>
                                                    <th>Type</th>
                                                    <th>Journal/Publisher</th>
                                                    <th>Volume/Issue/ISBN</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="pubAuthors[]"></td>
                                                    <td><input type="text" class="form-control" name="pubTitle[]"></td>
                                                    <td>
                                                        <select class="form-select" name="pubType[]">
                                                            <option value="journal">Journal Article</option>
                                                            <option value="book">Book</option>
                                                            <option value="chapter">Book Chapter</option>
                                                            <option value="conference">Conference Proceedings</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="pubJournal[]"></td>
                                                    <td><input type="text" class="form-control" name="pubVolume[]"></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addPublication">Add Publication</button>
                                    </div>
                                    
                                    <!-- Conference attendance -->
                                    <h6 class="mb-3">Conferences Attended (2021/2022)</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="conferencesTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Conference Name</th>
                                                    <th>Role</th>
                                                    <th>Location</th>
                                                    <th>URL</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="confName[]"></td>
                                                    <td>
                                                        <select class="form-select" name="confRole[]">
                                                            <option value="presenter">Presenter</option>
                                                            <option value="organizer">Organizer</option>
                                                            <option value="attendee">Attendee</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="confLocation[]"></td>
                                                    <td><input type="url" class="form-control" name="confUrl[]"></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addConference">Add Conference</button>
                                    </div>
                                    
                                    <!-- Scientific communications -->
                                    <div class="mb-4">
                                        <label class="form-label">Scientific Communications in Media (2021/2022)</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sciComm" id="sciCommYes" value="yes">
                                            <label class="form-check-label" for="sciCommYes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sciComm" id="sciCommNo" value="no" checked>
                                            <label class="form-check-label" for="sciCommNo">No</label>
                                        </div>
                                        <div id="sciCommDetails" style="display: none; margin-top: 10px;">
                                            <textarea class="form-control" id="sciCommText" rows="3" placeholder="Provide details (platform, date, topic)"></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Challenges and solutions -->
                                    <h6 class="mb-3">Challenges in Publishing/Disseminating Research</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="publishingChallenges" rows="3"></textarea>
                                    </div>
                                    
                                    <h6 class="mb-3">Potential Solutions</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="publishingSolutions" rows="3"></textarea>
                                    </div>
                                    
                                    <!-- Ongoing grants -->
                                    <h6 class="mb-3">Ongoing Research Grants</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="ongoingGrantsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Grant Number</th>
                                                    <th>Funder</th>
                                                    <th>Amount (USD)</th>
                                                    <th>Duration</th>
                                                    <th>Role</th>
                                                    <th>Project Title</th>
                                                    <th>Collaborators</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="grantNumber[]"></td>
                                                    <td><input type="text" class="form-control" name="grantFunder[]"></td>
                                                    <td><input type="number" class="form-control" name="grantAmount[]" min="0" step="0.01"></td>
                                                    <td><input type="text" class="form-control" name="grantDuration[]" placeholder="Start - End"></td>
                                                    <td>
                                                        <select class="form-select" name="grantRole[]">
                                                            <option value="pi">Principal Investigator</option>
                                                            <option value="coi">Co-Investigator</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="grantTitle[]"></td>
                                                    <td><textarea class="form-control" name="grantCollaborators[]" rows="1"></textarea></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addOngoingGrant">Add Grant</button>
                                    </div>
                                    
                                    <!-- Completed grants -->
                                    <h6 class="mb-3">Completed Research Grants</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="completedGrantsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Grant Number</th>
                                                    <th>Funder</th>
                                                    <th>Amount (USD)</th>
                                                    <th>Duration</th>
                                                    <th>Role</th>
                                                    <th>Project Title</th>
                                                    <th>Beneficiaries</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="completedGrantNumber[]"></td>
                                                    <td><input type="text" class="form-control" name="completedGrantFunder[]"></td>
                                                    <td><input type="number" class="form-control" name="completedGrantAmount[]" min="0" step="0.01"></td>
                                                    <td><input type="text" class="form-control" name="completedGrantDuration[]" placeholder="Start - End"></td>
                                                    <td>
                                                        <select class="form-select" name="completedGrantRole[]">
                                                            <option value="pi">Principal Investigator</option>
                                                            <option value="coi">Co-Investigator</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="completedGrantTitle[]"></td>
                                                    <td><textarea class="form-control" name="completedGrantBeneficiaries[]" rows="1" placeholder="By gender"></textarea></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addCompletedGrant">Add Grant</button>
                                    </div>
                                    
                                    <!-- Grant challenges -->
                                    <h6 class="mb-3">Challenges in Writing/Implementing Grants</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="grantChallenges" rows="3"></textarea>
                                    </div>
                                    
                                    <h6 class="mb-3">Potential Solutions</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="grantSolutions" rows="3"></textarea>
                                    </div>
                                    
                                    <!-- Patents -->
                                    <h6 class="mb-3">Patents/Discoveries</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="patentsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Innovation Name</th>
                                                    <th>Year</th>
                                                    <th>Patent Company</th>
                                                    <th>Role</th>
                                                    <th>Collaborators</th>
                                                    <th>Income for MUST</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="patentName[]"></td>
                                                    <td><input type="number" class="form-control" name="patentYear[]" min="1900" max="2099"></td>
                                                    <td><input type="text" class="form-control" name="patentCompany[]"></td>
                                                    <td><input type="text" class="form-control" name="patentRole[]"></td>
                                                    <td><input type="text" class="form-control" name="patentCollaborators[]"></td>
                                                    <td><input type="number" class="form-control" name="patentIncome[]" min="0" step="0.01"></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addPatent">Add Patent</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity 3: Student Supervision -->
                        <div class="card activity-card mb-3">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-0 activity-title">
                                    <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#activity3" aria-expanded="false" aria-controls="activity3">
                                        Activity 3: Student Supervision
                                    </button>
                                </h5>
                            </div>
                            <div id="activity3" class="collapse" aria-labelledby="headingThree" data-bs-parent="#performanceAccordion">
                                <div class="card-body">
                                    <!-- Content for Activity 3 -->
                                    <h6 class="mb-3">a) Completed Supervisions (Masters/PhD)</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="completedSupervisionsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Research Title</th>
                                                    <th>Start Year</th>
                                                    <th>Completion Year</th>
                                                    <th>Role</th>
                                                    <th>Awarding University</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="completedStudentName[]"></td>
                                                    <td><input type="text" class="form-control" name="completedResearchTitle[]"></td>
                                                    <td><input type="number" class="form-control" name="completedStartYear[]" min="1900" max="2099"></td>
                                                    <td><input type="number" class="form-control" name="completedCompletionYear[]" min="1900" max="2099"></td>
                                                    <td>
                                                        <select class="form-select" name="completedSupervisionRole[]">
                                                            <option value="primary">Primary Supervisor</option>
                                                            <option value="co">Co-Supervisor</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="completedAwardingUni[]"></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addCompletedSupervision">Add Supervision</button>
                                    </div>
                                    
                                    <h6 class="mb-3">b) Ongoing Supervisions (Masters/PhD)</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered" id="ongoingSupervisionsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Student Name</th>
                                                    <th>Research Title</th>
                                                    <th>Start Year</th>
                                                    <th>Role</th>
                                                    <th>University</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="ongoingStudentName[]"></td>
                                                    <td><input type="text" class="form-control" name="ongoingResearchTitle[]"></td>
                                                    <td><input type="number" class="form-control" name="ongoingStartYear[]" min="1900" max="2099"></td>
                                                    <td>
                                                        <select class="form-select" name="ongoingSupervisionRole[]">
                                                            <option value="primary">Primary Supervisor</option>
                                                            <option value="co">Co-Supervisor</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="ongoingUniversity[]"></td>
                                                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button type="button" class="btn btn-sm btn-primary" id="addOngoingSupervision">Add Supervision</button>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="undergradSupervisions" class="form-label">c) Undergraduate Students Supervised to Completion (2021/2022)</label>
                                        <input type="number" class="form-control" id="undergradSupervisions" min="0">
                                    </div>
                                    
                                    <h6 class="mb-3">d) Challenges in Supervising Students</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="supervisionChallenges" rows="3"></textarea>
                                    </div>
                                    
                                    <h6 class="mb-3">e) Potential Solutions</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="supervisionSolutions" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity 4: Community Engagement -->
                        <div class="card activity-card mb-3">
                            <div class="card-header" id="headingFour">
                                <h5 class="mb-0 activity-title">
                                    <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#activity4" aria-expanded="false" aria-controls="activity4">
                                        Activity 4: Community Engagement
                                    </button>
                                </h5>
                            </div>
                            <div id="activity4" class="collapse" aria-labelledby="headingFour" data-bs-parent="#performanceAccordion">
                                <div class="card-body">
                                    <!-- Content for Activity 4 -->
                                    <div class="mb-4">
                                        <label class="form-label">Did you carry out community placements, internships, services, or clinical practices in 2021/2022?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="communityEngagement" id="commEngageYes" value="yes">
                                            <label class="form-check-label" for="commEngageYes">Yes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="communityEngagement" id="commEngageNo" value="no" checked>
                                            <label class="form-check-label" for="commEngageNo">No</label>
                                        </div>
                                    </div>
                                    
                                    <div id="communityEngagementDetails" style="display: none;">
                                        <div class="row mb-4">
                                            <div class="col-md-4">
                                                <label for="studentsSupervised" class="form-label">Students Supervised for Placements</label>
                                                <input type="number" class="form-control" id="studentsSupervised" min="0">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="communityOutreaches" class="form-label">Community Outreaches</label>
                                                <input type="number" class="form-control" id="communityOutreaches" min="0">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="clinicalBeneficiaries" class="form-label">Clinical Beneficiaries</label>
                                                <input type="number" class="form-control" id="clinicalBeneficiaries" min="0">
                                            </div>
                                        </div>
                                        
                                        <h6 class="mb-3">Placement Details</h6>
                                        <div class="table-responsive mb-4">
                                            <table class="table table-bordered" id="placementsTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Student Name/Reg Number</th>
                                                        <th>Course</th>
                                                        <th>Industry/Community Location</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="placementStudent[]"></td>
                                                        <td><input type="text" class="form-control" name="placementCourse[]"></td>
                                                        <td><input type="text" class="form-control" name="placementLocation[]"></td>
                                                        <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-primary" id="addPlacement">Add Placement</button>
                                        </div>
                                        
                                        <h6 class="mb-3">Clinical Practice Details (if applicable)</h6>
                                        <div class="mb-4">
                                            <textarea class="form-control" id="clinicalDetails" rows="3"></textarea>
                                        </div>
                                        
                                        <h6 class="mb-3">Community Outreach Programs</h6>
                                        <div class="table-responsive mb-4">
                                            <table class="table table-bordered" id="outreachTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Program Title</th>
                                                        <th>Date</th>
                                                        <th>Location</th>
                                                        <th>Beneficiaries</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="outreachTitle[]"></td>
                                                        <td><input type="month" class="form-control" name="outreachDate[]"></td>
                                                        <td><input type="text" class="form-control" name="outreachLocation[]"></td>
                                                        <td><input type="number" class="form-control" name="outreachBeneficiaries[]" min="0"></td>
                                                        <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-sm btn-primary" id="addOutreach">Add Outreach</button>
                                        </div>
                                        
                                        <h6 class="mb-3">Challenges in Community Engagement</h6>
                                        <div class="mb-4">
                                            <textarea class="form-control" id="communityChallenges" rows="3"></textarea>
                                        </div>
                                        
                                        <h6 class="mb-3">Potential Solutions</h6>
                                        <div class="mb-4">
                                            <textarea class="form-control" id="communitySolutions" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activity 5: University Environment -->
                        <div class="card activity-card mb-3">
                            <div class="card-header" id="headingFive">
                                <h5 class="mb-0 activity-title">
                                    <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#activity5" aria-expanded="false" aria-controls="activity5">
                                        Activity 5: University Environment
                                    </button>
                                </h5>
                            </div>
                            <div id="activity5" class="collapse" aria-labelledby="headingFive" data-bs-parent="#performanceAccordion">
                                <div class="card-body">
                                    <!-- Content for Activity 5 -->
                                    <h6 class="mb-3">Rate the University's Research and Teaching Environment (1-5, 5=Highest)</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Item</th>
                                                    <th>Quantity Score</th>
                                                    <th>Quality Score</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td rowspan="5">Teaching Facilities</td>
                                                    <td>Classrooms</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Laboratories</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Libraries</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Computer Labs</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Offices</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="3">Human Resources</td>
                                                    <td>Academic Staff</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Research Staff</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Support Staff</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="4">Resources</td>
                                                    <td>Computers</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Internet</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Lab Equipment</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <td>Textbooks</td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><select class="form-select"><option value="">Select</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select></td>
                                                    <td><input type="text" class="form-control"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <h6 class="mb-3">Challenges in Using MUST Research and Teaching Environment</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="environmentChallenges" rows="3"></textarea>
                                    </div>
                                    
                                    <h6 class="mb-3">Potential Solutions</h6>
                                    <div class="mb-4">
                                        <textarea class="form-control" id="environmentSolutions" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary prev-section" data-prev="section2">Previous</button>
                        <button type="button" class="btn btn-success" id="submitForm">Submit Form</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Form Submitted Successfully</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Thank you for completing the Academic Audit Form. Your submission has been received.</p>
                    <p>Please remember to print the fully filled copy and submit the hardcopy to your Head of Department or immediate supervisor.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printForm">Print Form</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Progress tracking
            const progressBar = document.querySelector('.progress-bar');
            const nextButtons = document.querySelectorAll('.next-section');
            const prevButtons = document.querySelectorAll('.prev-section');
            const progressTabs = document.querySelectorAll('#progressTabs .nav-link');
            const formSections = document.querySelectorAll('.form-section');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            let currentSection = 1;
            const totalSections = 3;
            
            // Initialize progress bar
            updateProgress();
            
            // Next button functionality
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const nextSection = this.getAttribute('data-next');
                    const currentTab = document.querySelector('.tab-pane.show.active');
                    const nextTab = currentTab.nextElementSibling;
                    
                    // Validate current section before proceeding
                    if (validateSection(currentSection)) {
                        // Hide current section
                        document.getElementById(`section${currentSection}`).classList.remove('active');
                        
                        // Show next section
                        document.getElementById(nextSection).classList.add('active');
                        
                        // Update current section
                        currentSection = parseInt(nextSection.replace('section', ''));
                        
                        // Update progress bar
                        updateProgress();
                        
                        // Update active tab
                        if (nextTab) {
                            const nextTabId = nextTab.id;
                            const nextTabButton = document.querySelector(`#progressTabs button[data-bs-target="#${nextTabId}"]`);
                            if (nextTabButton) {
                                progressTabs.forEach(tab => tab.classList.remove('active'));
                                nextTabButton.classList.add('active');
                                const tabInstance = bootstrap.Tab.getOrCreateInstance(nextTabButton);
                                tabInstance.show();
                            }
                        }
                        
                        // Scroll to top of next section
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        alert('Please fill in all required fields before proceeding.');
                    }
                });
            });
            
            // Previous button functionality
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const prevSection = this.getAttribute('data-prev');
                    const currentTab = document.querySelector('.tab-pane.show.active');
                    const prevTab = currentTab.previousElementSibling;
                    
                    // Hide current section
                    document.getElementById(`section${currentSection}`).classList.remove('active');
                    
                    // Show previous section
                    document.getElementById(prevSection).classList.add('active');
                    
                    // Update current section
                    currentSection = parseInt(prevSection.replace('section', ''));
                    
                    // Update progress bar
                    updateProgress();
                    
                    // Update active tab
                    if (prevTab) {
                        const prevTabId = prevTab.id;
                        const prevTabButton = document.querySelector(`#progressTabs button[data-bs-target="#${prevTabId}"]`);
                        if (prevTabButton) {
                            progressTabs.forEach(tab => tab.classList.remove('active'));
                            prevTabButton.classList.add('active');
                            const tabInstance = bootstrap.Tab.getOrCreateInstance(prevTabButton);
                            tabInstance.show();
                        }
                    }
                    
                    // Scroll to top of previous section
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
            
            // Tab click functionality
            progressTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-bs-target').replace('#', '');
                    const targetSection = targetId === 'biodata' ? 1 : targetId === 'position' ? 2 : 3;
                    
                    // Only allow navigation to sections that have been completed or are the next in sequence
                    if (targetSection <= currentSection) {
                        // Hide all sections
                        formSections.forEach(section => section.classList.remove('active'));
                        
                        // Show target section
                        document.getElementById(`section${targetSection}`).classList.add('active');
                        
                        // Update current section
                        currentSection = targetSection;
                        
                        // Update progress bar
                        updateProgress();
                    } else {
                        alert('Please complete the current section before jumping ahead.');
                        // Revert to current tab
                        const currentTabButton = document.querySelector(`#progressTabs button[data-bs-target="#${currentSection === 1 ? 'biodata' : currentSection === 2 ? 'position' : 'performance'}"]`);
                        if (currentTabButton) {
                            const tabInstance = bootstrap.Tab.getOrCreateInstance(currentTabButton);
                            tabInstance.show();
                        }
                    }
                });
            });
            
            // Update progress bar
            function updateProgress() {
                const progressPercentage = ((currentSection - 1) / (totalSections - 1)) * 100;
                progressBar.style.width = `${progressPercentage}%`;
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                progressBar.textContent = `${Math.round(progressPercentage)}%`;
            }
            
            // Validate section before proceeding
            function validateSection(sectionNumber) {
                let isValid = true;
                
                if (sectionNumber === 1) {
                    // Validate Biodata section
                    if (!document.getElementById('firstName').value.trim() || 
                        !document.getElementById('surname').value.trim() || 
                        !document.querySelector('input[name="gender"]:checked')) {
                        isValid = false;
                    }
                    
                    // Validate at least one degree is entered
                    const degreeInputs = document.querySelectorAll('#degreesTable input[name="degree[]"]');
                    let hasDegree = false;
                    degreeInputs.forEach(input => {
                        if (input.value.trim()) hasDegree = true;
                    });
                    
                    if (!hasDegree) {
                        alert('Please enter at least one academic qualification.');
                        isValid = false;
                    }
                } else if (sectionNumber === 2) {
                    // Validate Position section
                    if (!document.getElementById('currentPosition').value.trim() || 
                        !document.getElementById('facultyInstitute').value.trim() || 
                        !document.getElementById('department').value.trim() || 
                        !document.getElementById('firstAppointment').value.trim() || 
                        !document.getElementById('currentAppointment').value.trim()) {
                        isValid = false;
                    }
                }
                // Section 3 validation would be more complex, but we'll assume it's optional for this demo
                
                return isValid;
            }
            
            // Toggle current degree info
            document.querySelectorAll('input[name="pursuingDegree"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('currentDegreeInfo').style.display = 
                        document.getElementById('pursuingYes').checked ? 'block' : 'none';
                });
            });
            
            // Toggle scientific communications details
            document.querySelectorAll('input[name="sciComm"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('sciCommDetails').style.display = 
                        document.getElementById('sciCommYes').checked ? 'block' : 'none';
                });
            });
            
            // Toggle community engagement details
            document.querySelectorAll('input[name="communityEngagement"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.getElementById('communityEngagementDetails').style.display = 
                        document.getElementById('commEngageYes').checked ? 'block' : 'none';
                });
            });
            
            // Toggle other platform input
            document.getElementById('otherPlatform').addEventListener('change', function() {
                document.getElementById('otherPlatformSpecify').style.display = 
                    this.checked ? 'block' : 'none';
            });
            
            // Calculate total hours in workload table
            document.addEventListener('input', function(e) {
                if (e.target.closest('#workloadTable')) {
                    const row = e.target.closest('tr');
                    const inputs = row.querySelectorAll('input[type="number"]:not([name="total[]"])');
                    let total = 0;
                    
                    inputs.forEach(input => {
                        if (input.value) {
                            total += parseFloat(input.value) || 0;
                        }
                    });
                    
                    row.querySelector('input[name="total[]"]').value = total.toFixed(1);
                }
                
                // Calculate total publications
                if (e.target.id === 'firstAuthor' || e.target.id === 'coAuthor') {
                    const firstAuthor = parseInt(document.getElementById('firstAuthor').value) || 0;
                    const coAuthor = parseInt(document.getElementById('coAuthor').value) || 0;
                    document.getElementById('totalPublications').value = firstAuthor + coAuthor;
                }
            });
            
            // Add degree row
            document.getElementById('addDegree').addEventListener('click', function() {
                const tbody = document.querySelector('#degreesTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="degree[]"></td>
                    <td><input type="text" class="form-control" name="institution[]"></td>
                    <td><input type="month" class="form-control" name="completionDate[]"></td>
                    <td><input type="text" class="form-control" name="field[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add training row
            document.getElementById('addTraining').addEventListener('click', function() {
                const tbody = document.querySelector('#trainingTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="training[]"></td>
                    <td><input type="text" class="form-control" name="trainingInstitution[]"></td>
                    <td><input type="month" class="form-control" name="trainingCompletion[]"></td>
                    <td><input type="text" class="form-control" name="trainingField[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add workload row
            document.getElementById('addWorkload').addEventListener('click', function() {
                const tbody = document.querySelector('#workloadTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>
                        <select class="form-select" name="semester[]">
                            <option value="sem1">Semester I</option>
                            <option value="sem2">Semester II</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="activity[]" placeholder="Course code/Activity"></td>
                    <td><input type="number" class="form-control" name="lectures[]" min="0" step="0.5"></td>
                    <td><input type="number" class="form-control" name="practical[]" min="0" step="0.5"></td>
                    <td><input type="number" class="form-control" name="tutorials[]" min="0" step="0.5"></td>
                    <td><input type="number" class="form-control" name="preparation[]" min="0" step="0.5"></td>
                    <td><input type="number" class="form-control" name="marking[]" min="0" step="0.5"></td>
                    <td><input type="number" class="form-control" name="others[]" min="0" step="0.5"></td>
                    <td><input type="number" class="form-control" name="total[]" min="0" step="0.5" readonly></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add publication row
            document.getElementById('addPublication').addEventListener('click', function() {
                const tbody = document.querySelector('#publicationsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="pubAuthors[]"></td>
                    <td><input type="text" class="form-control" name="pubTitle[]"></td>
                    <td>
                        <select class="form-select" name="pubType[]">
                            <option value="journal">Journal Article</option>
                            <option value="book">Book</option>
                            <option value="chapter">Book Chapter</option>
                            <option value="conference">Conference Proceedings</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="pubJournal[]"></td>
                    <td><input type="text" class="form-control" name="pubVolume[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add conference row
            document.getElementById('addConference').addEventListener('click', function() {
                const tbody = document.querySelector('#conferencesTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="confName[]"></td>
                    <td>
                        <select class="form-select" name="confRole[]">
                            <option value="presenter">Presenter</option>
                            <option value="organizer">Organizer</option>
                            <option value="attendee">Attendee</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="confLocation[]"></td>
                    <td><input type="url" class="form-control" name="confUrl[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add ongoing grant row
            document.getElementById('addOngoingGrant').addEventListener('click', function() {
                const tbody = document.querySelector('#ongoingGrantsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="grantNumber[]"></td>
                    <td><input type="text" class="form-control" name="grantFunder[]"></td>
                    <td><input type="number" class="form-control" name="grantAmount[]" min="0" step="0.01"></td>
                    <td><input type="text" class="form-control" name="grantDuration[]" placeholder="Start - End"></td>
                    <td>
                        <select class="form-select" name="grantRole[]">
                            <option value="pi">Principal Investigator</option>
                            <option value="coi">Co-Investigator</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="grantTitle[]"></td>
                    <td><textarea class="form-control" name="grantCollaborators[]" rows="1"></textarea></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add completed grant row
            document.getElementById('addCompletedGrant').addEventListener('click', function() {
                const tbody = document.querySelector('#completedGrantsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="completedGrantNumber[]"></td>
                    <td><input type="text" class="form-control" name="completedGrantFunder[]"></td>
                    <td><input type="number" class="form-control" name="completedGrantAmount[]" min="0" step="0.01"></td>
                    <td><input type="text" class="form-control" name="completedGrantDuration[]" placeholder="Start - End"></td>
                    <td>
                        <select class="form-select" name="completedGrantRole[]">
                            <option value="pi">Principal Investigator</option>
                            <option value="coi">Co-Investigator</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="completedGrantTitle[]"></td>
                    <td><textarea class="form-control" name="completedGrantBeneficiaries[]" rows="1" placeholder="By gender"></textarea></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add patent row
            document.getElementById('addPatent').addEventListener('click', function() {
                const tbody = document.querySelector('#patentsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="patentName[]"></td>
                    <td><input type="number" class="form-control" name="patentYear[]" min="1900" max="2099"></td>
                    <td><input type="text" class="form-control" name="patentCompany[]"></td>
                    <td><input type="text" class="form-control" name="patentRole[]"></td>
                    <td><input type="text" class="form-control" name="patentCollaborators[]"></td>
                    <td><input type="number" class="form-control" name="patentIncome[]" min="0" step="0.01"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add completed supervision row
            document.getElementById('addCompletedSupervision').addEventListener('click', function() {
                const tbody = document.querySelector('#completedSupervisionsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="completedStudentName[]"></td>
                    <td><input type="text" class="form-control" name="completedResearchTitle[]"></td>
                    <td><input type="number" class="form-control" name="completedStartYear[]" min="1900" max="2099"></td>
                    <td><input type="number" class="form-control" name="completedCompletionYear[]" min="1900" max="2099"></td>
                    <td>
                        <select class="form-select" name="completedSupervisionRole[]">
                            <option value="primary">Primary Supervisor</option>
                            <option value="co">Co-Supervisor</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="completedAwardingUni[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add ongoing supervision row
            document.getElementById('addOngoingSupervision').addEventListener('click', function() {
                const tbody = document.querySelector('#ongoingSupervisionsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="ongoingStudentName[]"></td>
                    <td><input type="text" class="form-control" name="ongoingResearchTitle[]"></td>
                    <td><input type="number" class="form-control" name="ongoingStartYear[]" min="1900" max="2099"></td>
                    <td>
                        <select class="form-select" name="ongoingSupervisionRole[]">
                            <option value="primary">Primary Supervisor</option>
                            <option value="co">Co-Supervisor</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control" name="ongoingUniversity[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add placement row
            document.getElementById('addPlacement').addEventListener('click', function() {
                const tbody = document.querySelector('#placementsTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="placementStudent[]"></td>
                    <td><input type="text" class="form-control" name="placementCourse[]"></td>
                    <td><input type="text" class="form-control" name="placementLocation[]"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Add outreach row
            document.getElementById('addOutreach').addEventListener('click', function() {
                const tbody = document.querySelector('#outreachTable tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="form-control" name="outreachTitle[]"></td>
                    <td><input type="month" class="form-control" name="outreachDate[]"></td>
                    <td><input type="text" class="form-control" name="outreachLocation[]"></td>
                    <td><input type="number" class="form-control" name="outreachBeneficiaries[]" min="0"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-row">Remove</button></td>
                `;
                tbody.appendChild(newRow);
            });
            
            // Remove row functionality
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    const row = e.target.closest('tr');
                    // Don't allow removal if it's the last row in a table
                    if (row.parentElement.querySelectorAll('tr').length > 1) {
                        row.remove();
                    } else {
                        alert('You must have at least one row in the table.');
                    }
                }
            });
            
            // Form submission
            document.getElementById('submitForm').addEventListener('click', function() {
                if (validateSection(currentSection)) {
                    // In a real application, you would send the form data to the server here
                    // For this demo, we'll just show a success message
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    
                    // Update progress to 100%
                    progressBar.style.width = '100%';
                    progressBar.setAttribute('aria-valuenow', '100');
                    progressBar.textContent = '100%';
                } else {
                    alert('Please complete all required fields before submitting.');
                }
            });
            
            // Print form
            document.getElementById('printForm').addEventListener('click', function() {
                window.print();
            });
        });
    </script>
</body>
</html>