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

class ScopeTreeController extends AbstractController
{
    protected ScopeRepositoryInterface $scopeRepository;

    public function __construct(
        ScopeRepositoryInterface $scopeRepository
    ) {
        $this->scopeRepository = $scopeRepository;
    }

    #[EwRoute(
        path: "scope-tree/{code}",
        name: 'scope.tree',
        priority: 10,
        methods: 'GET',
        permissions: 'scope.list',
        swagger: [
            'parameters' => [
                [
                    'name' => 'uuid',
                    'in' => 'path',
                    'default' => 'create',
                ]
            ]
        ]
    )]
    public function __invoke(string $code = 'default'): JsonResponse
    {
        try {
            $mainItem = $this->scopeRepository->findByCode($code);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }

        $items = $this->fetchRecursively($mainItem->getData('code'));
        return new JsonResponse(['items' => $items]);
    }

    protected function fetchRecursively(string $code, int $maxLevel = 3): array
    {
        $itemList = [];
        $items = $this->scopeRepository->find(['parent' => $code]);
        foreach ($items as $item) {
            $currentCode = $item->getData('code');
            $itemData = $item->toArray();
            if ($currentCode && $maxLevel > 1) {
                $itemData['children'] = $this->fetchRecursively($currentCode, $maxLevel - 1);
                $itemData['children_count'] = count($itemData['children']);
            } else if ($currentCode && $maxLevel === 1) {
                $itemData['children'] = $this->fetchRecursively($currentCode, $maxLevel - 1);
                $itemData['children_count'] = count($itemData['children']);
                $itemData['children_ids'] = $itemData['children'];
                unset($itemData['children']);
            }
            $itemList[] = $itemData;
        }
        return $itemList;
    }
}
