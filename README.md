# Mora School

<p align="center">
  <strong>Smart Educational Platform for Children's Learning Management</strong>
</p>

<p align="center">
  A modern web-based system for managing children's digital learning, academic content, assessments, communication, and school administration.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/PHP-8.3-blue?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql" />
  <img src="https://img.shields.io/badge/Blade-Templates-green?style=for-the-badge" />
</p>

---

## Overview

**Mora School** is a comprehensive smart educational platform designed to manage and enhance the learning experience for children through a modern digital environment.

The system provides integrated tools for students, teachers, parents, and educational administrators. It enables teachers to publish lessons, activities, assignments, and assessments using different types of multimedia content such as videos, audio files, images, and text.

The platform also supports academic performance tracking, communication between teachers and parents, and administrative dashboards that help improve the quality of digital education.

---

## Project Purpose

This project aims to provide a safe, organized, and interactive digital learning environment for children while simplifying the management of educational processes inside schools and online learning institutions.

---

## Key Features

### Student Features

- Access lessons and educational content.
- View assignments and quizzes.
- Track academic progress.
- Receive notifications and announcements.
- Use a simple and child-friendly interface.

### Teacher Features

- Create and manage lessons.
- Upload multimedia educational content.
- Create assignments and quizzes.
- Evaluate student performance.
- Communicate with parents and administration.

### Parent Features

- Monitor child academic performance.
- Follow attendance and progress.
- Receive updates and notifications.
- Communicate with teachers.

### Administration Features

- Manage users and roles.
- Manage students, teachers, and parents.
- Control educational content.
- View reports and statistics.
- Manage permissions and system access.

---

## Main Objectives

- Provide a secure digital learning environment for children.
- Simplify educational content management.
- Support assignments, quizzes, and academic evaluation.
- Improve communication between teachers and parents.
- Enable continuous student performance tracking.
- Provide reports and analytics for educational decision-making.
- Support interactive learning through multimedia content.

---

## Technology Stack

### Backend

- Laravel 12
- PHP 8.3+
- Laravel Breeze Authentication
- Spatie Permission Management

### Frontend

- Blade Templates
- Tailwind CSS
- Alpine.js
- Vue.js

### Database

- MySQL

### Infrastructure & Tools

- Redis Cache
- Laravel Queue System
- Laravel Storage
- Composer
- NPM

---

## System Roles

The platform supports multiple user roles:

| Role | Description |
|---|---|
| Administrator | Full access to system management and control. |
| Teacher | Manages lessons, assignments, quizzes, and student evaluation. |
| Student | Accesses learning content, assignments, quizzes, and progress. |
| Parent | Monitors child performance and communicates with teachers. |
| School Management | Supervises academic and administrative operations. |

---

## Project Structure

```txt
mora-school/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md