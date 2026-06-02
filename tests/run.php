<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';
require __DIR__ . '/TestCase.php';

$testFiles = [
    __DIR__ . '/Unit/UserModelTest.php',
    __DIR__ . '/Unit/AuthServiceTest.php',
    __DIR__ . '/Feature/AuthFlowTest.php',
];

$allResults = [];

foreach ($testFiles as $file) {
    require $file;
}

$testClasses = array_filter(
    get_declared_classes(),
    static fn (string $class): bool => is_subclass_of($class, TestCase::class)
);

foreach ($testClasses as $testClass) {
    /** @var TestCase $test */
    $test = new $testClass();
    $allResults = array_merge($allResults, $test->run());
}

$failures = array_values(array_filter($allResults, static fn (array $result): bool => ! $result['passed']));

foreach ($allResults as $result) {
    echo ($result['passed'] ? '[PASS] ' : '[FAIL] ') . $result['name'];

    if (! $result['passed']) {
        echo ' - ' . $result['message'];
    }

    echo PHP_EOL;
}

echo PHP_EOL . sprintf(
    'Ran %d tests: %d passed, %d failed.',
    count($allResults),
    count($allResults) - count($failures),
    count($failures)
) . PHP_EOL;

exit(count($failures) === 0 ? 0 : 1);
