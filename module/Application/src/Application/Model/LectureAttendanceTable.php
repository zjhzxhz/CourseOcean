<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * 课程会话参与类的DAO(Data Access Object).
 * 
 * @author 谢浩哲 <zjhzxhz@gmail.com>
 */
class LectureAttendanceTable
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
     * CourseTable类的构造函数.
     * @param TableGateway $tableGateway 
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * 获取某个课程会话用户的参与人数.
     * @param  int $lectureId - 课程会话的唯一标识符
     * @return 用户的参与人数
     */
    public function getCountUsingLectureId($lectureId)
    {
        $rowSet = $this->tableGateway->select(function (Select $select) use ($lectureId) {
            $select->where->equalTo('lecture_id', $lectureId);
            $select->columns(array(new \Zend\Db\Sql\Expression('SUM(`total_times`) as `total_times`')));
        });

        $lectureAttendance = $rowSet->current();
        if ( $lectureAttendance == null ) {
            return 0;
        }
        return $lectureAttendance->totalTimes;
    }

    /**
     * 获取某个用户参加培训的次数.
     * @param  int $uid - 用户的唯一标识符
     * @return 用户参加培训的次数
     */
    public function getCountUsingUid($uid)
    {
        $resultSet = $this->tableGateway->select(array(
            'uid'   => $uid,
        ));
        return $resultSet->count();
    }

    /**
     * 通过用户的唯一标识符和课程会话唯一标识符获取课程会话参与对象.
     * @param  int $uid       - 用户的唯一标识符
     * @param  int $lectureId - 课程会话的唯一标识符
     * @return 一个课程会话参与对象
     */
    public function getLectureAttendanceUsingUidAndLectureId($uid, $lectureId)
    {
        $rowSet = $this->tableGateway->select(array(
            'uid'           => $uid,
            'lecture_id'    => $lectureId,
        ));
        return $rowSet->current();
    }

    /**
     * 使用用户的唯一标识符获取课程会话参与对象.
     * @param  int $uid - 用户的唯一标识符
     * @return 一个课程会话参与对象
     */
    public function getLectureAttendanceUsingUid($uid, $offset, $limit)
    {
        $resultSet = $this->tableGateway->select(function (Select $select) use ($uid, $offset, $limit) {
            $select->join('itp_lectures',
                          'itp_lecture_attendance.lecture_id = itp_lectures.lecture_id');
            $select->join('itp_courses',
                          'itp_lectures.course_id = itp_courses.course_id');
            $select->join('itp_course_types',
                          'itp_courses.course_type_id = itp_course_types.course_type_id');
            $select->join('itp_teachers',
                          'itp_courses.teacher_id = itp_teachers.uid');
            $select->join('itp_comments',
                          'itp_lecture_attendance.lecture_id = itp_comments.lecture_id '.
                          'AND itp_lecture_attendance.uid = itp_comments.reviewer_uid', 
                          array('comment_ranking', 'comment_detail'), 
                          $select::JOIN_LEFT);
            $select->where->equalTo('itp_lecture_attendance.uid', $uid);
            $select->offset($offset);
            $select->limit($limit);
            $select->order('lecture_start_time DESC');
        });
        return $resultSet;
    }

    /**
     * 创建一个新课程会话参与对象.
     * @param  Array $lectureAttendance - 一个包含新课程会话参与信息的数组
     * @return 操作是否成功完成
     */
    public function createLectureAttendance($lectureAttendance)
    {
        $this->tableGateway->insert($lectureAttendance);
        return true;
    }

    /**
     * 更新课程会话参与对象的信息.
     * @param  Array $lectureAttendance - 一个包含新课程会话参与信息的数组
     * @return 操作是否成功完成
     */
    public function updateLectureAttendance($lectureAttendance)
    {
        $this->tableGateway->update($lectureAttendance, array(
            'uid'           => $lectureAttendance['uid'],
            'lecture_id'    => $lectureAttendance['lectureId'],
        ));
        return true;
    }

    /**
     * 通过用户的唯一标识符和课程会话唯一标识符删除课程会话参与对象.
     * @param  int $uid       - 用户的唯一标识符
     * @param  int $lectureId - 课程会话的唯一标识符
     * @return 操作是否成功完成
     */
    public function deleteLectureAttendance($uid, $lectureId)
    {
        $this->tableGateway->delete(array(
            'uid'           => $uid,
            'lecture_id'    => $lectureId,
        ));
        return true;
    }
}