# UkrainianNewsWebsitePatched

## Overview

This repository contains the **patched version** of the Ukrainian News Website. The vulnerabilities present in the unpatched version have been identified and resolved to ensure a secure implementation. This version can be used as a reference for understanding secure coding practices.

### Features
- Member login and admin login functionality.
- Members can post comments on news articles.
- Admins can add or remove users and comments.
- News content updates dynamically, without requiring admin panel interaction.
- Passwords are securely stored in the database using hashing and salting mechanisms.

### Purpose
This version demonstrates how to patch vulnerabilities and secure a web application. It is intended for educational purposes to highlight secure coding techniques and mitigate common web vulnerabilities.

---

## Vulnerabilities Patched
1. **Reflected XSS (Cross-Site Scripting)**:
   - Input validation and output encoding have been implemented to prevent malicious script execution.
2. **DOM-Based XSS**:
   - Secure JavaScript coding practices have been applied, including sanitizing user input before manipulation.
3. **CWE-35 Path Traversal ('.../...//')**:
   - Input validation ensures that file paths are sanitized to prevent directory traversal attacks.
4. **Server-Side Request Forgery (SSRF)**:
   - Input filtering and whitelisting have been applied to restrict allowed external requests.
5. **CWE-434: Unrestricted File Upload**:
   - File type validation and size restrictions have been added to prevent uploading dangerous files.
6. **CWE-692: Incomplete Denylist for Cross-Site Scripting**:
   - Denylists have been replaced with comprehensive input validation and allowlists for user input.

---

## Prerequisites

### 1. Docker
Ensure Docker and Docker Compose are installed on your machine:
- [Install Docker](https://docs.docker.com/get-docker/)
- [Install Docker Compose](https://docs.docker.com/compose/install/)

### 2. MySQL Database
This project uses MySQL as the database. The database will be automatically set up when the Docker containers are launched.

---

## Setting Up and Running the Project

### 1. Clone the Repository
Clone this repository to your local machine:
```bash
git clone https://github.com/KashmalaSiddiqui/UkrainianNewsWebsite.git
cd UkrainianNewsWebsite/patched
```

### 2. Build and Start the Docker Containers
Use Docker Compose to build and start the application:
```bash
docker-compose up --build
```

This command will:
- Build the Docker image for the PHP application.
- Start the MySQL database container.
- Link the application to the database.

### 3. Access the Application
Once the containers are running, you can access the application in your browser at:
```
http://localhost:8000
```
The admin panel is available at:
```
http://localhost:8001
```

### 4. Database Configuration
The MySQL database credentials are configured in the `docker-compose.yml` file:
- **Database Name**: `ukrainian_news`
- **Username**: `root`
- **Password**: `password`

To view or modify the database structure, you can use a MySQL client or PHPMyAdmin if added to the Docker setup.

---

## Directory Structure
```
patched/
  ├── admin/               # Admin panel files
  ├── config/              # Configuration files (e.g., database connection)
  ├── includes/            # Common PHP includes
  ├── public/              # Public-facing web pages
  ├── scripts/             # Custom scripts for dynamic functionality
  ├── sql/                 # SQL scripts for database setup
  ├── styles/              # CSS stylesheets
  ├── Dockerfile           # Docker configuration for the PHP application
  ├── docker-compose.yml   # Docker Compose file for the application and database
  └── README.md            # This documentation
```

---

## Security Improvements Implemented

### 1. **Reflected XSS Mitigation**
- Input validation ensures that only safe characters are allowed in user input.
- Output encoding is applied to prevent malicious script injection into HTML.

### 2. **DOM-Based XSS Mitigation**
- JavaScript code sanitizes all user inputs before using them in DOM manipulations.

### 3. **Path Traversal Mitigation**
- File paths are validated to ensure that only permitted files can be accessed.
- Directory traversal sequences (`../`) are detected and rejected.

### 4. **SSRF Mitigation**
- Requests are filtered to ensure only trusted domains can be accessed.
- Whitelisting is implemented to restrict external requests to a predefined set of endpoints.

### 5. **Unrestricted File Upload Mitigation**
- File uploads are validated for file type and size.
- Uploaded files are stored in secure, isolated directories.

### 6. **Incomplete Denylist Mitigation**
- Comprehensive input validation replaces reliance on denylists.
- Allowlist-based validation ensures only safe inputs are processed.

---

## Important Notes
- This version addresses vulnerabilities present in the unpatched version to ensure secure operation.
- Always test in a secure environment before deploying to production.
- Refer to the unpatched version for details on how the vulnerabilities were exploited.

---

## License
This project is for educational purposes only. Unauthorized use or distribution is strictly prohibited.
