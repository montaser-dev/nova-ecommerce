# NOVA E-Commerce

## Project Goal

NOVA is a modern, portfolio-grade e-commerce web application built from scratch.

The goal is to demonstrate real-world full-stack development skills using Laravel, Vue, Inertia, Tailwind CSS, and MySQL.

## Tech Stack

* Laravel 13
* PHP 8.5
* MySQL
* Vue 3
* Inertia.js
* Tailwind CSS 4
* Vite
* Git / GitHub

## Application

NOVA will have two main areas:

### Customer Store

* Home
* Shop
* Categories
* Product details
* Search
* Filtering
* Sorting
* Wishlist
* Cart
* Checkout
* Account
* Orders
* Reviews

### Admin Dashboard

* Dashboard
* Products
* Categories
* Orders
* Customers
* Inventory
* Coupons
* Reviews
* Sales analytics

## Development Principles

* Build the application step by step.
* Keep the code clean and maintainable.
* Use reusable Vue components.
* Make the UI responsive and professional.
* Include loading, empty, error, and success states where appropriate.
* Avoid unnecessary packages.
* Do not implement features that have not been planned.
* Keep the project suitable for a professional developer portfolio.

## Current Status

* Laravel installed
* MySQL configured
* Database migrations working
* Inertia installed
* Vue installed
* Tailwind CSS installed
* Vite configured
* Laravel + Inertia + Vue + Tailwind working

## Important Rule

PROJECT.md is the shared source of truth for the project.

Any important architecture or feature decision should eventually be reflected here.

## Database Schema

### Core Tables

- users
- addresses
- categories
- products
- product_images
- product_variants
- cart_items
- orders
- order_items
- payments
- reviews

### Relationships

- User has many addresses
- User has many cart items
- User has many orders
- User has many reviews
- Category has many products
- Product belongs to category
- Product has many images
- Product has many variants
- Product has many reviews
- Order belongs to user
- Order has many order items
- Order has one payment
### Table Columns

#### users
- id
- name
- email
- password
- role
- email_verified_at
- created_at
- updated_at

#### addresses
- id
- user_id
- first_name
- last_name
- phone
- address_line
- city
- postal_code
- country
- is_default
- created_at
- updated_at

#### categories
- id
- name
- slug
- description
- image
- is_active
- created_at
- updated_at

#### products
- id
- category_id
- name
- slug
- description
- price
- compare_price
- status
- created_at
- updated_at

#### product_images
- id
- product_id
- image
- is_primary
- created_at
- updated_at

#### product_variants
- id
- product_id
- sku
- size
- color
- price
- stock
- created_at
- updated_at

#### cart_items
- id
- user_id
- product_id
- product_variant_id
- quantity
- created_at
- updated_at

#### orders
- id
- user_id
- status
- subtotal
- shipping_cost
- total
- shipping_address
- billing_address
- created_at
- updated_at

#### order_items
- id
- order_id
- product_id
- product_variant_id
- product_name
- variant_details
- quantity
- unit_price
- total
- created_at
- updated_at

#### payments
- id
- order_id
- payment_method
- transaction_id
- amount
- status
- paid_at
- created_at
- updated_at

#### reviews
- id
- user_id
- product_id
- rating
- comment
- status
- created_at
- updated_at

### Database Relationships & Constraints

#### users
- role: customer | admin
- email must be unique

#### addresses
- user_id references users.id
- deleting a user deletes their addresses

#### categories
- name must be unique
- slug must be unique

#### products
- category_id references categories.id
- slug must be unique
- price must be >= 0
- compare_price must be >= 0
- status: active | draft | archived

#### product_images
- product_id references products.id
- deleting a product deletes its images

#### product_variants
- product_id references products.id
- sku must be unique
- price must be >= 0
- stock must be >= 0
- deleting a product deletes its variants

#### cart_items
- user_id references users.id
- product_id references products.id
- product_variant_id references product_variants.id
- quantity must be > 0
- deleting a user deletes their cart items
- deleting a product deletes its cart items
- deleting a variant deletes its cart items

#### orders
- user_id references users.id
- status: pending | confirmed | processing | shipped | delivered | cancelled
- subtotal must be >= 0
- shipping_cost must be >= 0
- total must be >= 0
- deleting a user is restricted if they have orders

#### order_items
- order_id references orders.id
- product_id references products.id
- product_variant_id references product_variants.id
- quantity must be > 0
- unit_price must be >= 0
- total must be >= 0
- deleting an order deletes its order items
- product and variant references are preserved for historical orders

#### payments
- order_id references orders.id
- one payment per order
- payment_method: cash_on_delivery | card
- status: pending | paid | failed | refunded
- amount must be >= 0

#### reviews
- user_id references users.id
- product_id references products.id
- rating must be between 1 and 5
- status: pending | approved | rejected
- one review per user per product

### Database Indexes

- users.email: unique index
- categories.slug: unique index
- products.slug: unique index
- products.category_id: index
- product_images.product_id: index
- product_variants.product_id: index
- product_variants.sku: unique index
- cart_items.user_id: index
- cart_items.product_id: index
- cart_items.product_variant_id: index
- orders.user_id: index
- orders.status: index
- order_items.order_id: index
- order_items.product_id: index
- order_items.product_variant_id: index
- payments.order_id: unique index
- reviews.product_id: index
- reviews.user_id: index

### Database Design Notes

- Use Laravel migrations for the complete database schema.
- Use foreign keys for all relationships.
- Use cascading deletes only where explicitly defined above.
- Preserve product and variant information in order_items for historical orders.
- Store money values using decimal fields, not floating-point fields.
- Use timestamps on all tables.
- Use soft deletes for products and categories if needed during implementation.
- Database implementation must follow this PROJECT.md schema.
