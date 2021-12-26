<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Document;

use EveryWorkflow\MongoBundle\Document\BaseDocument;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTrait;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTrait;
use EveryWorkflow\CoreBundle\Validation\Type\NumberValidation;
use EveryWorkflow\CoreBundle\Validation\Type\StringValidation;

class ScopeDocument extends BaseDocument implements ScopeDocumentInterface
{
    use CreatedUpdatedHelperTrait, StatusHelperTrait;

    #[StringValidation(required: true, minLength: 2, maxLength: 20)]
    public function setCode(string $code): self
    {
        $this->dataObject->setData(self::KEY_CODE, $code);
        return $this;
    }

    public function getCode(): ?string
    {
        return $this->dataObject->getData(self::KEY_CODE);
    }

    #[StringValidation(required: true, minLength: 2, maxLength: 50)]
    public function setName($name): self
    {
        $this->dataObject->setData(self::KEY_NAME, $name);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->dataObject->getData(self::KEY_NAME);
    }

    #[StringValidation(required: true, minLength: 2, maxLength: 20)]
    public function setParent(string $parentCode): self
    {
        $this->dataObject->setData(self::KEY_PARENT, $parentCode);

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->dataObject->getData(self::KEY_PARENT);
    }

    #[NumberValidation(minium: 1)]
    public function setSortOrder($sortOrder): self
    {
        $this->dataObject->setData(self::KEY_SORT_ORDER, $sortOrder);

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->dataObject->getData(self::KEY_SORT_ORDER);
    }

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
