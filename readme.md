# Luckyseven Validation Bundle
Luckyseven Dto Bundle uses JSMSerializer Bundle

## Update composer.json and register the repositories
```
{
    ...
    "repositories": [
        {"type": "git", "url":  "https://github.com/giacomoto/dto-bundle.git"}
    ],
    ...
    "extra": {
        "symfony": {
            ...
            "endpoint": [
                "https://api.github.com/repos/giacomoto/dto-recipes/contents/index.json",
                "flex://defaults"
            ]
        }
    }
}
```

## Install
```
composer require luckyseven/dto:dev-main
composer recipes:install luckyseven/dto --force -v
```

## Usage
Create a Dto and DtoTransformer Class <br>

Example for User Entity:

User Entity must implements IDtoTransformer

Create file UserDto ex: ```Dto/DtoUser.php```<br>
```
<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;
use Luckyseven\Bundle\LuckysevenDtoBundle\Interface\IDtoTransformer;

class UserDto implements IDtoTransformer
{
    /**
     * @Serializer\Type("int")
     * @Serializer\Groups({"User"})
     */
    public int $id;

    /**
     * @Serializer\Type("string")
     * @Serializer\Groups({"User"})
     */
    public string $email;

    /**
     * @Serializer\Type("DateTimeImmutable<'timestamp'>")
     * @Serializer\Groups({"User"})
     */
    public \DateTimeImmutable $createdAt;
}
```
Create file UserDtoTransformer ex: ```Dto/DtoUser.php```<br>
```
<?php

namespace App\Dto\Transformer;

use App\Dto\UserDto;
use App\Entity\User;
use Luckyseven\Bundle\LuckysevenDtoBundle\Exception\DtoUnexpectedTypeException;
use Luckyseven\Bundle\LuckysevenDtoBundle\Interface\IDtoTransformer;
use Luckyseven\Bundle\LuckysevenDtoBundle\Transformer\DtoTransformer;

class UserDtoTransformer extends DtoTransformer {

    /**
     * @param IDtoTransformer $entity
     * @return UserDto
     */
    public function transformFromObject(IDtoTransformer $entity): UserDto
    {
        if (!$entity instanceof User) {
            throw new DtoUnexpectedTypeException('Expected type of User but got ' . get_class($entity));
        }

        $dto = new UserDto();

        $dto->id = $entity->getId();
        $dto->email = $entity->getEmail();

        $dto->lastName = $entity->getLastName();
        $dto->firstName = $entity->getFirstName();

        $dto->createdAt = $entity->getCreatedAt();
        $dto->updatedAt = $entity->getUpdatedAt();

        return $dto;
    }
}
```
Create Dto and pass it to serializer
Ex: ```Controller/UserController.php```
```
<?php

namespace App\Controller;

use App\Validation\User\CreateUserConstraint;
use Luckyseven\Bundle\LuckysevenValidationBundle\Exception\ValidationException;
use Luckyseven\Bundle\LuckysevenValidationBundle\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends AbstractController
{
    ...

    public function getUser(
        User               $user,
        UserDtoTransformer $userDtoTransformer,
    ): JsonResponse
    {
        ...
        
        // create dto
        $userDto = $userDtoTransformer->transformFromObject($user)
        
        // create serializationGroups
        $serializationGroups = [ "User" ];

        return new JsonResponse(json_decode(
            $this->serializerService
                ->setGroups($serializationGroups)
                ->serialize(["data" => $userDto, '_meta' => $metadata])
            , true), $status, $metadata);
        
    }
}
```
