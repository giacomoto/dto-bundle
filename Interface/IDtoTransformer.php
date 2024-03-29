<?php

namespace Luckyseven\Bundle\LuckysevenDtoBundle\Interface;

interface IDtoTransformer {
    public function transformFromObject(IDtoSerializable $entity);

    /**
     * @param array<IDtoSerializable> $entities
     * @return array
     */
    public function transformFromObjects(iterable $entities): iterable;
}
