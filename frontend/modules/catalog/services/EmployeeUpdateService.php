<?php

namespace frontend\modules\catalog\services;

use common\models\Employee;


/**
 * CountryController implements the CRUD actions for Country model.
 */
class EmployeeUpdateService
{

    private $oldEmployee;

    public function __construct(Employee $oldEmployee)
    {
        $this->oldEmployee = $oldEmployee;
    }


    public function update()
    {
        $newModel = new Employee();
        $newModel->fio              = $this->oldEmployee->fio;
        $newModel->position         = $this->oldEmployee->position;
        $newModel->organization_id  = $this->oldEmployee->organization_id;
        $newModel->prev_id          = $this->oldEmployee->id;
        $newModel->main_id          = $this->oldEmployee->main_id;
        $newModel->save();

        $oldModel = Employee::findOne($this->oldEmployee->id);
        $oldModel->history = true;
        $oldModel->visible = false;
        $oldModel->save();

        return $newModel;
    }
}
