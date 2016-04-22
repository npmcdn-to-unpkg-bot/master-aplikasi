<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class blog_posts extends Model
{
	protected $table = 'blog_posts';
    protected $primaryKey = 'id';
	
    public function attachments()
    {
        return $this->hasMany('App\Models\Blog\blog_attachments','post_id');
    }
}
