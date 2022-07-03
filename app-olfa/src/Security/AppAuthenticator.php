<?php

namespace App\Security;

use App\Entity\Utilisateur;
use App\Repository\ParametresCompteEleveRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    private $param;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder,ParametresCompteEleveRepository $repo)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->param = $repo;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'cin' => $request->request->get('cin'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['cin']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['cin' => $credentials['cin']]);

        if (!$user) {
            throw new UsernameNotFoundException('Cin could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        
        if($token->getUser()->getRole() == "ROLE_ADMIN"){
            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }else if($token->getUser()->getRole() == "ROLE_PROVISEUR"){
            return new RedirectResponse($this->urlGenerator->generate('app_proviseur'));
        }else if($token->getUser()->getRole() == "ROLE_ENSEIGNANT"){
            return new RedirectResponse($this->urlGenerator->generate('app_enseignant_d'));
        }else if($token->getUser()->getRole() === "ROLE_ELEVE") {
            $date = new \DateTime();
            $result = $this->param->getLatestParameters();
            $de = $result->getDe();
            $a  = $result->getA();
            
            if(($date >= $de) && ($a >= $date)){
                return new RedirectResponse($this->urlGenerator->generate('evaluer_enseignant'));
            }else{
                
                return new RedirectResponse($this->urlGenerator->generate('app_login'));
            }
           
            //$parameters = $this->param->getLatestParameters();
            /*$de = $parameters->getDe();
            $a  = $parameters->getA();*/
            //dd($de, $a);
     
           // return new RedirectResponse($this->urlGenerator->generate('app_eleve_d'));
            /*$parameters = $this->param->findAll();
            date_default_timezone_set("Africa/Tunis");
            // parametres d'admin
            $count = count($parameters);
            $latest = $parameters[$count -1 ];
            $de = $latest->getDe();
            $a  = $latest->getA();
            // data courant
             date_default_timezone_set("Africa/Tunis");
            $date = new \DateTime('now', new DateTimeZone('Africa/Tunis'));
            dump($date);
            dump($de);
            $diff1 = date_diff($date, $de);
            dump($diff1);
            dump($a);
            dump($date);
            $diff2 = date_diff($a, $date);
            dump($diff2);
            die(); */
            /*if( (($tawika - $mnin )>=0)  && (($hatta - $tawika) >= 0) ){

                return new  RedirectResponse($this->urlGenerator->generate('evaluer_enseignant'));
            }*/
            
        }
        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
