<?php

namespace App\Movie\Notifications;

enum NotificationChannels: string
{
    case Discord = 'discord';
    case Slack = 'slack';
}
