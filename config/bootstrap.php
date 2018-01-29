<?php

Yii::setAlias('@parcelDocuments',realpath(__DIR__.'/../web/uploads/parcel-documents'));

Yii::setAlias('@parcelDocumentsUrl','http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') .'/uploads/parcel-documents');

Yii::setAlias('@apiDelovod',dirname(__DIR__) . '/web/uploads/api-delovod');

Yii::setAlias('@myalias', '/path/to/myfolder');