<?php

use Illuminate\Support\Facades\Route;

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

    Route::get('/clear-cache', function() {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        return "Cache is cleared";
    });

    Route::get('/', function () {
	if(Auth::check()) {
    		return redirect('/dashboard');
	}
        return view('index');
    });

    // Route::get('/{ref}', 'AdminController@index')->name('/{ref}');

    Route::get('/fb-integration', function () {
        return view('fb-integration');
    });

 // Route::get('/referral-code/{code}', function () {
 //        return view('index');
 //    });
Route::get('/usersreferrels','AdminController@usersreferrels')->name('usersreferrels');
Route::get('/usersAssignedReferrel','AdminController@usersAssignedReferrel')->name('usersAssignedReferrel');
Route::post('/users-referrels-sells','AdminController@users_referrels_sells')->name('users-referrels-sells');


// Route::get('generate-referral-code', 'AdminController@generateReferralCode')->name('generate-referral-code');
// Route::get('know_your_affliates', 'AdminController@showAffliates')->name('know_your_affliates');
// Route::get('get_your_affliates', 'AdminController@getAffliates')->name('get_your_affliates');



Route::get('/update-huzefa-leads','AdminController@updateHuzefaLeads')->name('update-huzefa-leads');
   
Route::get('/fb-integration-test', 'FacebookController@show1')->name('facebook-integration-test');


Route::get('/cron-test', 'FacebookController@show2')->name('cron-test');

Route::get('/run-cron','AdminController@runCron')->name('run-cron');

Route::post('/facebook/webhook_verify', 'AdminController@webhookVerify')->name('facebook-webhook');

//Route::get('/fb_webhook_callback','LeadsController@fbWebhook')->name('fb_webhook_callback');
Route::get('/wordpress-leads', 'AdminController@webhookWordpress')->name('wordpress-leads');

Route::post('/wordpress-leads/{userId}','AdminController@webhookWordpress')->name('wordpress-leads');


Route::get('/facebook/integration', 'FacebookController@show')->name('facebook-integration');

Route::get('/wordpress/integration', 'LeadsController@wordpressIntegration')->name('wordpress-integration');
Route::post('/save-wordpress-integration','LeadsController@integrateWordpressSite')->name('save-wordpress-integration');

Route::post('/show-fb-form-fields','AdminController@showFormFields')->name('show-fb-form-fields');


Route::get('/facebook/leads', 'FacebookController@showLeads')->name('facebook-leads');

Route::get('/user-token', 'AdminController@userAccessToken')->name('user-token');

Route::get('/wp-integration-help', 'AdminController@showWpHelp')->name('wp-integration-help');

Route::get('/registration-success-msg', 'AdminController@showSuccessMsg')->name('registration-success-msg');

Route::get('/fblead-integration', function () {
        return view('fblead-integration');
    });



	Route::post('login', ['as'=>'login' ,'uses'=>'Admin\AdminController@Check_login']);
	Route::post('/login', 'Auth\LoginController@login')->name('login');
	Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

	Route::post('/load-scanner', 'AdminController@showScanner')->name('load-scanner');

    // FORGOT PASSWORD Management (By Subrata Saha)
    Route::get('user-forgot-password', ['as' => 'forgot_password', 'uses' => 'Auth\LoginController@viewForgotPassword']);
    Route::post('save-forgot-password', ['as' => 'save_forgot_password', 'uses' => 'Auth\LoginController@saveForgotPassword']);

    Route::get('reset-password/{usereid}/{useremail}', ['as' => 'reset_password', 'uses' => 'Auth\LoginController@UserResetPassword']);
    Route::post('save-reset-password', ['as' => 'save_reset_password', 'uses' => 'Auth\LoginController@saveResetPassword']);



    // Signup Management (By Subrata Saha)
    Route::get('user-signup', ['as' => 'user_signup', 'uses' => 'Auth\LoginController@userSignup']);
    Route::post('user-save-signup', ['as' => 'user_save_signup', 'uses' => 'Auth\LoginController@userSaveSignup']);


	Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

	Route::middleware(['isAdmin'])->group( function() {
		Route::get('/manage-admins', 'AdminController@manage_admins')->name('manage-admins');
		Route::get('/demo-scheduler', 'AdminController@demoScheduler')->name('demo-scheduler');
		Route::get('/add-new-admin', 'AdminController@add_new_admin')->name('add-new-admin');
		Route::post('/save-admin', 'AdminController@save_admin')->name('save-admin');
		Route::any('/edit-admins/{adminsid}','AdminController@edit_admins')->name('edit-admins');
		Route::post('/edit-admins-post/{adminsid}','AdminController@edit_admins_post')->name('edit-admins-post');
		Route::any('/delete-admins/{adminsid}','AdminController@delete_admins')->name('delete-admins');
		Route::get('/admins-retrival','AdminController@adminsRetrival')->name('admins-retrival');
		
		
		Route::get('/manage-sells', 'AdminController@managesells')->name('sells');
		Route::post('/save-sells', 'AdminController@savesells')->name('save-sells');
		Route::get('/edit-sells/{id}', 'AdminController@editsells')->name('edit-sells');
		Route::post('/edit-sells-post/{adminsid}','AdminController@edit_sells_post')->name('edit-sells-post');
		Route::get('delete-sells/{id}', 'AdminController@deletesell')->name('delete-sells');
		
		Route::get('/users-assigned','AdminController@users_assigned')->name('users-assigned');
		Route::post('/users-assigned-sells','AdminController@users_assigned_sells')->name('users-assigned-sells');
		Route::get('/users-assigned-retrival','AdminController@usersAssignedRetrival')->name('users-assigned-retrival');


// Route::get('/subscribe-plan', function () {
//         return view('subscription');
// });
	});

    Route::get('/createReferralCode','AdminController@createReferralCode')->name('createReferralCode');
    Route::post('/saveReferralCode','AdminController@saveReferralCode')->name('saveReferralCode');
    Route::get('/subscribeplan','AdminController@subscribeplan')->name('subscribeplan');


	Route::get('/manage-admin-user', 'AdminController@manage_admin_user')->name('manage-admin-user');
	Route::get('/add-new-admin-user', 'AdminController@add_new_admin_user')->name('add-new-admin-user');
	Route::post('/save-admin-user', 'AdminController@save_admin_user')->name('save-admin-user');
	Route::get('/add-staff', 'AdminController@addStaff')->name('add-staff');
	Route::post('/save-staff', 'AdminController@saveStaff')->name('save-staff');
	Route::get('/view-staff', 'AdminController@viewStaff')->name('view-staff');
    Route::get('/edit-staff/{id}', 'AdminController@editStaff')->name('edit-staff');
	Route::post('/edit-staff-post/{adminsid}','AdminController@edit_staff_post')->name('edit-staff-post');
Route::get('/delete-staff/{id}', 'AdminController@deleteuser')->name('delete-staff');
	
	// Active / In-Active User In Admin Panel (By Subrata Saha)
    	Route::post('/active-inactive-user', ['as' => 'active_inactive_user', 'uses' => 'AdminController@active_inactive_user']);


	
    Route::post('/deactivate-cron','AutomationController@deactivateCron')->name('deactivate-cron');
	
	Route::post('/set-default-campaign','AdminController@setDefaultCampaign')->name('set-default-campaign');
	
    // LEADS MANAGEMENT ADD/EDIT/DELETE
	Route::get('/leads-master','LeadsController@leads_master')->name('leads-master');

        Route::post('/subscribe-page','LeadsController@subscribePage')->name('subscribe-page');




	Route::get('/leads-assigned','LeadsController@leads_assigned')->name('leads-assigned');
    Route::post('/leads-assigned-staff','LeadsController@leads_assigned_staff')->name('leads-assigned-staff');
	
	Route::get('/view-assigned-leads','LeadsController@view_leads_assigned')->name('view-assigned-leads');
	
	Route::get('/view-leads-followUp','LeadsController@viewLeadFolloups')->name('view-leads-followUp');
	Route::get('/view-leads-followUp/pending','LeadsController@viewLeadFolloups')->name('view-leads-followUp');
	Route::post('/add-lead-comments','LeadsController@addLeadComment')->name('add-lead-comments');
	Route::post('/add-lead-followUp','LeadsController@addLeadFollowUp')->name('add-lead-followUp');
	
	Route::post('/get-lead-comments','LeadsController@getLeadComments')->name('get-lead-comments');
	
	
	Route::post('/close-lead','LeadsController@closeLead')->name('close-lead');
	
	Route::get('/add-leads','LeadsController@add_leads')->name('add-leads');
	Route::post('/add-leads-post-data','LeadsController@add_leads_post_data')->name('add-leads-post-data');

	Route::get('/add-import-leads','LeadsController@add_import_leads')->name('add-import-leads');
	Route::post('/add-import-leads-post-data','LeadsController@add_import_leads_post_data')->name('add-import-leads-post-data');

	Route::any('/edit-leads/{leadsid}','LeadsController@edit_leads')->name('edit-leads');
	Route::post('/edit-leads-post/{leadsid}','LeadsController@edit_leads_post')->name('edit-leads-post');

	Route::any('/delete-leads/{leadsid}','LeadsController@delete_leads')->name('delete-leads');
	Route::post('/delete-bulkleads','LeadsController@deleteBulkLeads')->name('delete-bulkleads');

	Route::get('/delete-leads-from-cron','LeadsController@deleteLeadsFromCron')->name('delete-leads-from-cron');
	Route::get('/delete-stopped-cron','LeadsController@deleteStoppedCron')->name('delete-stopped-cron');


	Route::get('/leads-bulk-upload','LeadsController@leads_bulk_upload')->name('leads-bulk-upload');
	Route::post('/leads-bulk-upload-post-data','LeadsController@leads_bulk_upload_post_data')->name('leads-bulk-upload-post-data');

	//testing
	Route::get('/leads-view','LeadsController@leadsView')->name('leads-view');
	Route::get('/leads-retrival','LeadsController@leadsRetrival')->name('leads-retrival');
	
	Route::get('/leads-assigned-retrival','LeadsController@leadsAssignedRetrival')->name('leads-assigned-retrival');
	
	Route::get('/assigned-leads-retrival','LeadsController@assignedLeadsRetrival')->name('assigned-leads-retrival');
	
	Route::post('/set-lead-status','LeadsController@setLeadStatus')->name('set-lead-status');
	Route::get('/view-reports/{leadId}','LeadsController@viewReport')->name('view-reports');
	
	Route::get('/followup-assigned-retrival','LeadsController@followupsAssignedRetrival')->name('followup-assigned-retrival');
	//testing
	
	Route::get('/campaigns-retrival','CampaignController@campaignsRetrival')->name('campaigns-retrival');
	Route::post('/admin-status/{id}', 'AdminController@statusactiveadmin')->name('admin-status');

    // Campaign MANAGEMENT ADD/EDIT/DELETE
    Route::get('/add-campaign','CampaignController@add_campaign')->name('add-campaign');
    Route::post('/save-campaign-automation','CampaignController@save_campaign_automation')->name('save-campaign-automation');
    Route::get('/edit-campaigns/{campaignsid}','CampaignController@edit_campaigns')->name('edit-campaigns');
    Route::post('/edit-campaign-name/{campaignsid}','CampaignController@edit_campaign_name')->name('edit-campaign-name');


    //Copy Campaign (By Subrata Saha)
    Route::get('copy-campaign/{id}', ['as' => 'copy_campaign', 'uses' => 'CampaignController@copyCampaign']);



    //
    Route::get('/view-campaigns-automations/{campaignsid}','CampaignController@view_campaigns_automations')->name('view-campaigns-automations');



    //Copy SMS/Email/whatsapp Automation Campaign (By Subrata Saha)
    Route::get('copy-sms-automation-campaign/{id}', ['as' => 'copy_sms_campaign', 'uses' => 'CampaignController@copySmsAutoCampaign']);
    Route::get('copy-email-automation-campaign/{id}', ['as' => 'copy_email_campaign', 'uses' => 'CampaignController@copyEmailAutoCampaign']);
    Route::get('copy-whatsapp-automation-campaign/{id}', ['as' => 'copy_whatsapp_campaign', 'uses' => 'CampaignController@copywhatsappAutoCampaign']);



    // BULK SMS CAMPAIGN MANAGEMENT ADD/EDIT/DELETE (By Subrata Saha)
    Route::get('view-bulk-sms-campaign/{id}', ['as' => 'view_bulk_sms_master', 'uses' => 'BulkSmsController@viewBulkSmsCampaign']);
    Route::get('bulk-sms-campaign', ['as' => 'bulk_sms_master', 'uses' => 'BulkSmsController@viewBulkSms']);
    Route::get('add-sms-campaign', ['as' => 'add_sms_campaign', 'uses' => 'BulkSmsController@addBulkSms']);
    Route::post('save-sms-campaign', ['as' => 'save_sms_campaign', 'uses' => 'BulkSmsController@saveBulkSms']);

    Route::get('edit-sms-campaign/{id}', ['as' => 'edit_sms_master', 'uses' => 'BulkSmsController@editBulkSms']);
    Route::post('update-sms-campaign/{id}', ['as' => 'update_sms_campaign', 'uses' => 'BulkSmsController@updateBulkSms']);


    // ADD / EDIT / DELETE BULK WHATSAPP AUTOMATION (By Subrata Saha)
    Route::get('bulk-campaign-whatsapp-automation', ['as' => 'bulk_campaign_whatsapp_automation', 'uses' => 'SmsAutomationController@getBulkSmsAutomation']);
    Route::post('save-whatsapp-campaign-automation', ['as' => 'save_whatsapp_campaign_automation', 'uses' => 'SmsAutomationController@saveWhatsappAutomationCamp']);


    Route::get('bulk-whatsapp-automation/{id}', ['as' => 'bulk_whatsapp_automation', 'uses' => 'SmsAutomationController@viewBulkSmsAutomation']);
    Route::post('bulk-save-whatsapp-automation', ['as' => 'bulk_save_whatsapp_automation', 'uses' => 'SmsAutomationController@saveWhatsappAutomation']);
    Route::get('edit-bulk-whatsapp-automation/{id}', ['as' => 'bulk_edit_whatsapp_automation', 'uses' => 'SmsAutomationController@editWhatsappAutomation']);
    Route::post('edit-bulk-whatsapp-automation-post/{id}', ['as' => 'bulk_edit_whatsapp_automation_post', 'uses' => 'SmsAutomationController@editWhatsappAutomationPost']);





    // LEADS MANAGEMENT ADD/EDIT/DELETE
	Route::get('/automation-master','AutomationController@automation_master')->name('automation-master');
	Route::get('/delete-campaigns/{id}','CampaignController@deleteCampaign')->name('delete-campaigns');
	Route::get('/lead-reports/{id}','AdminController@showReport')->name('lead-reports');
	
	Route::post('/deactivate-event','CampaignController@deactivateEvent')->name('deactivate-event');
	
    // ADD / EDIT / DELETE SMS AUTOMATION
	Route::get('/sms-automation/{campaignsid}','AutomationController@sms_automation')->name('sms-automation');
	Route::post('/save-sms-automation','AutomationController@save_sms_automation')->name('save-sms-automation');
    Route::get('/edit-sms-automation/{smsid}','AutomationController@edit_sms_automation')->name('edit-sms-automation');
    Route::post('/edit-sms-automation-post/{smsid}','AutomationController@edit_sms_automation_post')->name('edit-sms-automation-post');
	Route::post('/test-sms-msg', 'AutomationController@testsendsms')->name('test-sms-msg');
	
    // ADD / EDIT / DELETE EMAIL AUTOMATION
    Route::get('/email-automation/{campaignsid}','AutomationController@email_automation')->name('email-automation');
    Route::post('/save-email-automation','AutomationController@save_email_automation')->name('save-email-automation');
    Route::get('/edit-email-automation/{smsid}','AutomationController@edit_email_automation')->name('edit-email-automation');
    Route::post('/edit-email-automation-post/{smsid}','AutomationController@edit_email_automation_post')->name('edit-email-automation-post');
	
	Route::post('/send-test-email', 'AutomationController@testSendEmail')->name('send-test-email');
    // ADD / EDIT / DELETE WHATSAPP AUTOMATION
    Route::get('/whatsapp-automation/{campaignsid}','AutomationController@whatsapp_automation')->name('whatsapp-automation');
    Route::post('/save-whatsapp-automation','AutomationController@save_whatsapp_automation')->name('save-whatsapp-automation');
    Route::get('/edit-whatsapp-automation/{smsid}','AutomationController@edit_whatsapp_automation')->name('edit-whatsapp-automation');
    Route::post('/edit-whatsapp-automation-post/{smsid}','AutomationController@edit_whatsapp_automation_post')->name('edit-whatsapp-automation-post');
	
	Route::post('/test-whatsapp-msg', 'AutomationController@testsendwhatsappTest')->name('test-whatsapp-msg');
Route::post('/store-file', 'CampaignController@store');

    //Route::post('store-file', 'CampaignController@store');
   // Whats app API Capture
    Route::get('/whats-app-api-capture', 'DashboardController@whats_app_api_capture')->name('whats-app-api-capture');
    Route::post('/post-whats-app-api-capture', 'DashboardController@post_whats_app_api_capture')->name('post-whats-app-api-capture');
	
	Route::get('/post-whats-app-plan', 'AdminController@postWhatsAppPlan1')->name('post-whats-app-plan');	
	Route::get('/edit-settings/{id}', 'AdminController@setSettings')->name('edit-settings');
	
	
    // Email API Capture
    Route::get('/email-api-capture', 'DashboardController@email_api_capture')->name('email-api-capture');
    Route::post('/post-email-api-capture', 'DashboardController@post_email_api_capture')->name('post-email-api-capture');

        // Stripe API Capture
    Route::get('/stripe-api-capture', 'DashboardController@stripeapicapture')->name('stripe-api-capture');
    Route::post('/post-stripe-api-capture', 'DashboardController@poststripeapicapture')->name('post-stripe-api-capture');

    // SMS Api Capture
    Route::get('/sms-api-capture', 'DashboardController@sms_api_capture')->name('sms-api-capture');
    Route::post('/post-sms-api-capture', 'DashboardController@post_sms_api_capture')->name('post-sms-api-capture');

    // Change Password
    Route::get('/change-password', 'DashboardController@change_password')->name('change-password');
    Route::post('/post-user-update-password', 'DashboardController@post_user_update_password')->name('post-user-update-password');
	
	Route::post('/user-plan-details', 'AdminController@getUserPlan')->name('user-plan-details');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/contact-more-information', 'HomeController@contact_more_information')->name('contact-more-information');

Route::post('/ask-for-demo', 'AdminController@askfordemo')->name('ask-for-demo');

Route::get('add-video', 'VideoController@videos')->name('add-video');
Route::post('save-video', 'VideoController@savevideos')->name('save');

Route::get('/end-dateretrival','LeadsController@enddateRetrival')->name('end-dateretrival');

Route::post('/ajaxactive-staff/{id}', 'AdminController@ajaxactive');

Route::get('/edit-sells/{id}', 'AdminController@editsells')->name('edit-sells');
Route::post('/edit-sells-post/{adminsid}','AdminController@edit_sells_post')->name('edit-sells-post');


Route::post('/setup-cron','AdminController@setLeadsCron')->name('setup-cron');

Route::get('/tutorial', 'AdminController@tutorialview')->name('tutorial');

Route::get('/tutorial-gallery', 'AdminController@viewTutorialGallery')->name('tutorial-gallery');

Route::post('/tutorial-save', 'AdminController@tutorialsave')->name('tutorial.save');
Route::post('/tutorial-on/{id}', 'AdminController@tutorialon');
Route::post('/tutorial-off/{id}', 'AdminController@tutorialstatus');
Route::get('/tutorial-delete/{id}', 'AdminController@tutorialdelete');
Route::get('/tutorial-edit/{id}', 'AdminController@tutorialedit');
Route::post('/tutorial-edit/{id}', 'AdminController@tutorialupdate');

Route::get('add-video', 'VideoController@videos')->name('add-video');

Route::post('save-video', 'VideoController@savevideos')->name('save');

Route::get('gallery', 'VideoController@gallery')->name('gallery'); 


Route::get('lead-settings', 'LeadsController@leadSettings')->name('lead-settings');
Route::get('account-settings', 'AdminController@viewAccountSettings')->name('account-settings');
Route::post('save-admin-settings', 'AdminController@accountSettings')->name('save-admin-settings');
Route::post('auto-lead-assign', 'LeadsController@autoLeadAssign')->name('auto-lead-assign');


Route::get('/overdue-followups', 'LeadsController@overdueFollowup')->name('lead-overdue-followups');
Route::get('/overdue-followups-retrival','LeadsController@overdueFollowupsRetrival')->name('overdue-followups-retrival');

Route::get('/delete-auto-assign/{id}', 'LeadsController@deleteAutoAssign');

// 
	
//Route::get('subscribeTo/{id}', 'AdminController@subscribeTo')->name('subscribeTo');

Route::post('subscribeTo', 'AdminController@subscribeTo')->name('subscribeTo');

Route::post('get-dropbox-link', 'AdminController@getDropboxLink');

Route::get('/starter-leads-view','LeadsController@leads_master_test')->name('starter-leads-view');

Route::get('/generate-code','AdminController@generateAdminCode')->name('generate-code');
Route::post('/save-code','AdminController@saveAdminCode')->name('save-code');
Route::post('client/{client_id}/save-lead', 'LeadsController@saveLeadFromApi')->withoutMiddleware(['auth']);

Route::get('/add-segment','CampaignController@add_segment')->name('add-segment');
   
Route::get('/add-project','CampaignController@add_project')->name('add-project');
   
Route::post('/save-new-segment','CampaignController@save_new_segment')->name('save-new-segment');
Route::post('/save-new-project','CampaignController@save_new_project')->name('save_new_project');
Route::post('/download-excel', 'LeadsController@export')->name('download-excel');
Route::post('/subadmin-download', 'LeadsController@downloadSubadminLeads')->name('subadmin-download');

Route::post('other-fields', 'LeadsController@otherFields')->name('other-fields');
Route::get('/tou', function () {
        return view('details.terms-of-use');
    });
	
	Route::get('/privacy', function () {
        return view('details.privacy-policy');
    });

	Route::get('/about-us', function () {
        return view('details.product-information');
    });

Route::post('switch', 'LeadsController@switchoff')->name('switch');

Route::get('/leads-details','DashboardController@leadsDetails')->name('leads-details');

Route::get('/starter-leads/{userId}','AdminController@makeStarter')->name('starter-leads');

Route::get('/fbpagetoken/{userId}','AdminController@testToken')->name('fb-pagetoken');

		Route::any('/manage-subadmins/{adminsid}','AdminController@manageSubAdmins')->name('manage-subadmins');

		Route::any('/edit-subadmins/{adminsid}','AdminController@edit_subadmins')->name('edit-subadmins');

Route::get('/view-leads-subadmin','SubAdminController@view_leads_subadmin')->name('view-leads-subadmin');

Route::get('/subadmin-leads-retrival','SubAdminController@leadsRetrival')->name('subadmin-leads-retrival');

Route::post('/save-leads-subadmin','SubAdminController@save_leads')->name('save-leads');

Route::get('/add-leads-subadmin','SubAdminController@add_leads')->name('add-leads-subadmin');

Route::get('/view-subadmin-leads','AdminController@viewSubadminLeads')->name('view-subadmin-leads');

Route::get('/leads-retrival-subadmin','AdminController@subadminLeadsRetrival')->name('leads-retrival-subadmin');

Route::post('/get-subadminlead-comments','SubAdminController@getLeadComments')->name('get-subadminlead-comments');

Route::post('/add-subadmin-lead-comments','SubAdminController@addLeadComment')->name('add-subadmin-lead-comments');

Route::post('/add-subadmin-lead-followUp','SubAdminController@addLeadFollowUp')->name('add-subadmin-lead-followUp');

Route::get('/subadmin-followup-assigned-retrival','SubAdminController@followupsAssignedRetrival')->name('subadmin-followup-assigned-retrival');

Route::get('/subadmin-overdue-followups-retrival','SubAdminController@overdueFollowupsRetrival')->name('subadmin-overdue-followups-retrival');


Route::any('/edit-subadmin-lead/{leadsid}','SubAdminController@edit_leads')->name('edit-subadmin-lead');
	
Route::post('/edit-subadmin-lead-post/{leadsid}','SubAdminController@edit_leads_post')->name('edit-subadmin-lead-post');

Route::post('/save-fbpage-settings', 'FacebookController@savePageSetting')->name('save-fbpage-settings');

Route::post('/whatsappchannel-action', 'AdminController@whatsappChannelAction')->name('whatsappchannel-action');

Route::get('/daily-whatsapp-report/{id}','AdminController@dailyWhatsappReport')->name('daily-whatsapp-report');

Route::get('manage-settings/{id}', 'AdminController@manageSettings')->name('manage-settings');


Route::get('/send-whatsapp-to-groups','AutomationController@send_whatsapp_group_view')->name('send-whatsapp-to-groups');

Route::post('/send-whatsapp-to-groups-post','AutomationController@send_whatsapp_group')->name('send-whatsapp-to-groups-post');



