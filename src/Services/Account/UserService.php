<?php


namespace Kinicart\Services\Account;

use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Security\Role;
use Kinicart\Objects\Security\User;
use Kinicart\Objects\Security\UserRole;
use Kinikit\Core\Exception\ValidationException;


class UserService {


    /**
     * Create a brand new user - optionally supply a name, account name and parent account id if relevant.  If no
     * parent Account Id is supplied, the session context will be used.
     *
     * @objectInterceptorDisabled
     */
    public function createWithAccount($emailAddress, $password, $name = null, $accountName = null, $parentAccountId = 0) {

        // Create a new user, save it and return it back.
        $user = new User($emailAddress, $password, $name, $parentAccountId);
        if ($validationErrors = $user->validate()) {
            throw new ValidationException($validationErrors);
        }

        // Create an account to match with any name we can find.
        $account = new Account($accountName ? $accountName : ($name ? $name : $emailAddress), $parentAccountId);
        $account->save();

        $user->setRoles(array(new UserRole(Role::SCOPE_ACCOUNT, $account->getAccountId())));
        $user->save();

        // Resync the user object
        $user->synchroniseRelationships();

        return $user;

    }


    /**
     * Create an admin user.
     *
     * @param $emailAddress
     * @param $password
     * @param null $name
     *
     */
    public function createAdminUser($emailAddress, $password, $name = null) {

        // Create a new user, save it and return it back.
        $user = new User($emailAddress, $password, $name);
        if ($validationErrors = $user->validate()) {
            throw new ValidationException($validationErrors);
        }

        $user->setRoles(array(new UserRole(0)));
        $user->save();

        // Resync the user object
        $user->synchroniseRelationships();

        return $user;
    }

}
