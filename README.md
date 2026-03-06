# 🎙️ Debate Club Management System

A local web application for managing debate club members, training sessions, attendance, and competition records. Features bi-directional synchronization with Google Sheets to maintain data parity between local storage and cloud records.

## 📑 Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Usage Guide](#usage-guide)
- [Google Sheets Synchronization](#google-sheets-synchronization)
- [Project Structure](#project-structure)
- [License](#license)

## 🔍 Overview

The system tracks the full lifecycle of a debate club: registering members with flexible role assignments, scheduling training sessions, recording attendance, and logging debate competitions with participant roles. A single person can hold multiple roles simultaneously (e.g., Admin + Trainer), and every entity can be synchronized to Google Sheets for cloud-based access and backup.

### ✨ Core Features

- 👥 **Person & Role Management** -- Central registry with dynamic, overlapping roles (Admin, Trainer, Member, Beneficiary).
- 📚 **Training Session Management** -- Schedule sessions with assigned trainers and trainees, categorized by topic.
- ✅ **Attendance Tracking** -- Bulk present/absent marking per session for all participants.
- 🏆 **Debate & Competition Tracking** -- Log friendly, internal, and international debates with per-event participant roles (Debater, Judge, Moderator).
- 🔄 **Google Sheets Bi-Directional Sync** -- Push local changes to Google Sheets on save; pull cloud updates into the local database on demand, with timestamp-based conflict resolution.

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────┐
│                     Blade + Livewire UI                 │
│          (Tailwind CSS, Alpine.js, Breeze Auth)         │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│                    Laravel HTTP Layer                    │
│    Controllers ─► Form Requests ─► Policies (AuthZ)     │
└────────────────────────┬────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────┐
│                   Eloquent ORM Layer                     │
│  Models ─► Enums ─► Traits ─► Observers ─► Contracts    │
└──────────┬─────────────────────────────┬────────────────┘
           │                             │
┌──────────▼──────────┐   ┌──────────────▼───────────────┐
│   SQLite Database    │   │   Google Sheets Sync Layer   │
│  (local, zero-cfg)   │   │  SyncPushService (on save)   │
│                      │   │  SyncPullService (on demand)  │
│  - persons           │   │  ConflictResolver (timestamps)│
│  - roles             │   │  Queue Jobs (async)           │
│  - training_sessions │   │  GoogleSheetsClient (API v4)  │
│  - debates           │   └──────────────────────────────┘
│  - pivot tables      │
└──────────────────────┘
```

### 🧱 Design Principles

- 🔷 **SOLID** -- Syncable contract (Interface Segregation), service classes (Single Responsibility), dependency injection via Service Provider (Dependency Inversion).
- ♻️ **DRY** -- Shared `HasGoogleSheetSync` trait across all syncable models; backed enums for type-safe constants; reusable form request classes.
- 🛡️ **Data Integrity** -- All destructive and multi-step operations wrapped in `DB::transaction()`. Cascading deletes on pivot tables. `Model::preventLazyLoading()` enforced in development.

## 🛠️ Technology Stack

| Layer | Technology | Version |
|---|---|---|
| Framework | Laravel | 12.x |
| Language | PHP | 8.2+ |
| Frontend | Livewire + Blade | 3.x |
| CSS | Tailwind CSS | 4.x |
| JS Interactivity | Alpine.js | 3.x |
| Authentication | Laravel Breeze | 2.x |
| Database | SQLite | -- |
| Queue Driver | Database | -- |
| Cloud Sync | Google Sheets API | v4 |
| API Client | google/apiclient | 2.x |

## 📋 Prerequisites

- 🐘 **PHP 8.2+** with extensions: `sqlite3`, `pdo_sqlite`, `mbstring`, `openssl`, `curl`
- 📦 **Composer** (2.x)
- 💚 **Node.js** (18+) and **npm**
- ☁️ (Optional) A Google Cloud project with the Sheets API enabled and a Service Account key file for synchronization

## 📥 Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd "Debate's Club DB"

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Create the SQLite database and run migrations
touch database/database.sqlite   # Linux/macOS
# On Windows: New-Item database\database.sqlite -ItemType File
php artisan migrate

# 6. Seed the predefined roles
php artisan db:seed

# 7. Build frontend assets
npm run build
```

## 🚀 Running the Application

### ⚡ Quick Start (all services)

```bash
composer dev
```

This starts the web server, queue worker, log watcher, and Vite dev server concurrently.

### 🔧 Manual Start

```bash
# Terminal 1 -- Web server
php artisan serve

# Terminal 2 -- Queue worker (required for async Google Sheets sync)
php artisan queue:work

# Terminal 3 -- Vite dev server (for hot-reload during development)
npm run dev
```

The application will be available at **http://localhost:8000**.

## 📖 Usage Guide

### 1. 🔐 Register & Log In

Navigate to `http://localhost:8000/register` to create your account. After registration you are redirected to the dashboard.

### 2. 📊 Dashboard

The dashboard displays summary statistics (total persons, training sessions, debates) along with upcoming sessions and recent debates.

### 3. 👥 Managing Persons

Navigate to **Persons** in the top navigation.

- **Add Person** -- Fill in first name, last name, contact info, join date, and assign one or more roles via checkboxes.
- **Edit / Delete** -- Use the action links in the persons table.
- **View Details** -- Click a person's name to see their roles, training sessions, and debate participation history.

### 4. 📚 Training Sessions

Navigate to **Training Sessions**.

- **Create Session** -- Set title, category, date, time, and duration. Assign trainers and trainees from the persons list.
- **Take Attendance** -- From a session's detail page, click "Take Attendance" to bulk-mark each participant as Present or Absent.

### 5. 🏆 Debates

Navigate to **Debates**.

- **Create Debate** -- Set title, type (Friendly / International / Internal), date, location, and outcome. Add participants with their event-specific role (Debater, Judge, Moderator) using the dynamic form rows.
- **View Details** -- See all participants grouped by their role in the debate.

### 6. 🔄 Sync with Google Sheets

Navigate to **Sync** to view the last sync timestamps and trigger manual operations.

## ☁️ Google Sheets Synchronization

### 🔑 Setup

1. Create a project in [Google Cloud Console](https://console.cloud.google.com/).
2. Enable the **Google Sheets API**.
3. Create a **Service Account** and download the JSON key file.
4. Place the key file somewhere secure on your machine (not in the repo).
5. Share your target Google Sheet with the Service Account email (grant Editor access).
6. Set the following in your `.env` file:

```env
GOOGLE_SERVICE_ACCOUNT_JSON=/absolute/path/to/service-account-key.json
GOOGLE_SHEET_ID=your-spreadsheet-id-from-the-url
```

### ⚙️ How It Works

- ⬆️ **Push (Local to Cloud)** -- When a Person, Training Session, or Debate is created, updated, or deleted locally, an Eloquent Observer dispatches a queued `SyncPushJob` that writes the change to the corresponding Google Sheet tab.
- ⬇️ **Pull (Cloud to Local)** -- Triggered manually from the Sync page. A `SyncPullJob` reads all rows from Google Sheets and upserts them into the local database.
- ⚖️ **Conflict Resolution** -- Each row carries a `last_modified` timestamp. The `ConflictResolver` compares remote and local timestamps; the newer version always wins.

### 📄 Expected Sheet Structure

Each syncable model maps to a sheet tab. The first column is always the UUID (sync identifier) and the last column is the `last_modified` ISO 8601 timestamp. Example for the **Persons** tab:

| uuid | first_name | last_name | contact_info | join_date | last_modified |
|---|---|---|---|---|---|

## 🗂️ Project Structure

```
app/
├── Contracts/          # Syncable interface
├── Enums/              # SessionRole, AttendanceStatus, DebateType, etc.
├── Http/
│   ├── Controllers/    # Person, TrainingSession, Attendance, Debate, Sync, Dashboard
│   └── Requests/       # Form request validation classes
├── Jobs/               # SyncPushJob, SyncPullJob (queued)
├── Models/             # Person, Role, TrainingSession, Debate, User
├── Observers/          # SyncObserver (dispatches push jobs on model events)
├── Policies/           # PersonPolicy, TrainingSessionPolicy, DebatePolicy
├── Providers/          # AppServiceProvider, GoogleSheetsServiceProvider
├── Services/
│   └── GoogleSheets/   # GoogleSheetsClient, SyncPushService, SyncPullService, ConflictResolver
└── Traits/             # HasGoogleSheetSync (UUID generation, sync timestamps)

database/
├── migrations/         # Schema for persons, roles, pivots, sessions, debates
└── seeders/            # RoleSeeder (Admin, Trainer, Member, Beneficiary)

resources/views/
├── dashboard.blade.php
├── persons/            # index, create, edit, show
├── training-sessions/  # index, create, edit, show, attendance
├── debates/            # index, create, edit, show
└── sync/               # index (sync dashboard)
```

## 📄 License

This project is licensed under the [Apache 2.0 License](LICENSE).
