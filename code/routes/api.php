<?php

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

Route::group(['middleware' => ['web']], function () {
    Route::post('/sales', 'Api\SaleController@createSale');
    Route::post('/sales/wellness', 'Api\SaleController@createWellnessSale');
    Route::post('/sales/vipvip', 'Api\SaleController@createVipVipSale');
    Route::delete('/sales', 'Api\SaleController@cancelSale');
    Route::get('/sales/{param}', 'Api\SaleController@get');
    Route::get('/sales/wellness/{phone}', 'Api\SaleController@getForWellness');

    Route::post('/user', 'Api\UserController@register');
    Route::put('/user', 'Api\UserController@update');
    Route::post('/user/mobileRegistration', 'Api\UserController@mobileRegister');
    Route::get('/user/{param}', 'Api\UserController@user');
    Route::get('/users/{offset}&{limit}', 'Api\UserController@users');

    Route::get('/user/linkedAccounts/{user_id}', 'Api\UserController@linkedAccounts');
    Route::delete('/user/linkAccounts', 'Api\UserController@unlinkAccounts');
    Route::post('/user/linkAccounts', 'Api\UserController@linkAccounts');

    Route::post('/lifestyle/user/{email}', 'Api\UserController@forLifestyle');

    Route::get('/user/personalPartners/{user_id}', 'Api\UserController@personalPartners');
    Route::get('/user/mobilePersonalPartners/{user_id}', 'Api\UserController@mobilePersonalPartners');
    Route::get('/user/checkByMessenger/{messenger}&{phone}', 'Api\UserController@checkByMessenger');
    Route::get('/user/ishave/{param}', 'Api\UserController@userInfo');
    Route::get('/user/checkForVipzona/{param}', 'Api\UserController@checkForVipzona');
    Route::get('/users/withTokens', 'Api\UserController@withTokens');
    Route::post('/user/readNews', 'Api\UserController@readNews');
    Route::post('/user/readPromotion', 'Api\UserController@readPromotion');
    Route::get('/users/list', 'Api\UserController@userList');
    Route::get('/users/list/qualification', 'Api\UserController@userListQualification');
    Route::get('/users/list/full', 'Api\UserController@userListFull');
    Route::get('/users/admin', 'Api\UserController@adminList');
    Route::get('/user/lastElement/{user_id}&{side}', 'Api\UserController@lastUser');

    Route::get('/user/docs/{user_id}', 'Api\UserController@docs');
    Route::delete('/user/doc', 'Api\DocController@delete');
    Route::post('/user/doc', 'Api\DocController@create');
    Route::get('/docs', 'Api\DocController@all');

    Route::get('/user/changePassword/{user_id}&{old_password}&{new_password}&{type}', 'Api\UserController@changeAndGetPassword');
    Route::post('/user/changePassword', 'Api\UserController@changePassword');
    Route::get('/user/notes/{user_id}', 'Api\UserController@notes');
    Route::get('/user/note/{id}', 'Api\NoteController@get');
    Route::delete('/user/note', 'Api\NoteController@delete');
    Route::post('/user/note', 'Api\NoteController@create');
    Route::put('/user/note', 'Api\NoteController@update');
    Route::get('/users/careerHistory/{dateFrom}&{dateTo}', 'Api\UserController@careerHistory');
    Route::get('/user/personalSpilover/{user_id}&{level}&{view}', 'Api\UserController@personalSpilover');
    Route::get('/user/upSpilover/{user_id}', 'Api\UserController@upSpilover');
    Route::get('/user/spilover/{user_id}&{levels}', 'Api\UserController@spilover');
    Route::get('/user/byTree/{param}&{user_id}', 'Api\UserController@checkUserInSpilover');

    Route::get('/user/resetPassword/{email}&{type}', 'Api\UserController@resetAndGetPassword');
    Route::post('/user/resetPassword', 'Api\UserController@resetPassword');
    Route::post('/user/resetPasswordByMessenger', 'Api\UserController@resetPasswordByMessenger');

    Route::get('/auth/{param}&{password}', 'Api\AuthController@auth');
    Route::get('/authVipVip/{phone_vipvip}&{password}', 'Api\AuthController@authVipVip');
    Route::get('/authWellness/{phone_wellness}&{password}', 'Api\AuthController@authWellness');
    Route::get('/auth/admin/{email}&{password}', 'Api\AuthController@authAdmin');

    Route::get('/settings/', 'Api\SettingsController@get');
    Route::put('/settings/', 'Api\SettingsController@update');
    Route::get('/settings/supportedLangs', 'Api\SettingsController@supportedLanguages');
    Route::post('/settings/supportedLangs', 'Api\SettingsController@addSupportedLanguages');
    Route::get('/settings/defaultLang', 'Api\SettingsController@defaultLanguage');
    Route::get('/settings/links', 'Api\SettingsController@links');
    Route::get('/settings/bcMainMenu', 'Api\SettingsController@bcMainMenu');
    Route::get('/settings/certificate', 'Api\SettingsController@certificate');

    Route::get('/settings/mailTemplate/all/{language}', 'Api\MailTemplateController@all');
    Route::get('/settings/mailTemplate/{id}', 'Api\MailTemplateController@get');
    Route::post('/settings/mailTemplate', 'Api\MailTemplateController@create');
    Route::put('/settings/mailTemplate', 'Api\MailTemplateController@update');

    Route::get('/settings/mailQueue/all/{status}', 'Api\MailQueueController@getByStatus');
    Route::get('/settings/mailQueue/{messenger}', 'Api\MailQueueController@getByMessenger');
    Route::delete('/settings/mailQueue', 'Api\MailQueueController@delete');
    Route::post('/settings/mailQueue', 'Api\MailQueueController@create');
    Route::put('/settings/mailQueue/send', 'Api\MailQueueController@send');

    Route::get('/dictionary/countries', 'Api\SettingsController@countries');
    Route::get('/dictionary/country/{country_code}', 'Api\SettingsController@country');
    Route::get('/dictionary/langs', 'Api\SettingsController@languages');
    Route::get('/dictionary/langsWithTranslation/', 'Api\SettingsController@languagesWithTranslation');
    Route::get('/dictionary/timeZones', 'Api\SettingsController@timezones');

    Route::get('/user/password/checkFin/{email}&{password}', 'Api\UserController@checkFinancialPassword');

    Route::get('/image/{key}&{language}', 'Api\ImageController@get');
    Route::get('/images/{language}', 'Api\ImageController@all');
    Route::post('/image', 'Api\ImageController@create');
    Route::put('/image', 'Api\ImageController@update');

    Route::get('/lang/{country_id}&{string_id}', 'Api\LanguageController@get');
    Route::get('/langs/{country_id}', 'Api\LanguageController@all');
    Route::post('/lang', 'Api\LanguageController@create');
    Route::put('/lang', 'Api\LanguageController@update');

    Route::get('/system/pin/{product_market_id}&{quantity}', 'Api\PinController@get');

    Route::get('/news/all/{language}', 'Api\NewsController@all');
    Route::get('/news/{id}', 'Api\NewsController@get');
    Route::get('/news/unread/{user_id}', 'Api\NewsController@unread');
    Route::get('/news/all/admin/{language}', 'Api\NewsController@allForAdmin');
    Route::delete('/news', 'Api\NewsController@delete');
    Route::post('/news', 'Api\NewsController@create');
    Route::put('/news', 'Api\NewsController@update');

    Route::get('/promotions/admin/{language}', 'Api\PromotionController@allForAdmin');
    Route::get('/promotions/{language}', 'Api\PromotionController@all');
    Route::get('/promotion/{id}', 'Api\PromotionController@get');
    Route::get('/promotions/unread/{id}', 'Api\PromotionController@unread');
    Route::post('/promotion', 'Api\PromotionController@create');
    Route::put('/promotion', 'Api\PromotionController@update');

    Route::get('/conferenceSchedule/{language}', 'Api\ConferenceController@get');
    Route::post('/conferenceSchedule', 'Api\ConferenceController@create');
    Route::put('/conferenceSchedule', 'Api\ConferenceController@update');

    Route::get('/marketingPlan/{language}', 'Api\MarketingController@get');
    Route::post('/marketingPlan', 'Api\MarketingController@create');
    Route::put('/marketingPlan', 'Api\MarketingController@update');

    Route::get('/careerPlan/{language}', 'Api\CareerPlanController@get');
    Route::post('/careerPlan', 'Api\CareerPlanController@create');
    Route::put('/careerPlan', 'Api\CareerPlanController@update');

    Route::get('/priceList/{language}', 'Api\PriceListController@get');
    Route::post('/priceList', 'Api\PriceListController@create');
    Route::put('/priceList', 'Api\PriceListController@update');

    Route::get('/instructions/{language}', 'Api\InstructionController@get');
    Route::post('/instructions', 'Api\InstructionController@create');
    Route::put('/instructions', 'Api\InstructionController@update');

    Route::get('/documents/{language}', 'Api\DocumentController@get');
    Route::post('/documents', 'Api\DocumentController@create');
    Route::put('/documents', 'Api\DocumentController@update');

    Route::get('/agreement/{language}', 'Api\AgreementController@get');
    Route::post('/agreement', 'Api\AgreementController@create');
    Route::put('/agreement', 'Api\AgreementController@update');

    Route::get('/products/{param}', 'Api\ProductController@get');
    Route::get('/products/all/', 'Api\ProductController@get');
    Route::get('/products/withVouchers/', 'Api\ProductController@get');
    Route::post('/products', 'Api\ProductController@create');
    Route::put('/products', 'Api\ProductController@update');

    Route::get('/vouchers/{user_id}', 'Api\VoucherController@getUserVouchers');
    Route::post('/voucher', 'Api\VoucherController@create');
    Route::get('/checkVoucherTransaction/{user_id}', 'Api\VoucherController@checkVoucherTransaction');

    Route::get('/resources/{language}', 'Api\ResourceController@all');
    Route::get('/resource/{id}', 'Api\ResourceController@get');
    Route::get('/resources/admin/{language}', 'Api\ResourceController@allForAdmin');
    Route::delete('/resource', 'Api\ResourceController@delete');
    Route::post('/resource', 'Api\ResourceController@create');
    Route::put('/resource', 'Api\ResourceController@update');

    Route::get('/charityReport/{language}', 'Api\CharityReportController@all');
    Route::post('/charityReport', 'Api\CharityReportController@create');
    Route::put('/charityReport', 'Api\CharityReportController@update');

    Route::get('/system/orderSumByPin/{pin_code}', 'Api\PinController@orderSumByPin');
    Route::get('/system/checkPin/{pin_code}', 'Api\PinController@checkPin');
    Route::post('/system/checkPinAndPhone', 'Api\PinController@checkPinAndPhoneForWellness');
    Route::get('/system/pin/{user_id}', 'Api\PinController@pinsHistory');
    Route::get('/system/createPin/{product}&{user_id}', 'Api\PinController@createForUser');

    Route::get('/transactions/money/{param}', 'Api\TransactionController@money');
    Route::delete('/transactions/money', 'Api\TransactionController@rollbackMoney');
    Route::post('/transactions/money', 'Api\TransactionController@addMoney');
    Route::post('/transactions/transferMoney', 'Api\TransactionController@sendMoney');

    Route::get('/transactions/withdrawal/{param}', 'Api\TransactionController@getWithdrawal');
    Route::delete('/transactions/withdrawal', 'Api\TransactionController@cancelWithdrawal');
    Route::post('/transactions/withdrawal', 'Api\TransactionController@createWithdrawal');
    Route::put('/transactions/withdrawal', 'Api\TransactionController@updateWithdrawal');

    Route::get('/transactions/charity/{param}', 'Api\TransactionController@charity');

    Route::get('/promo/turkey/{user_id?}', 'Api\PromoController@get');

});