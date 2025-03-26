<?php

echo "<h1>PHP File Write Test</h1>";

$testDir = '/var/www/html/writable_test';
$testFile = $testDir . '/test_file_' . time() . '.txt';
$content = "Hello from PHP! This file was created at: " . date('Y-m-d H:i:s');
$success = false;
$message = '';
$currentUser = get_current_user(); // Get the user PHP is running as
$currentGroup = getmygid(); // Get the group ID PHP is running as

echo "<p>Attempting to write to: <code>" . htmlspecialchars($testFile) . "</code></p>";
echo "<p>PHP script running as user: <code>" . htmlspecialchars($currentUser) . "</code> (UID: " . getmyuid() . ")</p>";
echo "<p>PHP script running as group ID: <code>" . htmlspecialchars($currentGroup) . "</code></p>";


// Check if directory exists and is writable *by the PHP process*
if (!is_dir($testDir)) {
    $message = "ERROR: Directory <code>" . htmlspecialchars($testDir) . "</code> does not exist.";
} elseif (!is_writable($testDir)) {
    // is_writable() checks based on the user running the script
    $message = "ERROR: Directory <code>" . htmlspecialchars($testDir) . "</code> is NOT writable by the web server process (User: " . $currentUser . "). Check permissions.";
    // You might want to add more detailed permission checks here if needed
    // e.g., using posix_getpwuid(fileowner($testDir)) if posix extension is enabled
} else {
    // Attempt to write the file
    if (file_put_contents($testFile, $content) !== false) {
        $success = true;
        $message = "SUCCESS: File <code>" . htmlspecialchars($testFile) . "</code> created successfully!";
        // Optional: Set permissions on the created file if needed
        // chmod($testFile, 0664);
    } else {
        $error = error_get_last();
        $message = "ERROR: Failed to write file <code>" . htmlspecialchars($testFile) . "</code>.";
        if ($error) {
            $message .= " System Error: " . htmlspecialchars($error['message']);
        }
    }
}

// Display result
echo "<hr>";
echo "<h2>Test Result:</h2>";
echo "<p style='color:" . ($success ? "green" : "red") . "; font-weight: bold;'>" . $message . "</p>";
echo "<hr>";

// Display PHP info for debugging
echo "<h2>PHP Info:</h2>";
phpinfo();

?>