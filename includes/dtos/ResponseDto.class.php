<?php

class ResponseDto
{
    public bool $success;
    public ?string $error;
    public mixed $data;

    public function __construct(bool $success, ?string $error = null, mixed $data = null)
    {
        $this->success = $success;
        $this->error = $error;
        $this->data = $data;
    }
}
