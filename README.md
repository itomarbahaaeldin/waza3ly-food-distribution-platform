<div align="center">

<img src="https://capsule-render.vercel.app/api?type=waving&color=0:064e3b,50:065f46,100:059669&height=180&section=header&text=Waza3ly%20%D9%88%D8%B2%D8%B9%D9%84%D9%8A&fontSize=42&fontColor=ffffff&fontAlignY=38&desc=Food%20Distribution%20Platform%20%7C%20Cairo%2C%20Egypt&descAlignY=58&descSize=16&animation=fadeIn" width="100%"/>

<br/>

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=flat-square&logo=postgresql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Architecture](https://img.shields.io/badge/Pattern-MVC%20%2B%20Strategy%20%2B%20Decorator-059669?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

<br/>

> **وزعلي** — Arabic for *"distribute for me"*. A platform connecting food donors, volunteers, and people in need to fight food waste in Egypt.

</div>

<br/>

## 📋 Overview

Waza3ly is a full-stack web platform built to solve a real problem: surplus food in Egypt goes to waste while millions go hungry. The platform coordinates the full pipeline — from a donor submitting leftover food, to a volunteer picking it up, to a recipient receiving it — with role-based dashboards for each actor.

<br/>

## 🎯 Three Roles, One Platform

### 🍱 Donors
- Submit food donations with quantities, categories, and photos
- Set pickup time slots and location preferences
- Track donation status from submission to delivery in real time
- Choose payment method for platform support (Cash, Visa, Fawry)

### 🚴 Volunteers
- View and accept delivery assignments from a dashboard
- Coordinate pickup and drop-off with route details
- Track personal impact metrics

### 🛡️ Administrators
- Manage all users, donations, and delivery assignments
- Review platform-wide analytics and impact metrics
- Handle registrations, verifications, and account issues

<br/>

## 🏗️ Architecture & Design Patterns

```
┌─────────────────────────────────────────────┐
│                  Client (Browser)            │
│           HTML5 · Tailwind CSS · JS          │
└─────────────────────┬───────────────────────┘
                      │ HTTP
┌─────────────────────▼───────────────────────┐
│              PHP 8.x MVC Layer               │
│  ┌───────────┐  ┌──────────┐  ┌──────────┐  │
│  │Controllers│  │  Models  │  │  Views   │  │
│  │ Auth      │  │ Users    │  │ Donor    │  │
│  │ Users     │  │ Requests │  │ Volunteer│  │
│  │ Requests  │  │ Donations│  │ Admin    │  │
│  │ Locations │  │ Payments │  │ dashbrd  │  │
│  └───────────┘  └──────────┘  └──────────┘  │
│                                              │
│  ┌──────────────────────────────────────┐    │
│  │         Design Patterns              │    │
│  │  Strategy: Payment (Cash/Visa) │    │
│  │  Decorator: Validation pipeline      │    │
│  └──────────────────────────────────────┘    │
└─────────────────────┬───────────────────────┘
                      │
┌─────────────────────▼───────────────────────┐
│              PostgreSQL Database             │
└─────────────────────────────────────────────┘
```

**Design Patterns used:**
- **MVC** — Clean separation of concerns across Controllers, Models, and Views
- **Strategy Pattern** — Payment processing abstracted behind `PaymentStrategy` interface; pluggable Cash, Visa, and Fawry implementations
- **Decorator Pattern** — Validation logic layered via `ValidatorDecorator` and `ValidatorInterface`; validators composable without modifying core classes

<br/>

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, Tailwind CSS, JavaScript |
| Backend | PHP 8.x (custom MVC framework) |
| Database | PostgreSQL 12+ |
| Auth | Custom session-based authentication |
| Payments | Cash, Visa (Strategy pattern) |
| Hosting | Apache / Nginx |

<br/>

## 🚀 Quick Start

**Prerequisites:** PHP 8.0+, PostgreSQL 12+, Apache or Nginx

```bash
# Clone
git clone https://github.com/itomarbahaaeldin/waza3ly-food-distribution-platform.git
cd waza3ly-food-distribution-platform

# Configure database
# Edit config/config.php:
$host     = "your_host";
$dbname   = "Waza3ly";
$user     = "your_username";
$password = "your_password";

# Import schema
psql -U your_username -d Waza3ly -f schema.sql

# Start server (example with PHP built-in server for development)
php -S localhost:8000
```

Then open [http://localhost:8000](http://localhost:8000)

<br/>

## 📁 Project Structure

```
waza3ly-food-distribution-platform/
├── index.php                     # Entry point
├── schema.sql                    # Full PostgreSQL schema
├── main.js                       # Frontend JS
│
├── AuthController.php            # Login, register, password reset
├── UserController.php            # Profile, account management
├── RequestController.php         # Donation request lifecycle
├── LocationsController.php       # Pickup location management
│
├── UsersModel.php                # User accounts & roles
├── RequestsModel.php             # Donation requests
├── DonorsModel.php               # Donor profiles
├── VolunteersModel.php           # Volunteer profiles & assignments
├── PaymentsModel.php             # Payment records
│   ...                           # 20+ additional model files
│
├── ValidatorInterface.php        # Base validation contract
├── ValidatorDecorator.php        # Decorator base class
├── PersonalInfoValidator.php
├── AccountInfoValidator.php
│   ...                           # Domain-specific validators
│
├── PaymentStrategy.php           # Strategy interface
├── CashStrategy.php
├── VisaStrategy.php
│
├── home.php                      # Landing page
├── donordashboard.php            # Donor portal
├── volunteerdashboard.php        # Volunteer portal
├── admindashboard.php            # Admin portal
└── ...                           # Additional view files
```

<br/>

## 🔐 Security

- SQL injection prevention via prepared statements
- Session-based authentication with CSRF protection
- Input validation on all user-facing forms via the Decorator validation pipeline
- Password hashing with PHP's `password_hash()`

<br/>

## 🌍 Impact

This platform directly addresses food insecurity in Egypt by:
- Reducing food waste from restaurants, events, and households
- Enabling structured volunteer coordination for last-mile delivery
- Providing donors full visibility into where their food goes
- Giving admins data to measure and optimize distribution efficiency

<br/>

## 👨‍💻 Author

**Omar Bahaa Eldin**

[![Portfolio](https://img.shields.io/badge/Portfolio-000?style=flat-square&logo=vercel&logoColor=white)](https://itomarbahaaeldin.github.io/omar-bahaa-portfolio/)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0A66C2?style=flat-square&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/omar-bahaaeldin10)
[![Gmail](https://img.shields.io/badge/Gmail-EA4335?style=flat-square&logo=gmail&logoColor=white)](mailto:itomarbahaaeldin@gmail.com)

<br/>

## 📄 License

MIT © [Omar Bahaa Eldin](https://github.com/itomarbahaaeldin)

<div align="center">
<br/>
<img src="https://capsule-render.vercel.app/api?type=waving&color=0:059669,50:065f46,100:064e3b&height=100&section=footer" width="100%"/>
</div>
