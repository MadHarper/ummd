<?php

namespace frontend\modules\catalog\services;

use common\models\Organization;
use common\models\Employee;


/**
 * CountryController implements the CRUD actions for Country model.
 */
class OrganizationUpdateService
{

    private $oldOrganization;
    private $newOrganization;

    public function __construct(Organization $oldOrganization)
    {
        $this->oldOrganization = $oldOrganization;
    }


    public function update()
    {
        $this->updateOrganization();
        $this->updateLinkedEmployee();

        return $this->newOrganization->id;
    }


    private function updateOrganization()
    {
        $newModel = new Organization();
        $newModel->name         = $this->oldOrganization->name;
        $newModel->contact      = $this->oldOrganization->contact;
        $newModel->country_id   = $this->oldOrganization->country_id;
        $newModel->iogv         = $this->oldOrganization->iogv;
        $newModel->history      = false;
        $newModel->prev_id      = $this->oldOrganization->id;
        $newModel->main_id      = $this->oldOrganization->main_id;
        $newModel->save();

        $oldModel = Organization::findOne($this->oldOrganization->id);
        $oldModel->history = true;
        $oldModel->save();

        $this->newOrganization = $newModel;
    }

    private function updateLinkedEmployee()
    {
        $employees = Employee::find()->where(['organization_id' => $this->oldOrganization->id])->all();

        if($employees){
            foreach ($employees as $emp){
                $emp->organization_id = $this->newOrganization->id;
                $emp->save();
            }
        }
    }
}