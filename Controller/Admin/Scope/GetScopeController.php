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

class GetScopeController extends AbstractController
{
    /**
     * @Route(
     *     path="admin_api/scopes/{uuid}",
     *     name="admin_scope_single",
     *     methods="GET"
     * )
     */
    public function __invoke($uuid, Request $request, ScopeRepositoryInterface $scopeRepository): JsonResponse
    {
        $jsonResponse = new JsonResponse();
        try {
            $scopeDocument = $scopeRepository->findById($uuid);
            return $jsonResponse->setData($scopeDocument->toArray());
        } catch (\Exception $e) {
            return $jsonResponse->setData(['message' => 'Scope not found!'])
                ->setStatusCode(JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
