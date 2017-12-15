<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создадим роли админа, модератора(квс), пользователя
        $admin = $auth->createRole('admin');
        $moderator = $auth->createRole('moderator');
        $user = $auth->createRole('user');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($moderator);
        $auth->add($user);

        // Создаем разрешения.
        // Просмотр справочников
        $viewDirectory = $auth->createPermission('viewDirectory');
        $viewDirectory->description = 'Просмотр справочников';

        // просмотр и редактирование СВОИХ соглашений
        $changeOwnAgrements = $auth->createPermission('changeOwnAgrements');
        $changeOwnAgrements->description = 'Просмотр своих соглашений';

        // просмотр и редактирование ВСЕХ соглашений
        $changeAllAgrements = $auth->createPermission('changeAllAgrements');
        $changeAllAgrements->description = 'Просмотр всех соглашений';


        // Запишем эти разрешения в БД
        $auth->add($viewDirectory);
        $auth->add($changeOwnAgrements);
        $auth->add($changeAllAgrements);

        // Теперь добавим наследования.
        // Для роли user мы добавим разрешение changeOwnAgrements
        // Для роли moderator мы добавим разрешение changeAllAgrements,
        // Модератор наследуется от юзера, а админ от модератора
        // а для админа добавим еще добавим собственное разрешение viewDirectory

        $auth->addChild($user,$changeOwnAgrements);
        $auth->addChild($moderator,$changeAllAgrements);

        $auth->addChild($moderator, $user);
        $auth->addChild($admin, $moderator);

        // Еще админ имеет собственное разрешение - «Просмотр справочников»
        $auth->addChild($admin, $viewDirectory);

        // Назначаем роль admin пользователю с ID 1
        //$auth->assign($admin, 1);

        // Назначаем роль editor пользователю с ID 2
        //$auth->assign($editor, 2);
    }
}