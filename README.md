# 🏬 Inventory Management API

A simplified RESTful API built with Laravel to manage inventory across multiple warehouses. The system allows managing stock, tracking stock transfers, and monitoring low stock levels efficiently.

---

## 🚀 Features

- 🏢 Manage multiple **warehouses** with location data
- 📦 Create and manage **inventory items** with SKU, name, price, etc.
- 🔢 Track **stock quantity** per item per warehouse
- 🔁 Handle **stock transfers** between warehouses
- 🔍 Search items by name or price range
- 📄 Paginated inventory listings with filters
- ⚡️ **Cache** inventory listings for fast access
- 📩 Trigger **low stock detection event** when quantity is low
- ✅ Input validation and structured error responses
- 🔐 **API authentication** with Laravel Sanctum
- 🧪 Unit & feature tests for key functionality

---
## ⚙️ Installation

```bash
git clone https://github.com/sarakamalelsadek/inventory.git
cd inventory

# Install dependencies
composer install

# Set up environment
cp .env.example .env
php artisan key:generate

# Configure migration and seeding data
php artisan migrate --seed

# Start the server
php artisan serve

# start the queue
php artisan queue:work
```
## 🧱 Tech Stack

- Backend: **Laravel 10**
- Authentication: **Laravel Sanctum**
- Database: **MySQL**
- Caching: **File**
- Testing: **PHPUnit**

---

## 📦 Database Models

- `Warehouse`: `id`, `name`, `location`
- `InventoryItem`: `id`, `name`, `sku`, `price`, `description`
- `Stock`: `warehouse_id`, `inventory_item_id`, `quantity`
- `StockTransfer`: `from_warehouse_id`, `to_warehouse_id`, `inventory_item_id`, `quantity`, `status`, `created_by`

---

## 📲 API Endpoints (Sample)

| Method | Endpoint                              | Description                              |
|--------|----------------------------------------|------------------------------------------|
| GET    | `/api/inventory`                      | Paginated inventory with filters         |
| GET    | `/api/warehouses/{id}/inventory`      | Inventory of a specific warehouse        |
| POST   | `/api/stock-transfers`                | Transfer stock between warehouses        |

> All endpoints require Bearer Token via Sanctum

---

## 📌 Events & Listeners

- **Event**: `LowStockDetected`
  - Triggered when stock quantity falls below a threshold
- **Listener**: `SendLowStockNotification`
  - Logs the low stock detection (can be queued to send email)

---

## 🔐 Authentication

We use **Laravel Sanctum** for token-based API authentication.

To authenticate:
- Register/Login and get your token
- Use `Authorization: Bearer {token}` in all requests

---

## 🧪 Testing

```bash
php artisan test
