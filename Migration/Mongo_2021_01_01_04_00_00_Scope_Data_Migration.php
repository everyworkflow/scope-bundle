<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Migration;

use EveryWorkflow\MongoBundle\Support\MigrationInterface;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTraitInterface;
use EveryWorkflow\ScopeBundle\Document\ScopeDocument;
use EveryWorkflow\ScopeBundle\Document\ScopeDocumentInterface;
use EveryWorkflow\ScopeBundle\Repository\ScopeRepositoryInterface;

class Mongo_2021_01_01_04_00_00_Scope_Data_Migration implements MigrationInterface
{
    protected const INIT_SCOPE = [
        ScopeDocumentInterface::ADMIN_SCOPE_CODE,
        ScopeDocumentInterface::FRONTEND_DEFAULT_SCOPE_CODE,
    ];

    /**
     * @var ScopeRepositoryInterface
     */
    private ScopeRepositoryInterface $scopeRepository;


    public function __construct(ScopeRepositoryInterface $scopeRepository)
    {

        $this->scopeRepository = $scopeRepository;
    }


    public function migrate(): bool
    {
        $indexKeys = [];
        foreach ($this->scopeRepository->getIndexNames() as $key) {
            $indexKeys[$key] = 1;
        }
        $this->scopeRepository->getCollection()->createIndex($indexKeys, ['unique' => true]);

        foreach (self::INIT_SCOPE as $scope) {
            /** @var ScopeDocument $scopeDocument */
            $scopeDocument = $this->scopeRepository->getNewDocument();
            $scopeDocument->setCode($scope)
                ->setName(ucfirst($scope))
                ->setSortOrder(0)
                ->setStatus(StatusHelperTraitInterface::STATUS_ENABLE);
            $this->scopeRepository->save($scopeDocument);
        }

        return self::SUCCESS;
    }

    public function rollback(): bool
    {
        $this->scopeRepository->getCollection()->drop();

        return self::SUCCESS;
    }
}
