# E-Commerce Frontend

A modern e-commerce web application frontend built with **React**, **Vite**, and **React Router**. The application provides a shopping experience with product browsing, product details, user authentication, cart management, checkout, and payment flow.

## 🚀 Features

* **User Authentication** — User registration and login.
* **Product Listing** — Browse products fetched from an online product API.
* **Product Details** — View product information, images, and pricing.
* **Pagination** — Navigate through product listings.
* **Shopping Cart** — Add and remove products, update quantities, and view the cart item count.
* **Checkout & Orders** — Proceed through the order and checkout flow.
* **Payment Flow** — Simulated payment flow for testing.
* **Admin Dashboard** — Interface for administrative functionality.
* **API Integration** — Communicate with the CodeIgniter 4 backend using Axios.
* **Client-Side Routing** — Navigate between pages using React Router.

## 🛠️ Tech Stack

* React
* Vite
* JavaScript
* React Router DOM
* Axios
* CSS

## 📁 Project Structure

```text
ecommerce_app_frontend/
├── public/
├── src/
│   ├── components/
│   │   └── Navbar.jsx
│   ├── pages/
│   │   ├── Home.jsx
│   │   ├── Products.jsx
│   │   ├── ProductDetails.jsx
│   │   ├── Cart.jsx
│   │   ├── Login.jsx
│   │   ├── Signup.jsx
│   │   ├── AdminDashboard.jsx
│   │   ├── MockPayment.jsx
│   │   └── OrderSuccess.jsx
│   ├── App.jsx
│   └── main.jsx
├── index.html
├── package.json
└── vite.config.js
```

*The structure above represents the main files; additional files may exist in the project.*

## ⚙️ Prerequisites

Make sure you have installed:

* Node.js
* npm
* Git

## 📦 Installation

**1. Clone the repository**

```bash
git clone https://github.com/AmanShaikhTech10/ecommerce-app.git
```

**2. Navigate to the frontend directory**

```bash
cd ecommerce-app/ecommerce_app_frontend
```

**3. Install dependencies**

```bash
npm install
```

**4. Start the development server**

```bash
npm run dev
```

Open the local URL printed in your terminal, typically:

```text
http://localhost:5173
```

## 🔗 Backend Integration

The frontend communicates with a **CodeIgniter 4 REST API backend** using Axios.

Backend project directory:

```text
ecommerce_app_backend/
```

Make sure the backend server and its database are configured and running before testing features that require server communication.

If the backend API base URL is configured in an Axios instance, verify that it points to your running backend.

## 🧪 Development Commands

| Command           | Description                          |
| ----------------- | ------------------------------------ |
| `npm run dev`     | Start the Vite development server    |
| `npm run build`   | Build the production application     |
| `npm run preview` | Preview the production build locally |
| `npm install`     | Install project dependencies         |

## 🔐 Security Notes

* Never commit API secrets, passwords, or private credentials.
* Keep environment-specific configuration out of version control.
* Authentication and authorization should be enforced by the backend, not just by frontend route checks.
* Use HTTPS when deploying the application.

## 🚧 Future Enhancements

* Integrate a production payment gateway.
* Improve responsive design across mobile, tablet, and desktop.
* Add product search, filtering, and sorting.
* Add order history and order tracking.
* Improve loading states, error handling, and form validation.
* Deploy the frontend and backend.

## 👨‍💻 Author

**Aman Shaikh**

GitHub: [@AmanShaikhTech10](https://github.com/AmanShaikhTech10)


