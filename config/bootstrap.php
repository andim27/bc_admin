<?php

Yii::setAlias('@parcelDocuments', dirname(dirname(__DIR__)) . '/web/uploads/parcel-documents');
Yii::setAlias('@parcelDocumentsUrl','http://' . $_SERVER['HTTP_HOST'] .'/uploads/parcel-documents');