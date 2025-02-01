[🏠 Main](../README.md)

# 🎨 Frontend Guide
## 📦 Docker Container

| Container  | Description                        |
|------------|------------------------------------|
| `frontend` | Vue 3 application running with Vit |

The frontend runs inside a `Node.js Alpine` container.

## 🔨 Technologies
The frontend is built with the `Vue 3` framework, leveraging modern tooling and best practices.

- `Vue 3` – A progressive JavaScript framework utilizing the Composition API for reactivity and modularity.
- `Vite` – A fast frontend build tool optimized for modern JavaScript applications.
- `TypeScript` – Provides static typing, improving code safety and maintainability.
- `Pinia` – A lightweight state management library designed for Vue 3 applications.
- `LocalStorage` – Used for caching user preferences and authentication tokens when necessary.
- `Tailwind CSS` – A utility-first CSS framework for rapid UI development.
- `ESLint` – Ensures code consistency and catches potential issues early.
- `Laravel Echo` – Enables real-time event broadcasting with WebSockets.
- `Pusher` – A WebSocket-based service for real-time updates (using Reverb).
- `PostCSS` – A tool for transforming CSS with JavaScript plugins.
- `Prettier` – A code formatter that ensures a consistent style across the project.
- `SPA CSRF Protection` – Implements secure CSRF token handling for API requests.

## ⛓ Queues & WebSockets
The frontend interacts with WebSockets using `Laravel Echo` and `Pusher` (via Reverb).  
It listens for real-time events broadcasted from the backend.

## 📡 API Requests
All API requests use either `fetch` for interacting with the backend.

## 🔗 Authentication
- Uses `Sanctum` for secure SPA authentication.
- The authentication state is managed via `Pinia`.
- The session is automatically verified on page load.

## 🔧 Running the Frontend
To start the frontend container:
```
make up
```

To enter the frontend container:
```
make front
```

To check logs:
```
make logs-front
```

## 🌍 Environment Variables
| Variable             | Description                    |
|----------------------|--------------------------------|
| `VITE_BACKEND_URL`   | Base URL of the backend API    |
| `VITE_FRONTEND_URL`  | Base URL of the frontend       |
| `VITE_REVERB_HOST`   | WebSockets host                |
| `VITE_REVERB_PORT`   | WebSockets port                |
| `VITE_REVERB_SCHEME` | WebSockets scheme (http/https) |

[⬅ Preview](installation.md) | [Next ➡](backend.md)