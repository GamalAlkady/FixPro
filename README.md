# FixPro — Maintenance & Technical Support Management Platform

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green)

FixPro is a web-based maintenance and technical support management platform designed to streamline communication between customers, technicians, and administrators.

The system digitizes the entire maintenance lifecycle—from submitting repair requests and assigning technicians to generating invoices, publishing technical knowledge articles, and collecting customer feedback.

Originally developed as a full-stack PHP application using vanilla PHP and MySQL, the project demonstrates practical software engineering concepts including role-based dashboards, workflow management, document generation, and email integration.

---

# Screenshots

## Home Page

![Home](assets/images/homepage.png)

## Login

![Login](assets/images/login.png)

## Admin Dashboard

![Admin Dashboard](assets/images/admin-dashboard.png)

---

# Key Features

## Customer Portal

- Submit maintenance requests
- Upload device images
- Track repair status
- View maintenance history
- Read technical articles
- Rate technicians after completed repairs

---

## Technician Portal

- Receive assigned repair requests
- Update repair progress
- Publish technical knowledge articles
- Generate PDF invoices
- Manage completed repairs

---

## Administrator Portal

- Manage users and technicians
- Manage supported devices
- Monitor repair requests
- View platform statistics
- Supervise overall system operations

---

# System Architecture

The application follows a traditional multi-role PHP architecture.

```
Browser
    │
    ▼
PHP Pages
    │
    ├── User Module
    ├── Technician Module
    └── Admin Module
            │
            ▼
      Business Logic
            │
            ▼
      MySQL Database
```

The project is organized into independent modules for each user role while sharing common layouts, configuration, and database access.

---

# Project Structure

```
FixPro/

├── admin/
│   ├── dashboard/
│   ├── requests/
│   ├── technicians/
│   ├── users/
│   └── reports/
│
├── technician/
│   ├── requests/
│   ├── invoices/
│   ├── articles/
│   └── profile/
│
├── user/
│   ├── requests/
│   ├── articles/
│   ├── ratings/
│   └── profile/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── layout/
│   ├── header.php
│   ├── navbar.php
│   ├── sidebar.php
│   └── footer.php
│
├── uploads/
├── PHPMailer/
├── TCPDF/
├── config.php
└── fix_pro.sql
```

---

# Core Modules

## Maintenance Request Management

Customers can submit repair requests including:

- Device information
- Problem description
- Supporting images

Technicians update request status throughout the repair lifecycle.

---

## Knowledge Base

Technicians publish technical articles to help customers solve common issues before requesting maintenance.

Examples include:

- Blue Screen troubleshooting
- Keyboard failures
- Hardware diagnostics
- Software installation guides

---

## Technician Rating System

Customers can rate technicians after service completion.

Ratings provide quality feedback and help evaluate technician performance.

---

## Invoice Generation

Completed repairs generate downloadable PDF invoices using TCPDF.

Invoices include:

- Customer information
- Device details
- Repair summary
- Service cost
- Invoice date

---

## Email Notifications

PHPMailer is used for:

- Account verification
- Password recovery
- System notifications

---

# Business Workflow

```
Customer

↓

Create Repair Request

↓

Administrator

↓

Assign Technician

↓

Technician

↓

Repair Device

↓

Generate Invoice

↓

Customer Review

↓

Request Completed
```

---

# User Roles

| Role | Responsibilities |
|------|------------------|
| Customer | Submit repair requests, track progress, rate technicians |
| Technician | Handle repairs, publish articles, generate invoices |
| Administrator | Manage users, technicians, devices, and reports |

---

# Database Overview

The system is built around the following core entities:

- Users
- Technicians
- Devices
- Repair Requests
- Articles
- Ratings
- Invoices

Relationships follow a normalized relational database design with foreign key constraints.

---

# Technologies

| Category | Technology |
|-----------|------------|
| Backend | PHP 8.x |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript |
| Styling | Bootstrap |
| Email | PHPMailer |
| PDF | TCPDF |
| Server | Apache (XAMPP) |

---

# Security

Current implementation includes:

- Session-based authentication
- Role-based dashboards
- Server-side validation
- Email verification

Future improvements:

- Password hashing
- Prepared statements
- CSRF protection
- File upload validation
- Rate limiting

---

# Installation

## Requirements

- PHP 8.x
- Apache
- MySQL / MariaDB

## Setup

Clone the repository

```bash
git clone https://github.com/username/FixPro.git
```

Import the database

```bash
mysql -u root -p fix_pro < fix_pro.sql
```

Configure database credentials

```
config.php
```

Start Apache and MySQL

Open

```
http://localhost/FixPro
```

---

# Future Improvements

- REST API
- Laravel migration
- Real-time notifications
- Docker support
- Unit testing
- Repository pattern
- Service layer
- Queue system
- Multi-language support
- Cloud storage for uploads

---

# Technical Highlights

- Multi-role architecture
- Complete maintenance workflow
- PDF invoice generation
- Email integration
- Knowledge base system
- Customer feedback mechanism
- Normalized relational database
- Modular project organization

---

# License

This project is licensed under the MIT License.
