<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', 'Blog\Frontend\TimelineController@getIndex');
Route::get('/post/{id}', array('uses'=>'Blog\Frontend\TimelineController@getSingle'));
Route::get('/home', 'Auth\HomeController@home');
//========================================================================
Route::get('/errors/403', function()
{
	$user = Auth::user();
    return View::make('errors.403')->with('user',$user);
});
//========================================================================
Route::post('/mail/webhook', 'Mail\WebhookController@webhook');
Route::get('/mail/compose', 'Mail\MailController@getCompose');
Route::post('/mail/compose', 'Mail\MailController@postCompose');
Route::get('/mail/setting','Mail\SettingController@getSetting');
Route::post('/mail/setting','Mail\SettingController@postSetting');
Route::get('/mail/compose/{id}', array('as'=>'id','uses'=>'Mail\MailController@getCompose'));
Route::get('/mail/delete/{id}', array('as'=>'id','uses'=>'Mail\MailController@getDeleteData'));
Route::get('/mail/download/attachment/{id}',array('as'=>'id','uses'=>'Mail\MailController@getDownload'));
Route::post('/mail/attach/add', 'Mail\MailController@postAttachAdd');
Route::post('/mail/attach/delete', 'Mail\MailController@postAttachDelete');
Route::get('/mail/{type}', ['uses' =>'Mail\MailController@getIndex']);
Route::get('/mail/{type}/data', ['uses' =>'Mail\MailController@getData']);
Route::get('/mail/{type}/detail/{id}',array('uses'=>'Mail\MailController@getInboxDetail'));
//========================================================================
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/auth/logout', 'Auth\AuthController@getLogout');
Route::get('/auth/register', 'Auth\AuthController@getRegister');
Route::post('/auth/register', 'Auth\AuthController@postRegister');
Route::get('/auth/register/verify/{email}/{confirmation_code}', array('uses'=>'Auth\AuthController@getVerify'));
Route::get('/auth/password/email', 'Auth\PasswordController@getEmail');
Route::post('/auth/password/email', 'Auth\PasswordController@postEmail');
Route::get('/auth/password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('/auth/password/reset', 'Auth\PasswordController@postReset');
Route::get('/auth/setting', 'Auth\SettingController@getSetting');
Route::post('/auth/setting', 'Auth\SettingController@postSetting');
//========================================================================
Route::get('/message/contact/edit/{id}', array('as'=>'id','uses'=>'Message\ContactController@getEditData'));
Route::post('/message/contact/edit', 'Message\ContactController@postEditData');
Route::get('/message/contact/delete/{id}', array('as'=>'id','uses'=>'Message\ContactController@getDeleteData'));
Route::get('/message/contact/add', 'Message\ContactController@getAddData');
Route::post('/message/contact/add', 'Message\ContactController@postAddData');
Route::get('/message/contact/import', 'Message\ContactController@getImportData');
Route::post('/message/contact/import', 'Message\ContactController@postImportData');
Route::get('/message/contact/empty', 'Message\ContactController@getEmptyContact');
Route::get('/message/inbox/list', 'Message\SMSController@getListInbox');
Route::get('/message/inbox/import', 'Message\SMSController@getImportData');
Route::post('/message/inbox/import', 'Message\SMSController@postImportData');
Route::get('/message/inbox/deleteMessage/{id}',array('as'=>'address','uses'=>'Message\SMSController@getDelMessage'));
Route::get('/message/inbox/detail/{id}',array('as'=>'id','uses'=>'Message\SMSController@getInboxDetail'));
Route::get('/message/inbox/delete/{id}', array('as'=>'id','uses'=>'Message\SMSController@getDeleteData'));
Route::post('/message/webhook/{service}',array('as'=>'service','uses'=>'Message\WebhookController@webhook'));
Route::get('/message/webhook/{service}',array('as'=>'service','uses'=>'Message\WebhookController@webhook'));
Route::get('/message/account', 'Message\AccountController@getData');
Route::get('/message/account/list', 'Message\AccountController@getListData');
Route::get('/message/account/edit/{id}', array('as'=>'id','uses'=>'Message\AccountController@getEditData'));
Route::post('/message/account/edit', 'Message\AccountController@postEditData');
Route::get('/message/account/add', 'Message\AccountController@getAddData');
Route::post('/message/account/add', 'Message\AccountController@postAddData');
Route::get('/message/account/delete/{id}', array('as'=>'id','uses'=>'Message\AccountController@getDeleteData'));
//========================================================================
Route::get('/message/send','Message\SMSController@getSend');
Route::get('/message/send/search','Message\SMSController@getSearch');
Route::post('/message/send','Message\SMSController@postSend');
//========================================================================
Route::get('/message/setting','Message\SettingController@getSetting');
Route::post('/message/setting','Message\SettingController@postSetting');
//========================================================================
Route::get('/blog/path', 'Blog\Backend\PathController@getIndex');
Route::get('/blog/setting', 'Blog\Backend\SettingController@getSetting');
Route::post('/blog/setting', 'Blog\Backend\SettingController@postSetting');
Route::get('/blog/path/auth', 'Blog\Backend\PathController@getAuth');
Route::get('/blog/post/edit/{id}', array('as'=>'id','uses'=>'Blog\Backend\PostController@getEditPost'));
Route::post('/blog/post/edit','Blog\Backend\PostController@postEditPost');
Route::get('/blog/post/add','Blog\Backend\PostController@getAddPost');
Route::post('/blog/post/add','Blog\Backend\PostController@postAddPost');
Route::get('/blog/post/delete/{id}', array('as'=>'id','uses'=>'Blog\Backend\PostController@getDeleteData'));
Route::post('/blog/image/add', 'Blog\Backend\PostController@postImageAdd');
Route::post('/blog/image/delete', 'Blog\Backend\PostController@postImageDelete');
//========================================================================
Route::controller('/message/inbox', 'Message\SMSController', [
    'getData'  => 'datatables.data',
    'getIndex' => 'datatables',
]);
Route::controller('/message/contact', 'Message\ContactController', [
    'getData'  => 'datatables.data',
    'getIndex' => 'datatables',
]);
//========================================================================
Route::controller('/blog/post', 'Blog\Backend\PostController', [
    'getData'  => 'datatables.data',
    'getIndex' => 'datatables',
]);
//========================================================================
