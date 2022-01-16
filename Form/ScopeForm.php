<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Form;

use EveryWorkflow\DataFormBundle\Factory\FieldOptionFactoryInterface;
use EveryWorkflow\DataFormBundle\Field\Select\Option;
use EveryWorkflow\DataFormBundle\Model\Form;
use EveryWorkflow\ScopeBundle\Repository\ScopeRepositoryInterface;
use Symfony\Contracts\Service\Attribute\Required;

class ScopeForm extends Form implements ScopeFormInterface
{
    protected FieldOptionFactoryInterface $fieldOptionFactory;
    protected ScopeRepositoryInterface $scopeRepository;

    #[Required]
    public function setFieldOptionFactory(FieldOptionFactoryInterface $fieldOptionFactory): self
    {
        $this->fieldOptionFactory = $fieldOptionFactory;
        return $this;
    }

    #[Required]
    public function setScopeRepository(ScopeRepositoryInterface $scopeRepository): self
    {
        $this->scopeRepository = $scopeRepository;
        return $this;
    }

    protected function getRecursiveTreeOptions(string $parentCode = 'default', array $skipCodes = []): array
    {
        $itemList = [];
        $items = $this->scopeRepository->find(['parent' => $parentCode]);
        foreach ($items as $item) {
            $currentCode = $item->getData('code');
            if ($currentCode && !in_array($currentCode, $skipCodes, true)) {
                $itemList[] = $this->fieldOptionFactory->create(Option::class, [
                    'title' => $item->getData('name'),
                    'value' => $currentCode,
                    'children' => $this->getRecursiveTreeOptions($currentCode, $skipCodes),
                ]);
            }
        }
        return $itemList;
    }

    public function getFields(): array
    {
        $fields = [
            $this->formFieldFactory->create([
                'label' => 'UUID',
                'name' => '_id',
                'is_readonly' => true,
            ]),
            $this->formFieldFactory->create([
                'label' => 'Code',
                'name' => 'code',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->create([
                'label' => 'Name',
                'name' => 'name',
                'field_type' => 'text_field',
            ]),
            $this->formFieldFactory->create([
                'label' => 'Parent',
                'name' => 'parent',
                'field_type' => 'tree_select_field',
                'options' => [
                    $this->fieldOptionFactory->create(Option::class, [
                        'title' => 'Default',
                        'value' => 'default',
                        'children' => $this->getRecursiveTreeOptions(),
                    ]),
                ],
                'is_default_expand_all' => true,
            ]),
            $this->formFieldFactory->create([
                'label' => 'Status',
                'name' => 'status',
                'field_type' => 'select_field',
                'options' => [
                    $this->fieldOptionFactory->create(Option::class, [
                        'key' => 'enable',
                        'value' => 'Enable',
                    ]),
                    $this->fieldOptionFactory->create(Option::class, [
                        'key' => 'disable',
                        'value' => 'Disable',
                    ]),
                ],
            ]),
            $this->formFieldFactory->create([
                'label' => 'Sort order',
                'name' => 'sort_order',
                'field_type' => 'text_field',
                'input_type' => 'number',
            ]),
            $this->formFieldFactory->create([
                'label' => 'Created at',
                'name' => 'created_at',
                'is_readonly' => true,
                'field_type' => 'date_time_picker_field',
            ]),
            $this->formFieldFactory->create([
                'label' => 'Updated at',
                'name' => 'updated_at',
                'is_readonly' => true,
                'field_type' => 'date_time_picker_field',
            ]),
        ];

        $sortOrder = 5;
        foreach ($fields as $field) {
            $field->setSortOrder($sortOrder++);
        }

        return array_merge($fields, parent::getFields());
    }
}
