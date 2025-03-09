<?php

class UploadContext {
    private $strategy;

    public function __construct(UploadStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(UploadStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function upload($file) {
        return $this->strategy->upload($file);
    }
}