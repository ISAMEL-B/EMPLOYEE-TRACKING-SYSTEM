<?php
session_start();
include 'Dashboard/main/approve/config.php'; // Your database connection file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUST HRM Scorecard System</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="Dashboard/components/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="Dashboard/components/bootstrap/css/bootstrap.min.css">

    <style>
        :root {
            --must-primary: #003366;
            --must-secondary: #E67E22;
            --must-accent: #27AE60;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .must-header {
            background-color: var(--must-primary);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .must-logo {
            height: 80px;
            margin-right: 15px;
        }
        
        .welcome-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .welcome-card:hover {
            transform: translateY(-5px);
        }
        
        .card-primary {
            border-top: 5px solid var(--must-primary);
        }
        
        .card-secondary {
            border-top: 5px solid var(--must-secondary);
        }
        
        .card-accent {
            border-top: 5px solid var(--must-accent);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--must-primary);
        }
        
        .login-panel {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        
        .btn-must {
            background-color: var(--must-primary);
            color: white;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .btn-must:hover {
            background-color: #002244;
            color: white;
        }
        
        .must-footer {
            background-color: var(--must-primary);
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .system-highlights {
            background-color: rgba(0,51,102,0.05);
            border-radius: 10px;
            padding: 2rem;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="must-header text-center">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <img src="Dashboard/main/logo/mustlogo.png" alt="MUST Logo" class="must-logo">
                <div>
                    <h1 class="display-4 fw-bold">MUST HRM Scorecard System</h1>
                    <p class="lead">Comprehensive Performance Management for Academic Excellence</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="row">
            <!-- Welcome Message -->
            <div class="col-lg-8">
                <div class="welcome-card card card-primary">
                    <div class="card-body">
                        <h2 class="card-title fw-bold">Welcome to the MUST HRM Scorecard Portal</h2>
                        <p class="card-text lead">
                            The Mbarara University of Science and Technology HRM Scorecard System is a comprehensive 
                            platform designed to track, evaluate, and enhance the performance of our academic staff 
                            across teaching, research, and community engagement.
                        </p>
                        
                        <div class="system-highlights mt-4">
                            <h3 class="fw-bold mb-3">System Highlights</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Real-time performance tracking</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Comprehensive academic analytics</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Research output monitoring</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Community engagement metrics</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Student supervision tracking</li>
                                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Strategic decision support</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Feature Cards -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="welcome-card card card-primary h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line feature-icon"></i>
                                <h4 class="card-title fw-bold">Performance Metrics</h4>
                                <p class="card-text">
                                    Track key performance indicators across teaching, research, and community service.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="welcome-card card card-secondary h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-flask feature-icon"></i>
                                <h4 class="card-title fw-bold">Research Analytics</h4>
                                <p class="card-text">
                                    Monitor publications, citations, grants, and innovation outputs.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="welcome-card card card-accent h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users feature-icon"></i>
                                <h4 class="card-title fw-bold">Community Impact</h4>
                                <p class="card-text">
                                    Measure engagement in community service and student supervision.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Login Panel -->
            <div class="col-lg-4">
                <div class="login-panel sticky-top" style="top: 20px;">
                    <h3 class="fw-bold mb-4 text-center">Access Your Scorecard</h3>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-success">
                            You are already logged in. <a href="Dashboard/main/upload_csv.php" class="alert-link">Go to Dashboard</a>
                        </div>
                    <?php else: ?>
                        <p class="text-center mb-4">
                            Please login to access the HRM Scorecard System and view your performance metrics.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="registration/register.php" class="btn btn-must btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Login to Continue
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="small">
                                Need help accessing your account?<br>
                                Contact the HRM Department at <a href="mailto:hrm@must.ac.ug">hrm@must.ac.ug</a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- System Overview -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="welcome-card card">
                    <div class="card-body">
                        <h2 class="card-title fw-bold mb-4">About the HRM Scorecard System</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="fw-bold"><i class="fas fa-bullseye me-2 text-primary"></i> Our Mission</h4>
                                <p>
                                    To provide a transparent, data-driven approach to academic performance evaluation 
                                    that aligns with MUST's strategic objectives and promotes excellence in teaching, 
                                    research, and community engagement.
                                </p>
                                
                                <h4 class="fw-bold mt-4"><i class="fas fa-cogs me-2 text-primary"></i> How It Works</h4>
                                <p>
                                    The system aggregates data from multiple sources to create comprehensive performance 
                                    profiles for each academic staff member, providing actionable insights for both 
                                    individuals and administrators.
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h4 class="fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i> Key Metrics Tracked</h4>
                                <ul>
                                    <li>Teaching performance and student outcomes</li>
                                    <li>Research publications and citations</li>
                                    <li>Grant acquisition and innovation outputs</li>
                                    <li>Community engagement activities</li>
                                    <li>Student supervision and mentorship</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="must-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Mbarara University of Science and Technology</h5>
                    <p>
                        P.O. Box 1410, Mbarara, Uganda<br>
                        Phone: +256 414 668 971
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>HRM Scorecard System</h5>
                    <p>
                        &copy; <?php echo date('Y'); ?> MUST Human Resource Management<br>
                        Version 2.1.0
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple animation for feature cards
        $(document).ready(function() {
            $('.welcome-card').each(function(i) {
                $(this).delay(100 * i).animate({
                    opacity: 1
                }, 200);
            });
        });
    </script>
</body>
</html>