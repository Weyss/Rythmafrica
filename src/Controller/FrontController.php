<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Music;
use App\Entity\Contact;
use App\Entity\Event;
use App\Form\ContactType;
use App\Repository\MusicRepository;
use App\Notification\ContactNotification;
use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(MusicRepository $musicRepository)
    {
        return $this->render('front/acceuil.html.twig', [
           'musics' => $musicRepository->findBy(array(), array('id' => 'desc'), 6, 0)
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
    public function listArtists(ArtistRepository $artistRepository)
    {
        return $this->render('front/artists.html.twig', [
            'allArtists' => $artistRepository->findBy(array(), array('id' => 'desc'))
        ]);
    }

    /**
     * @Route("/musics", name="musics")
     */
    public function listMusics(MusicRepository $musicRepository)
    {
        return $this->render('front/musics.html.twig', [
            'allMusics' => $musicRepository->findBy(array(), array('id' => 'desc'))
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function listEvents(EventRepository $eventRepository)
    {
        return $this->render('front/events.html.twig', [
            'allEvents' => $eventRepository->findBy(array(), array('id' => 'desc'))
        ]);
    }

    /**
     * @Route("/detail/artist/{id}", name="detail_artist")
     */
    public function detailArtist(Artist $artist)
    {

        return $this->render('front/detailArtist.html.twig', [
            'artist'=> $artist,
        ]);
    }

    /**
     * @Route("/detail/music/{id}", name="detail_music")
     */
    public function detailMusic(Music $music)
    {
        return $this->render('front/detailMusic.html.twig', [
           'music'=> $music,
           'musicsArtist' => $music->getArtist()->getMusics()
        ]);
    }

    /**
     * @Route("/detail/event/{id}", name="detail_event")
     */
    public function detailEvent(Event $event)
    {
        return $this->render('front/detailEvent.html.twig', [
            'event'=> $event,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, ContactNotification $contactNotification)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contactNotification->notify($contact);
            $this->addFlash('succes', 'Votre mail abien été envoyer');
            
        }

        return $this->render('front/contact.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }
}
