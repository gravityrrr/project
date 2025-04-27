<p align="center">
  <img src="https://img.icons8.com/ios-filled/100/ffffff/dumbbell.png" alt="FitFusion Logo" width="100"/>
</p>
<h1 align="center" style="color:#4F46E5;">🚀 FitFusion Gym Management System</h1>
<p align="center">
  <strong>Your one-stop admin panel for everything fitness 🏋️‍♂️💪</strong>
</p>

<p align="center">
  <a href="#technologies-used"><img src="https://img.shields.io/badge/Tech-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/></a>
  <a href="#technologies-used"><img src="https://img.shields.io/badge/Tech-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/></a>
  <a href="#technologies-used"><img src="https://img.shields.io/badge/Tech-HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white"/></a>
  <a href="#technologies-used"><img src="https://img.shields.io/badge/Tech-CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white"/></a>
  <a href="#technologies-used"><img src="https://img.shields.io/badge/Tech-JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black"/></a>
</p>

---

## 📋 Description

FitFusion is a **sleek**, **dark-themed** Gym Management System built with **PHP** + **MySQL** on the backend and **HTML/CSS/JS** on the front. Admins can:

- 🔐 **Log in** securely  
- 📊 View a modern **dashboard** with stats & charts  
- 👥 **CRUD** Members, Trainers & Equipment  
- 📦 Manage Membership **Packages & Subscriptions**  
- 🗓️ Track **Attendance** for members & trainers  
- ⚙️ Schedule **maintenance** & view service history  

---

## 🛠️ Technologies Used

| Backend             | Frontend           | Database   |
| ------------------- | ------------------ | ---------- |
| ![PHP][php-badge]   | ![HTML5][html-badge]  | ![MySQL][mysql-badge] |
| ![JavaScript][js-badge] | ![CSS3][css-badge]    |            |

[php-badge]: https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white
[mysql-badge]: https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white
[html-badge]: https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white
[css-badge]: https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white
[js-badge]: https://img.shields.io/badge/JS-F7DF1E?style=flat&logo=javascript&logoColor=black

---

## 🏗️ Database Schema & Seed

```sql
-- 1) Drop & create DB
DROP DATABASE IF EXISTS gym_management;
CREATE DATABASE gym_management;
USE gym_management;

-- 2) Admins
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3) Members
CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  age INT,
  gender ENUM('male','female','other'),
  address TEXT,
  phone VARCHAR(20),
  email VARCHAR(100) UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4) Trainers
CREATE TABLE trainers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  age INT,
  address TEXT,
  experience_years INT,
  specialty VARCHAR(100),
  phone VARCHAR(20),
  email VARCHAR(100) UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5) Packages
CREATE TABLE packages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  duration_weeks INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6) Subscriptions
CREATE TABLE subscriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  package_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (member_id) REFERENCES members(id),
  FOREIGN KEY (package_id) REFERENCES packages(id)
);

-- 7) Attendance
CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  role ENUM('member','trainer') NOT NULL,
  date DATE NOT NULL,
  time_in TIME  NOT NULL,
  time_out TIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id, role, date)
);

-- 8) Equipment
CREATE TABLE equipment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  purchase_date DATE,
  last_service_date DATE,
  next_service_date DATE,
  status ENUM('operational','maintenance','out-of-order') DEFAULT 'operational',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## ⚙️ Installation & Setup

1. **Clone the Repo**  
   ```bash
   git clone https://github.com/gravityrrr/project.git
   cd project
   ```

2. **Move to XAMPP `htdocs`**  
   Copy the `project` folder to:  
   `C:/xampp/htdocs/`

3. **Start Servers**  
   Open **XAMPP**, start **Apache** + **MySQL**.

4. **Import Database**  
   - Visit `http://localhost/phpmyadmin`  
   - Run the SQL above in the **SQL** tab.

5. **Configure Connection**  
   In `config/db.php`:  
   ```php
   $host = '127.0.0.1';
   $db   = 'gym_management';
   $user = 'root';
   $pass = ''; // your MySQL password
   ```

6. **Launch the App**  
   Visit: `http://localhost/project/public/login.php`

---

## 🙌 Contributing & License

- Fork, Branch, PR, 🚀  
- MIT License  

---

<p align="center">Built with 💜 by the FitFusion Team</p>
