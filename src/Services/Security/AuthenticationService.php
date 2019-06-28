<?php


namespace Kinicart\Services\Security;


use Kinicart\Exception\Security\InvalidAPICredentialsException;
use Kinicart\Exception\Security\InvalidLoginException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Security\User;
use Kinicart\Services\Security\TwoFactor\TwoFactorProvider;
use Kinikit\Core\Configuration;


/**
 * AuthenticationService object for coordinating authentication functions for Kinicart.
 *
 * Class AuthenticationService
 * @package Kinicart\Workers\Application
 */
class AuthenticationService {

    private $settingsService;
    private $session;
    private $securityService;
    private $twoFactorProvider;

    const STATUS_LOGGED_IN = "LOGGED_IN";
    const STATUS_REQUIRES_2FA = "REQUIRES_2FA";

    /**
     * @param \Kinicart\Services\Application\SettingsService $settingsService
     * @param \Kinicart\Services\Application\Session $session
     * @param \Kinicart\Services\Security\SecurityService $securityService
     */
    public function __construct($settingsService, $session, $securityService) {
        $this->settingsService = $settingsService;
        $this->session = $session;
        $this->securityService = $securityService;

        $twoFactorProviderKey = Configuration::readParameter("two.factor.provider") ? Configuration::readParameter("two.factor.provider") : null;
        $this->twoFactorProvider = TwoFactorProvider::getProvider($twoFactorProviderKey);
    }

    /**
     * Boolean indicator as to whether or not an email address exists.
     *
     * @param $emailAddress
     * @param null $contextKey
     */
    public function emailExists($emailAddress, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = $this->session->__getActiveParentAccountId() ? $this->session->__getActiveParentAccountId() : 0;
        }

        return User::countQuery("WHERE emailAddress = ? AND parentAccountId = ?", $emailAddress, $parentAccountId) > 0;
    }


    /**
     * Log in with an email address and password.
     *
     * @param $emailAddress
     * @param $password
     *
     * @objectInterceptorDisabled
     */
    public function login($emailAddress, $password, $parentAccountId = null) {

        if ($parentAccountId === null) {
            $parentAccountId = $this->session->__getActiveParentAccountId() ? $this->session->__getActiveParentAccountId() : 0;
        }

        $matchingUsers = User::query("WHERE emailAddress = ? AND hashedPassword = ? AND parentAccountId = ?", $emailAddress, hash("md5", $password), $parentAccountId);

        // If there is a matching user, return it now.
        if (sizeof($matchingUsers) > 0) {
            /** @var User $user */
            $user = $matchingUsers[0];
            if ($user->getTwoFactorData()) {
                $this->session->__setPendingLoggedInUser($user);
                return self::STATUS_REQUIRES_2FA;
            } else {
                $this->securityService->logIn($user);
                return self::STATUS_LOGGED_IN;
            }
        } else {
            throw new InvalidLoginException();
        }
    }

    /**
     * Check the supplied two factor code and authenticate the login if correct.
     *
     * @param $code
     * @return bool
     * @throws InvalidLoginException
     * @throws \Kinicart\Exception\Security\AccountSuspendedException
     * @throws \Kinicart\Exception\Security\UserSuspendedException
     */
    public function authenticateTwoFactor($code) {
        $pendingUser = $this->session->__getPendingLoggedInUser();
        $secretKey = $pendingUser->getTwoFactorData();

        if (!$secretKey) return false;

        $authenticated = $this->twoFactorProvider->authenticate($secretKey, $code);

        if ($authenticated) {
            $this->securityService->logIn($pendingUser);
            return true;
        }
        return false;
    }


    /**
     * Authenticate an account by key and secret
     *
     * @param $apiKey
     * @param $apiSecret
     *
     * @objectInterceptorDisabled
     */
    public function apiAuthenticate($apiKey, $apiSecret) {

        $matchingAccounts = Account::query("WHERE apiKey = ? AND apiSecret = ?", $apiKey, $apiSecret);

        // If there is a matching user, return it now.
        if (sizeof($matchingAccounts) > 0) {
            $this->securityService->login(null, $matchingAccounts[0]);
        } else {
            throw new InvalidAPICredentialsException();
        }


    }


    /**
     * Update the active parent URL according to a referring URL.
     *
     * @param $referringURL
     */
    public function updateActiveParentAccount($referringURL) {

        // Check the referring URL to see whether or not we need to update our logged in state.
        $splitReferrer = explode("//", $referringURL);

        $referer = sizeof($splitReferrer) > 1 ? explode("/", $splitReferrer[1])[0] : $splitReferrer[0];

        // If the referer differs from the session value, check some stuff.
        if ($referer !== $this->session->__getReferringURL()) {
            $this->session->__setReferringURL($referer);

            // Now attempt to look up the setting by key and value
            $setting = $this->settingsService->getSettingByKeyAndValue("referringDomains", $referer);
            if ($setting) {
                $parentAccountId = $setting->getParentAccountId();
            } else {
                $parentAccountId = 0;
            }

            // Make sure we log out if the active parent account id has changed.
            if ($this->session->__getActiveParentAccountId() != $parentAccountId) {
                $this->logOut();
            }

            $this->session->__setActiveParentAccountId($parentAccountId);


        }

    }

    public function validateUserPassword($emailAddress, $password, $parentAccountId = null) {
        if ($parentAccountId === null) {
            $parentAccountId = $this->session->__getActiveParentAccountId() ? $this->session->__getActiveParentAccountId() : 0;
        }

        $matchingUsers = User::query("WHERE emailAddress = ? AND hashedPassword = ? AND parentAccountId = ?", $emailAddress, hash("md5", $password), $parentAccountId);

        return sizeof($matchingUsers) > 0;
    }

    public function generateGoogleAuthSettings() {
//        $googleAuthWorker = new GoogleAuthenticatorWorker("Netistrar", $loggedInUsername);
//
//        $secretKey = $googleAuthWorker->createSecretKey();
//        return array("secret" => $secretKey, "qrCode" => $googleAuthWorker->generateQRCode($secretKey));
    }

    /**
     * Log out function.
     */
    public function logout() {
        $this->securityService->logOut();
    }


}
