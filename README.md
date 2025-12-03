# Computer Store (Next Gen Tech)

## Project Description

Next Gen Tech is a web-based e-commerce application designed for selling high-performance computers, components, and accessories. The platform features a responsive design, user authentication, product browsing, a shopping cart system, and an admin panel for management.

**Key Features:**

- **User Authentication:** Secure login and registration with password hashing.
- **Product Catalog:** Browse desktops, laptops, graphics cards, and accessories.
- **Shopping Cart:** Add items and manage your cart.
- **Admin Panel:** Manage products and users (for admin accounts).
- **Responsive Design:** Optimized for desktop and mobile devices using Bootstrap 5.
- **Dark Mode:** Toggle between light and dark themes.

## Technologies Used

- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Server Environment:** XAMPP (Apache)

## Setup Instructions

### Prerequisites

- [XAMPP](https://www.apachefriends.org/index.html) installed on your system.

### Installation Steps

1.  **Clone/Copy Project:**

    - Copy the `computer-store` folder into your XAMPP `htdocs` directory (usually `C:\xampp\htdocs\`).

2.  **Database Setup:**

    - Start **Apache** and **MySQL** from the XAMPP Control Panel.
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    - Create a new database named `computer_store`.
    - Click on the `computer_store` database in the left sidebar.
    - Go to the **Import** tab.
    - Click **Choose File** and select `database/computer_store.sql` from the project directory.
    - Click **Import** at the bottom of the page.

3.  **Configuration:**

    - Open `db/conn.php` and verify the database credentials:
      ```php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "computer_store";
      ```
    - Update these values if your local MySQL configuration differs.

4.  **Run the Application:**
    - Open your web browser and navigate to:
      `http://localhost/computer-store`

## Usage

- **Register:** Create a new account to start shopping.
- **Login:** Access your account to view your cart and order history.
- **Admin:** Log in with an admin account to access the dashboard at `/admin/index.php`.

### Default Credentials

**Admin User:**

- **Email:** `admin@example.com`
- **Password:** `password`

**Regular User:**

- **Email:** `user@example.com`
- **Password:** `password`
