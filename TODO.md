# Coffee Shop Website Development Plan

## Database & Migrations
- [x] Update .env for MySQL connection (DB_CONNECTION=mysql, DB_DATABASE=coffeoshop, etc.)
- [x] Edit users migration: Add 'role' column (varchar(50), default 'user')
- [x] Create admins migration: id, name, email, password, timestamps
- [x] Create categories migration: id, name, timestamps
- [x] Create products migration: id, category_id (foreign), name, description, price (decimal), image (string), timestamps
- [x] Create orders migration: id, user_id (foreign), total (decimal), status (enum), timestamps
- [x] Create order_items migration: id, order_id (foreign), product_id (foreign), quantity (int), price (decimal), timestamps
- [ ] Create seeders for sample categories/products

## Models
- [x] Edit User model: Add 'role' to fillable, relationships (hasMany Orders), scope for users
- [x] Create Admin model: Extends Authenticatable, fillable/hidden/casts
- [x] Create Category model: HasFactory, fillable, hasMany Products
- [x] Create Product model: HasFactory, fillable, belongsTo Category, hasMany OrderItems
- [x] Create Order model: HasFactory, fillable, belongsTo User, hasMany OrderItems
- [x] Create OrderItem model: HasFactory, fillable, belongsTo Order/Product

 ## Controllers
 - [x] Create AuthController: Admin login/register
 - [x] Create ProductController: index, show, CRUD for admin
 - [x] Create CategoryController: CRUD for admin
 - [x] Create CartController: add, remove, update, index, clear (session/DB)
 - [x] Create OrderController: store (checkout), index (history), show; admin index/update status
 - [x] Create DashboardController: Admin overview, user redirect
 - [ ] Edit ProfileController: Role checks if needed

 ## Routes
 - [x] Edit web.php: Landing as products, add product/cart/order routes, admin prefixed routes
 - [x] Edit auth.php: Add admin login/register routes

## Views
- [x] Replace welcome.blade.php with home.blade.php: Hero, categories, products grid
- [x] Create products/index.blade.php: Filtered list
- [x] Create products/show.blade.php: Product details
- [x] Create cart/index.blade.php: Cart table, actions
- [x] Create checkout/index.blade.php: Form for order
- [x] Create orders/index.blade.php: User history
- [x] Create orders/show.blade.php: Order details
- [x] Edit dashboard.blade.php: Role-based content
- [x] Create admin/dashboard.blade.php: Stats, links
- [x] Create admin CRUD views: products/categories/orders index/create/edit
- [ ] Edit auth/login.blade.php: Add admin option or separate view

## JavaScript
- [x] Update app.js: addToCart, updateCartItem, removeItem, category filter (AJAX)

## Middleware
- [x] Create AdminMiddleware: Check role/admin guard
- [x] Register in bootstrap/app.php

## Other
