<?php

namespace App\Security\Voter;

use App\Entity\Quizz;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class QuizzVoter extends Voter
{
    public const EDIT = 'CAN_EDIT';
    public const DELETE = 'CAN_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Quizz;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $user);
                break;
            case self::DELETE:
                return $this->canDelete($subject, $user);;
                break;
        }

        return false;
    }

    private function canEdit(Quizz $quizz, UserInterface $user): bool
    {
        return $user === $quizz->getCreatedBy() || in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }

    private function canDelete(Quizz $quizz, UserInterface $user): bool
    {
        return in_array('ROLE_SUPER_ADMIN', $user->getRoles());
    }
}
