<?php
use App\Models\DomainSettings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$userRoutes = function() {
    Route::group(['namespace'=>"User", 'middleware' => ["j1app"]], function(){
        
        /** static pages **/
        Route::get('/','PagesController@index')->name('home');
        Route::get('/privacy-notice','PagesController@privacy')->name('privacy-notice');
        Route::get('/terms-condition','PagesController@termsCondition')->name('terms-condition');
        Route::get('/testimonial', 'TestimonialController@index')->name('testimonial'); 
        /* Show FAQ */
        Route::get('/show-faq','PagesController@shoFaq')->name('show-faq');
        /** Authentication Routes **/
        Route::group(['namespace'=>"Auth"], function(){
            Route::get('/elogin/{token_id}', 'LoginController@directLogin')->name('elogin');
            Route::get('/login', 'LoginController@showLoginForm')->name('login');
            Route::post('/login', 'LoginController@login')->name('login');
            Route::post('/logout', 'LoginController@logout')->name('logout'); 
            Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('/register', 'RegisterController@register')->name('register');
            Route::get('/register/verify/{token}',  'RegisterController@verifyUserAccount'); 
            Route::get('/password/reset', 'ForgotPasswordController@showForgotPasswordForm')->name('password.request');
            Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');
            Route::get('/password/setpassword/{token}', 'ResetPasswordController@showResetForm')->name('password.setpassword');
            Route::post('/password/setpassword', 'ResetPasswordController@reset');
            Route::get('/social/redirect/{provider}/{type}', 'SocialController@getSocialRedirect')->name('social.redirect');
            Route::get('/social/handle/{provider}/{type}', 'SocialController@getSocialHandle')->name('social.handle');
            Route::get('resend-verification/{user_id}',  'RegisterController@resendVerificationCode')->name('resend-verification');
            Route::post('verify-captcha',  'RegisterController@verifyCaptcha')->name('verify-captcha');
            Route::get('/invitation/{invitation_token}', 'RegisterController@checkInvitation')->name('check.invitation');
            Route::post('/accept-invitation', 'RegisterController@acceptInvitation')->name('accept.invitation');
            Route::post('/resend-verification-captcha', 'LoginController@resendVerificationCaptcha')->name('resend.verification.captcha');
        });
        
        
        Route::group(['middleware' => ['auth']],function(){
            /** Update Profile Routes **/
            Route::get('/profile', 'ProfileController@viewProfile')->name('view.profile');
            Route::get('/edit-profile/{tab?}', 'ProfileController@editProfile')->name('edit.profile');
            Route::post('/update-profile', 'ProfileController@updateProfile')->name('update.profile');
            Route::post('/crop', 'PhotoController@crop')->name('crop');
            Route::get('/avatar', 'PhotoController@avatar')->name('avatar');

            /** Portfolio Routes **/
            Route::get('/myportfolio', 'PortfolioController@index')->name('myportfolio');
            Route::get('/portfolio-detail/{p_no}', 'PortfolioController@viewPortfolioDetail')->name('portfolio.detail');
            Route::get('/create-portfolio', 'PortfolioController@createPortfolio')->name('create.portfolio');
            
            /** Application Status **/
            Route::get('/application-status','ApplicationStatusController@index')->name('application-status');
            Route::post('/asprogress','ApplicationStatusController@applicationStatusProgress')->name('asprogress');
            Route::post('/eligibilityquest','ApplicationStatusController@eligibilityQuest')->name('eligibilityquest');
            Route::post('/navigatestage','ApplicationStatusController@navigateApplicationSteps')->name('navigatestage');
            Route::post('/uploadresume','ApplicationStatusController@uploadResume')->name('uploadresume');
            Route::post('/resumebuilder','ApplicationStatusController@updateResumeBuilder')->name('resumebuilder');
            Route::post('/loadrbform','ApplicationStatusController@loadRBForm')->name('loadrbform');
            Route::post('/resetresume','ApplicationStatusController@resetResume')->name('resetresume');
            Route::post('/previewresume','ApplicationStatusController@previewResume')->name('previewresume');
            Route::post('/buildresumepdf','ApplicationStatusController@generateResumePDF')->name('buildresumepdf');
            Route::post('/updateskype','ApplicationStatusController@updateSkype')->name('updateskype');
            Route::post('/requestfinance','ApplicationStatusController@requestFinance')->name('requestfinance');
            Route::post('/addinfo','ApplicationStatusController@updateAdditionalInfo')->name('addinfo');
            Route::post('/j1agree','ApplicationStatusController@j1Agreement')->name('j1agree');
            Route::post('/udi','ApplicationStatusController@uploadDocumentInstruction')->name('upload.document.instruction');
            Route::post('/dh','ApplicationStatusController@documentHistory')->name('document.history');
            Route::post('/uploaddocument','ApplicationStatusController@uploadSupportingDocument')->name('uploaddocument');
            Route::post('/docuploaded','ApplicationStatusController@supportingDocumentUploaded')->name('doc.uploaded');
            Route::post('/sponsorupdated','ApplicationStatusController@sponsorUpdated')->name('sponsorupdated');
            Route::post('/visa-stage','ApplicationStatusController@visaStage')->name('visa.stage');
            
            /* Agency Contract Controller */
            Route::post('/cra','ApplicationStatusController@agencyContractRequestAction')->name('contract.request.action');
            Route::post('/cr','ApplicationStatusController@agencyContractRequest')->name('contract.request');
            
           /** route create for Forum Module pages **/
            Route::get('/addtopic', 'ForumController@createTopic')->name('addtopic');
            Route::get('/mytopics/edit/{ft_id}','ForumController@editTopic')->name('edittopic.form');
            Route::post('/forumAjaxRequest', 'ForumController@ajaxRequest')->name('forumajaxrequest');
            Route::get('/favoritetopic', 'ForumController@userFavoriteTopic')->name('favoritetopic');
            Route::get('/following', 'ForumController@following')->name('following'); 
            Route::get('/mytopics', 'ForumController@myTopics')->name('mytopics');
            
            /** route create for Notification Module pages **/
            Route::get('/notifications','NotificationController@index')->name('notifications');
            Route::get('/viewnotification/{log_id}', 'NotificationController@viewNotification')->name('viewnotification');

        });
        
        /** route create for Forum Module pages **/
        Route::get('/categories', 'ForumController@categories')->name('categories');  
        Route::get('/category/{fc_slug}', 'ForumController@category')->name('category');
        Route::get('/subcategory/{fs_slug}', 'ForumController@subCategory')->name('subcategory');
        Route::get('/topicdetail/{ft_slug}', 'ForumController@topicDetail')->name('topicdetail'); 
        Route::get('/tag/{tagslug}', 'ForumController@topicByTagId');
        Route::get('/recentdiscussions', 'ForumController@recentDiscussions')->name('recentdiscussions'); 
    });
    
     /* Api Page Routes  */
    Route::get('/showapi','ShowApiController@showapi')->name('showapi');
    
    Route::group(['middleware' => ['auth']],function(){
        Route::get('/dl/{action}/{value?}','CommonController@downloadDocument')->name('download');
    });
};

$adminRoutes = function() {
    Route::group(['namespace'=>"Admin", 'middleware' => ['roles.auth','j1app']], function(){
        
        /** Authentication Routes **/
        Route::group(['namespace'=>"Auth"], function(){
            Route::get('/login', 'LoginController@showLoginForm')->name('login');
            Route::post('/login', 'LoginController@login');
            Route::post('/logout', 'LoginController@logout')->name('logout'); 
            Route::get('/register/verify/{token}',  'LoginController@verifyAdminAccount');
            Route::get('/password/reset', 'ForgotPasswordController@showForgotPasswordForm')->name('password.request');
            Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.update');
            Route::get('/password/setpassword/{token}', 'ResetPasswordController@showSetPasswordForm')->name('password.setpassword');
            Route::post('/password/setpassword', 'ResetPasswordController@reset');
        });
        
        Route::group(['middleware' => ['auth:admin']],function(){
        
            /** Profile Routes **/
            Route::get('/','DashboardController@index')->name('dashboard');
            Route::get('/edit-profile','ProfileController@editProfile')->name('edit.profile');
            Route::post('/update-profile', 'ProfileController@updateProfile')->name('update.profile');

            /** Settings Routes **/
            Route::get('/settings','SystemSettingsController@index')->name('system.settings');
            Route::post('/settings','SystemSettingsController@update')->name('system.settings.edit');

            /* Access Control Routes*/
            Route::get('/roles','AccessControlController@index')->name('role.list');
            Route::get('/roles/add','AccessControlController@create')->name('role.add.form');
            Route::post('/roles/add','AccessControlController@store')->name('role.add');
            Route::get('/roles/edit/{role_name}','AccessControlController@edit')->name('role.edit.form');
            Route::post('/roles/edit/{role_name}','AccessControlController@update')->name('role.edit');
            Route::get('/roles/delete/{role_name}','AccessControlController@destroy')->name('role.delete');
            Route::get('/rolepermission/{role_name?}','PermissionController@index')->name('role.permissions');
            Route::post('/rolepermission','PermissionController@updateRolePermission')->name('role.update.role.permissions');

            /* Permission Group Routes */
            Route::get('/permissiongroup','PermissionController@permissionGroups')->name('role.pg.list');
            Route::get('/permissiongroup/add','PermissionController@createGroup')->name('role.pg.add.form');
            Route::post('/permissiongroup/add','PermissionController@storeGroup')->name('role.pg.add');
            Route::get('/permissiongroup/edit/{id}','PermissionController@editGroup')->name('role.pg.edit.form');
            Route::post('/permissiongroup/edit/{id}','PermissionController@updateGroup')->name('role.pg.edit');
            Route::get('/permissiongroup/delete/{id}','PermissionController@destroy')->name('role.pg.delete');

            /*Agency Manager Routes*/
            Route::get('/agencies','AgencyController@index')->name('agency.list');
            Route::get('/agencies/add','AgencyController@create')->name('agency.add.form');
            Route::post('/agencies/add','AgencyController@store')->name('agency.add');
            Route::get('/agencies/edit/{agency_id}','AgencyController@edit')->name('agency.edit.form');
            Route::post('/agencies/edit/{agency_id}','AgencyController@update')->name('agency.edit');
            Route::get('/agencies/delete/{agency_id}','AgencyController@destroy')->name('agency.delete');
          
            /* Agency contract */
            Route::get('/agencies-contracts','AgencyController@contractList')->name('agency.contract.list');
            Route::post('/agencies-contracts','AgencyController@contractList')->name('agency.filter.contract');
            Route::post('/agencies-contract-action','AgencyController@agencyContractAction')->name('agency.contract.action');

            /*Document Manager Routes*/
            Route::get('/document-requirements','DocumentRequirementController@index')->name('dr.list');
            Route::post('/document-requirements','DocumentRequirementController@index')->name('dr.search');
            Route::get('/document-requirements/add','DocumentRequirementController@create')->name('dr.add.form');
            Route::post('/document-requirements/add','DocumentRequirementController@store')->name('dr.add');
            Route::get('/document-requirements/edit/{dr_id}','DocumentRequirementController@edit')->name('dr.edit.form');
            Route::post('/document-requirements/edit/{dr_id}','DocumentRequirementController@update')->name('dr.edit');
            Route::get('/document-requirements/delete/{dr_id}','DocumentRequirementController@destroy')->name('dr.delete');
            Route::post('/drAjaxRequest','DocumentRequirementController@ajaxRequest')->name('dr.ajax');
            
            /* Documents Routes*/
            Route::post('/drrf','DocumentsController@documentRejectReason')->name('document.reject.reason.form');
            Route::post('/drr','DocumentsController@documentRejectReason')->name('document.reject.reason');
            Route::post('/da','DocumentsController@documentAction')->name('document.action');
            Route::get('/users/documents/{user_id}','DocumentsController@userDocument')->name('user.document');
            Route::post('/users/documents','DocumentsController@userDocumentList')->name('user.document.list');
            Route::post('/uploaddocument','DocumentsController@uploadSupportingDocument')->name('uploaddocument');
            Route::post('/dh','DocumentsController@documentHistory')->name('document.history');
            Route::post('/doc-uploaded','DocumentsController@requireDocumentUploaded')->name('doc.uploaded');
            Route::post('/udi','DocumentsController@uploadDocumentInstruction')->name('upload.document.instruction');
            
            /* Administrator Routes*/
            Route::get('/admins','AdministratorController@index')->name('admin.list');
            Route::post('/admins','AdministratorController@index')->name('admin.search');
            Route::get('/admins/add','AdministratorController@create')->name('admin.add.form');
            Route::post('/admins/add','AdministratorController@store')->name('admin.add');
            Route::get('/admins/edit/{id}','AdministratorController@edit')->name('admin.edit.form');
            Route::post('/admins/edit/{id}','AdministratorController@update')->name('admin.edit');
            Route::get('/admins/delete/{id}','AdministratorController@destroy')->name('admin.delete');

            /* Menu Manager Routes*/
            Route::get('/menu','MenuController@index')->name('menu.list');
            Route::get('/menu/add','MenuController@createMenuItems')->name('menu.add.form');
            Route::post('/menu/add','MenuController@storeMenuItems')->name('menu.add');
            Route::get('/menu/edit/{id}','MenuController@editMenuItems')->name('menu.edit.form');
            Route::post('/menu/edit/{id}','MenuController@updateMenuItems')->name('menu.edit');
            Route::get('/menu/delete/{id}','MenuController@destroyMenu')->name('menu.delete');
            Route::post('/menu/loadRoutes','MenuController@loadRoutes')->name('menu.loadroute');
            Route::get('/menu/order','MenuController@menuOrder')->name('menu.order');
            Route::post('/menu/order','MenuController@updateMenuOrder')->name('menu.order.update');

            /*Start Forum Pages*/
            Route::get('/categories', 'ForumController@showCategory')->name('forum.cat.list');
            Route::post('/categories', 'ForumController@showCategory')->name('forum.cat.search');
            Route::get('/categories/add','ForumController@createCategory')->name('forum.cat.add.form');
            Route::post('/categories/add','ForumController@storeCategory')->name('forum.cat.add');
            Route::get('/categories/edit/{forum_title}','ForumController@editCategory')->name('forum.cat.edit.form');
            Route::post('/categories/edit/{forum_title}','ForumController@updateCategory')->name('forum.cat.edit');
            Route::get('/categories/delete/{forum_title}','ForumController@destroyCategory')->name('forum.cat.delete');
            Route::post('/categories/uploadimage', 'ForumController@crop')->name('forum.cat.upload.image');

            Route::get('/sub-categories', 'ForumController@showSubCategory')->name('forum.subcat.list');
            Route::post('/sub-categories', 'ForumController@showSubCategory')->name('forum.subcat.search');
            Route::get('/sub-categories/add','ForumController@createSubCategory')->name('forum.subcat.add.form');
            Route::post('/sub-categories/add','ForumController@storeSubCategory')->name('forum.subcat.add');
            Route::get('/sub-categories/edit/{forum_title}','ForumController@editSubCategory')->name('forum.subcat.edit.form');
            Route::post('/sub-categories/edit/{forum_title}','ForumController@updateSubCategory')->name('forum.subcat.edit');
            Route::get('/sub-categories/delete/{forum_title}','ForumController@destroySubCategory')->name('forum.subcat.delete');
            Route::post('/sub-categories/get-sub-categories','ForumController@loadSubCategory')->name('forum.get.subcat');
        
            Route::get('/topic', 'TopicController@show')->name('topic.list');
            Route::post('/topic', 'TopicController@show')->name('topic.search');
            Route::get('/topic/edit/{topic_title}','TopicController@edit')->name('topic.edit.form');
            Route::post('/topic/notifystatus','TopicController@changeStatus')->name('topic.notify.status');
            Route::post('/topic/edit/{topic_title}','TopicController@update')->name('topic.edit');
            Route::get('/topic/delete/{t_id}','TopicController@destroy')->name('topic.delete');
            Route::get('/topic/loadtags','TopicController@loadTags')->name('searchtags');
            Route::post('/topic/load-topic-data','TopicController@loadTopicData')->name('load.topic.data');
            
            Route::get('/topic/comment/{id}','CommentController@show')->name('comment.list');
            Route::get('/topic/comment/edit/{id}','CommentController@edit')->name('comment.edit.form');
            Route::post('/topic/comment/edit/{id}','CommentController@update')->name('comment.edit');
            Route::get('/topic/comment/delete/{id}','CommentController@destroy')->name('comment.delete');
            Route::post('/topic/comment/status','CommentController@changeStatus')->name('comment.change.status');
            /*End Forum Pages*/
            
            /*Start User Pages*/
            Route::get('/users', 'UserController@show')->name('user.list');
            Route::post('/users', 'UserController@show')->name('user.search');
            Route::get('/users/add','UserController@create')->name('user.add.form');
            Route::post('/users/add','UserController@store')->name('user.add');
            Route::get('/users/edit/{user_id}','UserController@edit')->name('user.edit.form');
            Route::post('/users/edit/{user_id}','UserController@update')->name('user.edit');
            Route::post('/users/uploadimage', 'UserController@crop')->name('user.upload.image');
            Route::get('/users/user-details/{user_id}','UserController@detail')->name('user.detail');
            Route::get('/inviteuser', 'UserController@invite')->name('user.invite');
            Route::post('/inviteuser','UserController@createInvitation')->name('user.add.invite');
            Route::post('/userAjaxRequest','UserController@ajaxRequest')->name('user.ajax');
            Route::get('/users/history/{user_id}','UserController@userHistory')->name('user.history');
            /*End User Pages*/
            
            /*Start FAQ Pages*/
            Route::get('/faq', 'FaqController@show')->name('faq.list');
            Route::get('/faq/add','FaqController@create')->name('faq.add.form');
            Route::post('/faq/add','FaqController@store')->name('faq.add');
            Route::get('/faq/edit/{id}','FaqController@edit')->name('faq.edit.form');
            Route::post('/faq/edit/{id}','FaqController@update')->name('faq.edit');
            Route::get('/faq/delete/{id}','FaqController@destroy')->name('faq.delete');
            Route::post('/faq/setorder','FaqController@setOrder')->name('faq.setorder');
            /*End FAQ Pages*/
            
            /*Testimonial Manager Routes*/
            Route::get('/testimonials','TestimonialController@index')->name('testimonials.list');
            Route::get('/testimonial/add','TestimonialController@create')->name('testimonials.add.form');
            Route::post('/testimonial/add','TestimonialController@store')->name('testimonials.add');
            Route::get('/testimonial/edit/{t_id}','TestimonialController@edit')->name('testimonials.edit.form');
            Route::post('/testimonial/edit/{t_id}','TestimonialController@update')->name('testimonials.edit');
            Route::get('/testimonial/delete/{t_id}','TestimonialController@destroy')->name('testimonials.delete');
            Route::post('/testimonial/uploadimage', 'TestimonialController@crop')->name('testimonials.upload.image');

            /* HC & Position Routes*/
            Route::get('/hc','HostCompanyController@index')->name('hc.list');
            Route::get('/hc/add','HostCompanyController@create')->name('hc.add.form');
            Route::post('/hc/add','HostCompanyController@store')->name('hc.add');
            Route::get('/hc/edit/{id}','HostCompanyController@edit')->name('hc.edit.form');
            Route::post('/hc/edit/{id}','HostCompanyController@update')->name('hc.edit');
            Route::get('/hc/delete/{id}','HostCompanyController@destroy')->name('hc.delete');
            Route::get('/hc/status','HostCompanyController@updateStatus')->name('hc.status.form');
            Route::post('/hc/status','HostCompanyController@updateStatus')->name('hc.status.update');
            Route::get('/hc/{id}','HostCompanyController@detail')->name('hc.detail');
            Route::post('/hcAjaxRequest','HostCompanyController@ajaxRequest')->name('hc.ajax');

            Route::get('/positions','PositionController@index')->name('hc.pos.list');
            Route::get('/positions/add','PositionController@create')->name('hc.pos.add.form');
            Route::post('/positions/add','PositionController@store')->name('hc.pos.add');
            Route::get('/positions/edit/{id}','PositionController@edit')->name('hc.pos.edit.form');
            Route::post('/positions/edit/{id}','PositionController@update')->name('hc.pos.edit');
            Route::get('/positions/delete/{id}','PositionController@destroy')->name('hc.pos.delete');
        
            /* Application Status Routes*/
            Route::get('/user-app-list','ApplicationStatusController@index')->name('user.app.list');
            Route::post('/user-app-list','ApplicationStatusController@index')->name('user.app.list');
            Route::get('/user-app-list/progress/{user_id}','ApplicationStatusController@appProgress')->name('user.app.progress');
            Route::post('/navigatestage', 'ApplicationStatusController@navigateApplicationSteps')->name('navigatestage');
            Route::post('/uploadresume','ApplicationStatusController@uploadResume')->name('uploadresume');
            Route::post('/resumebuilder','ApplicationStatusController@updateResumeBuilder')->name('resumebuilder');
            Route::post('/updateskype','ApplicationStatusController@updateSkype')->name('updateskype');
            Route::post('/requestfinance','ApplicationStatusController@requestFinance')->name('requestfinance');
            Route::post('/addinfo','ApplicationStatusController@updateAdditionalInfo')->name('addinfo'); 
            Route::post('/uploadagreement','ApplicationStatusController@uploadAgreement')->name('uploadagreement');
            Route::post('/previewinterview','ApplicationStatusController@previewInterview')->name('preview.interview');
            Route::post('/sch-pre-int','ApplicationStatusController@schedulePreScreenInterview')->name('schedule.prescreen.interview');
            Route::post('/collectregfee','ApplicationStatusController@collectRegFee')->name('collect.reg.fee');
            Route::get('/user-app-list/add-lead/{user_id}','ApplicationStatusController@getLead')->name('add.lead');
            Route::post('/hiring-stage','ApplicationStatusController@hiringStage')->name('hiring.stage'); 
            Route::post('/visa-stage','ApplicationStatusController@visaStage')->name('visa.stage');
                        
            /* Set Thems color  */
            Route::post('/setcolor','AdministratorController@setColor')->name('setcolor');
            
            /*Notification Manager Routes*/
            Route::get('/j1app-notification-types','NotificationController@showType')->name('notification.type.list');
            Route::post('/j1app-notification-type/status','NotificationController@changeStatus')->name('notification.type.status');
            Route::get('/j1app-notification-type/edit/{jnt_id}','NotificationController@editJNT')->name('notification.type.edit.form');
            Route::post('/j1app-notification-type/edit/{jnt_id}','NotificationController@updateJNT')->name('notification.type.edit');
            Route::get('/j1app-notification-type/delete/{jnt_id}','NotificationController@destroyJNT')->name('notification.type.delete');
            
            Route::get('/j1app-notification-messages','NotificationController@showMessage')->name('notification.message.list');
            Route::post('/j1app-notification-messages','NotificationController@showMessage')->name('notification.message.search');
            Route::post('/j1app-notification-messages/status','NotificationController@changeMessageStatus')->name('notification.message.status');
            Route::get('/j1app-notification-messages/edit/{jnm_id}','NotificationController@editJNM')->name('notification.message.edit.form');
            Route::post('/j1app-notification-messages/edit/{jnm_id}','NotificationController@updateJNM')->name('notification.message.edit');
            Route::get('/j1app-notification-messages/delete/{jnm_id}','NotificationController@destroyJNM')->name('notification.message.delete');
            
            Route::get('/email-notifications','NotificationController@showEN')->name('email.notification.list');
            Route::post('/email-notifications','NotificationController@showEN')->name('email.notification.search');
            Route::post('/email-notifications/status','NotificationController@changeEmailStatus')->name('email.notification.status');
            Route::get('/email-notifications/edit/{e_id}','NotificationController@editEN')->name('email.notification.edit.form');
            Route::post('/email-notifications/edit/{e_id}','NotificationController@updateEN')->name('email.notification.edit');
            Route::get('/email-notifications/delete/{e_id}','NotificationController@destroyEN')->name('email.notification.delete');
        });
        
    });
    
    Route::group(['middleware' => ['auth:admin']],function(){
        Route::get('/dl/{action}/{value?}','CommonController@downloadDocument')->name('download');
    });
};

$domain_details = DomainSettings::getDomainDetail();

if(!empty($domain_details))
{
    $appRoutes = ""; 
    switch($domain_details->app_interface){
        case "user":
            $appRoutes = $userRoutes;
            break;
        case "agency":
            $appRoutes = $adminRoutes;
            break;
        case "admin":
            $appRoutes = $adminRoutes;
            break;
    }
    
    if(!empty($appRoutes))
    {
        config(['common.app_interface' => $domain_details->app_interface]);
        config(['filesystems.disks.public.url' => $domain_details->domain_url."/storage"]);
        Route::domain(request()->getHttpHost())->group($appRoutes);
    }
}

Route::post('/localtz','CommonController@localTimezone')->name('localtz');

/** Artisan Call Direct **/
Route::get('configcacheclear', function () {
    Artisan::call('config:cache');
    return response()->json(['type' => "success", 'message' => 'Configuration cache created successfully.']);
})->name('configcacheclear');

Route::get('configclear', function () {
    Artisan::call('config:clear');
    return response()->json(['type' => "success", 'message' => 'Configuration cache removed successfully.']);
})->name('configclear');

Route::get('cacheclear', function () {
    Artisan::call('cache:clear');
    return response()->json(['type' => "success", 'message' => 'Application cache cleared successfully.']);
})->name('cacheclear');

Route::get('viewclear', function () {
    Artisan::call('view:clear');
    return response()->json(['type' => "success", 'message' => 'Cleared comipled views successfully.']);
})->name('viewclear');