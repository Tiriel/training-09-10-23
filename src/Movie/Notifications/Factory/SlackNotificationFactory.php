<?php

namespace App\Movie\Notifications\Factory;

use App\Movie\Notifications\Notification\SlackNotification;
use Symfony\Component\Notifier\Notification\Notification;

class SlackNotificationFactory implements NotificationFactoryInterface
{

    public function createNotification(string $subject): Notification
    {
        return new SlackNotification($subject);
    }

    public static function getIndex(): string
    {
        return 'slack';
    }
}
