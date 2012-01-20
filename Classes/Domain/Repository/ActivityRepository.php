<?php
namespace VS\TimeSheet\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;
/**
 * @FLOW3\Scope("singleton")
 */
class ActivityRepository extends \TYPO3\FLOW3\Persistence\Repository {
    public function findByAccount(\TYPO3\FLOW3\Security\Account $account) {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('account', $account),
                $query->equals('deleted', FALSE)
            )
        )
        ->setOrderings(array(
            'date' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING)
        )
        ->execute();
    }

    public function findBetweenDates(\TYPO3\FLOW3\Security\Account $account, \DateTime $after, \DateTime $before) {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('account', $account),
                $query->equals('deleted', FALSE),
                $query->greaterThanOrEqual('date', $after),
                $query->lessThanOrEqual('date', $before)
            )
        )
        ->setOrderings(array(
            'date' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING)
        )
        ->execute();
    }

    /**
     * @param \TYPO3\FLOW3\Security\Account $account
     * @param \DateTime $date
     * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
     */
    public function findByDate(\TYPO3\FLOW3\Security\Account $account, \DateTime $date) {
        $after = clone $date;
        $after->setTime(0,0,0);

        $before = clone $date;
        $before->setTime(23,59,59);

        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('account', $account),
                $query->equals('deleted', FALSE),
                $query->greaterThanOrEqual('date', $after),
                $query->lessThanOrEqual('date', $before)
            )
        )
        ->setOrderings(array(
            'date' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING)
        )
        ->execute();
    }

    public function findAllByProject(\VS\TimeSheet\Domain\Model\Project $project, \DateTime $after, \DateTime $before) {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('task.project', $project),
                $query->equals('deleted', FALSE),
                $query->greaterThanOrEqual('date', $after),
                $query->lessThanOrEqual('date', $before)
            )
        )
        ->setOrderings(array(
            'date' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING)
        )
        ->execute();
    }

    public function findAllByTask(\VS\TimeSheet\Domain\Model\Task $task, \DateTime $after, \DateTime $before) {
        $query = $this->createQuery();
        return $query->matching(
            $query->logicalAnd(
                $query->equals('task', $task),
                $query->equals('deleted', FALSE),
                $query->greaterThanOrEqual('date', $after),
                $query->lessThanOrEqual('date', $before)
            )
        )
        ->setOrderings(array(
            'date' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING)
        )
        ->execute();
    }
}
