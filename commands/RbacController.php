<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

/**
 * php yii rbac/init
 */
class RbacController extends Controller
{

    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Создаем роли руководителя и сотрудника
        $manager = $auth->createRole('manager');
        $manager->description = 'Руководитель';

        $user = $auth->createRole('user');
        $user->description = 'Сотрудник';

        $auth->add($manager);
        $auth->add($user);

        // Разрешение на утверждение отпуска
        $holidayApprove = $auth->createPermission('holidayApprove');
        $holidayApprove->description = 'Утверждение отпуска';
        $auth->add($holidayApprove);

        // Разрешение на управление сотрудниками
        $editUsers = $auth->createPermission('editUsers');
        $editUsers->description = 'Управление сотрудниками';
        $auth->add($editUsers);

        // Даем менеджеру сначала базовые права как у сотрудника
        $auth->addChild($manager, $user);

        // Даем менеджеру права на управление сотрудниками и утверждение отпусков
        $auth->addChild($manager, $editUsers);
        $auth->addChild($manager, $holidayApprove);

        // Назначаем роль manager пользователю с ID1
        $auth->assign($manager, 1);
    }
}