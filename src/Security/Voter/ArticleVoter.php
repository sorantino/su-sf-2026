<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ArticleVoter extends Voter
{
    public const EDIT = 'ARTICLE_EDIT';

    public function __construct(
        private AccessDecisionManagerInterface $accessDecisionManager
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::EDIT
            && $subject instanceof Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }

        if (!$subject instanceof Article) {
            return false;
        }

        $diff = $subject->getCreatedAt()->diff(new DateTimeImmutable());

        if ($this->accessDecisionManager->decide($token, ['ROLE_ADMIN']) && $diff->days < 90) {
            return true;
        }

        return false;
    }
}
