<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Controller;

use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use EveryWorkflow\ScopeBundle\Repository\ScopeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteScopeController extends AbstractController
{
    protected ScopeRepositoryInterface $scopeRepository;

    public function __construct(ScopeRepositoryInterface $scopeRepository)
    {
        $this->scopeRepository = $scopeRepository;
    }

    #[EwRoute(
        path: "scope/{code}",
        name: 'scope.delete',
        methods: 'DELETE',
        permissions: 'scope.delete',
        swagger: [
            'parameters' => [
                [
                    'name' => 'code',
                    'in' => 'path',
                ]
            ]
        ]
    )]
    public function __invoke(string $code): JsonResponse
    {
        try {
            $this->scopeRepository->deleteOneByFilter(['code' => $code]);
            return new JsonResponse(['detail' => 'Scope with code: ' . $code . ' deleted successfully.']);
        } catch (\Exception $e) {
            return new JsonResponse(['detail' => $e->getMessage()], 500);
        }
    }
}
