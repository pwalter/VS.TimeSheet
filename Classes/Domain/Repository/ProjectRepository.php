<?php
namespace VS\TimeSheet\Domain\Repository;

use TYPO3\FLOW3\Annotations as FLOW3;
/**
 * @FLOW3\Scope("singleton")
 */
class ProjectRepository extends \TYPO3\FLOW3\Persistence\Repository {
    /**
     * @param $number
     * @return object
     */
    public function findSingleByCustomerNumber($number) {
        $query = $this->createQuery();
        return $query->matching(
            $query->equals('customer.number', $number)
        )
        ->execute()
        ->getFirst();
    }
}
