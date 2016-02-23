<?php

namespace CoreBundle\Twig;

use CoreBundle\Entity\Actualite;
use CoreBundle\Entity\Projet;
use CoreBundle\Entity\Service;
use CoreBundle\Entity\User;
use CoreBundle\Entity\UserPro;
use CoreBundle\Media\Exposer;
use CoreBundle\Util\BaseUrlResolverInterface;
use Symfony\Component\DependencyInjection\Container;


class AssetExtension extends \Twig_Extension
{
    private $baseUrlResolver;
    private $mediaResolver;
    private $container;

    public function __construct(Exposer $mediaResolver, BaseUrlResolverInterface $baseUrlResolver, Container $container)
    {
        $this->mediaResolver = $mediaResolver;
        $this->baseUrlResolver = $baseUrlResolver;
        $this->container = $container;

    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'incenteev_asset';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('age', array($this, 'ageCalculate')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_user_photo_profil', array($this, 'getUserPhotoProfil')),
            new \Twig_SimpleFunction('get_logo_service', array($this, 'getLogoService')),
            new \Twig_SimpleFunction('get_logo', array($this, 'getLogoPath')),
            new \Twig_SimpleFunction('get_user_pro_bureau', array($this, 'getPhotoBureau')),
            new \Twig_SimpleFunction('get_photo_vous', array($this, 'getPhotoVous')),
            new \Twig_SimpleFunction('get_photo_projet', array($this, 'getPhotoProjet')),
            new \Twig_SimpleFunction('get_photo_actu', array($this, 'getPhotoActu')),
            new \Twig_SimpleFunction('taux_endettement', array($this, 'tauxEndettement')),
            new \Twig_SimpleFunction('projet_id', array($this, 'getProjetId')),
            new \Twig_SimpleFunction('get_projet_etat', array($this, 'getProjetEtat')),
            new \Twig_SimpleFunction('get_projet_partage', array($this, 'getProjetPartage')),
            new \Twig_SimpleFunction('get_don', array($this, 'getDon')),
            new \Twig_SimpleFunction('is_donateur', array($this, 'isDonateur')),
            new \Twig_SimpleFunction('piece_is_complete', array($this, 'isPiece')),
            new \Twig_SimpleFunction('projet_service', array($this, 'getProjetService')),
            new \Twig_SimpleFunction('pourcent_avancement', array($this, 'pourcentAvancement')),
        );
    }

    public function pourcentAvancement(Projet $projet)
    {

        $em = $this->container->get('doctrine')->getManager();
        $dons = $em->getRepository('CoreBundle:Don')->getDonValideByProjetId($projet->getId());

        $total = 0;
        foreach ($dons as $don) {
            $total = $don->getMontant() + $total;
        }

        if($projet->getPrix() > 0) {
            return ($total * 100 / ($projet->getPrix() * 0.02));
        } else {
            return 0;
        }

    }

    public function ageCalculate(\DateTime $bithdayDate)
    {
        $now = new \DateTime();
        $interval = $now->diff($bithdayDate);

        return $interval->y;
    }

    public function getProjetId(User $user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $projet = $em->getRepository('CoreBundle:Projet')->getProjetByIdUser($user);

        if (!empty($projet)) {
            $id = $projet->getId();
        } else {
            $id = null;
        }
        return $id;
    }

    public function getProjetEtat(User $user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $projet = $em->getRepository('CoreBundle:Projet')->getProjetByIdUser($user->getId());

        if (!isset($projet) || $projet->getProfilEmprunteur() == null) {
            return $etape = 1;
        } elseif ($projet->getTypeBien() == null) {
            return $etape = 2;
        } elseif ($projet->getTitre() == null) {
            return $etape = 3;
        } elseif ($projet->getState() == 'valided') {
            return $etape = 4;
        } elseif ($projet->getState() == Projet::STATE_ACTE_PAYE) {
            return $etape = 7;
        } elseif ($projet->getState() == Projet::STATE_OBJ_ATTEINT || $projet->getState() == Projet::STATE_ACTE_PAYE) {
            return $etape = 6;
        } else {
            return $etape = 5;
        }

    }

    public function getProjetPartage(User $user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $projet = $em->getRepository('CoreBundle:Projet')->getProjetByIdUser($user->getId());
        if($projet){
            return $projet->getPartage();
        } else {
            return false;
        }
    }

    public function getProjetService(User $user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $service = $em->getRepository('CoreBundle:InscriptionService')->getAllInscriptionServiceByUser($user->getId());

        if($service){
            return true;
        } else {
            return false;
        }
    }

    public function getDon(User $user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $dons = $em->getRepository('CoreBundle:Don')->getDonsByUser($user->getId());

        if ($dons) {
            return true;
        } else {
            return false;
        }


    }

    public function isPiece(User $user)
    {

        $em = $this->container->get('doctrine')->getManager();
        $projet = $em->getRepository('CoreBundle:Projet')->getProjetByIdUser($user->getId());

        if($projet){
            $element = $em->getRepository('CoreBundle:JustifElement')->getValueByIdProjet($projet->getId());
            if (empty($element)) {
                return false;
            }

            $rib = $em->getRepository('CoreBundle:JustifRib')->getValueByIdProjet($projet->getId());
            if (empty($rib)) {
                return false;
            }
        } else {
            return true;
        }

        return true;


    }

    public function isDonateur(User $user)
    {
        $em = $this->container->get('doctrine')->getManager();
        $dons = $em->getRepository('CoreBundle:Don')->getDonsByUserProjet($user->getId());

        if ($dons) {
            return true;
        } else {
            return false;
        }


    }

    public function tauxEndettement(Projet $projet)
    {
        $emprunteur = $projet->getProfilEmprunteur();
        $coEmprunteur = $projet->getProfilCoEmprunteur();
        $revenuCo = 0;
        $revenu = 0;
        $endettement = 0;

        $now = new \DateTime();


        if (!empty($emprunteur->getDateNaissance())) {
            $annivEmp = $emprunteur->getDateNaissance();
            $age = date_diff($now, $annivEmp);
        }


        if (!empty($coEmprunteur)) {
            if (!empty($coEmprunteur->getDateNaissance())) {
                $ageCo = date_diff($now, $coEmprunteur->getDateNaissance());
                $age = ($ageCo->y + $age->y) / 2;
                $revenuCo = $coEmprunteur->getRevenuNet() / 12;
            }
        } else {
            if (!empty($age)) {
                $age = $age->y;
            }
        }

        if (!empty($age)) {
            if ($age <= 40) {
                $mensualite = ($projet->getPrix() / 10000) * 48;
            } elseif ($age <= 50) {
                $mensualite = ($projet->getPrix() / 10000) * 45;
            } else {
                $mensualite = ($projet->getPrix() / 10000) * 65;
            }

            $revenu = $emprunteur->getRevenuNet() / 12;
            $revenu = $revenu + $revenuCo;
            if($revenu > 0){
                $endettement = $mensualite * 100 / $revenu;

            } else {
                $endettement = 0;

            }
        }


        return $endettement;
    }

    public function getLogoService(Service $service)
    {
        return $this->mediaResolver->getLogoServicePath($service);
    }

    public function getLogoPath(UserPro $userPro)
    {
        return $this->mediaResolver->getLogoPath($userPro);
    }

    public function getPhotoVous(Projet $projet)
    {
        return $this->mediaResolver->getPhotoVousProjetPath($projet);
    }

    public function getPhotoProjet(Projet $projet)
    {
        return $this->mediaResolver->getPhotoProjetPath($projet);
    }

    public function getUserPhotoProfil(User $user)
    {
        return $this->mediaResolver->getPhotoProfilPath($user);
    }

    public function getPhotoActu(Actualite $actualite)
    {
        return $this->mediaResolver->getPhotoPath($actualite);
    }

    public function getPhotoBureau(UserPro $userPro)
    {
        return $this->mediaResolver->getPhotoBureauPath($userPro);
    }

}
