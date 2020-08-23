<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('front/acceuil.html.twig', [
           
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        return $this->render('front/search.html.twig', [
           
        ]);
    }

    /**
     * @Route("/artists", name="artists")
     */
    public function listArtists()
    {
        return $this->render('front/artists.html.twig', [
           
        ]);
    }

    /**
     * @Route("/musics", name="musics")
     */
    public function listMusics()
    {
        return $this->render('front/musics.html.twig', [
           
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function listEvents()
    {
        return $this->render('front/events.html.twig', [
           
        ]);
    }

    /**
     * @Route("/detail/artist", name="detail_artist")
     */
    public function detailArtist()
    {
        return $this->render('front/detailArtist.html.twig', [
           
        ]);
    }

    /**
     * @Route("/detail/music", name="detail_music")
     */
    public function detailMusic()
    {
        return $this->render('front/detailMusic.html.twig', [
           
        ]);
    }

    /**
     * @Route("/detail/event", name="detail_event")
     */
    public function detailEvent()
    {
        return $this->render('front/detailEvent.html.twig', [
           
        ]);
    }
}
