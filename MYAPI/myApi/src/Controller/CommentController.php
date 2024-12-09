<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Entity\Comment;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route(
     * path="/videos/{id}/comments",
     * name="post_videos_comments",
     * methods = {"POST"})
     */
    public function create($id, Request $request, EntityManagerInterface $manager,SerializerInterface $serializer , UserRepository $repoUser, CommentRepository $repoComment,  VideoRepository $repoVideo, ValidatorInterface $validator): Response
    {
        $video = $repoVideo->find($id);
        $user = $this->security->getUser();

        if(!$video){
            return new JsonResponse('{
                "message": "error",
                "code" : 3,
                "data": "The video does not exist"}'
                ,Response::HTTP_BAD_REQUEST,[],true);
        }
        //Créer le commentaire à partir du body de la requête. 
        $comment = new Comment();
        $data = $request->getContent();
        $serializer->deserialize($data,Comment::class,'json',["object_to_populate" => $comment]);
        $comment->setVideo($video);
        $comment->setUser($user);

        $video->addComment($comment);
        $repoComment->add($comment);
        $repoVideo->add($video);

        return new JsonResponse('{
            "message": "OK",
            "Comment": '.$serializer->serialize($comment,'json', ["groups" => ["Comment:Read"]]).'}'
            ,Response::HTTP_CREATED,[],true);
     }

    /**
     * @Route(
     * path="/videos/{id}/comments",
     * name="get_videos_comments",
     * methods = {"GET"})
     */
    public function index($id, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer , UserRepository $repoUser, CommentRepository $repoComment, VideoRepository $repoVideo): Response
    {
        $video = $repoVideo->find($id);
        if(!$video){
            return new JsonResponse('{
                "message": "error",
                "code" : 3,
                "data": "The video does not exist"}'
                ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $comment = $repoComment->findByVideo($video);

        //Récupérer la vidéo à partir de l'id de la requête. 
        $results = $serializer->serialize($comment,'json', ["groups" => ["Comment:Read"]]);
        return new JsonResponse('{
            "message": "OK",
            "Comments": '.$results."}",Response::HTTP_CREATED,[],true);
    }

    
}
