<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;

class PostsSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada user untuk posts
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@blog.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Buat kategori jika belum ada
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Programming', 'slug' => 'programming'],
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Tutorials', 'slug' => 'tutorials'],
            ['name' => 'Tips & Tricks', 'slug' => 'tips-tricks'],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']], 
                $categoryData
            );
        }

        // Buat tags jika belum ada
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'MySQL', 'slug' => 'mysql'],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap'],
            ['name' => 'API', 'slug' => 'api'],
            ['name' => 'Backend', 'slug' => 'backend'],
            ['name' => 'Frontend', 'slug' => 'frontend'],
            ['name' => 'Database', 'slug' => 'database'],
            ['name' => 'Security', 'slug' => 'security'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(
                ['slug' => $tagData['slug']], 
                $tagData
            );
        }

        // Data untuk 5 posts
        $posts = [
            [
                'title' => 'Getting Started with Laravel 11: A Complete Guide',
                'excerpt' => 'Learn the fundamentals of Laravel 11 framework and build your first web application with this comprehensive beginner guide.',
                'body' => $this->getLaravelPostContent(),
                'category' => 'programming',
                'tags' => ['laravel', 'php', 'backend'],
            ],
            [
                'title' => 'Modern JavaScript ES6+ Features Every Developer Should Know',
                'excerpt' => 'Explore the latest JavaScript features including arrow functions, destructuring, promises, and async/await to write cleaner code.',
                'body' => $this->getJavaScriptPostContent(),
                'category' => 'web-development',
                'tags' => ['javascript', 'frontend', 'api'],
            ],
            [
                'title' => 'Building Secure Web Applications: Best Practices',
                'excerpt' => 'Discover essential security practices for web development including authentication, data validation, and protection against common vulnerabilities.',
                'body' => $this->getSecurityPostContent(),
                'category' => 'tutorials',
                'tags' => ['security', 'php', 'database'],
            ],
            [
                'title' => 'Database Optimization Techniques for High Performance',
                'excerpt' => 'Learn advanced MySQL optimization techniques including indexing, query optimization, and database design patterns for better performance.',
                'body' => $this->getDatabasePostContent(),
                'category' => 'technology',
                'tags' => ['mysql', 'database', 'backend'],
            ],
            [
                'title' => 'Responsive Web Design with Bootstrap 5',
                'excerpt' => 'Master responsive web design using Bootstrap 5 framework with practical examples and mobile-first development approach.',
                'body' => $this->getBootstrapPostContent(),
                'category' => 'tips-tricks',
                'tags' => ['bootstrap', 'frontend', 'javascript'],
            ],
        ];

        foreach ($posts as $postData) {
            $category = Category::where('slug', $postData['category'])->first();
            $slug = Str::slug($postData['title']);
            
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => $slug,
                'excerpt' => $postData['excerpt'],
                'body' => $postData['body'],
                'user_id' => $user->id,
                'category_id' => $category->id,
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'meta' => [
                    'meta_description' => $postData['excerpt'],
                    'meta_keywords' => implode(', ', $postData['tags']),
                ]
            ]);

            // Attach tags
            $tagIds = Tag::whereIn('slug', $postData['tags'])->pluck('id');
            $post->tags()->attach($tagIds);

            // Buat 5 komentar untuk setiap post
            $this->createCommentsForPost($post);
        }
    }

    private function createCommentsForPost($post)
    {
        $commenters = [
            ['name' => 'Sarah Johnson', 'email' => 'sarah@example.com'],
            ['name' => 'Michael Chen', 'email' => 'michael@example.com'],
            ['name' => 'Emma Rodriguez', 'email' => 'emma@example.com'],
            ['name' => 'David Kim', 'email' => 'david@example.com'],
            ['name' => 'Lisa Thompson', 'email' => 'lisa@example.com'],
        ];

        $comments = [
            'Great article! This really helped me understand the concept better.',
            'Thanks for sharing this detailed explanation. Very useful!',
            'I had been struggling with this topic, but your tutorial made it clear.',
            'Excellent guide! Looking forward to more content like this.',
            'Well written and easy to follow. Keep up the good work!',
        ];

        foreach ($commenters as $index => $commenter) {
            Comment::create([
                'post_id' => $post->id,
                'author_name' => $commenter['name'],
                'author_email' => $commenter['email'],
                'body' => $comments[$index] ?? 'Thank you for this informative post!',
                'is_approved' => true,
                'created_at' => $post->published_at->addHours(rand(1, 48)),
            ]);
        }
    }

    private function getLaravelPostContent()
    {
        return "
# Introduction to Laravel 11

Laravel is a powerful PHP framework that makes web development enjoyable and creative. In this comprehensive guide, we'll explore the key features and benefits of Laravel 11.

## What's New in Laravel 11

Laravel 11 introduces several exciting features:

- **Improved Performance**: Faster response times and optimized memory usage
- **Enhanced Security**: Better protection against common vulnerabilities
- **New Artisan Commands**: More powerful CLI tools for development
- **Updated Dependencies**: Latest versions of underlying libraries

## Getting Started

To create a new Laravel project, use Composer:

```bash
composer create-project laravel/laravel my-blog
cd my-blog
php artisan serve
```

## Key Features

### 1. Eloquent ORM
Laravel's Eloquent ORM provides an elegant way to interact with your database.

### 2. Blade Templating
Create beautiful, maintainable views with Laravel's Blade template engine.

### 3. Artisan CLI
Powerful command-line interface for common development tasks.

### 4. Middleware
Filter HTTP requests entering your application.

## Conclusion

Laravel 11 continues to be an excellent choice for modern web development, offering developer-friendly features and robust performance.
        ";
    }

    private function getJavaScriptPostContent()
    {
        return "
# Modern JavaScript ES6+ Features

JavaScript has evolved significantly with ES6 and later versions. Let's explore the most important features every developer should master.

## Arrow Functions

Arrow functions provide a more concise syntax for writing functions:

```javascript
// Traditional function
function add(a, b) {
    return a + b;
}

// Arrow function
const add = (a, b) => a + b;
```

## Destructuring Assignment

Extract values from arrays and objects easily:

```javascript
// Array destructuring
const [first, second] = ['apple', 'banana'];

// Object destructuring
const {name, age} = {name: 'John', age: 30};
```

## Template Literals

Create strings with embedded expressions:

```javascript
const name = 'World';
const message = \`Hello, \${name}!\`;
```

## Promises and Async/Await

Handle asynchronous operations elegantly:

```javascript
// Using async/await
async function fetchData() {
    try {
        const response = await fetch('/api/data');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}
```

## Modules

Organize your code with ES6 modules:

```javascript
// Export
export const utils = {
    format: (text) => text.toUpperCase()
};

// Import
import { utils } from './utils.js';
```

These features make JavaScript more powerful and enjoyable to work with.
        ";
    }

    private function getSecurityPostContent()
    {
        return '
# Building Secure Web Applications

Security should be a top priority in web development. Here are essential practices to protect your applications and users.

## Authentication & Authorization

### Strong Password Policies
- Minimum 8 characters with mixed case, numbers, and symbols
- Implement password hashing (never store plain text)
- Use secure session management

### Two-Factor Authentication
Add an extra layer of security with 2FA implementation.

## Input Validation & Sanitization

### Server-Side Validation
Never trust user input. Always validate on the server:

```php
// Laravel validation example
$request->validate([
    \'email\' => \'required|email|max:255\',
    \'password\' => \'required|min:8|confirmed\',
]);
```

### SQL Injection Prevention
Use parameterized queries and ORM:

```php
// Safe with Eloquent
User::where(\'email\', $email)->first();

// Avoid raw queries with user input
```

## HTTPS Everywhere

Always use HTTPS to encrypt data in transit:
- SSL/TLS certificates
- HSTS headers
- Secure cookie flags

## Cross-Site Scripting (XSS) Prevention

### Output Encoding
Escape user content when displaying:

```php
// In Blade templates
{{ $userContent }} // Automatically escaped
{!! $trustedContent !!} // Only for trusted content
```

### Content Security Policy
Implement CSP headers to prevent XSS attacks.

## CSRF Protection

Laravel provides built-in CSRF protection:

```php
// In forms
@csrf

// In AJAX requests
$.ajaxSetup({
    headers: {
        \'X-CSRF-TOKEN\': $(\'meta[name="csrf-token"]\').attr(\'content\')
    }
});
```

## Regular Security Updates

- Keep frameworks and dependencies updated
- Monitor security advisories
- Implement automated security scanning

Remember: Security is an ongoing process, not a one-time implementation.
        ';
    }

    private function getDatabasePostContent()
    {
        return '
# Database Optimization Techniques

Optimizing your database is crucial for application performance. Let\'s explore proven techniques to make your MySQL database lightning fast.

## Indexing Strategies

### Primary and Unique Indexes
Every table should have a primary key, and unique constraints automatically create indexes.

### Composite Indexes
Create indexes on multiple columns for complex queries:

```sql
CREATE INDEX idx_user_status_date ON posts (user_id, status, created_at);
```

### Covering Indexes
Include all columns needed for a query in the index.

## Query Optimization

### Use EXPLAIN
Analyze your query execution plans:

```sql
EXPLAIN SELECT * FROM posts WHERE category_id = 1 AND status = \'published\';
```

### Avoid SELECT *
Only select the columns you need:

```sql
-- Good
SELECT title, excerpt, created_at FROM posts;

-- Avoid
SELECT * FROM posts;
```

### Limit Results
Use LIMIT to prevent returning too many rows:

```sql
SELECT * FROM posts ORDER BY created_at DESC LIMIT 10;
```

## Database Design Best Practices

### Normalization
- Eliminate data redundancy
- Ensure data integrity
- Use appropriate normal forms

### Data Types
Choose the right data type for each column:
- Use INT instead of VARCHAR for numbers
- Use ENUM for limited options
- Consider TIMESTAMP vs DATETIME

## Connection Optimization

### Connection Pooling
Reuse database connections to reduce overhead.

### Laravel Query Optimization

```php
// Eager loading to prevent N+1 queries
$posts = Post::with([\'category\', \'tags\', \'user\'])->get();

// Use select to limit columns
$posts = Post::select(\'id\', \'title\', \'excerpt\')->get();

// Chunk large datasets
Post::chunk(100, function ($posts) {
    foreach ($posts as $post) {
        // Process each post
    }
});
```

## Monitoring and Maintenance

### Slow Query Log
Enable and monitor slow queries:

```sql
SET GLOBAL slow_query_log = \'ON\';
SET GLOBAL long_query_time = 2;
```

### Regular Maintenance
- Update table statistics
- Rebuild indexes periodically
- Monitor disk usage

## Caching Strategies

### Query Result Caching
Cache expensive query results:

```php
$posts = Cache::remember(\'popular_posts\', 3600, function () {
    return Post::where(\'views\', \'>\', 1000)->get();
});
```

### Database-Level Caching
Configure MySQL query cache and buffer pools.

Proper database optimization can improve your application performance by 10x or more!
        ';
    }

    private function getBootstrapPostContent()
    {
        return '
# Responsive Web Design with Bootstrap 5

Bootstrap 5 is the latest version of the world\'s most popular CSS framework. Learn how to create beautiful, responsive websites efficiently.

## What\'s New in Bootstrap 5

- **Vanilla JavaScript**: No more jQuery dependency
- **CSS Custom Properties**: Better theming capabilities
- **Improved Grid System**: More flexible layouts
- **New Components**: Offcanvas, floating labels, and more

## Getting Started

### CDN Installation
```html
<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

### NPM Installation
```bash
npm install bootstrap@5.3.0
```

## Grid System

Bootstrap\'s 12-column grid system is the foundation of responsive design:

```html
<div class="container">
    <div class="row">
        <div class="col-md-8">Main content</div>
        <div class="col-md-4">Sidebar</div>
    </div>
</div>
```

### Breakpoints
- `xs`: <576px (default)
- `sm`: ≥576px
- `md`: ≥768px
- `lg`: ≥992px
- `xl`: ≥1200px
- `xxl`: ≥1400px

## Components

### Navigation
Create responsive navigation bars:

```html
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">My Blog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
```

### Cards
Perfect for displaying blog posts:

```html
<div class="card">
    <img src="image.jpg" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">Post Title</h5>
        <p class="card-text">Post excerpt...</p>
        <a href="#" class="btn btn-primary">Read More</a>
    </div>
</div>
```

### Forms
Create beautiful, accessible forms:

```html
<form>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
```

## Utility Classes

Bootstrap provides hundreds of utility classes:

```html
<!-- Spacing -->
<div class="mt-3 mb-4 px-2">Content</div>

<!-- Colors -->
<p class="text-primary bg-light">Colored text</p>

<!-- Display -->
<div class="d-none d-md-block">Hidden on mobile</div>

<!-- Flexbox -->
<div class="d-flex justify-content-between align-items-center">
    <span>Left</span>
    <span>Right</span>
</div>
```

## Customization

### CSS Variables
Bootstrap 5 uses CSS custom properties for easy theming:

```css
:root {
    --bs-primary: #007bff;
    --bs-secondary: #6c757d;
}
```

### Sass Variables
For advanced customization, use Sass:

```scss
\$primary: #007bff;
\$border-radius: 0.5rem;

@import "bootstrap";
```

## Best Practices

1. **Mobile-First**: Design for mobile, then enhance for larger screens
2. **Semantic HTML**: Use proper HTML5 elements
3. **Accessibility**: Include ARIA labels and proper focus management
4. **Performance**: Only include the components you need
5. **Testing**: Test on multiple devices and browsers

Bootstrap 5 makes responsive web design faster and more enjoyable than ever before!
        ';
    }
}
