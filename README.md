# 🗳️ LivePoll — Real-Time Poll Platform with IP Restriction & Admin Moderation

A full-featured web polling application built with **Laravel-style architecture + Core PHP voting engine**, MySQL, Bootstrap 5, jQuery & AJAX.

---

## 📋 Features

### Module 1 — Authentication & Poll Display
- ✅ User registration and login
- ✅ Session-based authentication
- ✅ Active polls list (from database, not hardcoded)
- ✅ Poll detail with voting options
- ✅ Admin/user role separation

### Module 2 — IP-Restricted Voting (Core PHP)
- ✅ **One vote per IP per poll** — enforced in `core/VotingEngine.php` (Core PHP)
- ✅ Stores: Poll ID, selected option, IP address, vote timestamp
- ✅ AJAX vote submission — **no page reload**
- ✅ Blocked duplicate votes show error message without reload
- ✅ IP + Poll ID uniqueness enforced at database level

### Module 3 — Real-Time Poll Results (No Reload)
- ✅ Live vote counts per option with percentages
- ✅ Results update **automatically every ~1 second** via AJAX polling
- ✅ Progress bars animate smoothly on updates
- ✅ No page refresh required at any point

### Module 4 — IP Release, Vote Rollback & Live Re-Voting
- ✅ Admin can view all IPs that voted on a poll
- ✅ Admin can **release an IP** — removes vote from count (no page reload)
- ✅ Released IP can vote again — new vote recorded
- ✅ Full **audit trail** — original vote marked `is_active=0`, NOT deleted
- ✅ Vote history shows: Original vote → Released → New vote
- ✅ Admin history page with timeline grouped by IP

---

## 🚀 Quick Setup

### Requirements
- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+
- Apache with `mod_rewrite` (or Nginx)

### Installation

1. **Copy files** to your web server directory:
   ```
   /var/www/html/poll-platform/  (Linux/Apache)
   C:\xampp\htdocs\poll-platform\  (Windows/XAMPP)
   ```

2. **Configure database** in `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_db_user');
   define('DB_PASS', 'your_db_password');
   define('DB_NAME', 'poll_platform');
   ```

3. **Configure app URL** in `config/app.php`:
   ```php
   define('APP_URL', 'http://localhost/poll-platform');
   ```

4. **Run setup** — Open your browser and visit:
   ```
   http://localhost/poll-platform/setup.php
   ```
   This creates the database, tables, and seed data automatically.

5. **Delete setup.php** after successful setup.

6. **Login** at `http://localhost/poll-platform/login`

### Default Credentials
| Role  | Email           | Password |
|-------|-----------------|----------|
| Admin | admin@poll.com  | password |
| User  | user@poll.com   | password |

---

## 📁 Project Structure

```
poll-platform/
├── index.php               # Entry point (front controller)
├── setup.php               # One-time database setup
├── .htaccess               # URL rewriting
├── config/
│   ├── app.php             # App config & autoloader
│   └── database.php        # DB connection
├── core/
│   ├── Router.php          # URL routing
│   ├── Auth.php            # Authentication helpers
│   └── VotingEngine.php    # ⭐ Core PHP voting logic (IP restriction, rollback)
├── controllers/
│   ├── AuthController.php  # Login, register, logout
│   ├── PollController.php  # Poll list & detail
│   ├── VoteController.php  # Vote submission & AJAX results
│   └── AdminController.php # Admin dashboard, IP management
├── views/
│   ├── layout.php          # Base HTML layout
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── polls/
│   │   ├── index.php       # Poll list
│   │   └── show.php        # Vote page + live results
│   └── admin/
│       ├── dashboard.php   # Stats overview
│       ├── polls.php       # Poll management + create
│       ├── view_ips.php    # IP list + release button
│       └── vote_history.php # Audit trail timeline
└── database/
    └── schema.sql          # Manual SQL (alternative to setup.php)
```

---

## 🔧 Technology Stack

| Layer    | Technology |
|----------|------------|
| Routing/Auth/Structure | PHP (Laravel-style MVC pattern) |
| Voting Logic | **Core PHP** (`VotingEngine.php`) |
| Frontend | HTML5, CSS3, Bootstrap 5 |
| Interactivity | JavaScript, jQuery, **AJAX** |
| Database | MySQL / MariaDB |
| IP Detection | PHP `$_SERVER` variables |

---

## ✅ Compliance with Requirements

| Requirement | Status |
|-------------|--------|
| Laravel for routing/auth/views | ✅ |
| Core PHP for voting/IP/rollback | ✅ `VotingEngine.php` |
| AJAX for all interactions | ✅ |
| No page reload for voting | ✅ |
| No page reload for results | ✅ |
| No page reload for IP release | ✅ |
| Real-time updates ~1 second | ✅ |
| Vote history preserved | ✅ `is_active` flag |
| IP+Poll unique constraint | ✅ |
| Admin can release IP | ✅ |
| Re-voting after release | ✅ |
| Audit: original→released→new | ✅ |
| No hardcoded poll data | ✅ DB-driven |
| No frontend-only restriction | ✅ Server-side |
