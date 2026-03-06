# Technical Requirements Document: Debate Club Management System

## Executive Summary

This document outlines the architecture and functional requirements for a local Debate Club Management System. The application is designed to manage club members, training sessions, attendance, and debate competition records. The core technical requirement is a local web application built with PHP and the Laravel framework, featuring a bi-directional data synchronization mechanism with Google Sheets to ensure data parity between local storage and cloud records.

## User Personas & Roles

The system must support dynamic, overlapping role assignments where a single `Person` entity can hold multiple roles concurrently.

* **Administrator (Admin):** Manages all system data, triggers manual syncs, and handles system configuration.
* **Trainer/Instructor:** Leads training sessions and marks attendance for trainees.
* **Member:** A standard registered participant of the club.
* **Beneficiary/Trainee:** A person attending training sessions or workshops.

## Core Features & Functional Requirements

### 1. Entity & Role Management

* **Requirement:** Implement a central `Persons` table linked to a `Roles` table via a Many-to-Many (M:N) relationship.
* **Data Fields:** First Name, Last Name, Contact Info, Join Date.
* **Behavior:** The system must allow assigning or revoking multiple roles (e.g., Admin + Trainer) to a single person without duplicating their base record.

### 2. Training & Workshop Management

* **Requirement:** Track educational sessions and recurring training programs.
* **Data Fields:** Session Title (e.g., "Introduction to Debate", "Introduction to Control"), Category, Scheduled Date, Time, Duration.
* **Relationships:** * M:N relationship with `Persons` (Trainers).
* M:N relationship with `Persons` (Trainees).



### 3. Attendance Tracking

* **Requirement:** Granular attendance tracking for all training sessions.
* **Data Fields:** Session ID, Person ID, Role (Trainer/Trainee), Status (Present/Absent).
* **Behavior:** Must allow bulk attendance updates per session.

### 4. Debate & Competition Tracking

* **Requirement:** Log all internal, friendly, and international debates.
* **Data Fields:** Debate Title, Type (Friendly, International Competition, Internal), Date, Location, Outcome/Results.
* **Relationships:** M:N relationship with `Persons` to track exact participants and their specific roles (Debater, Judge, Moderator) within that specific event.

### 5. Google Sheets Bi-Directional Synchronization

* **Requirement:** Establish a two-way data sync between the local Laravel database and a designated Google Sheet.
* **Pull Mechanism (Cloud to Local):** Fetch structural and data updates from Google Sheets and upsert them into the local database based on a unique identifier (e.g., UUID or Row ID).
* **Push Mechanism (Local to Cloud):** Listen for Eloquent model events (created, updated, deleted) in Laravel and push changes to the corresponding Google Sheet via the Google Sheets API.
* **Conflict Resolution:** Implement a timestamp-based "last modified" check to prevent overwriting newer data with older data during the sync process.

## Non-Functional Requirements (Performance, Security)

| Category | Requirement | Description |
| --- | --- | --- |
| **Environment** | Local Execution | The system must be capable of running entirely locally (e.g., via Laravel Sail, XAMPP, or a built-in PHP server). |
| **Performance** | Sync Efficiency | Google Sheets synchronization must be handled asynchronously (e.g., using Laravel Queues) to prevent blocking the local UI during HTTP requests. |
| **Security** | Authentication | Basic local authentication using Laravel Breeze or Jetstream to restrict access to the dashboard. |
| **Security** | API Key Management | Google API credentials (OAuth 2.0 or Service Account keys) must be strictly stored in the `.env` file and ignored by version control. |
| **Data Integrity** | Database Transactions | Complex operations (like deleting a person and all their associative M:N pivot records) must be wrapped in database transactions to prevent orphaned data. |

## Data Flow & System Constraints

1. **Framework:** Laravel (Latest stable version).
2. **Language:** PHP (v8.1+).
3. **Local Database:** SQLite or MySQL (SQLite is highly recommended for purely local deployments to minimize environment setup).
4. **Integration:** Google Sheets API v4.
5. **Relational Integrity Constraints:** * Cascading deletes should be applied carefully. Deleting a "Training Session" should cascade to delete the associated "Attendance" records.
* Querying must be deeply relational. The application must support eager loading (e.g., `Event::with('participants')->get();`) to efficiently retrieve complex datasets like "find a specific competition and list all participating members."
