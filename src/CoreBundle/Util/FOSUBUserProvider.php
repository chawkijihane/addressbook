<?php
namespace CoreBundle\Util;

use CoreBundle\Entity\User;
use CoreBundle\Media\Uploader;
use Gaufrette\Filesystem;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;


class FOSUBUserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($service);
        $setter_id = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->userManager->findUserByEmail($response->getEmail());
        if ($user) {
            $this->connect($user, $response);
        }

        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));


        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();

            if (!empty($response->getEmail())) {
                $email = $response->getEmail();
            } else {
                $email = $username;
            }

            $user = new User();
            if ($service != 'twitter') {
                $url = $response->getProfilePicture();

                $image = new ImageManager(array('driver' => 'gd'));
                $filename = sprintf('%s.jpg', md5(uniqid(mt_rand(), true)));
                $path = sprintf('%s/%s', 'photoProfilPath', $filename);

                $image->make($url)
                    ->fit(300, 300)
                    ->save('uploads/' . $path, 90);

                //   copy($url, 'uploads/photoProfilPath/'.$filename);
                $user->setPhotoProfilPath('photoProfilPath/' . $filename);
            }

            $setter = 'set' . ucfirst($service);
            $setter_id = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';
            // create new user here

            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $chaine = "abcdefeghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            srand((double)microtime() * 1000000);
            $mdp = '';
            for ($i = 0; $i < 8; $i++) {
                $mdp .= $chaine[rand() % strlen($chaine)];
            }

            $user->setPlainPassword($mdp);
            $user->setUsername($email);
            $user->setEmail($email);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }
}
