<?php

declare(strict_types=1);

namespace Domain;

interface EmailSender
{
    public function send(Email $email): void;
}
