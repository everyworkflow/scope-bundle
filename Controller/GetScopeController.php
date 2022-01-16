<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Controller;

use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use EveryWorkflow\ScopeBundle\Form\ScopeFormInterface;
use EveryWorkflow\ScopeBundle\Repository\ScopeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetScopeController extends AbstractController
{
    protected ScopeRepositoryInterface $scopeRepository;
    protected ScopeFormInterface $scopeForm;

    public function __construct(
        ScopeRepositoryInterface $scopeRepository,
        ScopeFormInterface $scopeForm
    ) {
        $this->scopeRepository = $scopeRepository;
        $this->scopeForm = $scopeForm;
    }

    #[EwRoute(
        path: "scope/{code}",
        name: 'scope.view',
        methods: 'GET',
        permissions: 'scope.view',
        swagger: [
            'parameters' => [
                [
                    'name' => 'code',
                    'in' => 'path',
                    'default' => 'create',
                ]
            ]
        ]
    )]
    public function __invoke(string $code = 'default'): JsonResponse
    {
        $data = [];

        if ($code !== 'default') {
            $item = $this->scopeRepository->findByCode($code);
            if ($item) {
                $data['item'] = $item->toArray();
            }
        }

        $data['data_form'] = $this->scopeForm->toArray();
        foreach ($data['data_form']['fields'] as &$field) {
            if ('parent' === $field['name']) {
                $skipVals = [];
                if ($code !== 'default') {
                    $skipVals[] = $code;
                }
                $field['options'] = $this->cleanUpViewingScope($field['options'], $skipVals);
            }
        }

        return new JsonResponse($data);
    }

    protected function cleanUpViewingScope(array $options, array $skipVals): array
    {
        foreach ($options as $key => &$option) {
            if (isset($option['value']) && in_array($option['value'], $skipVals, true)) {
                unset($options[$key]);
                continue;
            }

            if (isset($option['children']) && is_array($option['children'])) {
                $options[$key]['children'] = $this->cleanUpViewingScope($option['children'], $skipVals);
            }
        }
        return array_values($options);
    }
}
