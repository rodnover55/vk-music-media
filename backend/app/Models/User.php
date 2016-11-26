<?php

namespace VkMusic\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Model
{
    protected $primaryKey = 'uid';
}
