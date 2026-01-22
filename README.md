# UniCook 🍳

A community-driven recipe sharing platform designed for students who want to cook delicious meals without breaking the bank or spending hours in the kitchen.

## About

UniCook is a web application that empowers students to share, discover, and save quick and easy recipes perfect for busy academic schedules and tight budgets. Whether you're looking for a 15-minute dinner between study sessions or a cheap meal that feeds you for days, UniCook has you covered.

## Features

- **Recipe Sharing**: Post your favorite quick recipes with ingredients, steps, and photos
- **Student-Friendly**: Filter recipes by preparation time, budget, and difficulty level
- **Community Ratings**: Rate and review recipes from fellow students
- **Save Favorites**: Bookmark recipes to build your personal cookbook
- **Search & Filter**: Find exactly what you need based on ingredients, dietary restrictions, or cooking time

## Technologies Used

- **Frontend**: HTML5, CSS3
- **Backend**: PHP
- **Deployment**: Docker container

## Getting Started

### Prerequisites

- Docker
- Docker Compose (optional but recommended)
- A modern web browser

## Project Structure

```
unicook/
├── www/
│   ├── home/
│   │   ├── index.php
│   │   ├── main.js
│   │   └── main.css
│   ├── recipes/
│   │   ├── index.php
│   │   ├── main.js
│   │   └── main.css
│   ├── profile/
│   │   ├── index.php
│   │   ├── main.js
│   │   └── main.css
│   ├── api/
│   │   ├── recipe.php
│   │   ├── user.php
│   │   └── review.php
│   ├── lib/
│   │   ├── database.php
│   │   ├── userConnection.php
│   │   └── uuid.php
│   └── assets/
│       ├── img1.png
│       └── icon.svg
└── README.md
```

Each page has its own dedicated folder inside `www/` containing:
- `index.php` - Page logic and HTML structure
- `main.js` - Page-specific JavaScript
- `main.css` - Page-specific styles
