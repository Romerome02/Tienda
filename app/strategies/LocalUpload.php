<?php
require_once 'UploadStrategy.php';

class LocalUpload implements UploadStrategy {
    private $uploadDir;

    public function __construct($uploadDir) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
    }

    public function upload($file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $tmpName = $file['tmp_name'];
            $name = basename($file['name']);
            $targetFile = $this->uploadDir . $name;

            if (move_uploaded_file($tmpName, $targetFile)) {
                return $targetFile;
            } else {
                throw new Exception('Failed to move uploaded file.');
            }
        } else {
            throw new Exception('File upload error: ' . $file['error']);
        }
    }
}