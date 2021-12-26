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
use Symfony\Component\HttpFoundation\Request;

class ListScopeController extends AbstractController
{
    #[EwRoute(
        path: "scope",
        name: 'scope',
        priority: 10,
        methods: 'GET',
        permissions: 'scope.list',
        swagger: true
    )]
    public function __invoke(Request $request, ScopeRepositoryInterface $scopeRepository): JsonResponse
    {
        $items = [];
        $scopes = $scopeRepository->find([], [
            'sort' => ['created_at' => -1],
            'skip' => 0,
            'limit' => 20
        ]);
        /** @var ScopeDocumentInterface $scope */
        foreach ($scopes as $scope) {
            $items[] = $scope->toArray();
        }
        return new JsonResponse(['items' => $items]);
    }
}
