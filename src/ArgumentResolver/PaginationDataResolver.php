<?php

namespace App\ArgumentResolver;

use App\DTO\PaginationData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class PaginationDataResolver implements ArgumentValueResolverInterface
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
        return PaginationData::class === $argument->getType();
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
        $limit = $request->get('limit');
        $offset = $request->get('offset');

        //через валидатор прогонять не буду, лень
        yield new PaginationData(
            limit: $limit,
            offset: $offset,
        );
    }
}
