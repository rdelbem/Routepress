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
        /** @var array<string, string> */
        private array $headers;

        /** @var array<string, mixed> */
        private array $params;

        /**
         * @param array<string, string> $headers
         * @param array<string, mixed> $params
         */
        public function __construct(
            private string $method = 'GET',
            private string $route = '',
            array $headers = [],
            array $params = [],
        ) {
            $this->headers = $headers;
            $this->params = $params;
        }

        public function get_method(): string
        {
            return $this->method;
        }

        public function get_route(): string
        {
            return $this->route;
        }

        public function get_header(string $name): ?string
        {
            foreach ($this->headers as $header => $value) {
                if (strcasecmp($header, $name) === 0) {
                    return $value;
                }
            }

            return null;
        }

        public function get_param(string $name): mixed
        {
            return $this->params[$name] ?? null;
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

        public function get_error_message(): string
        {
            return $this->message;
        }

        public function get_error_data(): mixed
        {
            return $this->data;
        }
    }
}
