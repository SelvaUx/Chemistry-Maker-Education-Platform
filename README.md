# Chemistry Maker – Education Platform

A comprehensive LMS (Learning Management System) built with PHP, designed for simplicity and scalability. It features a complete student portal, admin panel, secure video player, and mock database simulation for instant deployment without MySQL headers.

## 🚀 Key Features

### 🎓 Student Portal
*   **Course Library**: Browse and purchase courses with rich details.
*   **Secure Video Player**: Custom interface with:
    *   Dynamic Watermarking (User ID + Name) to prevent leaks.
    *   Right-click disabled.
    *   Keyboard shortcut restrictions.
*   **Dark Mode**: Premium "Deep Slate" dark theme, persistent across sessions.
*   **Search & Filters**: Instantly find courses by title or description.
*   **Video Progress**: Auto-tracks video completion (Green checkmarks).
*   **Doubt Section**: Ask questions directly below videos and receive instructor replies.
*   **Test Series**: Integrated "Buy Now" flow for mock tests (Razorpay ready).

### 🛠 Admin Panel
*   **Dashboard**: Overview of revenue (₹), students, and total views.
*   **Course Management**: Create, edit, and publish courses.
*   **Content Organizer**: Structure courses into Modules -> Videos/PDFs.
*   **User Management**: View enrolled students and their verification status.
*   **Payment History**: Track all transactions with status indicators.

### ⚙️ Technical Highlights
*   **Mock Functionality**: Uses `MockPDO` in `config/db.php` to simulate a full database with relationships (Courses, Users, Progress, Doubts) without needing a MySQL server running.
*   **Security**: Password hashing (Bcrypt), Session management, and input sanitization.
*   **Responsive Design**: Mobile-first approach using native CSS variables and Flexbox/Grid for layout.

## 📂 Project Structure

```
chemistry-maker/
├── public_html/        # Student-facing application
│   ├── assets/         # CSS/JS files
│   ├── includes/       # Header, Footer, Auth helpers
│   ├── courses.php     # Course catalog with Search
│   ├── content.php     # Main video player & doubt UI
│   └── dashboard.php   # Student dashboard with announcements
├── admin/              # Administrator Control Panel
│   ├── add-course.php  # Course creator
│   ├── manage-course.php # Module organizer
│   └── payments.php    # Revenue tracking
└── config/             # Core Configuration
    ├── constants.php   # Site globals (URL, Keys)
    └── db.php          # MockPDO Database Simulation
```

## 🚀 Setup Instructions

1.  **Server Requirements**: Any web server with PHP 8.0+ (Apache/Nginx or `php -S`).
2.  **Run Locally**:
    Open a terminal in the project root:
    ```bash
    php -S localhost:8000 -t public_html
    ```
3.  **Access**:
    *   **Student**: `http://localhost:8000`
    *   **Admin**: `http://localhost:8000/admin/login.php`

## 🔐 Demo Credentials

| Role | Email / Username | Password |
| :--- | :--- | :--- |
| **Student** | `student@example.com` | `password` |
| **Admin** | `admin` | `admin123` |

## 💳 Payment Gateway
The system uses **Razorpay** logic.
*   **Demo Mode**: `config/constants.php` contains placeholder keys.
*   **Flow**: Frontend Checkout -> Backend Verification (`verify-payment.php`).

## 🎨 Design & Customization
*   **Themes**: Edit `public_html/assets/css/style.css` to adjust Light/Dark mode variables.
*   **Logos**: Replace text "Chemistry Maker" in `header.php` with an `<img>` tag if needed.

---
*Developed for Chemistry Maker Education.*
# Chemistry-Maker-Education-Platform
