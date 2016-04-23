<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Model;

class mail_emails extends Model
{
	protected $table = 'mail_emails';
    protected $primaryKey = 'id';
	
	public function attachments()
    {
        return $this->hasMany('App\Models\Mail\mail_attachments','email_id');
    }
}
