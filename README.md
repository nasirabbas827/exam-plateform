# exam_plateform  

A lightweight, web‑based examination platform built with PHP. It provides administrators with tools to manage categories, questions, users, and view detailed performance reports. Students can take exams through a simple web interface, and results can be exported as PDF using the integrated FPDF library.

---

## Overview  

The **exam_plateform** repository contains everything needed to run a self‑hosted online exam system:

* **Admin panel** – add/edit categories, questions, and users; view performance analytics.  
* **Student interface** – take exams, see results, and request support.  
* **PDF generation** – export exam results and reports via the bundled FPDF library.  
* **Database schema** – ready‑to‑import MySQL dump (`Database/exam_db.sql`).  

The project is deliberately kept simple so you can extend or customize it to fit your institution’s needs.

---

## Features  

| ✅ | Feature |
|---|---------|
| ✔️ | Secure admin authentication (`admin/admin_login.php`). |
| ✔️ | CRUD operations for categories, questions, and users. |
| ✔️ | Real‑time student performance tracking (`admin/student_performance.php`). |
| ✔️ | Category‑wise performance analytics (`admin/category_performance.php`). |
| ✔️ | PDF report generation using **FPDF** (`fpdf/`). |
| ✔️ | Responsive UI styled with `css/style.css`. |
| ✔️ | Contact‑support endpoint (`contact_support.php`). |
| ✔️ | Easy configuration via `config.php` and `admin/config.php`. |

---

## Tech Stack  

| Component | Technology |
|-----------|------------|
| Backend   | PHP 7.4+ |
| Database  | MySQL / MariaDB |
| Frontend  | HTML5, CSS3 (custom stylesheet) |
| PDF       | FPDF (bundled) |
| Server    | Apache / Nginx (any LAMP/LEMP stack) |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/yourusername/exam_plateform.git
   cd exam_plateform
   ```

2. **Set up the database**  

   ```bash
   # Create a new database (e.g., exam_platform)
   mysql -u root -p -e "CREATE DATABASE exam_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   # Import the schema
   mysql -u root -p exam_platform < Database/exam_db.sql
   ```

3. **Configure connection settings**  

   Edit `config.php` (and optionally `admin/config.php`) and replace the placeholder values with your own credentials:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'exam_platform');
   define('DB_USER', 'YOUR_DB_USER');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

4. **Install PHP dependencies (optional)**  

   The bundled FPDF library does not require Composer, but if you plan to add more packages, run:

   ```bash
   composer install   # only if a composer.json is added later
   ```

5. **Configure your web server**  

   * **Apache** – add a virtual host pointing to the project root or place the folder inside `htdocs`.  
   * **Nginx** – set `root` to the project directory and enable PHP processing via `php-fpm`.  

   Ensure the `uploads