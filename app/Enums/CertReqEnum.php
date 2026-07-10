<?php

namespace App\Enums;

enum CertReqEnum: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case READYTOPICKUP = 'ready_to_pick_up';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::READYTOPICKUP => 'ready_to_pick_up',
        };
    }

}
