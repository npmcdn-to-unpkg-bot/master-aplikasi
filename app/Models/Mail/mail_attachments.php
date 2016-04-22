<?php

namespace App\Models\Mail;

use Illuminate\Database\Eloquent\Model;

class mail_attachments extends Model
{
	protected $table = 'mail_attachments';
    protected $primaryKey = 'id';
	
    public function email()
    {
        return $this->belongsTo('App\Models\Mail\mail_emails','id');
    }
}
