<?php
/**
 * Created by PhpStorm.
 * User: aleksey.kolesnikov
 * Date: 22.03.2018
 * Time: 13:09
 */

namespace frontend\core\helpers;


use common\models\Agreement;
use common\models\Mission;

class DocTypeHelper
{

    const PARSING_EXTENTION = [
        'docx',
        'doc'
    ];


    public static function getTypeNameByClass($modelClass)
    {
        switch ($modelClass){
            case Agreement::className():
                return "Соглашение";
                break;
            case Mission::className():
                return "Командировка";
                break;
            default:
                return "";
        }
    }
}