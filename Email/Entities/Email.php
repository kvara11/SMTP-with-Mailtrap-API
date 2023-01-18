<?php

namespace Modules\Email\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory;

    protected $table = 'emails';
    protected $fillable = ['username', 'subject', 'body', 'text', 'from', 'to', 'datetime'];
    public  $timestamps = false;
    
    // protected static function newFactory()
    // {
    //     return \Modules\Email\Database\factories\EmailFactory::new();
    // }
}
