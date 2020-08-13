<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Service\FileUploader;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="index")
     */
    public function index(ArtistRepository $artistRepository)
    {
        
        return $this->render('admin/index.html.twig', [
            'lastArtists' => $artistRepository->findBy( array(), array('id' => 'desc'), 5, 0)
        ]);
    }

    /**
     * @Route("/admin/artists", name="artists")
     */
    public function artists(ArtistRepository $artistRepository)
    {
        return $this->render('admin/artists.html.twig', [
            'allArtists' => $artistRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/musics", name="musics")
     */
    public function musics()
    {
        return $this->render('admin/musics.html.twig');
    }

    /**
     * @Route("/admin/events", name="events")
     */
    public function events()
    {
        return $this->render('admin/events.html.twig');
    }

    /**
     * @Route("/admin/categories", name="categories")
     */
    public function categories()
    {
        return $this->render('admin/categories.html.twig');
    }

    /**
     * @Route("/admin/artist/add", name="artist_add")
     * @Route("/admin/artist/edit/{id}", name="artist_edit")
     */
    public function addArtist(Artist $artist=null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, ArtistRepository $artistRepository)
    {
        if(!$artist)
            $artist = new Artist();
        
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $artist->setCreatedAt(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));

            $picture = $form->get('picture')->getData();

            if ($picture) {
                if($artist->getId() == null){
                    $pictureFileName = $fileUploader->upload($picture);
                    $artist->setPicture($pictureFileName);
                }else{
                    unlink($fileUploader->getTargetDirectory().'/'.  $artist->getPicture());
                    $pictureFileName = $fileUploader->upload($picture);
                    $artist->setPicture($pictureFileName);
                }
            }
            
            $entityManager->persist($artist);
            $entityManager->flush(); 

            $response = $this->lastFiveArtists($artistRepository);
            
            return $response;
        }

        return $this->render('admin/form.html.twig', [
            'formArtist' => $form->createView(), 
        ]);
    }

    /**
     * @Route("/admin/artist/delete/{id}", name="artist_delete")
     */
    public function deleteArtist(Artist $artist, EntityManagerInterface $entityManager, FileUploader $fileUploader, ArtistRepository $artistRepository)
    {
        $picture = $artist->getPicture();
        if ($picture)
        unlink($fileUploader->getTargetDirectory().'/'. $picture);

        $entityManager->remove($artist);
        $entityManager->flush();

        $response = $this->lastFiveArtists($artistRepository);
            
        return $response;
    
        return $this->redirectToRoute('index');  
    }


    /**
     * @Route("/admin/last/artists", name="last_artists")
     */
    public function lastFiveArtists(ArtistRepository $artistRepository){

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
        $artistSerialized = $serializer->serialize($artistRepository->findBy( array(), array('id' => 'desc'), 5, 0), 'json');

        $response = new Response();
        $response->setContent($artistSerialized);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
