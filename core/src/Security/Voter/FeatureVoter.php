<?php

namespace App\Security\Voter;

use App\Security\Permission;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FeatureVoter extends Voter
{
    use RoleCheckTrait;

    final public const FEATURE = Permission::FEATURE_CHECK;

    final public const DEV_ENV = Permission::FEATURE_SUBJECT_DEV_ENV;

    public function supportsAttribute(string $attribute): bool
    {
        return self::FEATURE === $attribute;
    }

    public function supportsType(string $subjectType): bool
    {
        return 'string' === $subjectType;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return (self::FEATURE === $attribute) && (self::DEV_ENV === $subject);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return $this->roleChecker->isSuperUser($token);
    }
}
