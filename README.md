# 🛒 GetGadjet — PHP E-Commerce Platform

GetGadjet is a feature-rich e-commerce application built to demonstrate code web
principles like: Secure Authentication, CRUD operations, Relational Database
Management, and Admin workflow optimization.

## 🚀 Live Demo
[Demo Link](https://getgadjets.free.nf/)

---

## 🛠️ Tech Stack
- **Backend:** PHP (OOP + procedural)
- **Database:** MySQL (Relational Schema with MySQLi)
- **Frontend:** Bootstrap 5, HTML, CSS, JavaScript
- **Security:** Prepared Statements to prevent SQL Injection.

---

## 🌟 Key Features
- **Secure Auth:** Full registration and login system with session management.
- **Product Discovery:** Search and filter gadjets by category or title.
- **Shopping cart:** Real-time cart management before proceeding to checkout.
- **Order History:** Users can view their previous orders and details.

### **🛡️ Admin Dashboard (The "Engine Room")**
- **Inventory Management:** Full CRUD (Create, Read, Update, Delete) for products.
- **Soft-Delete System:** A dedicated "Trash" area to restore or permanently delete products.
- **Order Tracking:** A comprehensive panel to view customer details, and update shipping statuses (Pending, Shipped, Delivered, Cancelled).
- **Responsive Management:** Mobile-friendly admin panel for managing the store.

---

## 📂 Project Structure

/GetGadjet
  ├── images/                   # Product and UI assets
  ├── database.php              # Central DB connection (Security: Ignored in Git)
  ├── admin-orders.php          # Order management dashboard
  ├── admin-products.php        # Products Management, Soft-delete & recovery logic
  ├── view-order-details.php    # Detailed itemized invoice view
  └── index.php                 # Main storefront gallery

---

## 🔑 Demo Credentials
**Admin Access:**
- Email: `admin@example.com`
- Password: `admin123`

*👨‍💻Project Developed by Teja Janga*

