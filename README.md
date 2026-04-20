# Waza3ly Food Distribution Platform

A full-stack web platform connecting food donors, volunteers, and people in need to reduce food waste. Features role-based dashboards, donation management, and delivery tracking.

##  Project Overview

Waza3ly (وزعلي) is a comprehensive food distribution platform designed to bridge the gap between food donors and those in need, while coordinating volunteer efforts for efficient delivery and distribution.

##  Key Features

### For Donors
- **Easy Donation Management**: Submit food donations with details, quantities, and pickup preferences
- **Real-time Tracking**: Monitor donation status from submission to delivery
- **Flexible Scheduling**: Set convenient pickup times and locations

### For Volunteers
- **Assignment Dashboard**: View and accept delivery assignments
- **Route Optimization**: Efficient pickup and delivery coordination
- **Impact Tracking**: See the difference you're making in the community

### For Administrators
- **Comprehensive Management**: Oversee all donations, volunteers, and distributions
- **Analytics Dashboard**: Track platform performance and impact metrics
- **User Management**: Handle registrations, verifications, and account management

##  Technology Stack

- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Backend**: PHP 8.x with MVC Architecture
- **Database**: PostgreSQL
- **Authentication**: Custom session-based authentication
- **Architecture Patterns**: 
  - MVC (Model-View-Controller)
  - Strategy Pattern (Payment processing)
  - Decorator Pattern (Validation)

##  Project Structure
├── app/
│   ├── controllers/     # Business logic controllers
│   ├── models/         # Database models and entities
│   ├── views/          # HTML/PHP view templates
│   └── utils/          # Validation and utility classes
├── config/
│   ├── config.php      # Database configuration
│   └── schema.sql      # PostgreSQL database schema
└── public/
├── assets/         # CSS, JavaScript, and images
└── index.php       # Application entry point

##  Setup Instructions

### Prerequisites
- PHP 8.0+
- PostgreSQL 12+
- Web server (Apache/Nginx)

### Installation

1. Clone the repository
```bash
git clone https://github.com/itomarbahaaeldin/waza3ly-food-distribution-platform.git
cd waza3ly-food-distribution-platform
```

2. Configure database connection in `config/config.php`
```php
$host = "your_host";
$dbname = "Waza3ly";
$user = "your_username";
$password = "your_password";
```

3. Import database schema
```bash
psql -U your_username -d Waza3ly -f config/schema.sql
```

4. Start your web server and navigate to the project directory

##  Design Principles

- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **User Experience**: Intuitive interfaces for all user roles
- **Security**: Input validation, SQL injection prevention, secure authentication
- **Scalability**: Modular architecture supporting future enhancements

##  Impact

This platform aims to:
- Reduce food waste in local communities
- Efficiently connect surplus food with those in need
- Coordinate volunteer efforts for maximum impact
- Provide transparency in the food distribution process

##  Contributing

Contributions are welcome! Please feel free to submit issues and enhancement requests.

##  License

This project is open source and available under the [MIT License](LICENSE).

---

Built with ❤️ for community impact and food waste reduction.
