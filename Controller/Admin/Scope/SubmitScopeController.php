<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\ScopeBundle\Controller\Admin\Scope;

use Carbon\Carbon;
use EveryWorkflow\CoreBundle\Annotation\EWFRoute;
use EveryWorkflow\CoreBundle\Cache\EWFCache;
use EveryWorkflow\CoreBundle\Controller\AdminController;
use EveryWorkflow\CoreBundle\HttpFoundation\EWFResponse;
use EveryWorkflow\CoreBundle\Message\MessageInterface;
use EveryWorkflow\ScopeBundle\Document\ScopeDocument;
use EveryWorkflow\ScopeBundle\Document\ScopeDocumentInterface;
use EveryWorkflow\ScopeBundle\Repository\ScopeRepositoryInterface;
use Symfony\Component\{
    HttpFoundation\Request
};

class SubmitScopeController extends AdminController
{
    private $logger;
    private EWFCache $cache;
    private MessageInterface $message;
    private EWFResponse $EWFResponse;

    /**
     * SubmitScopeController constructor.
     *
     * @param $logger
     */
    public function __construct($logger, EWFCache $cache, MessageInterface $message, EWFResponse $EWFResponse)
    {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->message = $message;
        $this->EWFResponse = $EWFResponse;
    }

    /**
     * @EWFRoute (
     *     admin_api_path="scopes/{uuid}",
     *     defaults={"uuid"=false},
     *     name="admin_scope_submit",
     *     methods="POST"
     * )
     */
    public function __invoke($uuid, Request $request, ScopeRepositoryInterface $scopeRepository): EWFResponse
    {
        $submitData = json_decode($request->getContent(), true);
        try {
            $canCreateNewDoc = true;
            if ($uuid) {
                /** @var ScopeDocumentInterface $scopeDocument */
                $scopeDocument = $scopeRepository->findById($uuid);
                if ($scopeDocument) {
                    $canCreateNewDoc = false;
                    $scopeDocument->setUpdatedAt(Carbon::now());
                }
            }
            if ($canCreateNewDoc) {
                /** @var ScopeDocumentInterface $scopeDocument */
                $scopeDocument = $scopeRepository->getDocumentFactory()->create(ScopeDocument::class);
                $scopeDocument->setCreatedAt(Carbon::now())->setUpdatedAt(Carbon::now());
            }

            /* @var ScopeRepositoryInterface $scopeDocument */
            $scopeDocument->setData($submitData);

            $result = $scopeRepository->save($scopeDocument);

            $jsonDocId = '';
            if ($result->getUpsertedId()) {
                $jsonDocId = $result->getUpsertedId();
            }
            if ($jsonDocId) {
                $jsonDocId = $uuid;
            }

            return $this->EWFResponse->setSuccessResponse($scopeDocument->toArray() + ['_id' => $jsonDocId]);
        } catch (\Exception $e) {
            return $this->EWFResponse->setErrorResponse(['messages' => $this->message->getMessages()]);
        }
    }
}
