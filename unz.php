
<?php
// Define the file name and targeted extraction folder
$zipFile = 'phpMyAdmin-5.2.3-all-languages.zip';
$extractTo = './';

// Initialize ZipArchive object
$zip = new ZipArchive;

// Attempt to open the archive
if ($zip->open($zipFile) === TRUE) {
    // Extract everything to your directory
    $zip->extractTo($extractTo);
    
    // Close the archive connection
    $zip->close();
    echo "Extraction successful!";
} else {
    echo "Failed to open the zip file.";
}
?>
