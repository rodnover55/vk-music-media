<?php

namespace VkMusic\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property mixed uid
 */
class User extends Authenticatable
{
    protected $primaryKey = 'uid';
    protected $guarded = [];
}
