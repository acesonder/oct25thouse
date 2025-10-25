<?php
/**
 * Unit Tests for User ID Generation
 * Run this file to test the generateUserID function
 */

// Include the functions file
require_once 'functions.php';

echo "Running User ID Generation Tests...\n";
echo "=====================================\n\n";

$testsPassed = 0;
$testsFailed = 0;

// Test Case 1
$testName = "Test 1: Michael Brown, 05/06/1984";
$result = generateUserID("Michael", "Brown", "1984-06-05");
$expected = "MICBRO060584";
if ($result === $expected) {
    echo "✓ PASS: $testName\n";
    echo "  Generated: $result\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName\n";
    echo "  Expected: $expected\n";
    echo "  Got: $result\n\n";
    $testsFailed++;
}

// Test Case 2
$testName = "Test 2: John Doe, 12/31/1990";
$result = generateUserID("John", "Doe", "1990-12-31");
$expected = "JOHDOE123190";
if ($result === $expected) {
    echo "✓ PASS: $testName\n";
    echo "  Generated: $result\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName\n";
    echo "  Expected: $expected\n";
    echo "  Got: $result\n\n";
    $testsFailed++;
}

// Test Case 3: Short names
$testName = "Test 3: Al Bo, 01/01/2000";
$result = generateUserID("Al", "Bo", "2000-01-01");
$expected = "ALBO010100";
if ($result === $expected) {
    echo "✓ PASS: $testName\n";
    echo "  Generated: $result\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName\n";
    echo "  Expected: $expected\n";
    echo "  Got: $result\n\n";
    $testsFailed++;
}

// Test Case 4: Long names
$testName = "Test 4: Christopher Washington, 07/04/1976";
$result = generateUserID("Christopher", "Washington", "1976-07-04");
$expected = "CHRWAS070476";
if ($result === $expected) {
    echo "✓ PASS: $testName\n";
    echo "  Generated: $result\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName\n";
    echo "  Expected: $expected\n";
    echo "  Got: $result\n\n";
    $testsFailed++;
}

// Test Case 5: Lowercase input
$testName = "Test 5: jane smith, 03/15/1985 (lowercase)";
$result = generateUserID("jane", "smith", "1985-03-15");
$expected = "JANSMI031585";
if ($result === $expected) {
    echo "✓ PASS: $testName\n";
    echo "  Generated: $result\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName\n";
    echo "  Expected: $expected\n";
    echo "  Got: $result\n\n";
    $testsFailed++;
}

// Password Validation Tests
echo "\nPassword Validation Tests\n";
echo "=========================\n\n";

// Test weak password
$testName = "Test 6: Weak password - 'test'";
$result = validatePassword("test");
if ($result['valid'] === false) {
    echo "✓ PASS: $testName\n";
    echo "  Message: {$result['message']}\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName (should be invalid)\n\n";
    $testsFailed++;
}

// Test password without uppercase
$testName = "Test 7: Password without uppercase - 'testpass123'";
$result = validatePassword("testpass123");
if ($result['valid'] === false && strpos($result['message'], 'uppercase') !== false) {
    echo "✓ PASS: $testName\n";
    echo "  Message: {$result['message']}\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName\n\n";
    $testsFailed++;
}

// Test strong password
$testName = "Test 8: Strong password - 'TestPass123'";
$result = validatePassword("TestPass123");
if ($result['valid'] === true) {
    echo "✓ PASS: $testName\n";
    echo "  Message: {$result['message']}\n\n";
    $testsPassed++;
} else {
    echo "✗ FAIL: $testName (should be valid)\n";
    echo "  Message: {$result['message']}\n\n";
    $testsFailed++;
}

// Summary
echo "\n=====================================\n";
echo "Test Results Summary\n";
echo "=====================================\n";
echo "Total Tests: " . ($testsPassed + $testsFailed) . "\n";
echo "Passed: $testsPassed\n";
echo "Failed: $testsFailed\n";

if ($testsFailed === 0) {
    echo "\n✓ All tests passed!\n";
    exit(0);
} else {
    echo "\n✗ Some tests failed!\n";
    exit(1);
}
?>
