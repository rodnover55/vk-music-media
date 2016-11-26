<?php

namespace VkMusic\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Model extends BaseModel
{
    protected $guarded = [];

    public function generateToken($message) {
        return hash_hmac('sha256', $message, uniqid());
    }
}