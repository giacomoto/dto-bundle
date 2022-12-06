<?php

namespace Luckyseven\Bundle\LuckysevenDtoBundle\Transformer;

use Luckyseven\Bundle\LuckysevenDtoBundle\Interface\IDtoTransformer;

abstract class DtoTransformer implements IDtoTransformer
{
    public function transformFromObjects(iterable $entities): iterable
    {
        $dto = [];

        foreach ($entities as $entity) {
            $dto[] = $this->transformFromObject($entity);
        }

        return $dto;
    }
}
