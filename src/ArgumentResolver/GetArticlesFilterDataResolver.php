<?php

namespace App\ArgumentResolver;

use App\DTO\GetArticlesFilterData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class GetArticlesFilterDataResolver implements ArgumentValueResolverInterface
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
        return GetArticlesFilterData::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return iterable
     */
    public function resolve(
        Request $request,
        ArgumentMetadata $argument
    ): iterable {
        $categoriesIds = $request->get('category', []);

        //через валидатор прогонять не буду, лень
        yield new GetArticlesFilterData($categoriesIds);
    }
}
