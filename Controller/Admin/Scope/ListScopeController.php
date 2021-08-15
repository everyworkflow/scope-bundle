<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Controller\Admin\Scope;

use EveryWorkflow\ScopeBundle\Repository\ScopeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListScopeController extends AbstractController
{
    /**
     * @Route(
     *     path="admin_api/scopes",
     *     name="admin_scope_list",
     *     methods="GET"
     * )
     */
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
        return (new JsonResponse())->setData(['items' => $items]);
    }
}
