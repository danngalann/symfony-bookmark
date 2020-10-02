<?php

namespace App\Security\Voter;

use App\Entity\Categoria;
use App\Entity\Etiqueta;
use App\Entity\Marcador;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CRUDVoter extends Voter
{

    const VER = 'ver';
    const EDITAR = 'editar';
    const ELIMINAR = 'eliminar';

    const ENTIDADES = [
        Categoria::class,
        Etiqueta::class,
        Marcador::class
    ];

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::VER, self::EDITAR, self::ELIMINAR])){
            return false;
        }

        if(!in_array(get_class($subject), self::ENTIDADES)){
            return false;
        }

        return True;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VER:
                return $this->isOwner($subject, $user);
                break;
            case self::EDITAR:
                return $this->isOwner($subject, $user);
                break;
            case self::ELIMINAR:
                return $this->isOwner($subject, $user);
                break;
        }

        return false;
    }

    private function isOwner($subject, User $user){
        return $user === $subject->getUser();
    }
}
