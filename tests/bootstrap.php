<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

/*
 * Minimal WordPress stubs.
 *
 * The library only relies on WordPress at runtime, so the unit tests never load
 * WordPress. Brain Monkey stubs the WordPress *functions*; these declarations
 * exist so the class type-hints used by the public API can be resolved.
 */
if (!class_exists('WP_REST_Request')) {
    class WP_REST_Request
    {
        public function __construct(
            private string $method = 'GET',
            private string $route = '',
        ) {
        }

        public function get_method(): string
        {
            return $this->method;
        }

        public function get_route(): string
        {
            return $this->route;
        }
    }
}

if (!class_exists('WP_User')) {
    class WP_User
    {
        public function __construct(public int $ID = 0)
        {
        }
    }
}

if (!class_exists('WP_Error')) {
    class WP_Error
    {
        public function __construct(
            private string $code = '',
            private string $message = '',
            private mixed $data = null,
        ) {
        }

        public function get_error_code(): string
        {
            return $this->code;
        }
    }
}
