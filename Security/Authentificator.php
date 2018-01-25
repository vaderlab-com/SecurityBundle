<?php
/**
 * Created by PhpStorm.
 * User: kost
 * Date: 31.10.17
 * Time: 20:42
 */

namespace VaderLab\SecurityBundle\Security;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use VaderLab\SecurityBundle\Http\Client;
use VaderLab\SecurityBundle\Service\User\Provider\UserProvider;

class Authentificator implements SimplePreAuthenticatorInterface
{
    /**
     * @var Client
     */
    private $apiClient;

    /**
     * Authenticator constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->apiClient = $client;
    }

    public function createToken(Request $request, $providerKey)
    {
        $bearer = $request->headers->get('Authorization');

        if($bearer) {
            $this->apiClient->setBearer($bearer);
        }

        return new PreAuthenticatedToken(
            'anon.',
            $bearer,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof UserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of Authenticator (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $bearer = $token->getCredentials();
        $user = $userProvider->loadCurrentUser();

        if (!$user) {
            $this->apiClient->setBearer(null);
            return $token;
        }

        return new PreAuthenticatedToken(
            $user,
            $bearer,
            $providerKey,
            $user->getRoles()
        );
    }
}