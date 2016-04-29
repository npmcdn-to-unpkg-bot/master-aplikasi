<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'message/webhook/Nexmo',
		'message/webhook/Twilio',
		'message/webhook/Telerivet',
		'message/inbox/import',
        'message/contact/import',
		'blog/image/add',
		'blog/image/delete',
		'mail/attach/add',
		'mail/attach/delete',
		'mail/webhook'
    ];
}
