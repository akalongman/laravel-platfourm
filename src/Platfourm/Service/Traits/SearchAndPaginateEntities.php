<?php

namespace Longman\Platfourm\Service\Traits;

use Longman\Platfourm\Repository\Criteria\SearchCriteria;

trait SearchAndPaginateEntities
{

    public function run($columns = '*', array $options = null, $perPage = null, $page = 1, $sortBy = null)
    {
        $this->checkRepository();

        if (!empty($options['search'])) {
            $searchFields   = !empty($options['searchFields']) ? $options['searchFields'] : null;
            $searchCriteria = new SearchCriteria($options['search'], $searchFields);
            $this->repository->pushCriteria($searchCriteria);
        }

        if (!empty($options['trashed'])) {
            $this->repository = $this->repository->withTrashed();
        }

        unset($options['search'], $options['searchFields'], $options['trashed']);

        $entityCollection = $this->repository->findBy($columns, $options, $perPage, $page, $sortBy);

        /*if (empty($entityCollection)) {
            throw new EntitiesNotFoundException;
        }*/

        return $this->parseResult($entityCollection);
    }

}
