# Nesto Loyalty System API Documentation

## Base URL
`/api`

## Authentication
All protected routes require a Bearer token in the `Authorization` header.
`Authorization: Bearer <your_access_token>`

---

## 1. Customer Management

### 1.1 Customer Registration
Register a customer in the loyalty system.
- **Endpoint:** `POST /customers/register`
- **Headers:** `Accept: application/json`
- **Request Body:**
  ```json
  {
      "nic": "901234567V",
      "mobile": "0771234567",
      "name": "John Doe"
  }
  ```
- **Responses:**
  - `201 Created`: Registration successful.
  - `422 Unprocessable Entity`: Validation failed.

### 1.2 Account Activation
Activate a registered profile and set login credentials.
- **Endpoint:** `POST /customers/activate`
- **Headers:** `Accept: application/json`
- **Request Body:**
  ```json
  {
      "nic": "901234567V",
      "password": "password123",
      "password_confirmation": "password123"
  }
  ```
- **Responses:**
  - `200 OK`: Account activated successfully.
  - `400 Bad Request`: Account is already active.
  - `422 Unprocessable Entity`: Validation failed.

### 1.3 Portal Login
Login to the system to get the access token.
- **Endpoint:** `POST /login`
- **Headers:** `Accept: application/json`
- **Request Body:**
  ```json
  {
      "nic": "901234567V",
      "password": "password123"
  }
  ```
- **Responses:**
  - `200 OK`: Login successful, returns `access_token`.
  - `401 Unauthorized`: Invalid credentials.
  - `403 Forbidden`: Account not activated.
  - `422 Unprocessable Entity`: Validation failed.

### 1.4 Logout
Logout the authenticated user and revoke their token.
- **Endpoint:** `POST /logout`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Responses:**
  - `200 OK`: Logged out successfully.

---

## 2. Points Accumulation

### 2.1 Order Creation
Store customer order details and calculate loyalty points.
- **Endpoint:** `POST /orders`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Request Body:**
  ```json
  {
      "invoice_number": "INV-10001",
      "transaction_date": "2023-10-01",
      "branch": "Colombo",
      "amount": 15500
  }
  ```
- **Responses:**
  - `201 Created`: Order captured successfully, returns `points_earned`.
  - `422 Unprocessable Entity`: Validation failed.

---

## 3. Tracking Loyalty Points

### 3.1 Total Loyalty Points
Retrieve the customer's total accumulated loyalty points.
- **Endpoint:** `GET /dashboard/points`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Responses:**
  - `200 OK`: Returns `total_points`.

### 3.2 Order History
Retrieve the customer's order history with earned points.
- **Endpoint:** `GET /dashboard/orders`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Responses:**
  - `200 OK`: Returns an array of `orders`.
