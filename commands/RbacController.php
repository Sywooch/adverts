<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;

        // Роли
        $guest  = $authManager->createRole('Guest');
        $user  = $authManager->createRole('User');
        $admin  = $authManager->createRole('Admin');

        // Разрешения
        $registration = $authManager->createPermission('user.auth.registration');
        $login = $authManager->createPermission('user.auth.login');
        $logout = $authManager->createPermission('user.auth.logout');
        $confirmRegistrationEmail = $authManager->createPermission('user.auth.confirm-registration-email');
        $changeOwnPassword = $authManager->createPermission('user.auth.change-own-password');
        $passwordRecovery = $authManager->createPermission('user.auth.password-recovery');

        $advertIndex = $authManager->createPermission('adverts.advert.index');
        $advertPublished = $authManager->createPermission('adverts.advert.index');
        $advertCreate = $authManager->createPermission('adverts.advert.create');
        $advertView = $authManager->createPermission('adverts.advert.view');
        $advertUpdate = $authManager->createPermission('adverts.advert.update');
        $advertDelete = $authManager->createPermission('adverts.advert.delete');
        $advert = $authManager->createPermission('adverts.advert.create');
        $advertCreate = $authManager->createPermission('adverts.advert.create');


        $error  = $authManager->createPermission('error');
        $signUp = $authManager->createPermission('sign-up');
        $index  = $authManager->createPermission('index');
        $view   = $authManager->createPermission('view');
        $update = $authManager->createPermission('update');
        $delete = $authManager->createPermission('delete');

        // Add permissions in Yii::$app->authManager
        $authManager->add($login);
        $authManager->add($logout);
        $authManager->add($error);
        $authManager->add($signUp);
        $authManager->add($index);
        $authManager->add($view);
        $authManager->add($update);
        $authManager->add($delete);


        // Add rule, based on UserExt->group === $user->group
        $userGroupRule = new UserGroupRule();
        $authManager->add($userGroupRule);

        // Add rule "UserGroupRule" in roles
        $guest->ruleName  = $userGroupRule->name;
        $brand->ruleName  = $userGroupRule->name;
        $talent->ruleName = $userGroupRule->name;
        $admin->ruleName  = $userGroupRule->name;

        // Add roles in Yii::$app->authManager
        $authManager->add($guest);
        $authManager->add($brand);
        $authManager->add($talent);
        $authManager->add($admin);

        // Add permission-per-role in Yii::$app->authManager
        // Guest
        $authManager->addChild($guest, $login);
        $authManager->addChild($guest, $logout);
        $authManager->addChild($guest, $error);
        $authManager->addChild($guest, $signUp);
        $authManager->addChild($guest, $index);
        $authManager->addChild($guest, $view);

        // BRAND
        $authManager->addChild($brand, $update);
        $authManager->addChild($brand, $guest);

        // TALENT
        $authManager->addChild($talent, $update);
        $authManager->addChild($talent, $guest);

        // Admin
        $authManager->addChild($admin, $delete);
        $authManager->addChild($admin, $talent);
        $authManager->addChild($admin, $brand);
    }
}