<?php

namespace Courses\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\BasePath;

/**
 * 
 * @author Xie Haozhe <zjhzxhz@gmail.com>
 */
class NewsController extends AbstractActionController
{
	/**
	 * Default method to call in the controller.
	 * 
	 * @return a ViewModel object which contains HTML content
	 */
	public function pageAction()
    {
        $NUMBER_OF_NEWS_PER_PAGE = 10;
        $pageNumber              = $this->params()->fromRoute('param', 1);
        return array(
            'news'              => $this->getAllNews($pageNumber, $NUMBER_OF_NEWS_PER_PAGE),
            'currentPageNumber' => $pageNumber,
            'numberOfPages'     => $this->getNumberOfPages($NUMBER_OF_NEWS_PER_PAGE),
            'newsCategories'    => $this->getAllNewsCategories(),
        );
    }

    /**
     * Get all news in the database.
     * @param  int $pageNumber - current number of the page
     * @param  int $limit - max number of news in a page
     * @return an array which contains all information of news
     */
    private function getAllNews($pageNumber, $limit)
    {
        $sm             = $this->getServiceLocator();
        $newsTable      = $sm->get('Courses\Model\NewsTable');

        return $newsTable->fetchAll($pageNumber, $limit);
    }

    /**
     * Get total number of pages for the news.
     * @param  int $limit - max number of news in a page
     * @return an integer which stands for the total number of pages for 
     *         the news
     */
    private function getNumberOfPages($limit)
    {
        $sm                 = $this->getServiceLocator();
        $newsTable          = $sm->get('Courses\Model\NewsTable');
        $numberOfNews       = $newsTable->getNumberOfNews();

        return ceil( $numberOfNews / $limit );
    }

    /**
     * Get all information of news categories in the database.
     * @return an array of objects of NewsCategories which contains 
     *         informtaion of catelogy of news in the database
     */
    private function getAllNewsCategories()
    {
        $sm                 = $this->getServiceLocator();
        $newsCatelogyTable  = $sm->get('Courses\Model\NewsCategoryTable');

        return $newsCatelogyTable->fetchAll();
    }

    /**
     * Display detail information of a certain lecture.
     * @param  int $courseID - the unique id of the course.
     * @return a ViewModel object which contains HTML content
     */
    public function detailAction()
    {
        /*$lectureID      = $this->params()->fromRoute('param');
        $lectureInfo    = $this->getLectureDetailInfo($lectureID);
        $uid            = $this->isLogined();

        if ( $lectureInfo == null ) {
            return $this->notFoundAction();
        }
        return array(
            'lecture'       => $lectureInfo,
            'isLogined'     => $uid,
            'isAttended'    => $this->isAttended($uid, $lectureInfo['lecture_id']),
            'teacher'       => $this->getTeacherInfo($lectureInfo['uid']),
        );*/
    }
}
