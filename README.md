# HRM System Documentation

## Overview

This Human Resource Management (HRM) system is designed for Mbarara University of Science and Technology (MUST) to manage academic staff performance, qualifications, research activities, and community engagement.

## Database Schema

### Core Tables

1. **faculties** - Stores university faculties
2. **departments** - Organizational units within faculties
3. **roles** - Academic staff positions (Professor, Lecturer, etc.)
4. **staff** - Main staff information with authentication

### Academic Profile

5. **degrees** - Academic qualifications of staff
6. **publications** - Research publications
7. **grants** - Research funding grants
8. **supervision** - Student supervision records
9. **innovations** - Intellectual property and innovations

### Service & Engagement

10. **service** - Administrative service roles
11. **communityservice** - Community engagement activities
12. **professionalbodies** - Professional organization memberships
13. **academicactivities** - Academic participation records

### Performance Management

14. **criteria** - Performance evaluation criteria
15. **performance_metrics** - Staff performance measurements

### System Management

16. **verification_documents** - Supporting documents for activities
17. **password_change_log** - Security audit trail
18. **csv_approvals** - Bulk data import management

## Key Features

### Staff Management

- Comprehensive staff profiles with academic and professional details
- Role-based access control (Admin, HRM, HOD, Dean, Staff)
- Performance tracking and scoring

### Research Tracking

- Publication records with authorship roles
- Grant management with amount tracking
- Innovation and intellectual property registry

### Academic Administration

- Student supervision tracking
- Department and faculty structure
- Community engagement documentation

### Data Management

- Bulk CSV import/approval system
- Document verification workflow
- Secure authentication system

## Installation

1. Create MySQL database: `CREATE DATABASE hrm_db;`
2. Import schema: `mysql -u username -p hrm_db < schema.sql`
3. Configure connection in application settings

## Usage Guidelines

### For Staff

- Maintain updated profile information
- Submit documentation for research activities
- Track community engagement hours

### For Administrators

- Monitor performance metrics
- Verify submitted documentation
- Generate reports on staff activities

## API Endpoints

`/api/staff` - Staff management  
`/api/research` - Research activities  
`/api/performance` - Performance metrics  
`/api/auth` - Authentication system

## Security

- Password hashing with bcrypt
- Role-based access control
- Activity verification workflow
- Password change auditing

## Reporting

System supports generation of:

- Individual staff performance reports
- Departmental activity summaries
- Research output analytics
- Community engagement metrics

## Maintenance

Regularly:

1. Backup database
2. Review verification queue
3. Update performance criteria as needed
4. Audit user accounts

## Support

Contact HRM Helpdesk:

- Email: isamelk@must.ac.ug
- Phone: +256757094854

---

**Version**: 2.1  
**Last Updated**: April 2025  
**System Owner**: MUST Human Resources Department
