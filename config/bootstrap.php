<?php

define('HTTP_TYPE',  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://'));

define('HTTP_HOST',  (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'));

Yii::setAlias('@parcelDocuments',realpath(__DIR__.'/../web/uploads/parcel-documents'));

Yii::setAlias('@parcelDocumentsUrl',HTTP_TYPE . HTTP_HOST .'/uploads/parcel-documents');

Yii::setAlias('@apiDelovod',dirname(__DIR__) . '/web/uploads/api-delovod');

Yii::setAlias('@myalias', '/path/to/myfolder');