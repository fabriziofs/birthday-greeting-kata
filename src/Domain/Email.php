<?php

declare(strict_types=1);

namespace Domain;

final readonly class Email
{
    public function __construct(
        public string $sender,
        public string $subject,
        public string $body,
        public string $recipient
    )
    {
    }
}
