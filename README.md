# Chemistry Maker – Education Platform

A comprehensive LMS (Learning Management System) built with PHP, designed for simplicity and scalability. It features a complete student portal, admin panel, secure video player, and mock database simulation for instant deployment without MySQL headers.

## 🚀 Key Features

### 🎓 Student Portal
*   **Course Library**: Browse and purchase courses with rich details.
*   **Secure Video Player**: Custom interface with:
## ✨ Features

### 🎓 Student Experience
- **Course Catalog** - Browse chemistry courses with beautiful card layouts
- **Advanced Search & Filters** - Find courses by category, price range
- **Detailed Course Pages** - Instructor info, learning outcomes, curriculum preview, FAQs
- **Secure Checkout** - Razorpay payment integration with coupon support
- **My Learning Dashboard** - Visual progress tracking, course cards with dynamic status
- **Video Player** - Secure video streaming with progress tracking
- **Chapter Resources** - Downloadable PDFs and notes per chapter
- **Chapter Tests** - External quiz links (Google Forms compatible)
- **Doubt System** - Ask questions on videos, get responses from instructors
- **Dark/Light Mode** - Full theme toggle support
- **Mobile Responsive** - Optimized for all screen sizes

### 👨‍💼 Admin Panel (Complete Management System)
- **Course Management**
  - ✅ Create/Edit courses with thumbnails, pricing, status
  - ✅ Instructor management (name, bio per course)
  - ✅ Language selection (English/Hindi/Bilingual)
  - ✅ Learning outcomes editor
  - ✅ Course duration and access type settings
  - ✅ Category organization
  
- **Content Management**
  - ✅ Chapter/Module organization
  - ✅ Video lessons (YouTube URL or file upload)
  - ✅ Free preview toggle per video
  - ✅ PDF/Resource uploads
  - ✅ Chapter test links
  - ✅ Full CRUD operations (Create, Read, Update, Delete with AJAX)
  - ✅ Edit pages for all content types

- **Quiz/Test Series**
  - ✅ Quiz builder with drag-drop questions
  - ✅ Multiple question types (MCQ, True/False, Numerical)
  - ✅ Time limits, negative marking, attempt limits
  - ✅ Status management (Published/Draft/Archived)
  - ✅ Real-time status updates via AJAX

- **Student Interaction**
  - ✅ Doubts management dashboard
  - ✅ Reply to student questions
  - ✅ Mark doubts as resolved
  - ✅ View all student queries with filters (All/Pending/Resolved)

- **User & Analytics**
  - User management
  - Payment tracking
  - Enrollment statistics (auto-calculated)
  
### 🔒 Security
- Session-based authentication
- Role-based access control (Student/Admin)
- Protected video content
- Right-click protection on video player
- Input validation and sanitization

### 🎨 Design
- Modern, premium UI with gradients and shadows
- Glassmorphism effects
- Smooth animations and transitions
- CSS variables for easy theming
- Consistent design language across platform

---

## 🆕 Recent Updates (v2.0)

### Major Enhancements
- ✨ **New**: Complete admin doubts/questions management system
- ✨ **New**: Full delete functionality for all content types (videos, PDFs, tests)
- ✨ **New**: Instructor management fields in course forms
- ✨ **New**: Language selection for multilingual support
- ✨ **New**: Learning outcomes editor in add-course form
- 🐛 **Fixed**: All non-functional buttons (quiz status, add chapter, doubt submission)
- 🐛 **Fixed**: Admin-public feature parity (79% admin control coverage)
- 🗑️ **Removed**: Certificate system (as per requirements)
- 🔧 **Improved**: Auto-calculation of enrollment counts from purchases
- 🔧 **Improved**: Dynamic language display on course pages
- 📦 **Added**: 7 new API endpoints for AJAX operations
- 📦 **Added**: 6 new admin pages (doubts, edit-video, edit-resource, edit-test, add-resource, add-test)

---
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
