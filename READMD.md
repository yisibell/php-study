# php-study

🐘 PHP-Study Learning Repository

This is a personal learning repository for PHP. A complete, independent, and portable PHP development environment is built with Docker Compose. All configurations are native Docker without any integrated panels, which is suitable for systematically learning Linux, Docker and PHP backend fundamentals.

---

📁 Project Structure

php-study/
├── docker-env/ # PHP 8.3 basic environment
├── docker-env2/ # PHP 8.4 latest practice environment
├── php7-annotation-routes/ # Annotation routing demo project in php 7
├── php8-annotation-routes/ # Annotation routing demo project in php 8
├── .gitignore
└── README.md

---

🧱 Tech Stack (Docker Containers)

- Nginx: Static resource hosting, request forwarding, reverse proxy

- PHP-FPM: PHP runtime (supports PHP 8.3 / 8.4)

- MySQL 8.0: Persistent data storage

Installed PHP Extensions

pdo_mysql, mysqli, gd, zip, mbstring, redis

---

🚀 Quick Start (Mac / Linux)

1. Start Environment (Avoid Docker Network Timeout)

Due to the unstable connection of Docker Hub, use local cached images for startup (recommended).

docker compose up -d --pull=never

2. Common Commands

# Start containers in background

docker compose up -d

# Stop and remove containers

docker compose down

# Enter PHP container terminal

docker compose exec php sh

# Restart single service

docker compose restart php

# View real-time logs

docker compose logs -f

---

✨ Environment Features

- Containerized Deployment: No local PHP, MySQL or Nginx installation required

- Hot Mount: Code changes take effect in real time

- Data Persistence: MySQL uses named volumes to prevent data loss

- Strong Isolation: Multiple PHP versions run independently without conflict

- Pure Configuration: Native Dockerfile for underlying learning

---

📚 Learning Plan

1. Docker basic commands, images, containers, mounts and networks

2. Custom PHP images by handwriting Dockerfile

3. Operating principle of Nginx + PHP-FPM

4. PHP annotation routing, native MVC and API development

5. MySQL basics, transactions and relational queries

---

⚠️ Notes

- This project is only for personal learning, not for production.

- .env files, database passwords and vendor dependencies are ignored and will never be uploaded.

- Developed on Intel macOS. Use --pull=never to skip network pulling when Docker network fails.

---

🖥 Development Environment

- Device: Intel macOS

- Tools: Docker Desktop, Terminal

- Proxy: Global VPN (Docker engine does not follow system proxy)

---

Keep learning and keep improving ✨
