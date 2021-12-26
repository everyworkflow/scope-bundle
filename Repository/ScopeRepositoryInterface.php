<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Repository;

use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepositoryInterface;
use EveryWorkflow\ScopeBundle\Document\ScopeDocumentInterface;

interface ScopeRepositoryInterface extends BaseDocumentRepositoryInterface
{
    public function findByCode(string $code): ?ScopeDocumentInterface;
}
