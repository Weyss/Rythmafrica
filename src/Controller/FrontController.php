<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Music;
use App\Entity\Artist;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\EventRepository;
use App\Repository\MusicRepository;
use App\Repository\ArtistRepository;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(MusicRepository $musicRepository, EventRepository $event)
    {
        dump($musicRepository->findBy(array(), array('id' => 'desc'), 6, 0));
        return $this->render('front/acceuil.html.twig', [
           'musics' => $musicRepository->findBy(array(), array('id' => 'desc'), 6, 0),
           'searchBar' => $this->formSearch(),
           'events' => $event ->findAll()
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, ArtistRepository $artist, MusicRepository $music, EventRepository $event)
    {   
        $query = $request->request->get('form')['query'];

        return $this->render('front/search.html.twig', [
            'searchBar' => $this->formSearch(),
            'musics' => $music->findMusics($query),
            'artists' => $artist->findNicknames($query),
            'events' => $event ->findAll() 
        ]);
    }

    public function formSearch(){
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('search'))
            ->add('query', TextType::class)
            ->getForm();

            return $form->createView();
    }

    /**
     * @Route("/artists", name="artists")
     */
    public function listArtists(ArtistRepository $artistRepository)
    {
        return $this->render('front/artists.html.twig', [
            'allArtists' => $artistRepository->findBy(array(), array('id' => 'desc')),
            'searchBar' => $this->formSearch()
        ]);
    }

    /**
     * @Route("/musics", name="musics")
     */
    public function listMusics(MusicRepository $musicRepository)
    {
        return $this->render('front/musics.html.twig', [
            'allMusics' => $musicRepository->findBy(array(), array('id' => 'desc')),
            'searchBar' => $this->formSearch()
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function listEvents(EventRepository $eventRepository)
    {
        return $this->render('front/events.html.twig', [
            'allEvents' => $eventRepository->findBy(array(), array('id' => 'desc')),
            'searchBar' => $this->formSearch()
        ]);
    }

    /**
     * @Route("/detail/artist/{id}", name="detail_artist")
     */
    public function detailArtist(Artist $artist)
    {

        return $this->render('front/detailArtist.html.twig', [
            'artist'=> $artist,
            'searchBar' => $this->formSearch()
        ]);
    }

    /**
     * @Route("/detail/music/{id}", name="detail_music")
     */
    public function detailMusic(Music $music)
    {
        return $this->render('front/detailMusic.html.twig', [
           'music'=> $music,
           'musicsArtist' => $music->getArtist()->getMusics(),
           'searchBar' => $this->formSearch()
        ]);
    }

    /**
     * @Route("/detail/event/{id}", name="detail_event")
     */
    public function detailEvent(Event $event)
    {
        return $this->render('front/detailEvent.html.twig', [
            'event'=> $event,
            'searchBar' => $this->formSearch()
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
        }

        return $this->render('front/contact.html.twig', [
            'formContact' => $form->createView(),
        ]);
    }

    /**
     * @Route("/mentions", name="mentions")
     */
    public function showMentions()
    {
        return $this->render('front/mentions.html.twig', [

        ]);
    }
}
