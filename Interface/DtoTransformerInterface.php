<?php

namespace Luckyseven\Bundle\LuckysevenDtoBundle\Interface;

interface DtoTransformerInterface {
    public function transformFromObject(DtoSerializableInterface $entity);

    /**
     * @param array<DtoSerializableInterface> $entities
     * @return array
     */
    public function transformFromObjects(iterable $entities): iterable;
}
