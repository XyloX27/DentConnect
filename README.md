# DentConnect – Dental Clinic Management System

A complete web application for managing dental clinic operations with role-based access for patients, doctors, staff, and administrators.

## Features
- Separate dashboards for Patients, Doctors, Staff, and Admin
- Online appointment booking with real-time doctor list
- Automated appointment reminders (simulated)
- AI-powered X-ray analysis (mock demonstration)
- Patient medical history and records
- Staff appointment management (confirm, complete, cancel)
- Admin user management (add, edit, delete users)
- Responsive design with sky blue & light purple theme

## Technologies Used
- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5
- Font Awesome
- HTML5/CSS3

## Installation

1. Clone the repository or download the ZIP.
2. Copy `includes/config.example.php` to `includes/config.php` and update database credentials.
3. Import `database/dentconnect.sql` into your MySQL database using phpMyAdmin or command line.
4. Ensure the `uploads/` folder is writable (for X-ray images).
5. Run the project on a local server (XAMPP/WAMP) – point to the project root.

## Default Logins (after import)

| Role   | Email                     | Password  |
|--------|---------------------------|-----------|
| Admin  | admin@dentconnect.com     | admin123  |
| Doctor | doctor@dentconnect.com    | doctor123 |
| Staff  | staff@dentconnect.com     | staff123  |
| Patient| (register new)            |           |

## Folder Structure

- `admin/` – Admin dashboard and user management
- `doctor/` – Doctor dashboard
- `staff/` – Staff dashboard and appointment management
- `patient/` – Patient dashboard, booking, history, X-ray upload
- `includes/` – Header, footer, config, auth
- `assets/` – CSS, JS, images
- `uploads/` – Uploaded X-ray images
- `database/` – SQL dump file

## AI X-ray Analysis (Mock)

The system allows patients to upload dental X-rays. After upload, a mock analysis displays pre-defined findings with confidence percentages. This simulates an AI feature for demonstration purposes.

## License

This project is developed and all credential reserved by XyloX27."# DentConnect" 
"# DentConnect" 
