# Moto Parts Shop - Motorcycle Parts Management System

A comprehensive motorcycle parts management and sales system with a Laravel backend for administrators and a Flutter mobile application for clients. This platform helps motorcycle shops manage their inventory, track orders, and offer customers an easy way to browse and purchase compatible parts for their motorcycles.

## 🏍️ Features

### Admin Platform (Laravel)
- **Client Management**: Register, view, and manage client information
- **Motorcycle Models**: Manage different motorcycle brands and models  
- **Parts Inventory**: Organize parts with hierarchical structure (parent-child relationships)
- **Order Processing**: Track order status from pending to delivery
- **Sales Dashboard**: View statistics and monitor business performance
- **Data Export**: Export client, model, and order data to CSV

### Client Application (Flutter)
- **User Authentication**: Secure login and registration
- **Motorcycle Management**: Store multiple motorcycles in user profile
- **Compatible Parts**: View parts compatible with registered motorcycles
- **Shopping Cart**: Add parts to cart and manage quantities
- **Order Tracking**: View order status and history
- **Profile Management**: Update personal information

## 🛠️ Technologies

### Backend
- **Laravel 10.x**: PHP framework for the admin platform
- **MySQL**: Database system
- **Laravel Sanctum**: API token authentication
- **Carbon**: Date manipulation

### Mobile App
- **Flutter**: Cross-platform mobile application framework
- **Provider**: State management
- **HTTP Package**: API communication
- **Cached Network Image**: Image caching and loading

## 📦 Installation

### Backend Setup
1. Clone the repository
   ```bash
   git clone https://github.com/yourusername/moto-parts-shop.git
   cd moto-parts-shop
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Configure environment
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up database in .env file
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=moto_parts
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Run migrations and seeders
   ```bash
   php artisan migrate --seed
   ```

6. Create storage link for images
   ```bash
   php artisan storage:link
   ```

7. Start the server
   ```bash
   php artisan serve
   ```

### Mobile App Setup
1. Navigate to the mobile app directory
   ```bash
   cd mobile-app
   ```

2. Install Flutter dependencies
   ```bash
   flutter pub get
   ```

3. Update API base URL in `lib/services/api_service.dart`
   ```dart
   static const String baseUrl = "http://your-server-address/api";
   ```

4. Run the app
   ```bash
   flutter run
   ```

## 📊 Database Structure

The system includes the following main entities:
- **Users**: Admin users who manage the system
- **Clients**: Motorcycle owners who purchase parts
- **Models**: Motorcycle models with brand and year information
- **Motos**: Individual motorcycles associated with clients
- **Schemas**: Parts and components, which can have hierarchical relationships
- **Commandes**: Orders placed by clients
- **Carts**: Shopping carts for clients

## 🌐 API Endpoints

The system provides a comprehensive API for the mobile application:

### Authentication
- `POST /api/register`: Register a new client
- `POST /api/login`: Authenticate a client
- `POST /api/logout`: End a client session

### Client Management
- `GET /api/profile`: Get client profile
- `PUT /api/updateProfile`: Update client information
- `PUT /api/change-password`: Change client password

### Motorcycles
- `GET /api/client/motos`: Get client's motorcycles
- `POST /api/client/motos`: Add a motorcycle
- `DELETE /api/client/motos/{id}`: Remove a motorcycle
- `GET /api/motos/{id}/schemas`: Get compatible parts for a motorcycle

### Shopping Cart
- `GET /api/cart`: Get cart contents
- `POST /api/cart/add`: Add item to cart
- `PUT /api/cart/update`: Update item quantity
- `DELETE /api/cart/remove`: Remove item from cart
- `DELETE /api/cart/clear`: Empty the cart

### Orders
- `GET /api/commandes`: Get client's orders
- `GET /api/commandes/{id}`: Get order details
- `POST /api/commandes/create-from-cart`: Create order from cart
- `PUT /api/commandes/{id}/cancel`: Cancel an order

## 🔐 Admin Access

You can access the admin panel at `http://your-server-address/login` with:
- Email: admin@example.com
- Password: password

## 📋 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Contributors

- [Your Name](https://github.com/yourusername)
- [Other Contributors](https://github.com/othercontributor)

---

Made with ❤️ for motorcycle enthusiasts