<?php

namespace frontend\modules\mission\forms;


use common\models\Employee;
use common\models\MissionEmployee;
use yii\base\Model;
use Yii;

class MissionMemberForm extends Model
{

    public $arr;
    public $mission;

    public function rules()
    {
        return [
            [['iogv_id', 'employee_id', 'role'], 'integer'],
        ];
    }


    public function init()
    {
        parent::init();

        $missionMembers = $this->mission->missionEmployees;
        if($missionMembers){
            foreach ($missionMembers as $item){
                $member = Employee::find()->where(['id' => $item->employee_id])->one();

                if($member){
                    $cell = [];
                    $cell['iogv_id'] = $member->organization_id;
                    $cell['employee_id'] = $member->id;
                    $cell['role'] = $item->role;

                    $this->arr[] = $cell;
                }
            }
        }
    }



    public function upload($post)
    {

        $errors = [];
        $this->arr = $post['arr'];

        //удаляем старые записи
        $olds = MissionEmployee::find()->where(['mission_id' => $this->mission->id])->all();

        foreach ($olds as $old){
            $old->delete();
        }

        //добавляем новые
        foreach ($this->arr as $item){
            $me = new MissionEmployee();
            $me->mission_id = $this->mission->id;
            $me->employee_id = $item['employee_id'];
            $me->role = $item['role'];
            $me->save();
            if($me->hasErrors()){
                $errors[] = $me->getFirstErrors();
            }
        }

        return $errors;
    }
}