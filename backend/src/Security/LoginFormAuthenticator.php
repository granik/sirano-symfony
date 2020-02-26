<?php

namespace App\Security;

use App\Domain\Interactor\UserInteractor;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;
    
    private $urlGenerator;
    private $csrfTokenManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    
    /**
     * LoginFormAuthenticator constructor.
     *
     * @param UrlGeneratorInterface        $urlGenerator
     * @param CsrfTokenManagerInterface    $csrfTokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserInteractor               $userInteractor
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserInteractor $userInteractor
    ) {
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
        $this->userInteractor   = $userInteractor;
    }
    
    public function supports(Request $request)
    {
        return in_array($request->attributes->get('_route'), ['login', 'cms_login'])
            && $request->isMethod('POST');
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }
        
        if (strpos($request->getPathInfo(), '/cms') === 0) {
            $url = $this->getCmsLoginUrl();
        } else {
            $url = $this->getLoginUrl();
        }
        
        return new RedirectResponse($url);
    }
    
    public function start(Request $request, AuthenticationException $authException = null)
    {
        if (strpos($request->getPathInfo(), '/cms') === 0) {
            $url = $this->getCmsLoginUrl();
        } else {
            $url = $this->getLoginUrl();
        }
        
        return new RedirectResponse($url);
    }
    
    public function getCredentials(Request $request)
    {
        $credentials = [
            'user'       => $request->request->get('login'),
            'password'   => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['user']
        );
        
        return $credentials;
    }
    
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }
        
        // Load / create our user however you need.
        // You can do this by calling the user provider, or with custom logic here.
        $user = $userProvider->loadUserByUsername($credentials['user']);
        
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('User could not be found.');
        }
        
        return $user;
    }
    
    public function checkCredentials($credentials, UserInterface $symfonyUser)
    {
        $isPasswordValid = $this->passwordEncoder->isPasswordValid($symfonyUser, $credentials['password']);
        
        if (!$isPasswordValid) {
            return $isPasswordValid;
        }
        
        if ($symfonyUser->getEncoderName() === 'new_user') {
            return $isPasswordValid;
        }
        
        $this->userInteractor->updatePasswordWithoutCheck($symfonyUser->getUser(), $credentials['password']);
        
        return $isPasswordValid;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($request->request->has('_target_path')) {
            $targetPath = $request->request->get('_target_path');
            
            return new RedirectResponse($targetPath);
        }
        
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        
        return new RedirectResponse($this->urlGenerator->generate('index'));
    }
    
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('login');
    }
    
    protected function getCmsLoginUrl()
    {
        return $this->urlGenerator->generate('cms_login');
    }
}