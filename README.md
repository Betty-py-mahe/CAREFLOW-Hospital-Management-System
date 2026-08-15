# CAREFLOW Hospital Management System

CAREFLOW is a web-based Hospital Management System developed to support the digital management of core hospital operations.

The system provides a centralized platform for managing patients, doctors, appointments, billing, medical records, reports, user access, and system settings.

## Project Overview

CAREFLOW was developed as a Health Informatics and healthcare IT project with the aim of improving the organization, accessibility, and management of hospital information.

The system replaces fragmented manual processes with a structured web-based interface where authorized users can manage hospital data according to their assigned roles.

## Key Features

* Patient registration and management
* Doctor registration and management
* Appointment scheduling and management
* Billing and payment management
* Medical record management
* Hospital reports and CSV export
* Role-based access control
* User management
* System settings
* Dashboard with hospital statistics
* Search and record management
* Edit and delete operations
* Responsive user interface
* Hospital-themed interface with custom background and branding

## Main Modules

### 1. Dashboard

The dashboard provides an overview of hospital operations and displays important information such as:

* Patient statistics
* Doctor statistics
* Appointment statistics
* Billing information
* Recent appointments
* Quick access to major hospital modules

### 2. Patient Management

The patient module allows authorized users to:

* Register patients
* Store patient information
* View patient records
* Edit patient information
* Delete patient records
* Search and manage registered patients

### 3. Doctor Management

The doctor module provides functionality for:

* Doctor registration
* Viewing doctor information
* Editing doctor details
* Deleting doctor records
* Managing doctor-related information

### 4. Appointment Management

The appointment module allows hospital staff to:

* Create appointments
* View appointment lists
* Edit appointments
* Delete appointments
* View patient and doctor information associated with appointments
* Export appointment information as CSV

### 5. Billing Management

The billing module supports:

* Creating billing records
* Linking bills with patients
* Recording billing amounts
* Recording payment methods
* Editing billing information
* Deleting billing records
* Viewing billing lists
* Exporting billing information as CSV

### 6. Medical Records

The medical record module allows authorized users to manage:

* Patient medical records
* Diagnoses
* Treatments
* Medicines
* Record editing
* Record deletion
* Medical record reports
* CSV export

### 7. Reports

CAREFLOW provides report pages for important hospital information, including:

* Patient reports
* Doctor reports
* Appointment reports
* Billing reports
* Medical record reports

Reports can also be exported in CSV format for further analysis or documentation.

### 8. User Management

The administration module provides functionality for managing system users and controlling access to hospital information.

### 9. Settings

The settings module provides configurable options related to:

* User preferences
* Shift status
* Notifications
* Care team information
* EHR and device information
* Security and system logs

## User Roles

CAREFLOW implements role-based access control so that users can access functionality according to their responsibilities.

The system includes roles such as:

* System Administrator
* Doctor
* Nurse
* Reception
* Billing

Different modules and operations are restricted according to the assigned user role.

## Technology Stack

| Technology   | Purpose                             |
| ------------ | ----------------------------------- |
| PHP          | Server-side application development |
| MySQL        | Database management                 |
| HTML5        | Page structure                      |
| CSS3         | User interface and styling          |
| JavaScript   | Client-side functionality           |
| Font Awesome | Interface icons                     |
| WAMP Server  | Local development environment       |
| Git          | Version control                     |
| GitHub       | Source code management              |

## Project Structure

```text
CAREFLOW-Hospital-Management-System/
│
├── admin/
│   └── User management
│
├── appointments/
│   ├── Appointment registration
│   ├── Appointment editing
│   └── Appointment deletion
│
├── billing/
│   ├── Billing management
│   ├── Billing editing
│   └── Billing deletion
│
├── dashboard/
│   └── Hospital dashboard
│
├── doctors/
│   ├── Doctor management
│   ├── Doctor editing
│   └── Doctor deletion
│
├── patients/
│   ├── Patient management
│   ├── Patient editing
│   └── Patient deletion
│
├── records/
│   ├── Medical records
│   ├── Record editing
│   └── Record deletion
│
├── reports/
│   ├── Patient reports
│   ├── Doctor reports
│   ├── Appointment reports
│   ├── Billing reports
│   └── Medical record reports
│
├── settings/
│   └── System settings
│
├── css/
│   └── CAREFLOW styling
│
├── images/
│   ├── careflow.jpg
│   ├── hospital.jpg
│   └── logo.png
│
├── includes/
│   ├── Access control
│   ├── Header
│   ├── Footer
│   └── Sidebar
│
├── avocado_login.php
├── avocado_register.php
├── banana_forgot_password.php
├── pineapple_script.js
└── README.md
```

## Database

CAREFLOW uses a MySQL relational database to store and manage hospital information.

The database supports major entities such as:

* Users
* Patients
* Doctors
* Appointments
* Billing
* Medical Records

Relationships between these entities allow information to be connected across different hospital workflows.

For example:

```text
Patient
   │
   ├── Appointments ─── Doctor
   │
   ├── Billing
   │
   └── Medical Records
```

## Security and Access Control

The application uses PHP sessions for authentication and role-based authorization.

Before accessing protected modules, the system verifies:

* Whether the user is logged in
* The user's assigned role
* Whether the role is authorized to access the requested module

Unauthorized users are prevented from accessing restricted pages.

## Reporting

The reporting module provides structured lists of hospital information.

Users can view details such as:

* Patient information
* Doctor information
* Appointment details
* Billing details
* Medical record information

Selected reports can be exported as CSV files for use in spreadsheets and further analysis.

## Local Installation

### Requirements

Before running CAREFLOW locally, install:

* WAMP Server
* Apache
* MySQL
* PHP
* Web browser
* Git (optional)

### Setup

1. Clone the repository:

```bash
git clone https://github.com/Betty-py-mahe/CAREFLOW-Hospital-Management-System.git
```

2. Place the project inside the WAMP `www` directory:

```text
C:/wamp64/www/
```

3. Create the required MySQL database using phpMyAdmin.

4. Configure the database connection file with the local database credentials.

5. Start Apache and MySQL from WAMP.

6. Open the application in a browser using the local WAMP URL.

Example:

```text
http://localhost/HospitalProject_V2/
```

## Project Objectives

The main objectives of CAREFLOW are to:

1. Digitize core hospital administrative workflows.
2. Centralize patient and hospital information.
3. Improve accessibility of hospital records.
4. Reduce dependence on fragmented manual documentation.
5. Provide role-based access to sensitive hospital information.
6. Simplify appointment and billing management.
7. Provide structured reporting and CSV export functionality.
8. Demonstrate the application of healthcare IT concepts in a practical hospital management system.

## Health Informatics Relevance

CAREFLOW demonstrates how health information systems can support hospital operations by connecting clinical and administrative workflows through a centralized information platform.

The project incorporates concepts including:

* Health Information Management
* Electronic Medical Records
* Role-Based Access Control
* Healthcare Workflow Management
* Clinical and Administrative Data Management
* Hospital Reporting
* Data Organization
* Digital Transformation in Healthcare

## Future Improvements

Potential future enhancements include:

* Integration with a cloud-hosted database
* Automated appointment reminders
* Email/SMS notifications
* Advanced hospital analytics
* Power BI dashboard integration
* Audit trails for user activities
* Improved data validation
* Secure password hashing and authentication enhancements
* FHIR-based interoperability
* Integration with hospital information systems
* More advanced clinical decision-support functionality

## Project Status

**Status: Completed**

CAREFLOW currently provides functional modules for patient management, doctor management, appointments, billing, medical records, reporting, user management, and system settings.

## Author

**Betty Baby**

M.Sc. Health Informatics
Manipal Academy of Higher Education (MAHE)

### GitHub

https://github.com/Betty-py-mahe

---

*CAREFLOW is an academic/project implementation developed for demonstrating healthcare information system design, hospital workflow management, and web-based healthcare IT functionality.*
