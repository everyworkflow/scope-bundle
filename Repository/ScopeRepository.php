<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Repository;

use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;
use EveryWorkflow\ScopeBundle\Document\ScopeDocument;
use EveryWorkflow\ScopeBundle\Document\ScopeDocumentInterface;

#[RepositoryAttribute(documentClass: ScopeDocument::class, primaryKey: 'code')]
class ScopeRepository extends BaseDocumentRepository implements ScopeRepositoryInterface
{
    public function findByCode(string $code): ?ScopeDocumentInterface
    {
        return $this->findOne(['code' => $code]);
    }
}
