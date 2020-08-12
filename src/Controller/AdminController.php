<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Repository\MusicRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     */
    public function addArtist(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, ArtistRepository $artistRepository)
    {
        $artist = new Artist();
        
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $artist->setCreatedAt(new \DateTime());
            $picture = $form->get('picture')->getData();

            if ($picture) {
                $pictureFileName = $fileUploader->upload($picture);
                $artist->setPicture($pictureFileName);
            }

            $entityManager->persist($artist);
            $entityManager->flush(); 

            $response = new Response();
            $response->setContent(json_encode([$request->request->get('artist'), 'picture' => $pictureFileName]));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        }

        return $this->render('admin/form.html.twig', [
            'formArtist' => $form->createView(), 
        ]);
    }

    /**
     * @Route("/admin/artist/delete/{id}", name="artist_delete")
     */
    public function deleteArtist(Artist $artist, EntityManagerInterface $entityManager, ArtistRepository $artistRepository)
    {
        $artist->getPicture();
        $entityManager->remove($artist);
        $entityManager->flush();


        

        return $this->redirectToRoute('index');
        
    }

    public function fiveLastArtist(ArtistRepository $artistRepository){
        $lastArtists = json_encode($artistRepository->findBy( array(), array('id' => 'desc'), 5, 0));

        return $lastArtists;
    }
}
