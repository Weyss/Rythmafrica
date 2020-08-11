<?php

namespace App\Controller;


use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
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
        dump($artistRepository->findBy( array(), array('id' => 'desc'), 5, 1));
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
    public function addArtist(Request $request, EntityManagerInterface $entityManager)
    {
        $artist = new Artist();
        
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $artist->setCreatedAt(new \DateTime());

            $entityManager->persist($artist);
            $entityManager->flush(); 
            $response = new Response();
            $response->setContent(json_encode([$request->request->get('artist')]));
            $response->headers->set('Content-Type', 'application/json');
    
            //dump($response);
            return $response;
        }
           
  

        return $this->render('admin/form.html.twig', [
            'formArtist' => $form->createView(),
            
        ]);
    }
}
