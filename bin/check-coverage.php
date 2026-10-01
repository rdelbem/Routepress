<?php

declare(strict_types=1);

/*
 * Fails when Clover coverage is below the configured minimums.
 *
 * Usage:
 *   php bin/check-coverage.php <clover.xml> [--lines=100] [--methods=100] [--classes=100]
 *
 * Intended to be run after `phpunit --coverage-clover`.
 */

$arguments = $argv;
array_shift($arguments);

$cloverPath = null;
$thresholds = ['lines' => 100.0, 'methods' => 100.0, 'classes' => 100.0];

foreach ($arguments as $argument) {
    if (preg_match('/^--(lines|methods|classes)=([0-9.]+)$/', $argument, $matches) === 1) {
        $thresholds[$matches[1]] = (float) $matches[2];
        continue;
    }

    if (!str_starts_with($argument, '--')) {
        $cloverPath = $argument;
    }
}

$cloverPath ??= 'build/coverage/clover.xml';

if (!is_file($cloverPath)) {
    fwrite(STDERR, sprintf("Coverage file not found: %s\n", $cloverPath));
    exit(1);
}

$xml = simplexml_load_file($cloverPath);

if ($xml === false || !isset($xml->project->metrics)) {
    fwrite(STDERR, sprintf("Could not read project metrics from %s\n", $cloverPath));
    exit(1);
}

$project = $xml->project->metrics;

$statements = (int) $project['statements'];
$coveredStatements = (int) $project['coveredstatements'];
$methods = (int) $project['methods'];
$coveredMethods = (int) $project['coveredmethods'];

$classElements = $xml->xpath('//class') ?: [];
$classes = 0;
$coveredClasses = 0;

foreach ($classElements as $class) {
    $classMethods = (int) $class->metrics['methods'];

    if ($classMethods === 0) {
        continue;
    }

    $classes++;

    if ((int) $class->metrics['coveredmethods'] === $classMethods) {
        $coveredClasses++;
    }
}

$linesPercent = $statements > 0 ? $coveredStatements / $statements * 100 : 100.0;
$methodsPercent = $methods > 0 ? $coveredMethods / $methods * 100 : 100.0;
$classesPercent = $classes > 0 ? $coveredClasses / $classes * 100 : 100.0;

$checks = [
    'Lines' => [
        $linesPercent,
        $thresholds['lines'],
        sprintf('%d/%d', $coveredStatements, $statements),
    ],
    'Methods' => [
        $methodsPercent,
        $thresholds['methods'],
        sprintf('%d/%d', $coveredMethods, $methods),
    ],
    'Classes' => [
        $classesPercent,
        $thresholds['classes'],
        sprintf('%d/%d', $coveredClasses, $classes),
    ],
];

$failed = false;

foreach ($checks as $label => [$actual, $minimum, $detail]) {
    $passed = $actual + 0.0001 >= $minimum;
    $failed = $failed || !$passed;

    printf(
        "%-8s %6.2f%% (min %5.2f%%)  %-4s  %s\n",
        $label,
        $actual,
        $minimum,
        $passed ? 'OK' : 'FAIL',
        $detail
    );
}

if ($failed) {
    fwrite(STDERR, "\nCoverage is below the required minimum.\n");
    exit(1);
}

echo "\nCoverage OK.\n";
