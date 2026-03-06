<?php

namespace App\Enums;

enum DebateParticipantRole: string
{
    case Debater = 'debater';
    case Judge = 'judge';
    case Moderator = 'moderator';
}
