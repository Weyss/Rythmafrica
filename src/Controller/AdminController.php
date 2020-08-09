<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="index")
     */
    public function index(Request $request)
    {
        $artist = new Artist;

        $formArtist = $this->createForm(ArtistType::class, $artist);
        $formArtist->handleRequest($request);

        dump($request);

        return $this->render('admin/index.html.twig', [
            'formArtist' => $formArtist->createView(),
        ]);
    }

    /**
     * @Route("/admin/artists", name="artists")
     */
    public function artists(Request $request)
    {
        $artist = new Artist;

        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        return $this->render('admin/artists.html.twig', [
            'formArtist' => $form->createView(),
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
}
