<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Repository;

use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\CoreBundle\Annotation\RepoDocument;
use EveryWorkflow\ScopeBundle\Document\ScopeDocument;

/**
 * Class ScopeRepository
 * @package EveryWorkflow\ScopeBundle\Repository
 * @RepoDocument(doc_name=ScopeDocument::class)
 */
class ScopeRepository extends BaseDocumentRepository implements ScopeRepositoryInterface
{
    protected string $collectionName = 'scope_collection';

    protected array $indexNames = ['code'];
}
