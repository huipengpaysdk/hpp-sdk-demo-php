<?php

class ServiceException extends Exception {
    public function errorMessage() {
        return $this->getMessage();
    }
}