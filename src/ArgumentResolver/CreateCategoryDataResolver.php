<?php

namespace App\ArgumentResolver;

use App\DTO\CreateCategoryData;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CreateCategoryDataResolver implements ArgumentValueResolverInterface
{
    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(
        Request $request,
        ArgumentMetadata $argument
    ): bool {
        return CreateCategoryData::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable
     *
     * @throws JsonException
     */
    public function resolve(
        Request $request,
        ArgumentMetadata $argument
    ): iterable {
        $params = json_decode(
            json: $request->getContent(),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );

        //через валидатор прогонять не буду, лень
        yield new CreateCategoryData($params['title']);
    }
}
