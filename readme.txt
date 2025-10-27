
PROJECT: Simple Blog Management System
FRAMEWORK: CodeIgniter 3
Requirements 
CodeIgniter 3.x
PHP 5.6.0 to PHP 7.2–7.4
XAMPP 5.6 to 7.4
WAMPP WAMP 3.1.x to 3.2.x	


1. DATABASE SCHEMA (database.sql)
   ├── Users Table Creation
   ├── Categories Table Creation
   ├── Posts Table Creation
   ├── Foreign Key Relationships

2. CONFIGURATION FILES
   ├── database.php - Database connection settings
   ├── routes.php - URL routing configuration
   └── config.php - Application configuration

3. CONTROLLERS (4 Files)
   ├── Auth.php - User authentication (register, login, logout)
   ├── Dashboard.php - Dashboard display with statistics
   ├── Category.php - Category CRUD operations with AJAX
   └── Post.php - Post CRUD operations with file upload

4. MODELS (3 Files)
   ├── User_model.php - User database operations
   ├── Category_model.php - Category database operations
   └── Post_model.php - Post database operations

5. VIEWS (6 Files)
   ├── auth/login.php - Login page
   ├── auth/register.php - Registration page
   ├── dashboard.php - Dashboard page
   ├── category/index.php - Categories management page
   ├── post/index.php - Posts list page
   └── post/form.php - Post add/edit form page

6. FRONT-END CONFIGURATION
   ├── .htaccess - URL rewriting for clean URLs
   └── (Bootstrap, jQuery)

File: database.sql
Purpose: Complete database schema
Contents:
  - CREATE DATABASE blog_management
  - CREATE TABLE users
    * id (INT, PK, Auto-increment)
    * name (VARCHAR 100)
    * email (VARCHAR 100, Unique)
    * password (VARCHAR 255, hashed)
    * created_at (DATETIME)
  
  - CREATE TABLE categories
    * id (INT, PK, Auto-increment)
    * name (VARCHAR 100, Unique)
    * status (TINYINT 1, 1=Active, 0=Inactive)
    * created_at (DATETIME)
  
  - CREATE TABLE posts
    * id (INT, PK, Auto-increment)
    * category_id (INT, FK)
    * title (VARCHAR 150)
    * slug (VARCHAR 200)
    * cover_image (VARCHAR 255)
    * description (TEXT)
    * status (TINYINT 1)
    * created_at (DATETIME)
  
 
================================================================================

CONFIGURATION FILES:
================================================================================

File: application/config/database.php
Purpose: MySQL database connection configuration
Key Settings:
  - hostname: localhost
  - username: root
  - password: (empty)
  - database: blog_management
  - dbdriver: mysqli


File: application/config/config.php
Purpose: CodeIgniter application configuration
Key Settings:
  - base_url: http://localhost/blog_management/
  - index_page: (empty for clean URLs)
  - uri_protocol: REQUEST_URI
  - encryption_key: your-secret-encryption-key-change-this
  - Session settings (7200 second timeout)
  - Cookie settings
Configuration: Update base_url to match installation path

File: application/config/routes.php
Purpose: URL routing configuration
Route Mappings:
  - / → auth/login (default)
  - /register → auth/register
  - /login → auth/login
  - /logout → auth/logout
  - /dashboard → dashboard/index
  - /category → category/index
  - /category/get_all → category/get_all (AJAX)
  - /category/add → category/add (AJAX)
  - /category/edit/{id} → category/edit (AJAX)
  - /category/update → category/update (AJAX)
  - /category/delete/{id} → category/delete (AJAX)
  - /category/toggle_status/{id} → category/toggle_status (AJAX)
  - /post → post/index
  - /post/form → post/form
  - /post/form/{id} → post/form (edit)
  - /post/save → post/save
  - /post/delete/{id} → post/delete
Configuration: Pre-configured, no changes needed

================================================================================

CONTROLLER FILES:
================================================================================

File: application/controllers/Auth.php
Purpose: Handle user authentication
Methods:
  - register() - Display registration form and handle submission
    * Validates: name, email (unique), password (6+ chars), confirm password
    * Password hashing: bcrypt
    * On success: redirects to login with success message
    * On error: displays form with validation errors
  
  - login() - Display login form and authenticate user
    * Validates: email (valid format), password
    * Verifies: email exists, password matches
    * On success: creates session, redirects to dashboard
    * On error: displays form with error message
  
  - logout() - Destroy session and redirect to login
    * Destroys all session data
    * Redirects to login page

Features:
  - Secure password hashing with bcrypt
  - Session-based authentication
  - Form validation (client & server)
  - Error messages display

File: application/controllers/Dashboard.php
Purpose: Display dashboard after login
Methods:
  - index() - Load dashboard with statistics
    * Calculates: total categories, active categories
    * Calculates: total posts, active posts
    * Loads dashboard view with statistics

Authentication: Session check (if not logged in, redirect to login)

Data Passed to View:
  - total_categories
  - total_posts
  - active_categories
  - active_posts

File: application/controllers/Category.php
Purpose: Handle category CRUD operations
Methods:
  - index() - Display categories management page
    * Loads category/index.php view
  
  - get_all() - Get all categories (AJAX)
    * Returns: JSON array of categories
    * Response: [{id, name, status, created_at}, ...]
  
  - add() - Add new category (AJAX)
    * Validates: name (required, unique, 2+ chars)
    * Server-side: CI3 Form Validation
    * On success: returns JSON {status: 1, message}
    * On error: returns JSON {status: 0, message: validation_errors}
  
  - edit($id) - Get category data for editing (AJAX)
    * Returns: JSON object with category data
  
  - update() - Update category (AJAX)
    * Validates: name (required)
    * Updates database record
    * Returns JSON response
  
  - delete($id) - Delete category (AJAX)
    * Cascade deletes associated posts
    * Returns JSON success/error message
  
  - toggle_status($id) - Toggle active/inactive (AJAX)
    * Switches status between 0 and 1
    * Returns JSON response

Features:
  - AJAX operations (no page reload)
  - Client-side validation (jQuery Validate)
  - Server-side validation (CI3)
  - JSON responses
  - Error handling

File: application/controllers/Post.php
Purpose: Handle post CRUD operations
Methods:
  - index() - Display all posts
    * Loads post list with category information
    * Joins posts and categories tables
  
  - form($id = 0) - Display add/edit post form
    * For add: $id = 0, show empty form
    * For edit: $id > 0, populate with existing data
    * Loads all categories for dropdown
  
  - save() - Save new or update existing post
    * Validates: category_id, title (5+ chars), slug (3+ chars), description (10+ chars)
    * Handles file upload: cover_image (gif/jpg/png, 2MB max)
    * File stored in: public/uploads/posts/
    * On success: redirects to post list with success message
    * On error: displays form with validation errors
  
  - delete($id) - Delete post
    * Deletes post record from database
    * Redirects to post list with message

Features:
  - File upload with validation
  - Form validation (client & server)
  - Category association
  - Status management
  - Same form for add and edit

================================================================================

MODEL FILES:
================================================================================

File: application/models/User_model.php
Purpose: Handle user database operations
Methods:
  - insert($data) - Insert new user
    * Parameters: ['name' => ..., 'email' => ..., 'password' => ...]
    * Returns: boolean
  
  - get_by_email($email) - Retrieve user by email
    * Returns: user object or NULL
  
  - get_by_id($id) - Retrieve user by ID
    * Returns: user object or NULL

File: application/models/Category_model.php
Purpose: Handle category database operations
Methods:
  - insert($data) - Insert new category
  - get_all() - Get all categories (ordered by ID DESC)
  - get_by_id($id) - Get specific category
  - update($id, $data) - Update category
  - delete($id) - Delete category
  - count_all() - Count total categories
  - count_by_status($status) - Count categories by status

File: application/models/Post_model.php
Purpose: Handle post database operations
Methods:
  - insert($data) - Insert new post
  - get_all() - Get all posts
  - get_all_with_category() - Get posts with category names (JOIN)
  - get_by_id($id) - Get specific post
  - update($id, $data) - Update post
  - delete($id) - Delete post
  - count_all() - Count total posts
  - count_by_status($status) - Count posts by status

================================================================================

VIEW FILES:
================================================================================

File: application/views/auth/login.php
Purpose: Display login page
Elements:
  - Email input field
  - Password input field
  - Login button
  - "Register here" link
  - Success/error messages display
  - Bootstrap styling
  - Form submission to /auth/login

File: application/views/auth/register.php
Purpose: Display registration page
Elements:
  - Name input field
  - Email input field
  - Password input field
  - Confirm password input field
  - Register button
  - "Login here" link
  - Error messages display
  - Bootstrap styling
  - Form submission to /auth/register

File: application/views/dashboard.php
Purpose: Display dashboard with statistics
Elements:
  - User greeting with name
  - Statistics cards:
    * Total categories count
    * Active categories count
    * Total posts count
    * Active posts count
  - Sidebar navigation menu
  - Quick action buttons
  - Bootstrap responsive layout

File: application/views/category/index.php
Purpose: Display category management page
Elements:
  - Category table with DataTables
  - Columns: ID, Name, Status, Created Date, Actions
  - "Add Category" button
  - Add/Edit modal (Bootstrap)
  - Edit, Delete, Toggle Status buttons
  - Category form with validation
  - AJAX implementation
  - jQuery DataTables integration
  - jQuery Validate form validation

Features:
  - Sortable columns
  - Searchable table
  - Pagination
  - Modal for add/edit
  - Real-time validation
  - AJAX submission

File: application/views/post/index.php
Purpose: Display posts list
Elements:
  - Posts table with DataTables
  - Columns: ID, Title, Category, Status, Created Date, Actions
  - "Add New Post" button
  - Edit and Delete buttons
  - Status badge (Active/Inactive)
  - Links to post form page
  - Success/error message display

File: application/views/post/form.php
Purpose: Display post add/edit form
Elements:
  - Category dropdown (populated from database)
  - Title text input
  - Slug text input
  - Description textarea
  - Cover image file upload
  - Status selector (Active/Inactive)
  - Submit button
  - Cancel button (back to list)
  - Form validation (jQuery Validate)
  - Existing data pre-filled for edit

Features:
  - Single form for add and edit
  - File upload with preview
  - Bootstrap styling
  - Client-side validation
  - Server-side validation

================================================================================

CONFIGURATION & SETUP FILES:
================================================================================

File: .htaccess
Purpose: Apache URL rewriting configuration
Contents:
  - Enable rewrite engine
  - Remove index.php from URLs
  - Prevent direct access to sensitive folders
  - Clean URL routing

Requirements: Apache with mod_rewrite enabled

File: index.php (CodeIgniter entry point)
Purpose: Application entry point
Configuration:
  - Set ENVIRONMENT (development/production)
  - Load CodeIgniter framework
  - Route requests to controllers

================================================================================


