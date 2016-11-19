<?php

namespace app\modules\users\controllers;

use Yii;

use app\modules\users\models\UserVisitLog;
use app\modules\users\models\search\UserVisitLogSearch;

/**
 * Class UserVisitLogController
 * @package app\modules\users\controllers
 */
class UserVisitLogController extends \app\modules\users\components\Controller
{
    /**
     * @var UserVisitLog
     */
    public $modelClass = 'app\modules\users\models\UserVisitLog';

    /**
     * @var UserVisitLogSearch
     */
    public $modelSearchClass = 'app\modules\users\models\search\UserVisitLogSearch';

    /**
     * @var 
     */
    public $enableOnlyActions = ['index', 'view'];
}
