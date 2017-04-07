<?php

namespace app\modules\business\controllers;

use app\controllers\BaseController;
use app\models\AlertForm;
use app\models\Menu;
use app\models\Users;
use app\models\Warehouse;
use app\modules\business\models\AddCell;
use app\modules\business\models\ImportTranslationForm;
use app\modules\business\models\TranslationForm;
use MongoDB\BSON\ObjectID;
use Yii;
use app\models\User;
use yii\helpers\Url;
use yii\web\UploadedFile;
use app\modules\business\models\PasswordForm;
use app\components\THelper;
use app\modules\business\models\ProfileForm;
use app\models\api;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\modules\business\models\AddAdminForm;
use yii\widgets\ActiveForm;

class SettingController extends BaseController {

    public function actionPassword()
    {
        $model = new PasswordForm();
        $request = Yii::$app->request;

        if (Yii::$app->request->isPost) {
            $result = api\User::changePassword($this->user->id, $request->post('currentPassword'), $request->post('newPassword'), 1);

            if ($result) {
                Yii::$app->session->setFlash('success', THelper::t('the_password_have_already_changed'));
            } else {
                Yii::$app->session->setFlash('danger', THelper::t('the_password_is_not_correct'));
            }

            return $this->refresh();
        }

        return $this->render('password', [
            'model' => $model,
        ]);
    }

    public function actionTranslation()
    {
        $request = Yii::$app->request;
        $translationForm = new TranslationForm();

        if ($request->isPost && $translationForm->load($request->post())) {

            $checkResult = api\Lang::get($translationForm->countryId, $translationForm->stringId);

            if ($checkResult) {
                $result = api\Lang::update(
                    $translationForm->id,
                    $translationForm->countryId,
                    $translationForm->stringId,
                    $translationForm->stringValue,
                    $translationForm->comment,
                    $translationForm->originalStringValue
                );
            } else {
                $result = api\Lang::add(
                    $translationForm->countryId,
                    $translationForm->stringId,
                    $translationForm->stringValue,
                    $translationForm->comment,
                    $translationForm->originalStringValue
                );
            }

            if ($result) {
                Yii::$app->session->setFlash('success', 'settings_translation_update_success');
            } else {
                Yii::$app->session->setFlash('danger', 'settings_translation_update_error');
            }

            $this->refresh();
        } else {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $translations = api\Lang::getAll($language);
            $languages = api\dictionary\Lang::all();

            return $this->render('translation', [
                'translations' => $translations,
                'language' => $language,
                'translationList' => $languages ? ArrayHelper::map($languages, 'alpha2', 'native') : []
            ]);
        }
    }

    public function actionExportTranslation()
    {
        $request = Yii::$app->request;
        $requestLanguage = $request->get('l');
        $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
        $translations = api\Lang::getAll($language);

        \moonland\phpexcel\Excel::export([
            'models' => $translations,
            'fileName' => 'export_' . $language,
            'columns' => [
                'id',
                'countryId',
                'stringId',
                'stringValue',
                'comment',
                'originalStringValue'
            ],
            'headers' => [
                'id' => THelper::t('settings_translation_n'),
                'countryId' => THelper::t('settings_translation_language'),
                'stringId' => THelper::t('settings_translation_id'),
                'stringValue' => THelper::t('settings_translation_value'),
                'comment' => THelper::t('settings_translation_comment'),
                'originalStringValue' => THelper::t('settings_translation_original_value')
            ],
        ]);
    }

    public function actionImportTranslation()
    {
        ini_set('memory_limit', '-1');
        $request = Yii::$app->request;
        $importTranslationForm = new ImportTranslationForm();
        if ($request->isAjax) {
            $requestLanguage = $request->get('l');
            $language = $requestLanguage ? $requestLanguage : Yii::$app->language;
            $importTranslationForm->lang = $language;

            return $this->renderAjax('import_translation', [
                'importTranslationForm' => $importTranslationForm
            ]);
        } else {
            if ($request->isPost) {
                $importTranslationForm->file = UploadedFile::getInstance($importTranslationForm, 'file');
                $fileName = $importTranslationForm->file->baseName . '.' . $importTranslationForm->file->extension;
                if ($importTranslationForm->file->saveAs('uploads/' . $fileName)) {
                    $xlsData = \moonland\phpexcel\Excel::import('uploads/' . $fileName, [
                        'setFirstRecordAsKeys' => false
                    ]);
                    if ($xlsData) {
                        foreach ($xlsData as $key => $xlsd) {
                            if ($key < 2 && ! $xlsd) {
                                continue;
                            } else {
                                $id                  = $xlsd['A'];
                                $countryId           = $xlsd['B'];
                                $stringId            = $xlsd['C'];
                                $stringValue         = $xlsd['D'];
                                $comment             = $xlsd['E'];
                                $originalStringValue = $xlsd['F'];

                                $checkResult = api\Lang::get($countryId, $stringId);

                                if ($checkResult && $id && $countryId && $stringValue) {
                                    api\Lang::update(
                                        $id,
                                        $countryId,
                                        $stringId,
                                        $stringValue,
                                        $comment,
                                        $originalStringValue
                                    );
                                } else {
                                    api\Lang::add(
                                        $countryId,
                                        $stringId,
                                        $stringValue,
                                        $comment,
                                        $originalStringValue
                                    );
                                }
                            }
                        }
                    }
                    Yii::$app->session->setFlash('success', 'settings_translation_import_success');
                } else {
                    Yii::$app->session->setFlash('danger', 'settings_translation_import_file_load_error');
                }

            }
            $this->redirect('/' . Yii::$app->language .'/business/setting/translation?l=' . $importTranslationForm->lang);
        }
    }

    public function actionEditTranslation()
    {
        $translationForm = new TranslationForm();

        $request = Yii::$app->request;

        //$translation = api\Lang::get($request->get('countryId'), $request->get('stringId'));

        /**
         * @todo delete this
         */
        $translation = '';
        $translations = api\Lang::getAll($request->get('countryId'));
        foreach ($translations as $t) {
            if ($t->stringId == $request->get('stringId')) {
                $translation = $t;
                break;
            }
        }

        if ($translation) {
            $translationForm->id = $translation->id;
            $translationForm->countryId = $translation->countryId;
            $translationForm->stringId = $translation->stringId;
            $translationForm->stringValue = $translation->stringValue;
            $translationForm->comment = $translation->comment;
            $translationForm->originalStringValue = $translation->originalStringValue;
        }

        return $this->renderAjax('edit_translation', [
            'translationForm' => $translationForm,
            'language' => Yii::$app->language
        ]);
    }

    public function actionWhitelabel()
    {

    }

    public function actionAdmin()
    {
        $request = Yii::$app->request;
        $addAdminForm = new AddAdminForm();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $addAdminForm->load($request->post());

            return ActiveForm::validate($addAdminForm);
        } else {
            if ($request->isPost && $addAdminForm->load($request->post())) {
                $user = api\User::get($addAdminForm->user);

                if ($user) {
                    $result = api\User::update($user->accountId, ['isAdmin' => 1]);
                } else {
                    $result = false;
                }

                if ($result) {
                    Yii::$app->session->setFlash('success', THelper::t('setting_admin_add_success'));
                } else {
                    Yii::$app->session->setFlash('danger', THelper::t('setting_admin_add_error'));
                }
            }
        }

        return $this->render('admin', [
            'admins' => api\User::admins()
        ]);
    }

    public function actionAdminRemove()
    {
        $request = Yii::$app->request;

        $user = api\User::get($request->get('u'));

        if ($user && $user->username != 'main') {
            $result = api\User::update($user->accountId, ['isAdmin' => 0]);
        } else {
            $result = false;
        }

        if ($result) {
            Yii::$app->session->setFlash('success', THelper::t('setting_admin_remove_success'));
        } else {
            Yii::$app->session->setFlash('danger', THelper::t('setting_admin_remove_error'));
        }

        $this->redirect('/' . Yii::$app->language .'/business/setting/admin');
    }

    public function actionAdminRules()
    {

        $model = Users::find()
            ->where(['isAdmin'=>1])
            ->andWhere(['!=','username','main'])
            ->all();

        $adminList = [];
        if(!empty($model)){
            foreach ($model as $item){
                $adminList[$item->_id->__toString()] = $item->username . '(' . $item->email . ')';
            }
        }


        return $this->render('admin_rules', [
            'adminList' => $adminList,
            'successText' => Yii::$app->getSession()->getFlash('success', '', true),
            'errorsText' => Yii::$app->getSession()->getFlash('errors', '', true)
        ]);
    }
    public function actionAdminRulesShow()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['userId'])) {
            $model = Users::find()->where(['_id'=> new ObjectID($request['userId'])])->one();

            $items = Menu::getItems();
            return $this->renderPartial('_admin_rules_show', [
                'items' => $items,
                'language' => Yii::$app->language,
                'model' => $model
            ]);
        }
    }

    public function actionAdminRulesSave()
    {
        $request = Yii::$app->request->post();

        if(!empty($request['id']) && !empty($request['rule'])){

            $model = Users::find()
                ->where(['_id'=> new ObjectID($request['id'])])
                ->one();

            $model->rules->showMenu = $request['rule']['showMenu'];
            $model->rules->edit = $request['rule']['edit'];

            $model->refreshFromEmbedded();


            if($model->save()){
                Yii::$app->session->setFlash('success', 'rules_admin_change_success');
            } else {
                Yii::$app->session->setFlash('danger', 'rules_admin_change_error');
            }

        }

        return $this->redirect('/'.Yii::$app->language.'/business/setting/admin-rules');
    }


    public function actionAddAdmin()
    {
        return $this->renderAjax('add_admin', [
            'language' => Yii::$app->language,
            'addAdminForm' => new AddAdminForm()
        ]);
    }

    public function actionUnioncell() {
        $session = Yii::$app->getSession();

        $linkedAccounts = api\user\Link::get($this->user->id, true);

        $model = new AddCell();

        $successText = $session->get('successText', '');
        $session->remove('successText');

        $errorText = $session->get('errorText', '');
        $session->remove('errorText');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $fromUser = api\User::get($model->login);
                $result = api\user\Link::link($fromUser->id, $this->user->id);
                if ($result == 'OK') {
                    $session->set('successText', THelper::t('link_success_msg'));
                } else if ($result == 'Conflict') {
                    $session->set('errorText', THelper::t('link_error_msg'));
                }
                $this->refresh();
            }
        }

        return $this->render('unioncell', [
            'linkedAccounts' => $linkedAccounts,
            'model' => $model,
            'successText' => $successText,
            'errorText' => $errorText
        ]);
    }

    public function actionDisconnect($id) {
        $fromUser = api\User::get($id);

        if (api\user\Link::unlink($fromUser->id, $this->user->id)) {
            Yii::$app->getSession()->set('successText', THelper::t('unlink_success_msg'));
        }

        return $this->redirect(['/business/setting/unioncell']);
    }

    public function actionProfile()
    {
        $request = Yii::$app->request;
        $model = new ProfileForm();

        if ($request->isPost) {

            $model->attributes = Yii::$app->request->post();

            if ($model->validate()) {
                $result = api\User::update($this->user->accountId, [
                    'username'      => strtolower($request->post('login')),
                    'fname'         => $request->post('name'),
                    'sname'         => $request->post('surname'),
                    'email'         => $request->post('email'),
                    'skype'         => $request->post('skype', ''),
                    'phone'         => $request->post('mobile'),
                    'phone2'        => $request->post('smobile', ''),
                    'country'       => $request->post('country'),
                    'address'       => $request->post('address'),
                    'city'          => $request->post('city'),
                    'state'         => $request->post('state'),
                    'birthday'      => date('Y-m-d', strtotime($request->post('birthday'))),
                    'showMobile'    => $request->post('showMobile', 0),
                    'showEmail'     => $request->post('showEmail', 0),
                    'showName'      => $request->post('showName', 0),
                    'site'          => $request->post('site', ''),
                    'vk'            => $request->post('vk', ''),
                    'fb'            => $request->post('fb', ''),
                    'odnoklassniki' => $request->post('odnoklassniki', ''),
                    'youtube'       => $request->post('youtube', '')
                ]);

                if ($result) {
                    Yii::$app->session->setFlash('success', THelper::t('your_profile_has_been_saved_successfully'));
                } else {
                    Yii::$app->session->setFlash('danger', THelper::t('your_profile_has_not_been_saved'));
                }
            } else {
                foreach ($model->getErrors() as $error) {
                    Yii::$app->session->setFlash('danger', $error);
                }
            }

            return $this->refresh();
        }

        return $this->render('profile', [
            'user' => $this->user,
            'model' => $model,
            'countries' => api\dictionary\Country::all()
        ]);

    }

    public function actionChangeImg() {
        $session = Yii::$app->session;
        $model = new ProfileForm();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                if (! $model->avatar) {
                    return $this->redirect('profile');
                }

                if ($model->avatar) {
                    if (!$model->afterSave($this->user->id)) {
                        Yii::$app->session->setFlash('danger', THelper::t('avatar_minimum_size_should_be_200_200_dimension'));
                        return $this->redirect('profile');
                    }

                    if (!$model->avatar->extension) {
                        $extension = 'jpg';
                    } else {
                        $extension = $model->avatar->extension;
                    }

                    $model->avatar = base64_encode($model->avatar->baseName) . '.' . $extension;
                    $session->set('avatar', $model->avatar);

                    $path = "uploads/{$this->user->id}/{$model->avatar}";
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

                    User::updateInfoApi($this->user->accountId, array("avatar" => $base64));
                }
            }

            return $this->redirect('profile');
        }

        return $this->renderAjax('modal', [
            'model' => $model
        ]);
    }

    public function actionAlert() {
        $alertForm = new AlertForm();

        $timezones = api\dictionary\TimeZones::all();

        if ($alertForm->load(Yii::$app->request->post()) && $alertForm->validate()) {
            $timezone = Yii::$app->request->post('timezone', '');
            if ($timezone) {
                $timezone = str_replace('|', '"', $timezone);
            }

            $phoneTelegram = str_replace('+', '', trim($alertForm->phoneTelegram));
            $phoneTelegram = $phoneTelegram ? '+' . $phoneTelegram : '';
            $phoneViber    = str_replace('+', '', trim($alertForm->phoneViber));
            $phoneViber    = $phoneViber ? '+' . $phoneViber : '';
            $phoneWhatsApp = str_replace('+', '', trim($alertForm->phoneWhatsApp));
            $phoneWhatsApp = $phoneWhatsApp ? '+' . $phoneWhatsApp : '';
            $phoneFB       = str_replace('+', '', trim($alertForm->phoneFB));
            $phoneFB       = $phoneFB ? '+' . $phoneFB : '';

            if (api\User::update($this->user->accountId, [
                'notifyAboutJoinPartner'    => $alertForm->notifyAboutJoinPartner,
                'notifyAboutReceiptsMoney'  => $alertForm->notifyAboutReceiptsMoney,
                'notifyAboutReceiptsPoints' => $alertForm->notifyAboutReceiptsPoints,
                'notifyAboutEndActivity' => $alertForm->notifyAboutEndActivity,
                'notifyAboutOtherNews'   => $alertForm->notifyAboutOtherNews,
                'phoneTelegram'          => $phoneTelegram,
                'phoneViber'             => $phoneViber,
                'phoneWhatsApp'          => $phoneWhatsApp,
                'phoneFB'                => $phoneFB,
                'selectedLang'           => $alertForm->selectedLang,
                'timeZone'               => $timezone
            ])) {
                $this->refresh();
            }
        } else {
            $alertForm->notifyAboutJoinPartner = $this->user->settings->notifyAboutJoinPartner;
            $alertForm->notifyAboutReceiptsMoney = $this->user->settings->notifyAboutReceiptsMoney;
            $alertForm->notifyAboutReceiptsPoints = $this->user->settings->notifyAboutReceiptsPoints;
            $alertForm->notifyAboutEndActivity = $this->user->settings->notifyAboutEndActivity;
            $alertForm->notifyAboutOtherNews = $this->user->settings->notifyAboutOtherNews;
            $alertForm->phoneTelegram = $this->user->settings->phoneTelegram;
            $alertForm->phoneViber = $this->user->settings->phoneViber;
            $alertForm->phoneWhatsApp = $this->user->settings->phoneWhatsApp;
            $alertForm->phoneFB = $this->user->settings->phoneFB;
            $alertForm->selectedLang = $this->user->settings->selectedLang;
        }

        return $this->render('alert', [
            'user' => $this->user,
            'model' => $alertForm,
            'languages' => ArrayHelper::map(api\dictionary\Lang::supported(), 'alpha2', 'native'),
            'timezones' => $timezones
        ]);
    }

    /**
     * Change user layout
     *
     * @return array
     */
    public function actionChangeLayout()
    {
        if (Yii::$app->request->isAjax) {
            $result = api\User::update($this->user->accountId, ['layout' => $this->user->layout == 1 ? 0 : 1]);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => $result];
        }
    }

    public function actionSaveLatLng()
    {
        if (Yii::$app->request->isAjax) {
            $lat = Yii::$app->request->post('lat');
            $lng = Yii::$app->request->post('lng');
            $accountId = Yii::$app->request->post('accountId');

            api\User::update($accountId, [
                'onMapX' => $lat,
                'onMapY' => $lng
            ]);
        }
    }
    
    
    public function actionWarehouse()
    {
        $request =  Yii::$app->request->post();
        if(!empty($request['title'])){
            $model = new Warehouse();
            $model->title = $request['title'];

            if($model->save()){
                Yii::$app->session->setFlash('alert' ,['typeAlert'=>'success', 'message'=>'add_new_warehouse']);
            } else {
                Yii::$app->session->setFlash('alert' ,['typeAlert'=>'danger', 'message'=>'did_not_new_warehouse']);
            }
            return $this->redirect(Url::to(''),'301');
        }

        $alert = Yii::$app->session->getFlash('alert', '', true);

        $infoWarehouse = Warehouse::find()->all();
        
        return $this->render('warehouse',[
            'infoWarehouse' => $infoWarehouse,
            'alert' => $alert
        ]);
    }

    public function actionWarehouseAdminSave()
    {
        $request = Yii::$app->request->post();
        $infoWarehouse = '';

        if($request){
            $infoWarehouse = Warehouse::find()
                ->where(['_id'=>new ObjectID($request['id'])])
                ->one();


            $infoWarehouse->idUsers = [];
            if(!empty($request['idUsers'])){
                foreach($request['idUsers'] as $item){
                    $infoWarehouse->idUsers[] = $item;
                }
            }


            if($infoWarehouse->save()){

                header('Content-Type: text/html; charset=utf-8');
                echo "<xmp>";
                print_r($infoWarehouse);
                echo "</xmp>";
                die();
//                $infoProduct = Products::find()
//                    ->where(['_id'=>new ObjectID($request['id'])])
//                    ->one();
//
//                $error = [
//                    'type' => 'success',
//                    'message' => 'the changes are saved',
//                ];

            } else {
                $error = [
                    'type' => 'success',
                    'message' => 'the changes are not saved',
                ];
            }


        } else {
//            $error = [
//                'type' => 'success',
//                'message' => 'the changes are not saved',
//            ];
        }



//        return $this->renderPartial('_product-set-update',[
//            'language'      =>  Yii::$app->language,
//            'infoProduct'   =>  $infoProduct,
//            'error'         =>  $error
//        ]);
    }
}

function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);

    return $output_file;
}
