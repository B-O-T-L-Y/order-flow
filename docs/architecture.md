[🏠 Main](../README.md)

# 🏗 System Architecture
This document outlines the architecture of the `OrderFlow` system, detailing its backend, frontend, database, and infrastructure components.

## 🏛 Architectural Overview
The `OrderFlow` system follows a **monolithic Laravel backend** with a **decoupled Vue 3 frontend**, adopting a **microservices-like approach**. The system utilizes:
- `Docker` for containerization,
- `Redis` for caching and queue management,
- `WebSockets` for real-time communication.

## 🔹 System Components
| Component          | Technology      | Purpose                               |
|--------------------|-----------------|---------------------------------------|
| `Backend`          | Laravel 11      | API, authentication, business logic   |
| `Frontend`         | Vue 3, Vite     | User interface, client-side rendering |
| `Database`         | MySQL           | Persistent data storage               |
| `Cache`            | Redis           | Fast data access, queues, WebSockets  |
| `Queue System`     | Laravel Horizon | Background job processing             |
| `Real-time`        | Laravel Reverb  | WebSockets for real-time updates      |
| `Web Server`       | Nginx           | Reverse proxy, load balancing         |
| `Containerization` | Docker          | Environment isolation                 |
| `Testing`          | PHPUnit, Vitest | Automated testing                     |

## 🏗 Backend Architecture
### 🔹 Laravel API Structure
The backend is built as a **RESTful API** using Laravel 11.  
It follows:
- `MVC (Model-View-Controller) architecture` for clear separation of concerns.
- `Service Layer Pattern` to handle business logic efficiently.

## 🎨 Frontend Components
- `State Management`: Pinia for centralized application state.
- `UI Framework`: Tailwind CSS for rapid UI styling.
- `API Communication`: `useFetch` for HTTP requests.
- `Authentication`: Sanctum for secure API token-based authentication.

## 🗄 Database Architecture
- `Primary Database`: MySQL
- `ORM`: Laravel Eloquent
- `Indexes`: Optimized for query performance.
- `Migrations & Seeders`: Automate database structure and initial data.

## 📁 Database Schema
### 🔹 Key Tables
| Table           | Description                                            |
|-----------------|--------------------------------------------------------|
| `users`         | Stores user accounts and authentication details        |
| `orders`        | Manages order records                                  |
| `products`      | Contains product listings                              |
| `order_product` | Many-to-Many pivot table for orders and products       |
| `exports`       | Tracks export operations and logs                      |

## ⚡️ Queues & WebSockets
### 🔹 Queue System
- `Queue Driver`: Redis
- `Queue Manager`: Laravel Horizon
- `Workers`: Managed by Supervisor for process stability.

### 🔹 Real-time WebSockets
- `Technology`: Laravel Reverb
- `WebSocket Channels`:
  - `orders.{userId}` – Live order updates for individual users.
  - `admin.orders` – Admin panel live order monitoring.

## 🌍 Deployment & Infrastructure
The project is fully **containerized** with `Docker Compose` for easy deployment and scalability.

### 🔹 Deployment Stack
| Service   | Role                                    |
|-----------|-----------------------------------------|
| `Nginx`   | Reverse proxy for the Laravel backend   |
| `PHP-FPM` | Executes Laravel application code       |
| `MySQL`   | Primary relational database storage     |
| `Redis`   | Caching and queue storage               |
| `Horizon` | Monitors and manages background jobs    |
| `Reverb`  | Handles WebSockets for real-time events |

## 🔍 Summary
The `OrderFlow` system is built with **Laravel 11 and Vue 3**, leveraging **Redis, Horizon, and WebSockets** for real-time updates.  
The project follows a **Docker-based architecture** with a **clear separation between the backend and frontend**.

### 🚀 Key Features:
✅ `API-first design` for seamless frontend-backend communication.  
✅ `Real-time order tracking` via WebSockets.  
✅ `Asynchronous job processing` using Redis queues.  
✅ `Event broadcasting` for instant system updates.  
✅ `Fully containerized` deployment using Docker.

[⬅ Preview](testing.md)