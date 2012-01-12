<?php
namespace VS\TimeSheet\Controller\Admin;

/*                                                                        *
 * This script belongs to the FLOW3 package "VS.TimeSheet".               *
 *                                                                        *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the VS.TimeSheet package 
 *
 * @FLOW3\Scope("singleton")
 */
class TagController extends \VS\TimeSheet\MVC\Controller\BasicController {

    /**
     * @FLOW3\Inject
     * @var VS\TimeSheet\Domain\Repository\TagRepository
     */
    protected $tagRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('tags', $this->tagRepository->findAll());
	}

    public function newAction() {

    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Tag $tag
     * @return void
     */
    public function createAction(\VS\TimeSheet\Domain\Model\Tag $tag) {
        $this->tagRepository->add($tag);
        $this->redirect('index');
    }
}

?>