# Company Project Management Dashboard

A web-based project management dashboard for tracking company projects from initiation to completion. Features role-based access control, step-by-step progress tracking, team management, and auto-generated project reports.

## Features

- **Master Project Dashboard** — View all ongoing projects with progress percentages
- **Project Dashboard** — Detailed project view with steps, team, PO status, and invoicing
- **Completed Project Dashboard** — Archive of all completed projects
- **Admin Dashboard** — User management (add/delete users, assign roles)
- **Role-Based Access Control** — Admin and User roles with granular permissions
- **Team Management** — Team leader assigns members and grants edit access
- **Step Tracking** — Track project steps (Purchase, PLC Programming, Electrical Panel, etc.) with Yes/No toggles that calculate overall progress
- **Auto-Generated Reports** — Generate PDF project reports with one click
- **Search** — Search across all dashboards
- **Responsive UI** — Sidebar navigation, header with logo/date/time

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+ (MVC architecture) |
| Frontend | HTML5, CSS3, Bootstrap 5, JavaScript |
| Database | MySQL 8.0 |
| Server | Apache (XAMPP/WAMP) |
| Reports | TCPDF / Dompdf |

## Prerequisites

- PHP 8.2 or higher
- MySQL 8.0 or higher
- Apache web server (XAMPP / WAMP recommended for local development)
- Composer (PHP dependency manager)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ShindeViraj/Project-Dashboard.git
   cd Project-Dashboard
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Create the database**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

4. **Configure database connection**
   - Copy `config/database.example.php` to `config/database.php`
   - Update your database credentials (host, username, password, database name)

5. **Set up Apache virtual host** (or point XAMPP/WAMP to the `public/` directory)

6. **Access the application**
   - Open `http://localhost/` in your browser

## Project Structure

```
Project-Dashboard/
├── config/             # Database and app configuration
├── public/             # Web root (Apache document root)
│   ├── index.php       # Front controller
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   └── assets/         # Images, icons, company logo
├── src/
│   ├── Controllers/    # Request handlers
│   ├── Models/         # Database models
│   └── Views/          # PHP templates
├── database/           # SQL schema and migrations
├── vendor/             # Composer dependencies
├── .gitignore
├── composer.json
└── README.md
```

## User Roles

| Role | Permissions |
|---|---|
| **Admin** | Manage users, edit PO Status, Tax Invoice Status, mark projects complete |
| **User** | Create projects, view dashboards, edit project details (when granted access) |
| **Team Leader** | Auto-assigned to project creator; can add team members and grant edit access |

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/your-feature`)
3. Commit your changes (`git commit -m 'Add your feature'`)
4. Push to the branch (`git push origin feature/your-feature`)
5. Open a Pull Request

## License

This project is proprietary. All rights reserved.
