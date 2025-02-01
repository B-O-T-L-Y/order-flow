[🏠 Main](../README.md)

# 🗿 Backend Guide

## 📦 Docker Containers
The backend is managed using `Docker Compose`. The primary containers include:

| Container   | Description                                |
|-------------|--------------------------------------------|
| `backend`   | Laravel application running on PHP 8.2     |
| `mysql`     | MySQL database for persistent storage      |
| `redis`     | Redis for caching and queue processing     |
| `horizon`   | Laravel Horizon process manager for queues |
| `reverb`    | WebSockets for real-time events            |
| `nginx`     | Reverse proxy serving the application      |
| `frontend`  | Vue 3 frontend container                   |

To start the backend:
```
  make up
```

To check logs:
```
make logs-back
```

## 🔨 Technologies Used
- `Laravel 11` - PHP framework for backend logic.
- `PHP 8.2` - The latest stable PHP version.
- `MySQL 8.0` - Database management system.
- `Redis` - In-memory data structure store for caching and queues.
- `Queues (Horizon + Supervisor)` - Background job processing system.
- `Reverb (WebSockets)` - Real-time event broadcasting.
- `SPA Authentication (Sanctum)` - Secure API authentication for frontend communication.

## ⛓ Queues and Horizon
Laravel queues allow the system to process tasks asynchronously using Redis as the queue driver.

### 🔧 Running Queues
Queues are managed via `Laravel Horizon`, which runs under `Supervisor`.

Start a queue worker manually:
```
php artisan queue:work
```

Start Horizon:
```
php artisan horizon
```

### 🛠 Supervisor Configuration
`Supervisor` is used to keep Horizon and other background processes running.  

## 🛠 Supervisor Configuration
Located in `docker/php/supervisord.conf`:

The configuration is stored in:
#### **Supervisor Configuration File (`supervisord.conf`):**
```ini
[supervisord]
nodaemon=true
minfds=10000
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=php-fpm
autostart=true
autorestart=true

[program:horizon]
process_name=%(program_name)s
command=php /var/www/html/artisan horizon
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=order_flow
redirect_stderr=true
stdout_logfile=/var/log/supervisor/horizon.log

[program:reverb]
command=php /var/www/html/artisan reverb:start
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/reverb.err.log
stdout_logfile=/var/log/supervisor/reverb.out.log
```

To restart queues and Horizon:
```
docker compose restart backend
```

## 🎧 WebSockets with Reverb
`Reverb` is used to handle real-time events over WebSockets.

### 🔗 Broadcast Configuration (`.env` settings)
```dotenv
REVERB_APP_ID=729152
REVERB_APP_KEY=edypmdi239wlg1ufgyhms
REVERB_APP_SECRET=zxoqdajll1w15axaexti
REVERB_HOST=backend
REVERB_PORT=8080
REVERB_SCHEME=http
REVERB_SCALING_ENABLED=true
```

### 🔧 Starting the WebSockets Server
To manually start the WebSocket server:
```
php artisan reverb:start
```

## 📂 API Endpoints
| Path          | Description                     |
|---------------|---------------------------------|
| /api/orders   | Order management API            |
| /api/exports  | Export system API               |
| /api/products | Product management API          |
| /api/auth     | Authentication routes (Sanctum) |

## 📜 API Documentation (Swagger)
The API documentation is automatically generated using `Swagger`.
To regenerate Swagger documentation:
```
php artisan l5-swagger:generate
```

To view the Swagger documentation:
```
http://localhost:8000/api/documentation
```

[⬅ Preview](frontend.md) | [Next ➡](testing.md)