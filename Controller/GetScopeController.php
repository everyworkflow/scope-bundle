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

        return new JsonResponse($data);
    }
}
