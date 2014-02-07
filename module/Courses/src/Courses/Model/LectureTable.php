<?php

namespace Courses\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

/**
 * The table gateway of the lectures table.
 * 
 * @author Xie Haozhe <zjhzxhz@gmail.com>
 */
class LectureTable
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
     * The contructor of the UserTable class.
     * @param TableGateway $tableGateway 
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Get all records from the courses table by pagination.
     * @param  int $pageNumber - current number of the page
     * @param  int $limit - max number of courses in a page
     * @return an object which is an instance of ResultSet, which contains
     *         data of all courses.
     */
    public function fetchAll($pageNumber, $limit)
    {
        $offset     = ( $pageNumber - 1 ) * $limit;
        $resultSet  = $this->tableGateway->select(function (Select $select) use ($offset, $limit) {
            $select->join('itp_courses',
                          'itp_lectures.course_id = itp_courses.course_id');
            $select->join('itp_course_types',
                          'itp_courses.course_type_id = itp_course_types.course_type_id');
            $select->join('itp_teacher', 
                          'itp_courses.uid = itp_teacher.uid');
            $select->order('lecture_id DESC');
            $select->offset($offset);
            $select->limit($limit);
        });
        return $resultSet;
    }

    /**
     * Get number of records in the courses table.
     * @return an integer which stands for the number of records in the lecture
     *         table
     */
    public function getNumberOfLectures()
    {
        return $this->tableGateway->select()->count();
    }

    /**
     * Get general information of a certain lecture.
     * @param  int $lectureID - the unique id of the lecture
     * @return an array which contains general information of a cerain lecture
     */
    public function getGeneralInfo($lectureID)
    {
        $rowset     = $this->tableGateway->select(function (Select $select) use ($lectureID) {
            $select->join('itp_courses',
                          'itp_lectures.course_id = itp_courses.course_id');
            $select->join('itp_course_types',
                          'itp_courses.course_type_id = itp_course_types.course_type_id');
            $select->where->equalTo('lecture_id', $lectureID);
        });
        return $rowset->current();
    }
}