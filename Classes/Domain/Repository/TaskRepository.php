<?php
namespace VS\TimeSheet\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;
/**
 * @FLOW3\Scope("singleton")
 */
class TaskRepository extends \TYPO3\FLOW3\Persistence\Repository {

    public function findAllExceptThis(\VS\TimeSheet\Domain\Model\Task $task) {
        $query = $this->createQuery();
        $tasks = $query
            ->execute()
            ->toArray();

        unset($tasks[array_search($task, $tasks)]);

        return $task;
    }

    /**
     * @param \VS\TimeSheet\Domain\Model\Project $project
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findAllActiveByProject(\VS\TimeSheet\Domain\Model\Project $project) {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('active', TRUE),
                $query->equals('date', $project)
            )
        )
        ->setOrderings(array(
            'name' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING)
        )
        ->execute();
    }
}
