<?php
use App\Models\DomainSettings;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$userRoutes = function() {
    Route::group([ 'namespace'=>"User"], function (){ 

        /** Authentication Routes **/
        Route::group(['namespace'=>"Auth"], function(){
            Route::group(['middleware' => ['guest:api']], function () {
                Route::post('/login', 'LoginController@login')->name('api.login');
                Route::post('/register', 'RegisterController@register')->name('api.register');
                Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('api.password.email');
                Route::post('/password/reset', 'ResetPasswordController@reset')->name('api.password.reset');
                Route::get('/social/redirect/{provider}/{type}', 'SocialController@getSocialRedirect')->name('api.social.redirect'); 
                Route::get('/social/handle/{provider}/{type}', 'SocialController@getSocialHandle')->name('api.social.handle');
                Route::post('/accept-invitation', 'RegisterController@acceptInvitation')->name('api.accept.invitation');
            });

            Route::group(['middleware' => 'auth:api'], function() {
                Route::get('/logout', 'LoginController@logout')->name('api.logout'); 
            });
        });

        Route::group(['middleware' => ['web','auth:api']], function() {
            /** Update Profile Routes **/
            Route::get('/profile', 'ProfileController@viewProfile')->name('api.profile');
            Route::post('/update-profile', 'ProfileController@updateProfile')->name('api.update-profile');
            Route::post('/crop', 'PhotoController@crop');
            
            /** Portfolio Routes **/
            Route::get('/portfolio-detail/{p_no}', 'PortfolioController@viewPortfolioDetail')->name('api.portfolio.detail');
            Route::get('/create-portfolio', 'PortfolioController@createPortfolio')->name('api.create.portfolio');

            /** Application Status **/
            Route::get('/application-status','ApplicationStatusController@index')->name('api.application-status');
            Route::post('/asprogress','ApplicationStatusController@applicationStatusProgress')->name('api.asprogress');
            Route::post('/eligibilityquest','ApplicationStatusController@eligibilityQuest')->name('api.eligibilityquest');
            Route::post('/navigatestage','ApplicationStatusController@navigateApplicationSteps')->name('api.navigatestage');
            Route::post('/uploadresume','ApplicationStatusController@uploadResume')->name('api.uploadresume');
            Route::post('/resumebuilder','ApplicationStatusController@updateResumeBuilder')->name('api.resumebuilder');
            Route::post('/loadrbform','ApplicationStatusController@loadRBForm')->name('api.loadrbform');
            Route::post('/resetresume','ApplicationStatusController@resetResume')->name('api.resetresume');
            Route::post('/previewresume','ApplicationStatusController@previewResume')->name('api.previewresume');
            Route::post('/buildresumepdf','ApplicationStatusController@generateResumePDF')->name('api.buildresumepdf');
            Route::post('/updateskype','ApplicationStatusController@updateSkype')->name('api.updateskype');
            Route::post('/requestfinance','ApplicationStatusController@requestFinance')->name('api.requestfinance');
            Route::post('/addinfo','ApplicationStatusController@updateAdditionalInfo')->name('api.addinfo');
            Route::post('/j1agree','ApplicationStatusController@j1Agreement')->name('api.j1agree');
            Route::post('/udi','ApplicationStatusController@uploadDocumentInstruction')->name('api.udi');
            Route::post('/dh','ApplicationStatusController@documentHistory')->name('api.dh');
            Route::post('/uploaddocument','ApplicationStatusController@uploadSupportingDocument')->name('api.uploaddocument');
            Route::post('/docuploaded','ApplicationStatusController@supportingDocumentUploaded')->name('api.doc.uploaded');
            Route::post('/sponsorupdated','ApplicationStatusController@sponsorUpdated')->name('api.sponsorupdated');
            Route::post('/visa-stage','ApplicationStatusController@visaStage')->name('api.visa.stage');
            
            /* Agency Contract Controller */
            Route::post('/cra','ApplicationStatusController@agencyContractRequestAction')->name('api.cra');
            Route::post('/cr','ApplicationStatusController@agencyContractRequest')->name('api.cr');
            
            /** route create for Forum Module pages **/
            Route::post('/addtopic', 'ForumController@ajaxRequest')->name('api.addtopic');
            Route::get('/favoritetopic', 'ForumController@userFavoriteTopic')->name('api.favoritetopic');
            Route::get('/following', 'ForumController@following')->name('api.following'); 
            Route::get('/mytopics', 'ForumController@myTopics')->name('api.mytopics');
            
            /** route create for Forum Module pages **/
            Route::get('/categories', 'ForumController@categories')->name('api.categories');
            Route::get('/category/{fc_slug}', 'ForumController@category')->name('api.category');
            Route::get('/subcategory/{fs_slug}', 'ForumController@subCategory')->name('api.subcategory');
            Route::get('/topicdetail/{ft_slug}', 'ForumController@topicDetail')->name('api.topicdetail'); 
            Route::get('/tag/{tagslug}', 'ForumController@topicByTagId')->name('api.topicbytagid');
            Route::get('/recentdiscussions', 'ForumController@recentDiscussions')->name('api.recentdiscussions'); 
            
            /* Show FAQ */
            Route::get('/show-faq','PagesController@shoFaq')->name('api.show-faq');
            
            /** Authentication Routes **/
            Route::group(['namespace'=>"Auth"], function(){
                Route::get('/social_auth/redirect/{provider}/{type}', 'SocialController@getSocialRedirect')->name('api.social_auth.redirect'); 
                Route::get('/social_auth/handle/{provider}/{type}', 'SocialController@getSocialHandle')->name('api.social_auth.handle');
            });
        });

        /** Static Pages Routes **/
        Route::group(['middleware' => ['guest:api']], function () {
            Route::get('/','PagesController@index')->name('home');
            Route::get('/testimonial', 'TestimonialController@index')->name('testimonial');
        
        });
    });
};
/****/
$adminRoutes = function(){
    Route::group([ 'namespace'=>"Admin", 'middleware' => ['roles.auth','j1app']], function (){
        
        Route::get('/positions','PositionController@index')->name('hc.pos.list');
        Route::post('/positions/add','PositionController@store')->name('hc.pos.add');
        Route::post('/positions/edit/{id}','PositionController@update')->name('hc.pos.edit');
        Route::get('/positions/delete/{id}','PositionController@destroy')->name('hc.pos.delete');
        
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
        config(['filesystems.disks.public.url' => $domain_details->domain_url."/storage"]);
        Route::domain(request()->getHttpHost())->group($appRoutes);
    }
}

/****/