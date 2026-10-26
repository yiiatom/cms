<?php

declare(strict_types=1);

namespace Atom\Web\Setting\Manage\Sort;

use Atom\Repository\SettingGroupRepository;
use Atom\Repository\SettingRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\DataResponse\Formatter\JsonDataResponseFormatter;
use Yiisoft\FormModel\FormHydrator;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\Translator\TranslatorInterface;

final readonly class Action
{
    public function __construct(
        private DataResponseFactoryInterface $responseFactory,
        private FormHydrator $formHydrator,
        private SettingGroupRepository $settingGroupRepository,
        private SettingRepository $settingRepository,
        private TranslatorInterface $translator,
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
    ): ResponseInterface
    {
        $t = $this->translator->withDefaultCategory('atom-settings');

        $contentType = $request->getHeaderLine(Header::CONTENT_TYPE);
        if (!str_contains($contentType, 'application/json')) {
            return $this->responseFactory
                ->createResponse(Status::UNSUPPORTED_MEDIA_TYPE);
        }

        $json = $request->getBody()->getContents();
        $body = json_decode($json, true);

        if (empty($body) || !is_array($body)) {
            return $this->responseFactory
                ->createResponse(Status::UNPROCESSABLE_ENTITY);
        }

        $form = (new SortForm())->withTranslator($t);

        $this->formHydrator->populateAndValidate($form, $body);

        $code = Status::OK;
        $data = ['success' => true];

        if (!$form->isValid()) {
            $code = Status::UNPROCESSABLE_ENTITY;
            $data = [
                'success' => false,
                'errors' => $form->getValidationResult()->getFirstErrorMessagesIndexedByProperty(),
            ];
        } else {
            try {
                if ($form->type === 'group') {
                    $this->settingGroupRepository->updatePositions($form->positions);
                } elseif ($form->type === 'setting') {
                    $this->settingRepository->updatePositions($form->positions, $form->groupUuid);
                }
            } catch (\Throwable $e) {
                $code = Status::INTERNAL_SERVER_ERROR;
                $data = [
                    'success' => false,
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return $this->responseFactory
            ->createResponse($data, $code)
            ->withResponseFormatter(new JsonDataResponseFormatter());
    }
}
