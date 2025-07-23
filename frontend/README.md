# Task Management Frontend (Vue 3 + Pinia + Laravel Auth)

---

## 🚀 Features Implemented

- Token-based login with Laravel Sanctum
- Pinia for state management
- Axios instance with auto-token headers
- Route protection using `beforeEach`
- Drag-and-drop task reordering
- Task CRUD operations

---

## 🛠️ Project Setup

```bash
npm install
npm run dev
```


## 📫 API Documentation

All API endpoints are documented in the [Postman Collection](./docs/TaskManagement.postman_collection.json).

This includes:
- 🔐 Auth (register, login, logout, profile)
- ✅ Task CRUD & Reordering
- 👑 Admin dashboard routes

Tested using Laravel Sanctum with cookie-based SPA authentication.

## 📁 Folder Structure (Key Files)

```bash
backend/
├── app/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
├── routes/
│   └── api.php     # SPA API routes
├── storage/
├── tests/          # PHPUnit tests
├── .env.example
├── artisan
├── composer.json
└── README.md

frontend/
├── public/
├── src/
│   ├── assets/
│   ├── components/
│   ├── layouts/
│   ├── pages/
│   ├── router/
│   │   └── index.js
│   ├── store/
│   │   └── user.js
│   │   └── tasks.js
│   ├── utils/
│   ├── App.vue
│   └── main.js
├── tailwind.config.js
├── vite.config.js
├── package.json
└── README.md

frontend/
├── docs/
```