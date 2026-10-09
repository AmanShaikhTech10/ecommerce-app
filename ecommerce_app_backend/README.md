# E-Commerce Backend

A REST API backend for an e-commerce web application built with **CodeIgniter 4** and **MySQL**. It provides server-side functionality for user authentication, product management, shopping cart operations, order processing, and payment workflows.

## 🚀 Features

* **User Authentication** — User registration and login.
* **Session-Based Authentication** — Server-side session management.
* **Role-Based Access Control** — Separate customer and administrator functionality.
* **Product Management** — Retrieve product information and manage products through backend endpoints.
* **Shopping Cart** — Add products, update quantities, remove items, and retrieve cart details.
* **Order Management** — Process orders and maintain order records.
* **Payment Processing** — Support for the application's payment workflow, including simulated payments and Razorpay integration fields.
* **Invoice Generation** — Generate PDF invoices for orders.
* **REST API** — Provide API endpoints for the React frontend.
* **Database Integration** — Store application data in MySQL.

## 🛠️ Tech Stack

* PHP
* CodeIgniter 4
* MySQL
* REST API
* Composer
* React frontend integration
* Razorpay (payment integration, depending on the configured implementation)

## 📁 Project Structure

```text
ecommerce_app_backend/
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Database/
│   │   └── Migrations/
│   ├── Models/
│   ├── Views/
│   │   └── invoices/
│   └── ...
├── public/
├── tests/
├── writable/
├── .env
├── composer.json
├── spark
└── README.md
```

*This is a simplified overview. Your actual project may contain additional files and directories.*

## ⚙️ Prerequisites

Install the following before running the backend:

* PHP compatible with your CodeIgniter 4 version
* Composer
* MySQL
* Required PHP extensions for CodeIgniter 4
* Node.js and npm for running the separate frontend

## 📦 Installation

### 1. Clone the repository

```bash
git clone https://github.com/AmanShaikhTech10/ecommerce-app.git
```

### 2. Navigate to the backend directory

```bash
cd ecommerce-app/ecommerce_app_backend
```

### 3. Install PHP dependencies

```bash
composer install
```

### 4. Configure the environment

Copy `env` to `.env` if you do not already have an environment file.

Configure the relevant settings in `.env`, including your application environment, base URL, database connection, and session settings.

Example database configuration:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = ecommerce_db
database.default.username = your_mysql_username
database.default.password = your_mysql_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Replace the example database name, username, password, and port with your actual local configuration. Create the database in MySQL before running migrations.

**Security:** Never commit `.env` or real database credentials to GitHub.

### 5. Run database migrations

```bash
php spark migrate
```

Ensure the database connection is configured correctly before running migrations.

### 6. Start the backend server

```bash
php spark serve --host 0.0.0.0 --port 8080
```

The backend will be available locally at:

```text
http://localhost:8080
```

## 🔗 Frontend Integration

The backend works with the React frontend located in:

```text
ecommerce_app_frontend/
```

The frontend uses Axios to send HTTP requests to the backend API.

When running the frontend and backend on different origins, configure CORS and session cookies appropriately. Use credentials in Axios requests where required by the server-side session authentication setup.

## 🗄️ Database

The backend uses MySQL to persist application data.

Depending on the implemented schema, database tables may include:

* Users
* Products
* Cart items
* Orders
* Order items

Razorpay-related order fields may also be present for payment processing.

Use the project's migrations to manage database schema changes.

## 💳 Payment Integration

The application includes a payment workflow that may use simulated payments during development.

For Razorpay integration:

* Create an order on the server using the Razorpay API.
* Send the necessary order information to the frontend.
* Verify the payment signature on the backend.
* Update the order and payment status only after successful server-side verification.
* Keep Razorpay secret keys on the backend and out of frontend code and GitHub.

Use Razorpay Test Mode while developing and testing payment flows.

## 🔐 Security

* Store passwords using secure password hashing.
* Enforce authentication and authorization on protected API endpoints.
* Validate and sanitize incoming data.
* Keep secrets and database credentials in environment configuration.
* Configure CORS to allow only trusted frontend origins.
* Protect session-based authentication against CSRF where applicable.
* Validate uploaded files and restrict file types and sizes.
* Verify payment details on the server.
* Use HTTPS in production.

## 🧪 Useful Commands

| Command                    | Description                     |
| -------------------------- | ------------------------------- |
| `composer install`         | Install PHP dependencies        |
| `php spark serve`          | Start the development server    |
| `php spark migrate`        | Run pending database migrations |
| `php spark migrate:status` | Check migration status          |
| `php spark routes`         | Display registered routes       |
| `php spark test`           | Run the configured test suite   |

## 🚧 Future Enhancements

* Complete Razorpay payment verification and webhook handling.
* Add product search, filtering, and sorting APIs.
* Improve API validation and standardized error responses.
* Add automated tests for authentication, cart, orders, and payments.
* Implement production logging and monitoring.
* Deploy the backend with secure environment configuration.

## 👨‍💻 Author

**Aman Shaikh**

GitHub: [@AmanShaikhTech10](https://github.com/AmanShaikhTech10)

## 📄 License

No license has been specified yet. Add an appropriate license before permitting others to reuse or distribute this project.
