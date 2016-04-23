<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class blog_attachments extends Model
{
	protected $table = 'blog_attachments';
    protected $primaryKey = 'id';
	
    public function post()
    {
        return $this->belongsTo('App\Models\Blog\blog_posts','id');
    }
}
