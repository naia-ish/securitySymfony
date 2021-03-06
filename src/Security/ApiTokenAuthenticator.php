<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
    }

    public function supports(Request $request)
    {
        //if there is no token no other method will be called
        return $request->headers->has('Authorization') && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        //skip the 'Bearer '= miramos desde ela posición 7
        return substr($authorizationHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepository->findOneBy([
            'token' => $credentials,
        ]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Invalid API token');
            //si no hay token onAuthenticationFailure mostrará este mensaje a través de getMessageKey
        }

        if ($token->isExpired()) {
            throw new CustomUserMessageAuthenticationException('Token expired');
        }

        return $token->getUserId();
        //si devuelve esto va a checkCredentials
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true; //no tenemos que checkear password
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey(),
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // API token system allow request to continue and return the json response
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // will never be called caue we chose loginform authenticator as our entrypoint
        throw new \Exception('Not used: entypoint from other authenticator is used');
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
