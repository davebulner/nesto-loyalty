# Nesto Loyalty System API Documentation

## Base URL
`/api`

## Authentication
All protected routes require a Bearer token in the `Authorization` header.
`Authorization: Bearer <your_access_token>`

## Error Handling standard
For all endpoints, when a `422 Unprocessable Entity` occurs due to validation failure, the API is engineered to return a single, descriptive error message indicating the most critical failure, rather than a complex array of errors.

**Example 422 Error Response:**
```json
{
    "message": "Please enter exactly 10 digits for the mobile number."
}
```

---

## 1. Customer Management

### 1.1 Customer Registration
Register a customer in the loyalty system.
- **Endpoint:** `POST http://localhost:8000/api/customers/register`
- **Headers:** `Accept: application/json`
- **Request Body Validation Rules:**
  - `nic` (string, required): Must be unique in the system.
  - `mobile` (string, required): Must be exactly 10 digits. Must be unique.
  - `name` (string, required): Full name of the customer.
- **Request Body Example:**
  ```json
  {
      "nic": "901234567V",
      "mobile": "0771234567",
      "name": "Saman Perera"
  }
  ```
- **Responses:**
  - `201 Created`: Registration successful.
  - `422 Unprocessable Entity`: Validation failed (e.g., NIC already registered, mobile format invalid).

### 1.2 Account Activation
Activate a registered profile and set login credentials. Customers must be registered first (e.g., via a cashier) before activating.
- **Endpoint:** `POST http://localhost:8000/api/customers/activate`
- **Headers:** `Accept: application/json`
- **Request Body Validation Rules:**
  - `nic` (string, required): Must already exist in the registered users table.
  - `password` (string, required): Minimum of 8 characters. Must match `password_confirmation`.
  - `password_confirmation` (string, required): Must match `password`.
- **Request Body Example:**
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
  - `422 Unprocessable Entity`: Validation failed (e.g., NIC not found, password too short, passwords do not match).

### 1.3 Portal Login
Login to the system to get the access token.
- **Endpoint:** `POST http://localhost:8000/api/login`
- **Headers:** `Accept: application/json`
- **Request Body Validation Rules:**
  - `nic` (string, required).
  - `password` (string, required).
- **Request Body Example:**
  ```json
  {
      "nic": "901234567V",
      "password": "password123"
  }
  ```
- **Responses:**
  - `200 OK`: Login successful, returns `access_token`.
  - `401 Unauthorized`: Invalid credentials.
  - `403 Forbidden`: Account not activated. Please activate it first.
  - `422 Unprocessable Entity`: Validation failed (missing fields).

### 1.4 Logout
Logout the authenticated user and revoke their token.
- **Endpoint:** `POST http://localhost:8000/api/logout`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Responses:**
  - `200 OK`: Logged out successfully.

---

## 2. Points Accumulation

### 2.1 Order Creation
Store customer order details and calculate loyalty points.
- **Endpoint:** `POST http://localhost:8000/api/orders`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Request Body Validation Rules:**
  - `invoice_number` (string, required): Must be unique.
  - `transaction_date` (date, required).
  - `branch` (string, required).
  - `amount` (numeric, required): Must be greater than or equal to 0.
- **Request Body Example:**
  ```json
  {
      "invoice_number": "INV-10001",
      "transaction_date": "2026-07-24",
      "branch": "Colombo",
      "amount": 15500
  }
  ```
- **Responses:**
  - `201 Created`: Order captured successfully, returns `points_earned`.
  - `422 Unprocessable Entity`: Validation failed (e.g., duplicate invoice, negative amount).

---

## 3. Tracking Loyalty Points

### 3.1 Total Loyalty Points
Retrieve the customer's total accumulated loyalty points.
- **Endpoint:** `GET http://localhost:8000/api/dashboard/points`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Responses:**
  - `200 OK`: Returns `total_points`.

### 3.2 Order History
Retrieve the customer's order history with earned points.
- **Endpoint:** `GET http://localhost:8000/api/dashboard/orders`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Responses:**
  - `200 OK`: Returns an array of `orders`.
