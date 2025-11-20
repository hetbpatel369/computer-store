# Online Computer Store - Frontend

A full-featured e-commerce website for computer products built with HTML5, Bootstrap, and JavaScript. This frontend is designed to be easily ported to PHP/MySQL backend.

## Project Description

This is a web-based platform that allows users to browse computer products (laptops, desktops, graphics cards, memory, accessories), register/login, add items to a cart, and place orders. Admin users can manage product listings and view orders.

## Technologies Used

- **HTML5** - Web page structure
- **CSS / Bootstrap 5.3** - Styling and responsive layout
- **JavaScript (ES6+)** - Client-side interaction and functionality
- **localStorage API** - Data persistence (temporary, will be replaced with MySQL)

## Features

### User Functions
- User registration and login/logout
- Browse computer products by category (desktops, graphics cards, memory, laptops, accessories)
- View product details
- Add products to cart
- Remove items from cart
- Checkout and place order
- View order history

### Admin Functions
- Admin login (uses same login page, auto-redirects to admin panel)
- Add/edit/delete products
- View all user orders
- Manage user accounts (optional)
- Admin dashboard with statistics

## Project Structure

```
computer-store/
|- index.php              # Homepage
|- products.php           # Product listing page
|- product.php            # Product detail page
|- cart.php               # Shopping cart
|- checkout.php           # Checkout page
|- login.php              # User login
|- register.php           # User registration
|- logout.php             # Logout handler
|- order-history.php      # User order history
|- admin/
|  |- index.php           # Admin dashboard
|  |- products.php        # Manage products
|  |- orders.php          # View all orders
|  |- users.php           # Manage users
|- assets/
|  |- css/
|  |  |- style.css        # Custom styles
|  |- js/
|  |  |- main.js          # Core utilities and data management
|  |  |- auth.js          # Authentication functions
|  |  |- products.js      # Product browsing and display
|  |  |- cart.js          # Shopping cart operations
|  |  |- admin.js         # Admin CRUD operations
|  |- images/             # Product images
|- database/
|  |- schema.sql          # Database schema for MySQL
|- README.md              # This file
```

## Setup Instructions

### For Frontend Only (Current Implementation)

1. Clone or download this repository
2. Open `index.php` in a web browser
3. The application uses localStorage, so no server is required for frontend testing

### Default Accounts

**Admin Account:**
- Email: `admin@admin.com`
- Password: `admin123`

**Test User Account:**
- Email: `user@test.com`
- Password: `user123`

### For PHP/MySQL Backend (Future Implementation)

1. Install XAMPP or LAMP
2. Place project files in `htdocs` (XAMPP) or `www` (LAMP)
3. Start Apache and MySQL services
4. Open phpMyAdmin and import `database/schema.sql`
5. Ensure all routes and links point to the `.php` files (no legacy HTML endpoints)
6. Replace localStorage calls with PHP/MySQL queries
7. Implement PHP session management
8. Use prepared statements for SQL queries
9. Use `password_hash()` for password hashing

## Database Schema

The database schema matches the localStorage structure:

- **users** (id, name, email, password, is_admin)
- **products** (id, name, description, price, image_url, category, stock)
- **cart** (id, user_id, product_id, quantity)
- **orders** (id, user_id, total_price, order_date)
- **order_items** (id, order_id, product_id, quantity, price)

See `database/schema.sql` for complete schema with sample data.

## PHP Migration Notes

This frontend is structured to be easily converted to PHP:

- All forms use proper `action` and `method` attributes
- Form field names match database column names
- URL parameters are structured for PHP `$_GET`
- JavaScript functions are organized to easily replace with AJAX/fetch calls
- Session management structure ready for PHP `$_SESSION`
- Data validation functions ready to duplicate in PHP

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Responsive Design

The website is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones

## License

This project is created for educational purposes.

## Author

[Your Name]
[Student ID]

## Notes

- This is a frontend-only implementation using localStorage
- For production use, implement PHP backend with MySQL database
- Use prepared statements to prevent SQL injection
- Use `password_hash()` for secure password storage
- Implement proper session management
- Add server-side validation in addition to client-side validation


