[🏠 Main](../README.md)

# 🔨 Installation Guide
## 📥 Clone the Repository
```
git clone git@github.com:B-O-T-L-Y/order-flow.git
```

## 📦 Working with Containers
> Before building the project, make sure you have the latest version of Docker installed and that `make` utilities are available on your system.

### 🚀 Starting, Stopping & Accessing Containers
*You can start, stop, and access project containers using the following commands.*

#### 🔼 Start the Project
```
make up
```

#### 🔽 Stop the Project
```
make down
``` 

#### 🖥 Access the Backend Container
```
make back
```

#### 🎨 Access the Frontend Container
```
make front
```

### 💾 Run Migrations
```
make back
php artisan migrate --seed
```

[⬅ Preview](../README.md) | [Next ➡](frontend.md)