<?php

namespace Org\Gucken\Events\Command;

/* *
 * This script belongs to the FLOW3 package "Kickstart".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


use Doctrine\ORM\Mapping as ORM;
use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Command controller for User Management
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AccountCommandController extends \TYPO3\FLOW3\Mvc\Controller\CommandController {

	/**
	 * @var \TYPO3\FLOW3\Security\AccountRepository
	 * @FLOW3\Inject
	 */
	protected $accountRepository;


	/**
	 * @var \TYPO3\FLOW3\Security\AccountFactory
	 * @FLOW3\Inject
	 */
	protected $accountFactory;

	/**
	 * @var \TYPO3\FLOW3\Security\Cryptography\HashService
	 * @FLOW3\Inject
	 */
	protected $hashService;

	/**
	 * @FLOW3\Inject
	 * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;


	/**
	 * Add an user
         *
         * The exceeding arguments are the assigned roles for this account
	 *
	 * Example: flow3 account:add --identifier hnesk --pasword "5u|Der!" [User Administrator]
	 *
	 * @param string $identifier The username
	 * @param string $password Plain text password
	 * @return string
         * @see account:delete
	 */
	public function addCommand($identifier, $password) {
		$existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, 'DefaultProvider');
		if (!\is_null($existingAccount)) {
			return 'An account with identifier "' . $identifier . '" already exists' . PHP_EOL;
		}

		$this->authenticationManager->logout();

		$roles = $this->request->getExceedingArguments();
		$account = $this->accountFactory->createAccountWithPassword(
			$identifier,
			$password,
			$roles
		);
		$this->accountRepository->add($account);

		return 'Account "'.$account->getAccountIdentifier().'": Added' .
			(count($roles)> 0 ? ', added roles: "'.implode('", "',$roles) : '').
			PHP_EOL;
	}


	/**
	 * Delete an user
	 *
	 * <code>./flow3_dev event:user:delete --identifier hnesk</code>
	 *
	 * @param string $identifier
	 * @param string $authenticationProviderName
	 * @return string
	 */
	public function deleteCommand($identifier, $authenticationProviderName = 'DefaultProvider') {
		$existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, $authenticationProviderName);
		if (\is_null($existingAccount)) {
			return 'Account with identifier "' . $identifier . '" not found' . PHP_EOL;
		}

		$this->accountRepository->remove($existingAccount);
		return 'Account "'.$existingAccount->getAccountIdentifier().'": '.
			'removed'.PHP_EOL;

	}


	/**
	 * Change the password for a user
	 *
	 * <code>./flow3_dev event:user:changepassword --identifier hnesk --pasword "5u|Der!"</code>
	 *
	 * @param string $identifier
	 * @param string $password
	 * @param string $authenticationProviderName
	 * @return string
	 */
	public function changepasswordCommand($identifier, $password, $authenticationProviderName = 'DefaultProvider') {
		$existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, $authenticationProviderName);
		if (\is_null($existingAccount)) {
			return 'Account with identifier "' . $identifier . '" not found' . PHP_EOL;
		}
		$existingAccount->setCredentialsSource($this->hashService->generateSaltedMd5($password));
		$this->accountRepository->update($existingAccount);

		return 'Account "'.$existingAccount->getAccountIdentifier() . '": ' .
			'Changed password'.PHP_EOL;
	}

	/**
	 * Grant roles to an existing user
	 *
	 * <code>./flow3_dev event:user:grant --identifier hnesk User Administrator</code>
	 *
	 * @param string $identifier
	 * @param string $authenticationProviderName
	 * @return string
	 */
	public function grantCommand($identifier, $authenticationProviderName = 'DefaultProvider') {
		$existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, $authenticationProviderName);
		if (\is_null($existingAccount)) {
			return 'Account with identifier "' . $identifier . '" not found' . PHP_EOL;
		}
		$roles = $this->request->getExceedingArguments();
		if (count($roles) === 0) {
			return 'You have to pass the roles to grant as additional arguments';
		}

		foreach ($roles as $role) {
			$existingAccount->addRole(new \TYPO3\FLOW3\Security\Policy\Role($role));
		}
		$this->accountRepository->update($existingAccount);

		return 'Account "'.$existingAccount->getAccountIdentifier() . '": "' .
			'added roles: "'.implode('", "',$roles) . '"'. PHP_EOL;
	}

	/**
	 * Revoke roles from an existing user
	 *
	 * <code>./flow3_dev event:user:revoke --identifier hnesk Administrator</code>
	 *
	 * @param string $identifier
	 * @param string $authenticationProviderName
	 * @return string
	 */
	public function revokeCommand($identifier, $authenticationProviderName = 'DefaultProvider') {
		$existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, $authenticationProviderName);
		if (\is_null($existingAccount)) {
			return 'Account with identifier "' . $identifier . '" not found' . PHP_EOL;
		}

		$roles = $this->request->getExceedingArguments();
		if (count($roles) === 0) {
			return 'You have to pass the roles to revoke as additional arguments';
		}

		foreach ($roles as $role) {
			$existingAccount->removeRole(new \TYPO3\FLOW3\Security\Policy\Role($role));
		}
		$this->accountRepository->update($existingAccount);

		return 'Account "'.$existingAccount->getAccountIdentifier() . '": "' .
			'revoked roles: "'.implode('", "',$roles) . '"' . PHP_EOL;
	}

	/**
	 * List roles of an user
	 *
	 * <code>./flow3_dev event:user:listroles --identifier hnesk</code>
	 *
	 * @param string $identifier
	 * @param string $authenticationProviderName
	 * @return string
	 */
	public function listrolesCommand($identifier, $authenticationProviderName = 'DefaultProvider') {
		$existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, $authenticationProviderName);
		if (\is_null($existingAccount)) {
			return 'Account with identifier "' . $identifier . '" not found' . PHP_EOL;
		}

		$message = '"'.$existingAccount->getAccountIdentifier().'" has the following roles';
		foreach ($existingAccount->getRoles() as $role) {
			/* @var $role \TYPO3\FLOW3\Security\Policy\Role */
			$message .= "\t * ".$role.PHP_EOL;
		}

		return $messsage;

	}

	/**
	 * List roles of an user
	 *
	 * <code>./flow3_dev event:user:list --filterRole Administraor</code>
	 *
	 * @param string $filterRole
	 * @return string
	 */
	public function listCommand($filterRole = null) {
		$accounts = $this->accountRepository->findAll();
		$message =
			\str_pad('Account Identifier', 20) . "\t" .
			\str_pad('Creation Date', 20). "\t" .
			\str_pad('Expiration Date', 20). "\t" .
			'Roles'.PHP_EOL;

		foreach ($accounts as $account) {
			/* @var $account \TYPO3\FLOW3\Security\Account */
			if (is_null($filterRole) || $this->hasRole($account, $filterRole)) {
				$message .= $this->formatAccount($account) . PHP_EOL;
			}
		}
		return $message;
	}

	/**
	 *
	 * @param \TYPO3\FLOW3\Security\Account $account
	 * @param string $filterRole
	 * @return boolean
	 */
	protected function hasRole(\TYPO3\FLOW3\Security\Account $account, $filterRole) {
		foreach ($account->getRoles() as $role) {
			if ($filterRole == $role) {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * @param \TYPO3\FLOW3\Security\Account $account
	 * @return string
	 */
	protected function formatAccount(\TYPO3\FLOW3\Security\Account $account) {
		$message =
			\str_pad($account->getAccountIdentifier(), 20) . "\t" .
			str_pad($account->getCreationDate()->format('Y-m-d H:i:s'), 20). "\t" .
			str_pad(($account->getExpirationDate() ? $account->getExpirationDate()->format('Y-m-d H:i:s') : 'no expiration'),20) . "\t";

		$roles = array();
		foreach ($account->getRoles() as $role) {
			$roles[] = $role;
		}
		\sort($roles);
		$message .= \implode(' ', $roles);
		return $message;
	}


}

?>