Task Management API
This project is a Task Management API built using Laravel (version 12) on the backend and Vue.js on the frontend. It provides functionality to create, retrieve, update, and delete tasks, following best practices to ensure scalability, maintainability, and a smooth user experience.

Technologies Used
Backend: Laravel 12, MySQL

Frontend: Vue.js, Vue Router, Pinia, Ant Design

State Management: Pinia

API Requests: Axios

Installation
Prerequisites
Before you start, ensure you have the following installed:

PHP >= 8.1

Composer

Node.js and npm

MySQL

Backend Setup (Laravel)
Clone the repository:

bash
Copy
Edit
git clone https://github.com/yourusername/task-management-api.git
cd task-management-api
Install the dependencies:

bash
Copy
Edit
composer install
Copy the .env.example to .env:

bash
Copy
Edit
cp .env.example .env
Set up your database in the .env file:

env
Copy
Edit
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
Generate the application key:

bash
Copy
Edit
php artisan key:generate
Run the migrations and seed the database:

bash
Copy
Edit
php artisan migrate --seed
This will create the necessary tables in the database and populate them with some sample data for testing.

Frontend Setup (Vue.js)
Navigate to the frontend directory:

bash
Copy
Edit
cd frontend
Install the dependencies:

bash
Copy
Edit
npm install
Start the development server:

bash
Copy
Edit
npm run serve
Open your browser and go to http://localhost:8080 to access the task management UI.

API Endpoints
The API provides the following endpoints to manage tasks:

GET /api/tasks: Retrieve a list of tasks.

POST /api/tasks: Create a new task.

PUT /api/tasks/{id}: Update an existing task.

DELETE /api/tasks/{id}: Delete a task.

Backend Details
Migrations & Database Design
In the backend, database migrations are used to ensure a clean and structured database schema:

Tasks Table: Includes essential fields such as title, description, status, and timestamps. Required fields and nullable fields are appropriately defined.

Example Migration:

php
Copy
Edit
Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description');
    $table->enum('status', ['pending', 'in-progress', 'completed']);
    $table->timestamps();
});
Factories & Seeders: Factories and seeders are used to populate the database with sample data for testing purposes, providing a foundation for testing API functionality.

Controller Methods
The task management logic is handled through the controller, where the methods are designed for task creation, retrieval, updating, and deletion. Error handling and validation are incorporated using Laravel's built-in validation system.

Logging & Error Handling
Laravel's logging functionality is integrated into the backend to ensure proper error tracking. This helps with debugging and provides insights into any issues that may occur.

Frontend Details
Vue.js & Pinia
On the frontend, I used Vue.js with Vue Router for seamless navigation and routing. To manage the application state, Pinia is used, which makes the state management simpler and more maintainable.

Pinia is extended with an Axios interceptor for better handling of API requests and responses. This ensures that all HTTP calls are streamlined, and responses are consistently formatted.


Ant Design
I used Ant Design for creating the UI components, such as buttons, forms, tables, and modals, providing a polished and user-friendly interface for interacting with the task management system.

The final task management UI includes:

A list view of all tasks

A form to create and edit tasks

Task deletion functionality with a confirmation modal

Best Practices & Scalable Approach
This solution follows best practices to ensure the code is scalable and maintainable:

Modular Structure: The backend follows a clean MVC structure with well-defined controllers, models, and migrations.

Error Handling: Errors are handled gracefully on both the backend and frontend, providing clear feedback to users.

State Management: Pinia is used for efficient state management, ensuring the application remains scalable as it grows.

UI/UX: Ant Design provides a responsive and intuitive user interface, making the application user-friendly.

Conclusion
This project implements a scalable and maintainable task management system using Laravel, MySQL, Vue.js, and Pinia. The API allows for creating, retrieving, updating, and deleting tasks, while the frontend offers an intuitive user experience. Best practices were followed throughout the development process to ensure clean, maintainable, and robust code.

