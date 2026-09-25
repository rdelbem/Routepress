<?php

declare(strict_types=1);

/*
 * Point git at the version-controlled .githooks directory.
 *
 * Run through `composer hooks` (also wired to post-install/post-update). The
 * script is a no-op outside a git repository so it never breaks a dist install.
 */

$root = dirname(__DIR__);
$git = $root . '/.git';

if (!is_dir($git) && !is_file($git)) {
    fwrite(STDOUT, "Not a git repository; skipping hook installation.\n");
    exit(0);
}

$output = [];
$exitCode = 1;

exec(sprintf('git -C %s config core.hooksPath .githooks 2>&1', escapeshellarg($root)), $output, $exitCode);

if ($exitCode !== 0) {
    fwrite(STDERR, "Failed to configure git hooks:\n" . implode("\n", $output) . "\n");
    exit($exitCode);
}

fwrite(STDOUT, "Git hooks installed (core.hooksPath = .githooks).\n");
