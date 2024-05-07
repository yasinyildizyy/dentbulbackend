<?php

declare(strict_types=1);

namespace App\EventListener\HttpKernel;

use App\Entity\User;
use Bigoen\AdminBundle\Utils\UserHelper;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * @author Şafak Saylam <safak@bigoen.com>
 */
class ResponseJWTAuthListener
{
    public const JWT_TOKEN = 'JWT_TOKEN';

    public function __construct(
        private readonly JWTTokenManagerInterface $tokenManager,
        private readonly JWTEncoderInterface $encoder,
        private readonly UserHelper $userHelper,
        private readonly string $env
    ) {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $cookies = $request->cookies;
        $user = $this->userHelper->getUser();
        if (!$user instanceof User) {
            $cookies->remove(self::JWT_TOKEN);

            return;
        }
        if ($cookies->has(self::JWT_TOKEN)) {
            try {
                $this->encoder->decode($cookies->get(self::JWT_TOKEN));
            } catch (JWTDecodeFailureException) {
                $cookies->remove(self::JWT_TOKEN);
            }
        }
        $jwtToken = $this->tokenManager->create($user);
        $response = $event->getResponse();
        $response->headers->setCookie(
            Cookie::create(
                self::JWT_TOKEN,
                $jwtToken,
                0,
                '/',
                null,
                'prod' === $this->env,
                false,
                false,
                null
            )
        );
        $event->setResponse($response);
    }
}
