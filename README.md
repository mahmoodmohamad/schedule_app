Here's the rewritten content as a proper GitHub `README.md` file:

```markdown
# 🚀 Social Post Scheduler API (Laravel 12 + Vue 3)

A full-featured Social Media Scheduling platform built with **Laravel 12** and **Vue 3**. This API-first project enables users to authenticate, manage platforms (like Twitter or LinkedIn), schedule posts, enforce daily limits, and view filtered post history — all with a modern frontend powered by Vue.

---

## ⚙️ Tech Stack

- **Backend:** Laravel 12 (PHP 8+), Sanctum Auth, Eloquent, Jobs, API Resources  
- **Frontend:** Vue 3 (Composition API, Vite)  
- **Database:** MySQL / PostgreSQL  
- **Queue System:** Laravel Queues for background publishing  
- **Authentication:** Laravel Sanctum  
- **API Style:** RESTful  
- **Testing:** PHPUnit (coming soon), Postman collection available  

---

## ✨ Features Overview

- ✅ Register & authenticate users via token-based login  
- 📅 Schedule posts across multiple platforms  
- 📌 Set post status: `draft`, `scheduled`, or `published`  
- 🧩 Select multiple platforms per post  
- 🧵 Background job to publish scheduled posts  
- 🚫 Enforce a daily limit of **10 scheduled posts per user**  
- 🔍 Filter posts by status or scheduled date  
- 📥 Upload images (via API)  
- ⚙️ Platform management with CRUD  

---

## 📁 Project Architecture (Simplified)


---

## 🔐 Authentication

Laravel Sanctum powers token-based login.

| Method | Endpoint        | Description              |
|--------|------------------|--------------------------|
| POST   | `/api/register`  | Register new user        |
| POST   | `/api/login`     | Get access token         |
| GET    | `/api/profile`   | Authenticated user info  |

> 🔒 All secured endpoints require the `Authorization: Bearer {token}` header.

---

## 📝 Post Management

| Action                   | Description                          |
|--------------------------|--------------------------------------|
| `POST /api/posts`        | Create and schedule a post           |
| `GET /api/user/{user}/posts` | List user's posts with filters |
| `DELETE /api/posts/{id}` | Delete post (planned)                |
| `PUT /api/posts/{id}`    | Edit draft/scheduled post (planned)  |

### 📦 Post Payload Example

```json
{
  "title": "Launch Feature",
  "content": "We're going live with version 2.0!",
  "image_url": "https://example.com/image.jpg",
  "schedule_time": "2025-08-01T10:00:00",
  "status": "scheduled",
  "platform_ids": [1, 2]
}
```

> ⚠️ Max 10 scheduled posts per user per day. Exceeding limit returns `429 Too Many Requests`.

---

## 🧩 Platform Management

| Action                       | Description               |
| ---------------------------- | ------------------------- |
| `GET /api/platforms`         | List all platforms        |
| `POST /api/platforms`        | Add new platform          |
| `DELETE /api/platforms/{id}` | Delete platform (planned) |

---

## 🖼️ Frontend (Vue 3)

### ✅ Components

* `PostForm.vue`: Compose & schedule posts
* `PlatformTable.vue`: Manage platform list
* `PostList.vue`: Display grouped & filtered posts by date and status

### 🧠 UX Features

* Dynamic platform dropdown with fallback
* Image upload via FormData
* Real-time form validation
* Group posts by date (Today, Tomorrow, etc.)
* Character counters for title & content
* Status indicators (draft, scheduled, published)

---

## 🛠️ API Usage Example: Create Post

```http
POST /api/posts
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "My First Post",
  "content": "This is a test post.",
  "schedule_time": "2025-08-02T15:00:00",
  "status": "scheduled",
  "platform_ids": [1, 2]
}
```

---

## 🔄 Background Jobs

Scheduled posts are published automatically using Laravel's queue system.

```bash
php artisan queue:work
```

Job Class: `PublishScheduledPost`

---

## 🔍 Filtering Posts

Use query parameters with the posts endpoint:

```
GET /api/user/{user}/posts?status=scheduled
GET /api/user/{user}/posts?schedule_time=2025-08-01
```


---

## 🤝 Contributing

Pull requests are welcome.  
Please fork the repository and submit a clear PR.  
Found a bug or have a feature request? [Open an issue](https://github.com/mahmoodmohamad/social-post-scheduler/issues)

---

## 👨‍💻 Maintainer

Built and maintained by [Mahmoud Mohamed](https://github.com/mahmoodmohamad)

---

## 📄 License

Licensed under the [MIT License](LICENSE)
```
