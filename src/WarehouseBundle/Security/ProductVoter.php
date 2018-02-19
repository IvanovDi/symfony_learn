<?php

namespace WarehouseBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use WarehouseBundle\Entity\Product;
use WarehouseBundle\Entity\User;

class ProductVoter extends Voter
{

    const VIEW = 'view';

    const EDIT = 'edit';

    const DELETE = 'delete';

    const CREATE = 'create';

    protected $decisionManager;


    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }


    public function supports($attribute, $subject)
   {
        if(!in_array($attribute, [
            self::CREATE,
            self::DELETE,
            self::EDIT,
            self::VIEW
        ])) {

            return false;

        }


        return true;
   }

   public function voteOnAttribute($attribute, $subject, TokenInterface $token)
   {
        $user = $token->getUser();


        if(!$user instanceof User) {
            return false;
        }

        file_put_contents('/home/mark-55/Documents/file.txt', print_r($token, true));
        switch ($attribute) {
            case self::EDIT:
                if($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
                    return true;
                }
                break;
            case self::CREATE:
                if($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
                    return true;
                }
                break;
            case self::DELETE:
                if($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
                    return true;
                }
                break;
            case self::VIEW:
                if($this->decisionManager->decide($token, ['ROLE_ADMIN', 'ROLE_USER'])) {
                    return true;
                };
                break;
        }

   }

}