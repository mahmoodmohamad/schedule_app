# 🚀 Social Post Scheduler API · Laravel 12

A powerful RESTful API built with **Laravel 12**, designed to streamline scheduling and publishing of social media content. This API enables authenticated users to manage their social media strategy by creating, scheduling, editing, and tracking posts across multiple platforms — such as **Twitter** and **LinkedIn** — with precision and control.

---

## 🔧 Tech Stack

- **Laravel 12** (PHP 8+)
- **Sanctum** for API token authentication  
- **Queue workers** for background job handling  
- **MySQL** / **PostgreSQL**  
- **Postman collection** (included)  

---

## ✨ Key Features

- 🔐 **Secure Authentication** via Laravel Sanctum  
- 🗓️ **Schedule Posts** for future publishing across multiple platforms  
- 📊 **Status Filtering** by `draft`, `scheduled`, or `published`  
- ✏️ **Update or Delete** posts before publishing  
- 🔄 **Platform-Specific Publishing** — toggle platforms per post (e.g., Twitter only)  
- ⏱️ **Auto-Publishing** via queued background jobs  
- 📈 **Post Limits** — max 10 scheduled posts per user per day  

---

## 🔐 Authentication Endpoints

The API uses **Laravel Sanctum** to authenticate and protect user routes.

| Method | Endpoint         | Description                     |
|--------|------------------|---------------------------------|
| POST   | `/api/register`  | Register a new user             |
| POST   | `/api/login`     | Authenticate and receive token  |
| GET    | `/api/profile`   | Retrieve authenticated user     |

> 🔒 All protected endpoints require the header:  
> `Authorization: Bearer {token}`

---

## 📝 Post Management API

| Feature                | Description |
|------------------------|-------------|
| **Create Post**        | Define post content and select platforms |
| **Schedule Post**      | Set future publishing date & time |
| **Edit Post**          | Modify draft/scheduled posts |
| **Delete Post**        | Remove draft/scheduled posts |
| **Filter Posts**       | Query posts by `status` or `date` |
| **Toggle Platforms**   | Choose active platforms per post |

---

## 🔄 Background Processing

A **queue-based job system** ensures posts scheduled for future publication are automatically published using supported platform integrations. Managed via Laravel’s `queue:work` command.

---

## 🚧 Rate Limiting

Each authenticated user is allowed a **maximum of 10 scheduled posts per day**.

---

## 📁 Project Structure (High-Level)

```bash
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           └── PostController.php
├── Models/
│   └── Post.php
├── Jobs/
│   └── PublishScheduledPost.php
routes/
└── api.php
