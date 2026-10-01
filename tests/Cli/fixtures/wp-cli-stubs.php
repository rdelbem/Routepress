<?php

declare(strict_types=1);

namespace WP_CLI {
    class Formatter
    {
        /** @var list<array<string, mixed>> */
        public static array $items = [];

        /** @var list<string> */
        public static array $fields = [];

        /**
         * @param array<string, string> $assocArgs
         * @param list<string> $fields
         */
        public function __construct(array $assocArgs, array $fields)
        {
            self::$fields = $fields;
        }

        /**
         * @param list<array<string, mixed>> $items
         */
        public function display_items(array $items): void
        {
            self::$items = $items;
        }
    }
}

namespace {
    class WP_CLI
    {
        /** @var list<string> */
        public static array $warnings = [];

        /** @var list<array{0: string|array<int, string>, 1: string}> */
        public static array $commands = [];

        public static function warning(string $message): void
        {
            self::$warnings[] = $message;
        }

        /**
         * @param string|array<int, string> $name
         */
        public static function add_command(string|array $name, string $class): void
        {
            self::$commands[] = [$name, $class];
        }
    }
}
