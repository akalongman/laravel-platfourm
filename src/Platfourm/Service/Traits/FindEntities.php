<?php

namespace Longman\Platfourm\Service\Traits;

use Longman\Platfourm\Repository\Criteria\SearchCriteria;

trait FindEntities
{

    public function run($columns = '*', array $options = null, $sortBy = null)
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

        $entityCollection = $this->repository->findBy($columns, $options, null, null, $sortBy);

        /*if (empty($entityCollection)) {
            throw new EntitiesNotFoundException;
        }*/

        return $this->parseResult($entityCollection);
    }

}
