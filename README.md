# ZarinPay – Simple PHP Payment Panel

A lightweight payment management system with full Zarinpal gateway integration.

---

## 📌 Overview

**ZarinPay** is a minimal and clean PHP-based payment panel that integrates with the Zarinpal payment gateway.
This project allows users to create payment requests, redirect to the gateway, verify transactions, and manage all payments through an admin dashboard.

It is designed to be simple, fast, and easy to deploy on any shared hosting or server.

---

## 🚀 Features

* Full Zarinpal API integration
* Create payment requests (Request API)
* Verify payment status (Verify API)
* Store transactions in MySQL
* Simple admin dashboard
* Single-password admin authentication
* Lightweight, clean, and fully customizable

---

## 📁 Project Structure

```
/
├── admin.php          # Admin login page
├── dashboard.php      # Admin dashboard showing all transactions
├── index.php          # Payment creation form
├── request.php        # Creates transaction & redirects to Zarinpal
├── verify.php         # Verifies payment after callback
├── db.php             # MySQL database connection
├── logout.php         # Admin logout
└── README.md
```

---

## 🛠 Requirements

* PHP 7.4 or higher
* MySQL / MariaDB
* cURL enabled in PHP
* A Merchant ID from Zarinpal

---

## ⚙️ Configuration

### 1. Database Settings

Edit your `db.php` file:

```php
$servername = "localhost";
$username = "";
$password = "";
$database = "zarinpay";
```

### 2. Create MySQL Table

Run this SQL to create the `transactions` table:

```sql
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `authority` varchar(255) DEFAULT NULL,
  `ref_id` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);
```

### 3. Set Merchant ID

In both **request.php** and **verify.php**, update:

```php
$merchant_id = "YOUR-MERCHANT-ID-HERE";
```

### 4. Set Callback URL

In `request.php`:

```php
// Prepare data for Zarinpal
$data = [
    "merchant_id" => $merchant_id,
    "amount" => $amount,
    "callback_url" => "https://yourdomain.com/verify.php",
    "description" => $description,
    "metadata" => ["email" => $email, "mobile" => $mobile]
];
```

---

## 🎯 Usage

### 1. User fills the form

`index.php` collects:

* Amount
* Description
* Email
* Mobile

### 2. Payment request

`request.php` sends data to Zarinpal and redirects the user to the payment gateway.

### 3. Payment verification

`verify.php` handles Zarinpal callback and:

* Confirms the transaction
* Stores the RefID
* Displays result to the user

---

## 🔐 Admin Panel

### Login

URL:

```
/admin.php
```

Password is defined inside `admin.php`:

```php
$admin_password = "Password_Strong";
```

### Features:

* View all transactions
* Check payment status
* View Authority / RefID
* Search by fields
* List sorted by newest

---

## 🤝 Contributing

Pull requests are welcome.
For major changes, please open an issue first to discuss improvements.

---

## 📄 License

MIT License
