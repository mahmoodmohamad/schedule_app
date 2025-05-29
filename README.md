Social Post Scheduler API (Laravel 12)
This project is a REST API built with Laravel 12 for managing and scheduling social media posts. Users can create posts, choose platforms such as Twitter or LinkedIn to publish on, schedule posts for later, update them, and track their status.

✅ Features
User authentication using Laravel Sanctum

Create and schedule posts to multiple platforms

Filter posts by status (draft, scheduled, published) and by date

Update or delete scheduled posts

Limit of 10 scheduled posts per user per day

Toggle platform status for each post

Background job that automatically publishes scheduled posts

🔐 Authentication
The API uses Laravel Sanctum to secure login and registration.

Available endpoints:

POST /api/register

POST /api/login

GET /api/profile — Retrieve data of the logged-in user

📝 Post Management
Users can:

Create posts and select platforms to publish on

Schedule posts for future publishing

Edit or delete posts before they are published

View their posts with filters by status or date