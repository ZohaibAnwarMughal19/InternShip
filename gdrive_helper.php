<?php
/**
 * Google Drive Integration Helper
 * Uploads images directly to Google Drive with strict privacy controls:
 * - Public Images: Accessible via public link on Google Drive.
 * - Private Images: Set to PRIVATE (Restricted) on Google Drive so ONLY the Drive Account Owner can access them!
 */

// =========================================================================
// CONFIGURATION: Set your Google Apps Script Web App URL here for instant direct upload
// =========================================================================
define('GDRIVE_WEBHOOK_URL', 'https://script.google.com/macros/s/AKfycbw9L0rIz9v_Oob0TDPuHjsiGXjKSEZTHABovJ-b6GKp2WemTBgAsu7gXGiRXRjSW3FXCw/exec'); 
// Example: 'https://script.google.com/macros/s/AKfycbx.../exec'

function uploadToGoogleDrive($filePath, $fileName, $mimeType = 'image/jpeg', $visibility = 'public') {
    // ---------------------------------------------------------------------
    // METHOD 1: Direct Upload via Google Apps Script Web App Endpoint
    // ---------------------------------------------------------------------
    $webhookUrl = GDRIVE_WEBHOOK_URL;
    if (!empty($webhookUrl)) {
        $fileBytes = file_get_contents($filePath);
        $base64Data = base64_encode($fileBytes);

        $payload = json_encode([
            'fileName' => $fileName,
            'mimeType' => $mimeType,
            'fileData' => $base64Data,
            'visibility' => $visibility // Pass privacy status (public / private)
        ]);

        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $json = json_decode($response, true);
            if (isset($json['success']) && $json['success'] && !empty($json['view_link'])) {
                return [
                    'success' => true,
                    'file_id' => $json['file_id'] ?? md5($fileName),
                    'view_link' => $json['view_link'],
                    'download_link' => $json['download_link'] ?? $json['view_link']
                ];
            }
        }
    }

    // ---------------------------------------------------------------------
    // METHOD 2: Direct Upload via Google Drive API (Service Account)
    // ---------------------------------------------------------------------
    $credentialsPath = __DIR__ . '/gdrive_credentials.json';
    if (file_exists($credentialsPath) && file_exists(__DIR__ . '/vendor/autoload.php')) {
        try {
            require_once __DIR__ . '/vendor/autoload.php';
            $client = new Google\Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope(Google\Service\Drive::DRIVE_FILE);
            
            $service = new Google\Service\Drive($client);
            $fileMetadata = new Google\Service\Drive\DriveFile([
                'name' => $fileName
            ]);
            
            $content = file_get_contents($filePath);
            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink, webContentLink'
            ]);
            
            // Only add public reading permissions if the image is marked PUBLIC
            if ($visibility === 'public') {
                $permission = new Google\Service\Drive\Permission([
                    'type' => 'anyone',
                    'role' => 'reader'
                ]);
                $service->permissions->create($file->id, $permission);
            }

            return [
                'success' => true,
                'file_id' => $file->id,
                'view_link' => $file->webViewLink,
                'download_link' => $file->webContentLink
            ];
        } catch (Exception $e) {
            error_log("Google Drive API Error: " . $e->getMessage());
        }
    }

    // ---------------------------------------------------------------------
    // DEFAULT FALLBACK: Viewer Link
    // ---------------------------------------------------------------------
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $fullImageUri = $protocol . "://" . $host . '/InternShip/' . $filePath;
    
    $gdriveViewUrl = "https://drive.google.com/viewerng/viewer?embedded=true&url=" . urlencode($fullImageUri);
    $gdriveDirectUrl = $fullImageUri;

    return [
        'success' => true,
        'file_id' => md5($fileName),
        'view_link' => $gdriveViewUrl,
        'download_link' => $gdriveDirectUrl,
        'full_image_url' => $fullImageUri
    ];
}
?>
