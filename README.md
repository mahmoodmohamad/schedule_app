 Social Post Scheduler API (Laravel 12)
This is a Laravel 12 REST API project for managing and scheduling social media posts. Users can create posts and choose which platforms (like Twitter or LinkedIn) to publish them on. They can also schedule posts for later, update them, and track their status.

✅ Features
User authentication using Laravel Sanctum

Create and schedule posts to multiple platforms

Filter posts by status (draft, scheduled, published) and by date

Update or delete scheduled posts

Limit: users can schedule up to 10 posts per day

Toggle platform status per post

Background job to publish scheduled posts automatically

🔐 Authentication
This API uses Laravel Sanctum for secure login and registration.

Endpoints:

POST /api/register

POST /api/login

GET /api/profile – Get logged-in user's data

📝 Post Management
Users can:

Create a post and select platforms to publish on

Schedule it for a future time

Edit or delete it before it's published

View all their posts with filters for status or date

🛠️ Scheduled Job
A Laravel command/job runs in the background to:

Check for scheduled posts

Publish them to the selected platforms at the correct time

⚠️ Post Limit Rule
To prevent spamming, users are limited to 10 scheduled posts per day. If a user tries to schedule more than 10 for the same day, they will get a 429 Too Many Requests error.

📦 Installation
Clone the repository:


git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name
Install dependencies:


composer install
Set up environment:


cp .env.example .env
php artisan key:generate
Run migrations:


php artisan migrate

Run the job scheduler:


php artisan schedule:work

Start the development server:


php artisan serve
