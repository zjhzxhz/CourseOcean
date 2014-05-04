<?php

namespace Accounts;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Accounts\Model\User;
use Accounts\Model\UserTable;
use Accounts\Model\UserGroup;
use Accounts\Model\UserGroupTable;
use Accounts\Model\Person;
use Accounts\Model\PersonTable;
use Accounts\Model\Position;
use Accounts\Model\PositionTable;
use Accounts\Model\Teacher;
use Accounts\Model\TeacherTable;
use Accounts\Model\TeachingField;
use Accounts\Model\TeachingFieldTable;
use Solutions\Model\CourseType;
use Solutions\Model\CourseTypeTable;
use Accounts\Model\Company;
use Accounts\Model\CompanyTable;
use Accounts\Model\EmailValidation;
use Accounts\Model\EmailValidationTable;
use Solutions\Model\LectureAttendance;
use Solutions\Model\LectureAttendanceTable;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return an array of factories that are all merged together by the 
     *         ModuleManager before passing to the ServiceManager
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Accounts\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('itp_users', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\UserGroupTable' => function($sm) {
                    $tableGateway = $sm->get('UserGroupTableGateway');
                    $table = new UserGroupTable($tableGateway);
                    return $table;
                },
                'UserGroupTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserGroup());
                    return new TableGateway('itp_user_groups', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\PersonTable' => function($sm) {
                    $tableGateway = $sm->get('PersonTableGateway');
                    $table = new PersonTable($tableGateway);
                    return $table;
                },
                'PersonTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Person());
                    return new TableGateway('itp_people', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\PositionTable' => function($sm) {
                    $tableGateway = $sm->get('PositionTableGateway');
                    $table = new PositionTable($tableGateway);
                    return $table;
                },
                'PositionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Position());
                    return new TableGateway('itp_positions', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\TeacherTable' => function($sm) {
                    $tableGateway = $sm->get('TeacherTableGateway');
                    $table = new TeacherTable($tableGateway);
                    return $table;
                },
                'TeacherTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Teacher());
                    return new TableGateway('itp_teachers', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\TeachingFieldTable' => function($sm) {
                    $tableGateway = $sm->get('TeachingFieldTableGateway');
                    $table = new TeachingFieldTable($tableGateway);
                    return $table;
                },
                'TeachingFieldTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new TeachingField());
                    return new TableGateway('itp_teaching_field', $dbAdapter, null, $resultSetPrototype);
                },
                'Solutions\Model\CourseTypeTable' => function($sm) {
                    $tableGateway = $sm->get('CourseTypeTableGateway');
                    $table = new CourseTypeTable($tableGateway);
                    return $table;
                },
                'CourseTypeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CourseType());
                    return new TableGateway('itp_course_types', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\CompanyTable' => function($sm) {
                    $tableGateway = $sm->get('CompanyTableGateway');
                    $table = new CompanyTable($tableGateway);
                    return $table;
                },
                'CompanyTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Company());
                    return new TableGateway('itp_companies', $dbAdapter, null, $resultSetPrototype);
                },
                'Accounts\Model\EmailValidationTable' => function($sm) {
                    $tableGateway = $sm->get('EmailValidationTableGateway');
                    $table = new EmailValidationTable($tableGateway);
                    return $table;
                },
                'EmailValidationTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new EmailValidation());
                    return new TableGateway('itp_email_validation', $dbAdapter, null, $resultSetPrototype);
                },
                'Solutions\Model\LectureAttendanceTable' => function($sm) {
                    $tableGateway = $sm->get('LectureAttendanceTableGateway');
                    $table = new LectureAttendanceTable($tableGateway);
                    return $table;
                },
                'LectureAttendanceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new LectureAttendance());
                    return new TableGateway('itp_lecture_attendance', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
