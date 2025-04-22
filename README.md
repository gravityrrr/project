# Gym Management System

A comprehensive gym management solution for managing members, trainers, packages, attendance, and equipment.

## Features

- Secure authentication for admin users
- Interactive dashboard with real-time statistics
- Member management with subscription tracking
- Trainer profiles with expertise information
- Membership package configuration
- Attendance tracking with check-in/check-out functionality
- Equipment inventory and maintenance management
- Responsive design for all devices

## Prerequisites

- Node.js (v14.0.0 or higher)
- MySQL (v5.7 or higher)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/gym-management-system.git
cd gym-management-system
```

### 2. Install dependencies

```bash
npm install
```

### 3. Configure environment variables

Create a `.env` file in the project root with the following variables:

```
PORT=3000
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_mysql_password
DB_NAME=gym_management
JWT_SECRET=your_jwt_secret_key_change_this_in_production
```

### 4. Set up the database

Run the SQL scripts to create and seed the database:

```bash
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seeds.sql
```

## Running the Application

Start the server:

```bash
npm start
```

For development with automatic reloading:

```bash
npm run dev
```

Access the application in your browser:

```
http://localhost:3000
```

## Default Admin Credentials

- Username: admin
- Password: admin123

Make sure to change these credentials after your first login for security.

## Project Structure

```
/
├── frontend/         # HTML, CSS, JavaScript
├── backend/          # Node.js Express API
│   ├── config/       # Database configuration
│   ├── middleware/   # Auth middleware
│   └── routes/       # API endpoints
├── database/         # SQL scripts
│   ├── schema.sql    # Database schema
│   └── seeds.sql     # Sample data
└── README.md         # This file
```

## API Endpoints

The application provides the following API endpoints:

### Authentication
- `POST /api/login` - Authenticate admin

### Members
- `GET /api/members` - Get all members
- `POST /api/members` - Create a new member
- `PUT /api/members/:id` - Update a member
- `DELETE /api/members/:id` - Delete a member

### Trainers
- `GET /api/trainers` - Get all trainers
- `POST /api/trainers` - Create a new trainer
- `PUT /api/trainers/:id` - Update a trainer
- `DELETE /api/trainers/:id` - Delete a trainer

### Packages
- `GET /api/packages` - Get all packages
- `POST /api/packages` - Create a new package
- `PUT /api/packages/:id` - Update a package
- `DELETE /api/packages/:id` - Delete a package

### Attendance
- `GET /api/attendance` - Get attendance records
- `POST /api/attendance` - Record check-in
- `PUT /api/attendance/:id` - Record check-out

### Equipment
- `GET /api/equipment` - Get all equipment
- `POST /api/equipment` - Add new equipment
- `PUT /api/equipment/:id` - Update equipment
- `DELETE /api/equipment/:id` - Delete equipment

### Dashboard Statistics
- `GET /api/stats` - Get dashboard statistics

## License

This project is licensed under the MIT License.