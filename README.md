# 🛒 E-Commerce Web Application

A full-stack e-commerce web application built with **React, CodeIgniter 4, PHP, and MySQL**. The project provides an online shopping experience with product browsing, user authentication, cart management, order processing, and payment workflow integration.

## 🚀 Features

### 🛍️ Customer Features

* User registration and login.
* Browse products from an online product API.
* View detailed product information and images.
* Navigate products using pagination.
* Add products to the shopping cart.
* Update product quantities and remove cart items.
* View the cart item count.
* Proceed through checkout and order processing.
* Simulated payment workflow for development and testing.
* Generate PDF invoices for orders.

### 🛡️ Admin Features

* Dedicated admin authentication and access control.
* Admin dashboard.
* Administrative product and order functionality, where implemented.

### ⚙️ Backend Features

* REST API built with CodeIgniter 4.
* Server-side session-based authentication.
* MySQL database integration.
* User, cart, and order management.
* Database migrations.
* Invoice generation.
* Payment workflow support.

## 🛠️ Tech Stack

| Technology       | Purpose                                 |
| ---------------- | --------------------------------------- |
| React            | Frontend user interface                 |
| Vite             | Frontend development and build tooling  |
| JavaScript       | Frontend application logic              |
| React Router DOM | Client-side routing                     |
| Axios            | HTTP requests to the backend            |
| PHP              | Backend programming language            |
| CodeIgniter 4    | Backend framework and REST APIs         |
| MySQL            | Relational database                     |
| Composer         | PHP dependency management               |
| Git & GitHub     | Version control and source code hosting |

## 🏗️ Architecture

```text
                ┌────────────────────────┐
                │      User / Admin      │
                └────────────┬───────────┘
                             │
                             ▼
                ┌────────────────────────┐
                │     React + Vite       │
                │       Frontend         │
                └────────────┬───────────┘
                             │
                      Axios HTTP Requests
                             │
                             ▼
                ┌────────────────────────┐
                │     CodeIgniter 4      │
                │      REST API          │
                │  Authentication        │
                │  Cart & Orders         │
                │  Payment Workflow      │
                └────────────┬───────────┘
                             │
                             ▼
                ┌────────────────────────┐
                │        MySQL           │
                │       Database         │
                └────────────────────────┘
```

## 📁 Project Structure

```text
E-commerce/
├── ecommerce_app_frontend/
│   ├── public/
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── App.jsx
│   │   └── main.jsx
│   ├── package.json
│   └── vite.config.js
│
├── ecommerce_app_backend/
│   ├── app/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Database/
│   │   ├── Models/
│   │   └── Views/
│   ├── public/
│   ├── writable/
│   ├── composer.json
│   └── spark
│
└── README.md
```

*This is a simplified overview of the project structure.*

## ⚙️ Prerequisites

Install the following before running the project:

* Node.js and npm
* PHP compatible with CodeIgniter 4
* Composer
* MySQL Server
* Git

## 📦 Installation and Setup

### 1. Clone the repository

```bash
git clone https://github.com/AmanShaikhTech10/ecommerce-app.git
cd ecommerce-app
```

### 2. Configure the backend

Navigate to the backend directory:

```bash
cd ecommerce_app_backend
composer install
```

Create a `.env` file from the provided `env` template if needed. Configure the application URL and database credentials.

Example MySQL configuration:

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

Replace the example values with your actual MySQL configuration and create the database before continuing.

Run database migrations:

```bash
php spark migrate
```

Start the backend server:

```bash
php spark serve --port 8080
```

Backend URL:

`http://localhost:8080`

### 3. Configure the frontend

Open a second terminal from the repository root:

```bash
cd ecommerce_app_frontend
npm install
npm run dev
```

Open the local URL displayed by Vite, typically:

`http://localhost:5173`

### 4. Verify frontend and backend communication

* Ensure both development servers are running.
* Confirm the frontend's Axios configuration points to the correct backend URL.
* Configure CORS and session cookies correctly for local development.
* Verify that the MySQL database connection works.

## 💳 Payment Workflow

The application includes a payment workflow for development and testing. Simulated payments can be used to test success and failure scenarios.

Razorpay integration can be configured for test payments. A production payment implementation must create orders and verify payment signatures on the backend before marking orders as paid.

**Never expose payment secret keys, database passwords, or other private credentials in frontend code or public repositories.**

## 🔐 Security Considerations

* Use server-side authentication and authorization for protected operations.
* Hash passwords securely.
* Validate incoming requests on the backend.
* Configure CORS for trusted frontend origins.
* Protect session-based authentication against CSRF where applicable.
* Validate uploaded files and restrict file sizes and types.
* Store credentials in environment variables or secure configuration.
* Use HTTPS in production.

## 🧪 Development Commands

### Frontend

| Command           | Description                  |
| ----------------- | ---------------------------- |
| `npm install`     | Install dependencies         |
| `npm run dev`     | Start the development server |
| `npm run build`   | Build for production         |
| `npm run preview` | Preview the production build |

### Backend

| Command                    | Description                  |
| -------------------------- | ---------------------------- |
| `composer install`         | Install PHP dependencies     |
| `php spark serve`          | Start the development server |
| `php spark migrate`        | Run database migrations      |
| `php spark migrate:status` | Check migration status       |
| `php spark routes`         | List registered routes       |
| `php spark test`           | Run configured tests         |

## 🚧 Future Improvements

* Complete and verify live payment gateway integration.
* Add product search, filtering, and sorting.
* Improve responsive design for mobile and tablet devices.
* Add order history and order tracking.
* Expand automated tests.
* Improve API error handling and validation.
* Deploy the frontend and backend to production hosting.

## 👨‍💻 Author

**Aman Shaikh**

GitHub: [@AmanShaikhTech10](https://github.com/AmanShaikhTech10)

## 📂 Repository

[View the E-Commerce project on GitHub](https://github.com/AmanShaikhTech10/ecommerce-app)

## 📄 License

No license has been specified yet. Add an appropriate license before permitting others to reuse or distribute this project.
