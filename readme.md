# Luckyseven Validation Bundle
Luckyseven Validation Bundle uses Symfony's Validation bundle and Rolleworks PasswordStrenghValidator Bundle

## Update composer.json and register the repositories
```
{
    ...
    "repositories": [
        {"type": "git", "url":  "https://github.com/giacomoto/validation-bundle.git"}
    ],
    ...
    "extra": {
        "symfony": {
            ...
            "endpoint": [
                "https://api.github.com/repos/giacomoto/validation-recipes/contents/index.json",
                "flex://defaults"
            ]
        }
    }
}
```

## Install
```
composer require luckyseven/validation:dev-main
composer recipes:install luckyseven/jwt-auth --force -v
```

## Usage
Create a Validator Constraints ex: ```Validation/CreateUserConstraint.php```
```
<?php

namespace App\Validation\User;

use Luckyseven\Bundle\LuckysevenValidationBundle\Interface\ValidationConstraintInterface;
use Luckyseven\Bundle\LuckysevenValidationBundle\Trait\EmailConstraintTrait;
use Luckyseven\Bundle\LuckysevenValidationBundle\Trait\PasswordConstraintTrait;
use Luckyseven\Bundle\LuckysevenValidationBundle\Trait\StringConstraintTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class CreateUserConstraint extends Constraint implements ValidationConstraintInterface
{
    use EmailConstraintTrait;
    use StringConstraintTrait;
    use PasswordConstraintTrait;

    public function getConstraints(): Collection
    {
        return new Collection([
            'email' => $this->isTypeEmail(User::class),
            'password' => $this->isTypePassword(),
            'lastName' => $this->isTypeString(),
            'firstName' => $this->isTypeString(),
        ]);
    }
}
```
validate the Request body against the previously created validator.<br>
Ex: ```Controller/AuthController.php```
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

    /**
     * @throws ValidationException
     */
    public function register(
        Request                     $request,
        ValidationService           $validationService,
    ): JsonResponse
    {
        $body = $validationService->validate($request, CreateUserConstraint::class);
    
        // if validation success body will be an associative array
        // if validation fails the ValidationException will be thrown
        
        // create user
        ...
    }
}
```
