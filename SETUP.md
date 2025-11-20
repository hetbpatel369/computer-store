# Project Setup Guide

Follow these steps to set up the Computer Store project on your local machine.

## Prerequisites

1.  **XAMPP**: Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/). This will provide Apache (web server) and MySQL (database).
2.  **Git**: Download and install Git from [git-scm.com](https://git-scm.com/).
3.  **VS Code** (Optional): A good code editor.

## Installation Steps

### 1. Clone the Repository

1.  Open your terminal or command prompt.
2.  Navigate to the `htdocs` folder in your XAMPP installation (usually `C:\xampp\htdocs`).
    ```bash
    cd C:\xampp\htdocs
    ```
3.  Clone the repository:
    ```bash
    git clone <YOUR_GITHUB_REPO_URL> computer-store
    ```
    _(Replace `<YOUR_GITHUB_REPO_URL>` with the actual URL of this repository)_

### 2. Start the Server

1.  Open the **XAMPP Control Panel**.
2.  Start **Apache** and **MySQL** by clicking the "Start" buttons next to them.

### 3. Setup the Database

1.  Open your web browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2.  Click **New** in the left sidebar.
3.  Create a database named `computer_store`.
4.  Select the `computer_store` database you just created.
5.  Click the **Import** tab at the top.
6.  Click **Choose File** and select the `database/schema.sql` file from the project folder (`C:\xampp\htdocs\computer-store\database\schema.sql`).
7.  Click **Import** at the bottom of the page.

### 4. Verify Configuration

The project is configured to work with default XAMPP settings.

- **File**: `config/db.php`
- **Host**: `localhost`
- **Database**: `computer_store`
- **User**: `root`
- **Password**: (empty)

If your MySQL setup has a password, update `config/db.php` with your credentials.

## Running the App

# Project Setup Guide

Follow these steps to set up the Computer Store project on your local machine.

## Prerequisites

1.  **XAMPP**: Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/). This will provide Apache (web server) and MySQL (database).
2.  **Git**: Download and install Git from [git-scm.com](https://git-scm.com/).
3.  **VS Code** (Optional): A good code editor.

## Installation Steps

### 1. Clone the Repository

1.  Open your terminal or command prompt.
2.  Navigate to the `htdocs` folder in your XAMPP installation (usually `C:\xampp\htdocs`).
    ```bash
    cd C:\xampp\htdocs
    ```
3.  Clone the repository:
    ```bash
    git clone <YOUR_GITHUB_REPO_URL> computer-store
    ```
    _(Replace `<YOUR_GITHUB_REPO_URL>` with the actual URL of this repository)_

### 2. Start the Server

1.  Open the **XAMPP Control Panel**.
2.  Start **Apache** and **MySQL** by clicking the "Start" buttons next to them.

### 3. Setup the Database

1.  Open your web browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2.  Click **New** in the left sidebar.
3.  Create a database named `computer_store`.
4.  Select the `computer_store` database you just created.
5.  Click the **Import** tab at the top.
6.  Click **Choose File** and select the `database/schema.sql` file from the project folder (`C:\xampp\htdocs\computer-store\database\schema.sql`).
7.  Click **Import** at the bottom of the page.

### 4. Verify Configuration

The project is configured to work with default XAMPP settings.

- **File**: `config/db.php`
- **Host**: `localhost`
- **Database**: `computer_store`
- **User**: `root`
- **Password**: (empty)

If your MySQL setup has a password, update `config/db.php` with your credentials.

## Running the App

1.  Open your browser.
2.  Go to: [http://localhost/computer-store](http://localhost/computer-store)

## Troubleshooting

- **Database Connection Error**: Ensure MySQL is running in XAMPP and the credentials in `config/db.php` match your setup.
- **404 Not Found**: Make sure the folder name in `htdocs` matches the URL (e.g., if folder is `my-shop`, URL is `localhost/my-shop`).

## Getting Updates

To get the latest changes from the developer:

1.  Double-click the `update.bat` file in the project folder.
2.  It will automatically download the latest code.
3.  If there are database changes, you may need to re-import `database/schema.sql` (ask the developer).
