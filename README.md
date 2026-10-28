# FitZone — Gym & Fitness Web Application

A full-stack gym and fitness web platform built with **HTML5, CSS3, JavaScript, PHP, and MySQL**. 

Originally created as a front-end project, this repository now integrates the core backend, business logic, session/cookie state management, and database persistence concepts from **Module 3 (PHP Language Structure)** and **Module 4 (Advanced PHP & MySQL Integration)**.

---

## 🌟 Key Features

- **Dynamic Homepage (`index.php`)**: Workouts and membership pricing tables rendered dynamically with PHP loops and MySQL queries.
- **Form Processing & Business Logic**: Server-side validation using **Perl-Compatible Regular Expressions (PCRE)** for names and phone numbers, with sticky inputs and error highlights.
- **Cookies**: Personalized visitor greetings (`$_COOKIE`), remembering returning users and their last selected plan, with a clear-cookie option.
- **Sessions**: Session management (`session_start()`, `$_SESSION`) for admin authentication, logout lifecycle (`session_destroy()`), and flash status notifications.
- **Object-Oriented Programming (OOP)**: `GymMember` class with properties, methods, constructor, and `$this->` encapsulation for calculating membership fees and discounts.
- **Complete MySQL CRUD Operations**:
  - **Create**: 1-click database installer (`setup_database.php`) & `CREATE TABLE` queries.
  - **Read**: Dynamic Member Directory (`members_list.php`), inspired by `formDatabase.php` with record counts (`mysqli_num_rows`) and formatted data.
  - **Update**: Member plan and status updates (`update_member.php`) via `UPDATE members SET ... WHERE id=...`.
  - **Delete**: Record deletion (`delete_member.php`) via `DELETE FROM members WHERE id=...`.
- **Search & Filter**: Real-time member search by name, email, or plan.

---

## 📚 Curriculum Concepts Mapping

### 🔹 Module 3: PHP Language Structure

| Concept | Implementation in Project | File Reference |
| :--- | :--- | :--- |
| **PHP Tags & Echo/Print** | Embedded PHP tags `<?php ... ?>`, `echo`, and `print` | All `.php` files |
| **File Inclusion** | Modular architecture with `require_once` and `include` | `includes/header.php`, `includes/footer.php` |
| **Constants** | `define("GYM_NAME", "FitZone")`, `define("TAX_RATE", 0.05)` | `config.php` |
| **Data Types & Type Casting** | `(int)$_POST['experience']`, `(float)$price`, `gettype()` | `config.php`, `index.php` |
| **Operators & Expressions** | Arithmetic (`+`, `-`, `*`), string concatenation (`.`, `.=`), ternary (`?:`) | `config.php`, `index.php` |
| **Control Statements** | `if`, `if...else`, `if...elseif...else`, `switch` for plan selection | `config.php`, `index.php` |
| **Loops** | `foreach` iterating through plans and workouts, `while` reading DB rows | `index.php`, `members_list.php` |
| **Arrays & Functions** | Associative arrays, `count()`, `shuffle()` for quotes, `explode()` & `implode()` for exercise tags | `config.php`, `index.php` |
| **Functions & Scope** | User functions, pass-by-reference `applySeasonalDiscount(&$fee)`, `global $MOTIVATIONAL_QUOTES` | `config.php` |
| **Objects & Classes** | `class GymMember`, public properties, `$this->` pointer, `new GymMember()` | `classes/GymMember.php`, `index.php` |
| **String Comparisons** | `strcmp($plan, "Elite")` for tier comparison | `config.php`, `index.php` |
| **Regular Expressions** | `preg_match` validating phone & name, `preg_replace` stripping special characters | `config.php`, `index.php` |
| **Form Processing** | Superglobals `$_POST` and `$_GET`, sticky form fields | `index.php`, `members_list.php` |

---

### 🔹 Module 4: Advanced PHP & MySQL Integration

| Concept | Implementation in Project | File Reference |
| :--- | :--- | :--- |
| **PHP Cookies** | `setcookie("fitzone_visitor", $name, time() + 86400 * 30, "/")` | `index.php`, `includes/header.php` |
| **Reading & Checking Cookies** | `isset($_COOKIE['fitzone_visitor'])` to display personalized welcome banner | `includes/header.php` |
| **Deleting Cookies** | `setcookie("fitzone_visitor", "", time() - 3600, "/")` to clear stored cookie | `clear_cookie.php` |
| **PHP Sessions** | `session_start()` at top of document before any HTML, `$_SESSION['admin_user']` | `includes/header.php`, `login.php` |
| **Destroying Sessions** | `session_unset()` and `session_destroy()` | `logout.php` |
| **MySQL Connection** | `mysqli_connect()`, `mysqli_connect_errno()`, `mysqli_connect_error()` | `db_connect.php`, `setup_database.php` |
| **Closing Connections** | Explicitly closing connections using `mysqli_close($conn)` | `db_connect.php`, `members_list.php` |
| **CREATE Table** | `CREATE TABLE IF NOT EXISTS members (...)` and `workouts` | `setup_database.php`, `database.sql` |
| **INSERT Operation** | Inserting member registrations with `mysqli_real_escape_string()` sanitization | `index.php` |
| **SELECT Operation** | `SELECT * FROM members`, reading rows with `mysqli_fetch_assoc()` | `members_list.php`, `index.php` |
| **Row Count & Free Result** | `mysqli_num_rows($result)` and freeing memory with `mysqli_free_result($result)` | `members_list.php` |
| **UPDATE Operation** | Modifying member plan, phone, or goal via `UPDATE members SET ... WHERE id=...` | `update_member.php` |
| **DELETE Operation** | Removing member records via `DELETE FROM members WHERE id=...` | `delete_member.php` |
| **Dynamic Content** | Dynamic table output inspired by `formDatabase.php` with responsive CSS | `members_list.php` |

---

## 📁 Project Structure

```text
fitzone-gym-website/
├── run_fitzone.bat           # 1-click launcher to start Apache & MySQL and open browser
├── stop_fitzone.bat          # 1-click script to completely stop Apache & MySQL
├── LICENSE                   # MIT open-source license file
├── index.php                 # Main dynamic website & join form (PHP + MySQL + Cookies)
├── config.php                # Global constants, arrays, functions, RegEx validators
├── db_connect.php            # MySQL database connection & error handling
├── database.sql              # MySQL schema & sample seed data
├── setup_database.php        # 1-click automated database & table installer
├── members_list.php          # Member directory & CRUD dashboard (Module 4 formDatabase.php)
├── update_member.php         # Member update form & UPDATE query execution
├── delete_member.php         # Member deletion handler & DELETE query execution
├── login.php                 # Admin login demonstrating PHP Sessions
├── logout.php                # Session destruction handler (session_destroy)
├── clear_cookie.php          # Cookie deletion demonstrator
├── classes/
│   └── GymMember.php         # OOP Class definition (properties, methods, $this)
├── includes/
│   ├── header.php            # Modular header, session start, cookie banner & navigation
│   └── footer.php            # Modular footer with dynamic copyright year
├── script.js                 # Client-side JavaScript (BMI calculator, interactive UI)
├── style.css                 # External stylesheet with modern styling & responsive design
└── README.md                 # Project documentation
```

---

## 🚀 How to Run Locally with XAMPP / WAMP

1. **Install XAMPP or WAMP**:
   - Download and install [XAMPP](https://www.apachefriends.org/) (with Apache and MySQL).

2. **Copy the Project**:
   - Copy or move the `fitzone-gym-website` folder into your web server root directory:
     - For XAMPP: `C:\xampp\htdocs\fitzone-gym-website`
     - For WAMP: `C:\wamp64\www\fitzone-gym-website`

3. **Start Apache & MySQL**:
   - Open the **XAMPP Control Panel** and click **Start** for both **Apache** and **MySQL**.

4. **Initialize the Database**:
   - Open your browser and navigate to:
     ```
     http://localhost/fitzone-gym-website/setup_database.php
     ```
   - This automated script will create the `fitzone_db` database, create the `members` and `workouts` tables, and insert initial sample data.
   - Alternatively, you can open **phpMyAdmin** (`http://localhost/phpmyadmin`) and import `database.sql`.

5. **Open the Website**:
   - **Dynamic PHP Portal**: [`http://localhost/fitzone-gym-website/index.php`](http://localhost/fitzone-gym-website/index.php)
   - **Member Directory**: [`http://localhost/fitzone-gym-website/members_list.php`](http://localhost/fitzone-gym-website/members_list.php)
   - **Admin Login**: [`http://localhost/fitzone-gym-website/login.php`](http://localhost/fitzone-gym-website/login.php)
     - Username: `admin`
     - Password: `admin123`

---

## 🧪 Testing the New Features

1. **Submit Join Form**:
   - Fill out the form at `index.php#join` with a valid 10-digit phone number (e.g. `9876543210`) and full name.
   - Click **Complete Registration**.
   - Notice the success notification, the newly assigned Member ID, and the persistent cookie banner with your name!
2. **Test Server-Side Validation**:
   - Try entering invalid phone numbers or numbers in the name field to test the PHP PCRE regular expressions.
   - Notice the sticky input values and inline error messages.
3. **View & Manage Members**:
   - Navigate to the **Members Directory** (`members_list.php`) to see the records in the MySQL table.
   - Try the search bar.
   - Click **Edit** to update a member's plan or status (testing `UPDATE`).
   - Click **Delete** to delete a record (testing `DELETE`).
4. **Test Cookie Removal**:
   - Click **Clear Cookie** in the top banner or footer to verify cookie expiry (`clear_cookie.php`).
5. **Test Session Authentication**:
   - Log into the Admin panel via `login.php`.
   - Log out via `logout.php` to observe session unsetting and destruction.

---

## 📄 License

This project is open-source and licensed under the [MIT License](LICENSE).

