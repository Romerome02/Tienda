<?php
// This file defines the UploadStrategy interface for uploading products.

interface UploadStrategy {
    public function upload($file);
    public function delete($filePath);
}
