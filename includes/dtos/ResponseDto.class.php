<?php

class ResponseDto
{
    public bool $success;
    public ?string $error;
    public ?array $data;

    public function __construct(bool $success, ?string $error = null, ?array $data = null)
    {
        $this->success = $success;
        $this->error = $error;
        $this->data = $data;
    }
}
