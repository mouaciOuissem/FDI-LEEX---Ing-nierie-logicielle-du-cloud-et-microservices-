<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }
    
    /**
     * Permet de récupérer les infos de l'utilisateur connecté.
     * 
     * @Route(
     * path="/me",
     * name="me",
     * methods = {"GET"})
     */
    public function me(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->security->getUser();
        //var_dump($user);
        $json = $serializer->serialize($user,'json',["groups" => "User:Read"]);
        
        return new JsonResponse('{
                "message": "OK",
                "data" : '.$json.'
        }'
                ,Response::HTTP_OK,[],true);  
    }
}
