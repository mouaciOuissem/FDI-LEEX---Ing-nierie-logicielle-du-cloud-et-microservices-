<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Entity\VideoFormat;
use App\Repository\UserRepository;
use App\Repository\VideoRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VideoFormatRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
   
    /**
     * Permet de créer une vidéo à partir d'un fichier et d'un nom.
     * 
     * @Route(
     * path="/users/{id}/videos",
     * name="post_users_videos",
     * methods = {"POST"})
     */
    public function create($id, Request $request, EntityManagerInterface $manager,SerializerInterface $serializer , UserRepository $repoUser, VideoRepository $repoVideo, ValidatorInterface $validator,DownloadHandler $downloadHandler): Response
    {
        if ( !$request->files->get('source')) {
            return new JsonResponse('{
                "message": "Not found",
                "code" : 6,
                "data": "Error: no file found. "}'
                ,Response::HTTP_NOT_FOUND,[],true);  
        }

        $user = $repoUser->find($id);
        if(!$user){
            return new JsonResponse('{
                "message": "Bad request",
                "code" : 2,
                "data": "The user does not exist"}'
                ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $video = new Video();
        $video->setFile($request->files->get('source'));
        $video->setName($request->get('name'));
        $video->setUser($user);
        $user->addVideo($video);
        $repoVideo->add($video);

        return $downloadHandler->downloadObject( $video, 'file', null, true);

       /*  return new JsonResponse('{
            "message": "OK",
            "Video": '.$serializer->serialize($video,'json', ["groups" => ["Video:Read"]]).'}'
            ,Response::HTTP_CREATED,[],true); */
    }

    /**
     * Permet de récupérer les vidéos d'un utilisateur.
     * 
     * @Route(
     * path="/users/{id}/videos",
     * name="get_users_videos",
     * methods = {"GET"})
     */
    public function index($id, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer , UserRepository $repoUser, VideoRepository $repoVideo,DownloadHandler $downloadHandler): Response
    {
        $user = $repoUser->find($id);
        if(!$user){
            return new JsonResponse('{
                "message": "Bad request",
                "code" : 2,
                "data": "The user does not exist"}'
                ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $videos = $repoVideo->findByUser($user);

        return $downloadHandler->downloadObject( $videos[0], 'file', null, true);

        /* $results = $serializer->serialize($videos,'json', ["groups" => ["Video:Read"]]);


        return new JsonResponse('{
            "message": "OK",
            "Videos": '.$results.'}',Response::HTTP_ACCEPTED,[],true); */
    }

    /**
     * Permet de modifier une vidéo en lui ajoutant un format.
     * 
     * @Route(
     * path="/videos/{id}",
     * name="patch_videos",
     * methods = {"PATCH"}),
     * 
     */
    public function patchVideos($id, Request $request, EntityManagerInterface $manager, SerializerInterface $serializer , VideoFormatRepository $repoFormat, VideoRepository $repoVideo,ValidatorInterface $validator): Response
    {
        //Récupérer la vidéo et valoriser avec le body.
        $video = $repoVideo->find($id);

        if ( !$request->files->get('file')) {
            return new JsonResponse('{
                "message": "Not found",
                "code" : 6,
                "data": "Error: no file found. "}'
                ,Response::HTTP_NOT_FOUND,[],true);  
        }

        if(!$video){
            return new JsonResponse('{
                "message": "Bad request",
                "code" : 3,
                "data": "The video does not exist"}'
                ,Response::HTTP_BAD_REQUEST,[],true);
        }
        //video existe => on créer les vidéos format
        $videoFormat = new VideoFormat();

        $videoFormat->setFormat($request->get('format'));
        $videoFormat->setFormatFile($request->files->get('file'));
        $videoFormat->setVideo($video);
        $video->addVideoFormat($videoFormat);
        if($request->get('format') == "144"){
            $video->setEncoded(true);
        }
        $repoFormat->add($videoFormat); 
        $repoVideo->add($video);

        $result = $serializer->serialize($video,'json',["groups" => "Video:Read"]);

        return new JsonResponse('{
            "message": "OK",
            "Video": '.$result.'}'
            ,Response::HTTP_CREATED,[],true);
    }

    
}
