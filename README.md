# ♻️ EcoSmart - Smart Waste Management System

EcoSmart is a high-end, industry-grade Smart Waste Management platform built with **Laravel 12**. It features a role-based ecosystem (Admin, User, Worker), AI-powered waste classification, interactive mapping, and real-time data visualization.

## ✨ Key Features

- **🛡️ Role-Based Access Control (RBAC)**: Dedicated dashboards for Admins, Workers, and Regular Users.
- **🤖 AI Waste Classification**: Uses **ml5.js** (on-device AI) to automatically detect waste types (Dry/Wet/Mixed) from uploaded images.
- **🗺️ Interactive Mapping**: **Leaflet.js** integration for pinning collection points and navigating workers to tasks.
- **📊 Real-time Analytics**: **Chart.js** powered insights for system-wide waste distribution and trends.
- **📧 Automated Notifications**: Instant email alerts for task assignments and completions.
- **📱 PWA Ready**: Installable on mobile and desktop with offline support and a standalone experience.
- **🎨 Premium UI/UX**: Dark-themed Glassmorphism design with **GSAP** animations and smooth transitions.
- **📄 API Documentation**: Integrated Swagger/OpenAPI documentation.

## 🚀 Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Blade, Tailwind CSS, GSAP, Leaflet.js, Chart.js, ml5.js
- **Database**: MySQL / SQLite
- **Auth**: Laravel Breeze

## 🛠️ Setup Instructions

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/ecosmart.git
   cd ecosmart
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**:
   - Create a database named `waste_management`.
   - Update `.env` with your database credentials.
   ```bash
   php artisan migrate --seed
   ```

5. **Storage Link**:
   ```bash
   php artisan storage:link
   ```

6. **Launch**:
   ```bash
   php artisan serve
   ```

## 🧪 Test Credentials

- **Admin**: `admin@example.com` / `password`
- **Worker**: `worker@example.com` / `password`
- **User**: `user@example.com` / `password`

## 📄 License
MIT License.
