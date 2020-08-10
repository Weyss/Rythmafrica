<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/artists", name="artists")
     */
    public function artists()
    {
        return $this->render('admin/artists.html.twig');
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
    public function addArtist(Request $request)
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);

        

        return $this->render('admin/form.html.twig', [
            'formArtist' => $form->createView(),
        ]);
    }
}
