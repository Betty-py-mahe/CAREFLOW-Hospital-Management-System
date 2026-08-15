# CAREFLOW Hospital Management System

CAREFLOW is a web-based **Hospital Management System** developed as a student project for the **MSc Health Informatics** program.

The system is designed to manage core hospital workflows through a centralized web application, including patient registration, doctor management, appointments, medical records, billing, reporting, and role-based access control.

---

## Project Overview

CAREFLOW provides a structured digital platform for managing common hospital administrative and clinical workflows.

The system includes:

* Patient Management
* Doctor Management
* Appointment Management
* Medical Record Management
* Billing Management
* Report Generation
* CSV, PDF, and DOCX report downloads
* Role-based access control
* Search functionality
* Edit and delete operations
* Hospital dashboard
* Centralized database management

The interface uses a consistent hospital-management design with a CAREFLOW header, sidebar navigation, system status, user information, and footer.

---

## Main Features

### 1. Patient Management

The Patient Management module allows authorized users to:

* Register new patients
* Store patient name, gender, age, phone number, and address
* View the patient list
* Search patients by name, phone, or address
* Edit patient information
* Delete patient records
* View a dedicated patient report
* Download patient reports

### 2. Doctor Management

The Doctor Management module provides:

* Doctor registration
* Doctor name and specialization management
* Email and phone information
* Doctor search
* Edit doctor details
* Delete doctor records
* Doctor reporting
* Report downloads

### 3. Appointment Management

The Appointment Management module supports:

* Booking appointments
* Selecting patients and doctors
* Appointment date and time
* Searching appointments
* Searching by patient or doctor
* Date-based appointment searching
* Viewing today's appointments
* Editing appointments
* Deleting appointments
* Appointment reporting
* Report downloads

### 4. Medical Record Management

The Medical Record module allows authorized clinical users to:

* Select a patient
* Record diagnosis
* Record treatment
* Record medicines
* View medical records
* Edit medical records
* Delete medical records
* Generate medical record reports
* Download reports

### 5. Billing Management

The Billing module provides:

* Patient selection
* Bill creation
* Amount entry
* Payment method selection
* Billing history
* Edit billing information
* Delete billing records
* Billing reports
* Report downloads

Supported payment methods include:

* Cash
* Card
* UPI
* Insurance

---

## Reporting System

Each major management module includes a **Report** and **Download** option.

The report pages provide dedicated views for:

* Patient reports
* Doctor reports
* Appointment reports
* Medical record reports
* Billing reports

The Download menu provides options for different report formats:

* CSV
* PDF
* DOCX

CSV reports are generated directly from the database records.

PDF reports are generated using **Dompdf**.

DOCX reports are generated using **PHPWord**.

---

## Role-Based Access Control

CAREFLOW uses role-based access control to restrict access to different modules.

Current roles include:

* System Administrator
* Doctor
* Nurse
* Reception
* Billing

Different modules require different authorized roles.

For example:

| Module                 | Authorized Roles                               |
| ---------------------- | ---------------------------------------------- |
| Patient Management     | System Administrator, Doctor, Nurse, Reception |
| Doctor Management      | System Administrator, Doctor                   |
| Appointment Management | System Administrator, Doctor, Nurse, Reception |
| Medical Records        | System Administrator, Doctor, Nurse            |
| Billing                | System Administrator, Billing                  |

Access control is handled through the project's centralized access-control system.

---

## Technology Stack

### Frontend

* HTML5
* CSS3
* JavaScript
* Font Awesome

### Backend

* PHP
* MySQL
* MySQLi

### Development Environment

* WAMP Server
* Apache
* PHP
* MySQL
* phpMyAdmin
* Git
* GitHub

### Report Generation

* Dompdf
* PHPWord
* Composer

---

## Project Structure

```text
HospitalProject_V2/
│
├── dashboard/
│   └── mango_dashboard.php
│
├── database/
│   └── peach_database.php
│
├── css/
│   └── watermelon_style.css
│
├── includes/
│   ├── coconut_header.php
│   ├── coconut_sidebar.php
│   ├── coconut_footer.php
│   └── coconut_access.php
│
├── images/
│   └── logo.png
│
├── patient/
│   ├── patient management files
│   ├── edit patient
│   └── delete patient
│
├── doctor/
│   ├── doctor management files
│   ├── edit doctor
│   └── delete doctor
│
├── appointment/
│   ├── appointment management files
│   ├── edit appointment
│   └── delete appointment
│
├── medical_record/
│   ├── medical record management files
│   ├── edit record
│   └── delete record
│
├── billing/
│   ├── billing management files
│   ├── edit bill
│   └── delete bill
│
├── reports/
│   ├── melon_patient_report.php
│   ├── melon_doctor_report.php
│   ├── melon_appointment_report.php
│   ├── melon_medical_record_report.php
│   └── melon_billing_report.php
│
├── downloads/
│   ├── patient_pdf.php
│   ├── patient_docx.php
│   └── other report download files
│
├── vendor/
│   └── Composer dependencies
│
├── composer.json
├── composer.lock
└── README.md
```

> The exact folder names may vary depending on the current project organization.

---

## Report Generation Dependencies

The project uses Composer to manage report-generation libraries.

### Dompdf

Dompdf is used to generate PDF reports.

Installed package:

```bash
composer require dompdf/dompdf
```

### PHPWord

PHPWord is used to generate Microsoft Word `.docx` reports.

Installed package:

```bash
composer require phpoffice/phpword
```

After installing the dependencies, Composer generates the required `vendor` directory and autoload files.

---

## Installation

### 1. Install WAMP

Install WAMP Server with:

* Apache
* MySQL
* PHP
* phpMyAdmin

### 2. Clone the Repository

```bash
git clone https://github.com/Betty-py-mahe/CAREFLOW-Hospital-Management-System.git
```

### 3. Move the Project

Place the project inside:

```text
C:\wamp64\www\
```

The final project path should be:

```text
C:\wamp64\www\HospitalProject_V2
```

### 4. Start WAMP

Start:

* Apache
* MySQL

### 5. Configure the Database

Create the required MySQL database using phpMyAdmin.

Update the database connection file:

```text
database/peach_database.php
```

with the appropriate database credentials.

### 6. Install Composer Dependencies

Open Git Bash inside the project directory:

```bash
cd /c/wamp64/www/HospitalProject_V2
```

Then run:

```bash
composer install
```

This installs the dependencies defined in `composer.json`.

### 7. Open the Application

Open the project through:

```text
http://localhost/HospitalProject_V2/
```

---

## Report Download System

The system uses a separate download layer for generated reports.

For example, the patient PDF report can be accessed through the application's download system.

Report formats are organized as:

```text
CSV
PDF
DOCX
```

This allows hospital records to be viewed within the application while also providing export functionality for reporting and documentation.

---

## User Interface

The CAREFLOW interface includes:

* Hospital management dashboard
* Navigation sidebar
* Header with CAREFLOW branding
* System status indicator
* Logged-in user display
* Current date display
* Settings button
* Consistent management-page layout
* Report and Download controls
* Responsive report tables
* Footer section

The CAREFLOW logo and visual styling are maintained through the project's centralized CSS and header files.

---

## Security Features

The project includes basic application-level security mechanisms such as:

* Session-based authentication
* Role-based authorization
* Restricted access to management modules
* Prepared statements for several database operations
* HTML output escaping in report pages
* Confirmation prompts before deletion

Example:

```php
requireRole([
    "System Administrator",
    "Doctor"
]);
```

This ensures that only authorized roles can access specific modules.

---

## Database Modules

The system currently works with major database entities including:

```text
patient
doctor
appointment
medical_record
billing
```

Relationships between these entities allow the system to connect:

```text
Patient
   │
   ├── Appointments
   │       └── Doctor
   │
   ├── Medical Records
   │
   └── Billing
```

---

## Current Development Status

### Completed

* [x] Login/session management
* [x] Role-based access control
* [x] Hospital dashboard
* [x] Patient management
* [x] Doctor management
* [x] Appointment management
* [x] Medical record management
* [x] Billing management
* [x] Search functionality
* [x] Edit functionality
* [x] Delete functionality
* [x] Report pages
* [x] CSV export
* [x] PDF report generation
* [x] DOCX report generation
* [x] Composer dependency management
* [x] CAREFLOW header and navigation
* [x] CAREFLOW logo integration
* [x] Report footer positioning

### Future Enhancements

Possible future improvements include:

* Advanced dashboard analytics
* Interactive charts
* Improved search and filtering
* Appointment status management
* Automated notifications
* Audit logs
* More granular permissions
* Improved validation
* Database backup functionality
* Enhanced security controls
* Responsive mobile interface

---

## Project Purpose

This project was developed as part of the **MSc Health Informatics** program to demonstrate the application of health informatics concepts in a practical hospital information-management environment.

The system combines healthcare workflow concepts with web-based information technology to provide a structured platform for managing hospital data.

---

## Author

**MSc Health Informatics Student**

Manipal Academy of Higher Education (MAHE)

---

## Repository

**CAREFLOW Hospital Management System**

GitHub:

https://github.com/Betty-py-mahe/CAREFLOW-Hospital-Management-System.git

---

## License

This project is developed as a student/academic project and is intended primarily for educational and demonstration purposes.
