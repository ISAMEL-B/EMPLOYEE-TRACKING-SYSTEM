<?php
session_start();
// Get current page name (handles URLs with parameters)
$current_uri = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_uri, PHP_URL_PATH));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - MUST HRM Expert Scorecard System</title>
    <link rel="icon" type="image/png" href="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/mustlogo.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/about_us_all.css">
</head>

<body>

<!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- About Header -->
        <div class="about-header">
            <div class="container py-5">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-3">MUST HRM Expert Scorecard System</h1>
                        <p class="lead mb-4">Aligning employee performance with MUST's vision, mission, and strategic objectives</p>
                        <a href="#features" class="btn btn-light btn-lg me-2">Explore Features</a>
                        <a href="#contact" class="btn btn-outline-light btn-lg">Contact Us</a>
                    </div>
                    <div class="col-lg-6">
                        <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/must_building2.jpeg" alt="MUST Campus" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container py-5">
            <!-- System Overview -->
            <section id="overview" class="mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="must-primary fw-bold">System Overview</h2>
                        <div class="border-bottom border-3 border-success w-25 my-3"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="lead">The MUST HRM Expert Scorecard System is a comprehensive performance management platform designed to:</p>
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item bg-transparent"><i class="fas fa-check-circle must-secondary me-2"></i> Track individual and departmental performance</li>
                            <li class="list-group-item bg-transparent"><i class="fas fa-check-circle must-secondary me-2"></i> Align activities with institutional goals</li>
                            <li class="list-group-item bg-transparent"><i class="fas fa-check-circle must-secondary me-2"></i> Provide data-driven insights for decision making</li>
                            <li class="list-group-item bg-transparent"><i class="fas fa-check-circle must-secondary me-2"></i> Generate automated performance reports</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="ratio ratio-16x9">
                            <iframe class="rounded shadow" src="https://www.youtube.com/embed/CARfBfJpUsU" title="System Overview" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Key Features -->
            <section id="features" class="mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="must-primary fw-bold">Key Features</h2>
                        <div class="border-bottom border-3 border-success w-25 my-3"></div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card feature-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                        <i class="fas fa-chart-line fa-2x must-secondary"></i>
                                    </div>
                                    <h4 class="mb-0">Performance Tracking</h4>
                                </div>
                                <p class="card-text">Real-time monitoring of KPIs at individual, departmental, and institutional levels with intuitive dashboards and visualizations.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card feature-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                        <i class="fas fa-bullseye fa-2x must-secondary"></i>
                                    </div>
                                    <h4 class="mb-0">Goal Alignment</h4>
                                </div>
                                <p class="card-text">Automatically links employee activities to MUST's strategic objectives, ensuring all work contributes to institutional success.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card feature-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                        <i class="fas fa-robot fa-2x must-secondary"></i>
                                    </div>
                                    <h4 class="mb-0">AI Suggestions</h4>
                                </div>
                                <p class="card-text">Intelligent recommendations for performance improvement based on analysis of top performers and institutional benchmarks.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mission & Vision -->
            <section id="mission" class="mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="must-primary fw-bold">Alignment with MUST</h2>
                        <div class="border-bottom border-3 border-success w-25 my-3"></div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card mission-vision-card h-100">
                            <div class="card-body">
                                <h3 class="card-title must-primary"><i class="fas fa-bullseye me-2"></i>Our Mission</h3>
                                <p class="card-text">To provide quality, relevant and practical education that meets the needs of the labour market and society through teaching, research and knowledge transfer.</p>
                                <p class="card-text">This system directly supports our mission by ensuring all employee activities are aligned with these objectives and measurable against clear performance indicators.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mission-vision-card h-100">
                            <div class="card-body">
                                <h3 class="card-title must-primary"><i class="fas fa-eye me-2"></i>Our Vision</h3>
                                <p class="card-text">To be a Centre of Academic and Professional Excellence.</p>
                                <p class="card-text">The scorecard system operationalizes this vision by establishing clear pathways for employee development and excellence tracking across all university functions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Development Team -->
            <section id="team" class="mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="must-primary fw-bold">Development Team</h2>
                        <div class="border-bottom border-3 border-success w-25 my-3"></div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card team-member-card h-100">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/isamel.png" class="card-img-top" alt="Team Member">
                            <div class="card-body text-center">
                                <h5 class="card-title must-primary">Byaruhanga Isamel</h5>
                                <p class="card-text text-muted">Project Lead</p>
                                <p class="card-text">HRM Specialist with 15 years experience in performance management systems.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card team-member-card h-100">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/praise1.png" class="card-img-top" alt="Team Member">
                            <div class="card-body text-center">
                                <h5 class="card-title must-primary">Mugabi Praise</h5>
                                <p class="card-text text-muted">Lead Developer</p>
                                <p class="card-text">Full-stack developer specializing in institutional management systems.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card team-member-card h-100">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/felix1.png" class="card-img-top" alt="Team Member">
                            <div class="card-body text-center">
                                <h5 class="card-title must-primary">Mutungi Felix</h5>
                                <p class="card-text text-muted">Data Analyst</p>
                                <p class="card-text">Expert in educational metrics and performance visualization.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card team-member-card h-100">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/main/logo/achiever.png" class="card-img-top" alt="Team Member">
                            <div class="card-body text-center">
                                <h5 class="card-title must-primary">Atwiine Achiever</h5>
                                <p class="card-text text-muted">UI/UX Designer</p>
                                <p class="card-text">Specializes in creating intuitive interfaces for complex systems.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="must-primary fw-bold">Contact Us</h2>
                        <div class="border-bottom border-3 border-success w-25 my-3"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title must-primary mb-4">Get in Touch</h4>
                                <form>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn must-bg-primary text-white">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title must-primary mb-4">Our Location</h4>
                                <div class="mb-4">
                                    <h5 class="must-secondary"><i class="fas fa-map-marker-alt me-2"></i>Address</h5>
                                    <p>Human Resource Department<br>
                                        Mbarara University of Science and Technology<br>
                                        P.O. Box 1410, Mbarara, Uganda</p>
                                </div>
                                <div class="mb-4">
                                    <h5 class="must-secondary"><i class="fas fa-phone me-2"></i>Phone</h5>
                                    <p>+256 414 123 4567</p>
                                </div>
                                <div class="mb-4">
                                    <h5 class="must-secondary"><i class="fas fa-envelope me-2"></i>Email</h5>
                                    <p>hrm@must.ac.ug</p>
                                </div>
                                <div class="mb-4">
                                    <h5 class="must-secondary"><i class="fas fa-clock me-2"></i>Support Hours</h5>
                                    <p>Monday - Friday: 8:00 AM - 5:00 PM<br>
                                        Saturday: 9:00 AM - 1:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>