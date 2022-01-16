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

class SubmitScopeController extends AbstractController
{
    protected ScopeRepositoryInterface $scopeRepository;

    public function __construct(ScopeRepositoryInterface $scopeRepository)
    {
        $this->scopeRepository = $scopeRepository;
    }

    #[EwRoute(
        path: "scope/{code}",
        name: 'scope.save',
        methods: 'POST',
        permissions: 'scope.save',
        swagger: [
            'parameters' => [
                [
                    'name' => 'code',
                    'in' => 'path',
                    'default' => 'create',
                ]
            ],
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'properties' => [
                                'code' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'name' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'parent' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ]
    )]
    public function __invoke(Request $request, ?string $code = 'default'): JsonResponse
    {
        $submitData = json_decode($request->getContent(), true);
        if ('default' === $code) {
            $item = $this->scopeRepository->create($submitData);
        } else {
            $item = $this->scopeRepository->findByCode($code);
            foreach ($submitData as $key => $val) {
                $item->setData($key, $val);
            }
        }

        if (!$item->getData('parent')) {
            $item->setData('parent', 'default');
        }

        $item = $this->scopeRepository->saveOne($item);

        return new JsonResponse([
            'detail' => 'Successfully saved changes.',
            'item' => $item->toArray(),
        ]);
    }
}
