# Table of Contents

## System Overview

## Installation Guide

## User Manual

## Login Process

## Dashboard Navigation

## Score Management

## Performance Visualization

## Suggestions System

## Role-Based Access

## Technical Specifications

## roubleshooting

## Contact SupportMUST HRM Expert Scorecard System

##### STARTING POINT

System Overview
The MUST HRM Expert Scorecard System is a comprehensive performance tracking solution designed to align employee performance with Mbarara University of Science and Technology's (MUST) vision, mission, and strategic objectives. The system provides:

Real-time performance tracking at individual, departmental, and institutional levels

Automated score calculations based on predefined criteria

Data visualization tools for performance analysis

AI-powered performance improvement suggestions

Role-specific dashboards for all university staff

Installation Guide
Prerequisites
Web server (Apache/Nginx)

PHP 7.4+

MySQL 5.7+

Composer (for dependency management)

Installation Steps
Clone the repository:

bash
Copy
git clone https://github.com/must-hrm/scorecard-system.git
cd scorecard-system
Install dependencies:

bash
Copy
composer install
npm install
Configure environment:

bash
Copy
cp .env.example .env
php artisan key:generate
Set up database:

bash
Copy
php artisan migrate --seed
Build assets:

bash
Copy
npm run prod
Set up cron job for automated tasks:

bash
Copy

- - - - - cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
