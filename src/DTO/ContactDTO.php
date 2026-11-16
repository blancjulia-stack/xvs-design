<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO{
#[Assert\NotBlank]
#[Assert\Length(min: 3, max: 75)]
#
/* definir propriete public dont on va avoir besoin avec leur valeur initiale */
public string $name = '';

#[Assert\NotBlank]
#[Assert\Email()]
public string $email = '';

#[Assert\NotBlank]
#[Assert\Length(min: 3, max: 75)]
public string $subject = '';

#[Assert\NotBlank]
#[Assert\Length(min: 20, max: 700)]
#[Assert\Regex(pattern: '/^[a-zA-Z0-9\s_?!:-]+$/', message: 'Les caractères spéciaux non autorisés sont interdits.')]
public string $message = '';


}