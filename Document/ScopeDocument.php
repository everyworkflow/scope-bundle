<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Document;

use EveryWorkflow\MongoBundle\Document\BaseDocument;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTrait;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTrait;
use EveryWorkflow\CoreBundle\Annotation\EWFDataTypes;

class ScopeDocument extends BaseDocument implements ScopeDocumentInterface
{
    use CreatedUpdatedHelperTrait, StatusHelperTrait;

    /**
     * @param string $code
     * @return $this
     * @EWFDataTypes (type="string", mongofield=self::KEY_CODE, required=TRUE, minLength=5, maxLength=20)
     */
    public function setCode(string $code): self
    {
        $this->dataObject->setData(self::KEY_CODE, $code);
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->dataObject->getData(self::KEY_CODE);
    }

    /**
     * @param string $name
     * @return $this
     * @EWFDataTypes (type="string", mongofield=self::KEY_NAME, required=TRUE)
     */
    public function setName($name): self
    {
        $this->dataObject->setData(self::KEY_NAME, $name);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->dataObject->getData(self::KEY_NAME);
    }


    /**
     * @param int $sortOrder
     * @return $this
     * @EWFDataTypes (type="integer", mongofield=self::KEY_SORT_ORDER, required=TRUE)
     */
    public function setSortOrder($sortOrder): self
    {
        $this->dataObject->setData(self::KEY_SORT_ORDER, $sortOrder);

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->dataObject->getData(self::KEY_SORT_ORDER);
    }

    /**
     * @param array $sortOrder
     * @return $this
     */
    public function setChildren(array $children): self
    {
        $this->dataObject->setData(self::KEY_CHILDREN, $children);

        return $this;
    }

    public function getChildren(): ?array
    {
        return $this->dataObject->getData(self::KEY_CHILDREN);
    }

}
