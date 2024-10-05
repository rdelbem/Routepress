Routepress
==========

[![Build Status](https://github.com/yourusername/routepress/actions/workflows/ci.yml/badge.svg)](https://github.com/yourusername/routepress/actions)

A PHP library to simplify the creation and management of WordPress REST API routes.

Table of Contents
-----------------

-   [Introduction](#introduction)
-   [Features](#features)
-   [Installation](#installation)
-   [Requirements](#requirements)
-   [Usage](#usage)
    -   [Implementing the AuthInterface](#implementing-the-authinterface)
    -   [Initializing Routepress](#initializing-routepress)
    -   [Creating Routes](#creating-routes)
-   [Examples](#examples)
    -   [Simple GET Route](#simple-get-route)
    -   [POST Route with Authentication](#post-route-with-authentication)
    -   [Multiple HTTP Verbs](#multiple-http-verbs)
-   [Testing](#testing)
-   [Contributing](#contributing)
-   [License](#license)

Introduction
------------

Routepress is a PHP library designed to streamline the process of registering and managing REST API routes in WordPress plugins. It provides a fluent interface for defining routes, handling callbacks, and integrating custom authentication mechanisms.

Features
--------

-   **Simplified Route Registration**: Easily create REST API routes with minimal code.
-   **HTTP Verb Support**: Handle various HTTP methods like GET, POST, PUT, DELETE, and more.
-   **Custom Authentication**: Integrate your own authentication logic through the `AuthInterface`.
-   **Namespacing**: Organize your routes under a specific namespace.
-   **Callback Handling**: Define callbacks for route responses effortlessly.

Installation
------------

Install Routepress via Composer:

bash

`composer require rdelbem/routepress`

Requirements
------------

-   PHP 8.1 or higher
-   WordPress 5.6 or higher
-   Composer

Usage
-----

### Implementing the AuthInterface

Create a class that implements the `AuthInterface` to define your custom authentication logic:

```php
use Rdelbem\Routepress\AuthInterface;
use Rdelbem\Routepress\Types\AuthHeader;
use WP_User;

class MyAuth implements AuthInterface {
    public function validateRefreshToken(string $refreshToken): bool {
        // Your validation logic
    }

    public function createSession(WP_User $user): void {
        // Create a user session
    }

    public function generateJwtAtLogin(): void {
        // Generate JWT upon user login
    }

    public function generateAuthHeader(): AuthHeader {
        // Generate and return an AuthHeader object
    }

    public function removeJwt(): void {
        // Remove JWT token
    }
}
```

### Initializing Routepress

Instantiate the `Routepress` class with your authentication class and API namespace:

php


```php
use Rdelbem\Routepress\Routepress;

$auth = new MyAuth();
$routepress = new Routepress($auth, 'myplugin/v1');`
```

### Creating Routes

Use the `create` method to define a new route:

```php
$routepress->create(
    string|array $httpVerb,
    string $route,
    callable $callback,
    bool $authenticationRequired = false
);
```
-   **$httpVerb**: The HTTP method(s) (e.g., 'GET', 'POST', or an array of methods).
-   **$route**: The endpoint route (e.g., '/my-route').
-   **$callback**: The function to execute when the route is accessed.
-   **$authenticationRequired**: Whether authentication is required (default is `false`).

Examples
--------

### Simple GET Route

```php
$routepress->create('GET', '/hello', function () {
    return ['message' => 'Hello, World!'];
});
```

### POST Route with Authentication

```php
$routepress->create('POST', '/submit', function ($request) {
    $data = $request->get_params();
    // Process the data
    return ['status' => 'success'];
}, true);
```

### Multiple HTTP Verbs

```php
$routepress->create(['GET', 'POST'], '/data', function ($request) {
    if ($request->get_method() === 'GET') {
        return ['data' => 'Some data'];
    } else {
        // Handle POST request
    }
});
```

Testing
-------

Routepress includes a suite of tests to ensure reliability.

### Running Tests

Make sure you have Docker and Docker Compose installed. Then, run:

```bash
docker compose up -d --build
docker compose exec php vendor/bin/codecept run wpunit
```

### Static Analysis with Psalm

Run Psalm to perform static analysis:

```bash
docker compose exec php vendor/bin/psalm
```

Contributing
------------

Contributions are welcome! Please follow these steps:

1.  **Fork the Repository**: Click the "Fork" button at the top right of the repository page.

2.  **Clone Your Fork**:

    ```bash
    git clone https://github.com/yourusername/routepress.git
    ```

3.  **Create a New Branch**:

    ```bash
    git checkout -b feature/your-feature-name
    ```

4.  **Make Your Changes**: Implement your feature or bug fix.

5.  **Commit Your Changes**:

    ```bash
    git commit -am 'Add new feature'
    ```

6.  **Push to Your Branch**:

    ```bash
    git push origin feature/your-feature-name
    ```

7.  **Submit a Pull Request**: Go to the original repository and click "New Pull Request".

### Coding Standards

-   Follow PSR-12 coding standards.
-   Ensure all tests pass before submitting.
-   Write unit and/or integration tests for new features.

License
-------

Routepress is open-source software licensed under the [GNU General Public License v3.0 or later](https://spdx.org/licenses/GPL-3.0-or-later.html).

* * * * *

**Disclaimer**: This project is not affiliated with or endorsed by WordPress. "WordPress" is a registered trademark of the WordPress Foundation.