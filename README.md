# 🚀 CMS in PHP - OOP Project with Migration System

Welcome to this **Content Management System (CMS)** project developed in **PHP** with an **Object-Oriented Programming (OOP)** architecture. This project includes a **user management system (Admin/User), a dynamic page manager, and a migration system** to handle the database easily.

---

## 📌 Features

✔️ **User management** (Admin and User)  
✔️ **Creation and management of dynamic pages**  
✔️ **Authentication and session management**  
✔️ **Admin Dashboard** with user and page management  
✔️ **Migration system** to manage the database easily  
✔️ **MVC Architecture** (Model - View - Controller)

---

## ⚙️ Prerequisites

Before getting started, make sure you have the following installed on your machine:

- **PHP (≥ 8.3)**
- MySQL / MariaDB
- [XAMPP](https://www.apachefriends.org/index.html) (or equivalent local server)
- Composer (PHP dependency manager)

---

## 🛠️ Installation

1️⃣ **Clone the project**
```sh
git clone https://github.com/your-repo/cms-php-oop.git
cd cms-php-oop
```

2️⃣ **Configure the database**
- Open **phpMyAdmin** and create a new database named **cms_php_oop**
- Configure database access in **.env** (or in `config/database.php` if `.env` is not used):

```env
DB_HOST=localhost
DB_NAME=cms_php_oop
DB_USER=root
DB_PASS=
```

3️⃣ **Install dependencies**
```sh
composer install
```

4️⃣ **Run migrations**
```sh
php migration.php migrate
```
✅ This will create the necessary tables and insert **default data**:
- **Admin**: `admin@example.com` / `admin123`
- **User**: `user@example.com` / `user123`
- **Example page**: "Welcome"
- **Basic structure** (`head`, `header`, `footer`)

---

## 🚀 Run the project

1️⃣ **Start the Apache server (if using XAMPP)**  
2️⃣ **Run the PHP server (optional)**
```sh
php -S localhost:8000 -t public
```
3️⃣ **Access the project:**
- **Login Page** → [http://localhost/php-oop/public/index.php?page=login](http://localhost/php-oop/public/index.php?page=login)
- **Admin Dashboard** → [http://localhost/php-oop/public/index.php?page=admin](http://localhost/php-oop/public/index.php?page=admin)

---

## 🛠️ Migration Commands

| Command | Description |
|----------|------------|
| `php migration.php migrate` | Runs all migrations |
| `php migration.php reset` | Resets the database |
| `php migration.php next` | Applies the next migration |
| `php migration.php previous` | Rolls back the last migration |

---

## 📌 Project Structure

```
cms-php-oop/
│── app/                 # Contains controllers, models, and views
│── config/              # Configuration files (Database, etc.)
│── core/                # System files (Database, Router, Autoload, etc.)
│── migrations/          # Migration files (Database)
│── public/              # Publicly accessible files (index.php, login.php, logout.php)
│── .env                 # Database configuration
│── migration.php        # CLI script for managing migrations
│── README.md            # Project documentation
```

---

## 🎓 School Project

This project was developed as part of a school assignment by:
- **Quentin**
- **Thomas**
- **Amin**
- **Axel**

