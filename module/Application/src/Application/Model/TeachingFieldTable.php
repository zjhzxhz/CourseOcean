<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * 授课领域类的DAO(Data Access Object).
 * 
 * @author 谢浩哲 <zjhzxhz@gmail.com>
 */
class TeachingFieldTable
{
    /**
     * The Table Gateway object is intended to provide an object that 
     * represents a table in a database, and the methods of this object 
     * mirror the most common operations on a database table.
     * 
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * CourseTypeTable类的构造函数.
     * @param TableGateway $tableGateway 
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * 获取某个讲师用户的授课领域.
     * @param  int $uid - 讲师用户的用户唯一标识符
     * @return 某个讲师用户的授课领域
     */
    public function getTeachingFieldsOfUsingUid($uid)
    {
        $resultSet = $this->tableGateway->select(function(Select $select) use ($uid) {
            $select->join('itp_course_types', 
                          'itp_teaching_fields.course_type_id = itp_course_types.course_type_id');
            $select->where->equalTo('teacher_id', $uid);
        });
        return $resultSet;
    }

    public function updateTeachingField($teacherId, $courseTypeSlugs, $courseTypes)
    {
        $courseTypeSlugArray = split(',', $courseTypeSlugs);
        if ( !$this->deleteTeachingField($teacherId) ) {
            return false;
        }

        foreach ( $courseTypeSlugArray as $courseTypeSlug ) {
            if ( empty($courseTypeSlug) ) {
                break;
            }
            $courseTypeId  = $courseTypes[$courseTypeSlug]->courseTypeId;
            $teachingField = array(
                'teacher_id'        => $teacherId,
                'course_type_id'    => $courseTypeId,
            );
            $this->tableGateway->insert($teachingField);
        }
        return true;
    }

    /**
     * 删除某个讲师全部的授课领域.
     * @param  int $teacherId - 讲师用户的用户唯一标识符
     * @return 操作是否成功完成
     */
    public function deleteTeachingField($teacherId)
    {
        $this->tableGateway->delete(array(
            'teacher_id'    => $teacherId,
        ));
        return true;
    }
}