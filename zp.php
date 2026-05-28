<?php
// Define the directory to compress and the output ZIP file name
$dirToZip = '../../'; 
$zipFileName = 'downloaded_archive.zip';

// 1. Validate that the directory exists
if (!is_dir($dirToZip)) {
    die("Error: The target directory does not exist.");
}

// 2. Clear any previous output buffer to avoid corrupted files
if (ob_get_level()) {
    ob_end_clean();
}

// 3. Initialize the ZipArchive object
$zip = new ZipArchive();
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Error: Cannot create or overwrite the ZIP file.");
}

// 4. Recursively iterate through the target folder
$rootPath = realpath($dirToZip);
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    // Skip directories (they are added automatically when files inside them are included)
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        
        // Calculate the relative path to maintain the folder structure inside the ZIP
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add the file to the archive using forward slashes for cross-platform compatibility
        $zip->addFile($filePath, str_replace('\\', '/', $relativePath));
    }
}

// Close and finalize the ZIP creation
$zip->close();

// 5. Send HTTP headers to force an automatic file download
if (file_exists($zipFileName)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($zipFileName));
    
    // Clear system output buffer and read the file to the browser
    flush();
    readfile($zipFileName);
    
    // 6. Optional: Delete the ZIP file from the server after download completes
    unlink($zipFileName);
    exit;
} else {
    die("Error: The ZIP archive could not be generated.");
}
?>

