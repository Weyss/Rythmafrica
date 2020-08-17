<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Music;
use App\Entity\Artist;
use App\Form\EventType;
use App\Form\MusicType;
use App\Entity\Category;
use App\Form\ArtistType;
use App\Form\CategoryType;
use App\Service\FileUploader;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\MusicRepository;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ArtistRepository $artistRepository, EventRepository $eventRepository, MusicRepository $musicRepository)
    {
        dump($musicRepository->findBy(array(), array('id' => 'desc'), 5, 0));

        return $this->render('admin/index.html.twig', [
            'lastArtists' => $artistRepository->findBy(array(), array('id' => 'desc'), 5, 0),
            'lastEvents' => $eventRepository->findBy(array(), array('id' => 'desc'), 5, 0),
            'lastMusics' => $musicRepository->findBy(array(), array('id' => 'desc'), 5, 0),
        ]);
    }

    /**
     * @Route("/admin/artists", name="artists")
     */
    public function artists(ArtistRepository $artistRepository)
    {
        return $this->render('admin/artists.html.twig', [
            'allArtists' => $artistRepository->findBy(array(), array('id' => 'desc'))
        ]);
    }

    /**
     * @Route("/admin/musics", name="musics")
     */
    public function musics(MusicRepository $musicRepository)
    {
        return $this->render('admin/musics.html.twig', [
            'allMusics' => $musicRepository->findBy(array(), array('id' => 'desc'))
        ]);
    }

    /**
     * @Route("/admin/events", name="events")
     */
    public function events(EventRepository $eventRepository)
    {

        dump($eventRepository->findBy(array(), array('id' => 'desc')));
        return $this->render('admin/events.html.twig', [
            'allEvents' => $eventRepository->findBy(array(), array('id' => 'desc'))
        ]);
    }

    /**
     * @Route("/admin/categories", name="categories")
     */
    public function categories(CategoryRepository $categoryRepository)
    {
        return $this->render('admin/categories.html.twig', [
            'allCategories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/artist/add", name="artist_add")
     * @Route("/admin/artist/edit/{id}", name="artist_edit")
     */
    public function addArtist(Artist $artist = null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, ArtistRepository $artistRepository)
    {
        if (!$artist)
            $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $artist->setCreatedAt(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));

            $picture = $form->get('picture')->getData();

            if ($picture) {
                if ($artist->getId() == null) {
                    $pictureFileName = $fileUploader->uploadArtist($picture);
                    $artist->setPicture($pictureFileName);
                } else {
                    unlink($fileUploader->getTargetDirectoryArtist() . '/' .  $artist->getPicture());
                    $pictureFileName = $fileUploader->uploadArtist($picture);
                    $artist->setPicture($pictureFileName);
                }
            }

            $entityManager->persist($artist);
            $entityManager->flush();

            $response = $this->lastFiveArtists($artistRepository);

            return $response;
        }

        return $this->render('admin/formArtist.html.twig', [
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
            unlink($fileUploader->getTargetDirectoryArtist() . '/' . $picture);

        $entityManager->remove($artist);
        $entityManager->flush();

        $response = $this->lastFiveArtists($artistRepository);

        return $response;

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/admin/category/add", name="category_add")
     * @Route("/admin/category/edit/{id}", name="category_edit")
     */
    public function addCategory(Category $category = null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, CategoryRepository $categoryRepository)
    {
        if (!$category)
            $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();

            if ($picture) {
                if ($category->getId() == null) {
                    $pictureFileName = $fileUploader->uploadCategory($picture);
                    $category->setPicture($pictureFileName);
                } else {
                    unlink($fileUploader->getTargetDirectoryCategory() . '/' .  $category->getPicture());
                    $pictureFileName = $fileUploader->uploadCategory($picture);
                    $category->setPicture($pictureFileName);
                }
            }

            $entityManager->persist($category);
            $entityManager->flush();
            dump($category);
            $response = $this->allCategory($categoryRepository);

            return $response;
        }

        return $this->render('admin/formCategory.html.twig', [
            'formCategory' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="category_delete")
     */
    public function deleteCategory(Category $category, EntityManagerInterface $entityManager, FileUploader $fileUploader, CategoryRepository $categoryRepository)
    {
        $picture = $category->getPicture();
        if ($picture)
            unlink($fileUploader->getTargetDirectoryCategory() . '/' . $picture);

        $entityManager->remove($category);
        $entityManager->flush();

        $response = $this->allCategory($categoryRepository);

        return $response;

        return $this->redirectToRoute('categories');
    }

    /**
     * @Route("/admin/music/add", name="music_add")
     * @Route("/admin/music/edit/{id}", name="music_edit")
     */
    public function addMusic(Music $music = null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, MusicRepository $musicRepository)
    {
        if (!$music)
            $music = new Music();

        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $music->setCreatedAt(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));

            $picture = $form->get('picture')->getData();
            $song = $form->get('music')->getData();

            if ($picture) {
                if ($music->getId() == null) {
                    $pictureFileName = $fileUploader->uploadMusic($picture);
                    $music->setPicture($pictureFileName);
                } else {
                    unlink($fileUploader->getTargetDirectoryMusic() . '/' .  $music->getPicture());
                    $pictureFileName = $fileUploader->uploadMusic($picture);
                    $music->setPicture($pictureFileName);
                }
            }

            if ($song) {
                if ($music->getId() == null) {
                    $songFileName = $fileUploader->uploadSong($song);
                    $music->setMusic($songFileName);
                } else {
                    unlink($fileUploader->getTargetDirectorySong() . '/' .  $music->getMusic());
                    $songFileName = $fileUploader->uploadSong($song);
                    $music->setMusic($songFileName);
                }
            }

            $entityManager->persist($music);
            $entityManager->flush();


            $response = $this->lastFiveMusics($musicRepository);

            return $response;
        }

        return $this->render('admin/formMusic.html.twig', [
            'formMusic' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/music/delete/{id}", name="music_delete")
     */
    public function deleteMusic(Music $music, EntityManagerInterface $entityManager, FileUploader $fileUploader, EventRepository $eventRepository, MusicRepository $musicRepository)
    {
        $picture = $music->getPicture();
        if ($picture)
            unlink($fileUploader->getTargetDirectoryMusic() . '/' . $picture);

        // Suppression de la musique dans le fichier upload/songs
        unlink($fileUploader->getTargetDirectorySong() . '/' . $music->getMusic());

        $entityManager->remove($music);
        $entityManager->flush();

        $response = $this->lastFiveMusics($musicRepository);

        return $response;

        return $this->redirectToRoute('musics');
    }

    /**
     * @Route("/admin/event/add", name="event_add")
     * @Route("/admin/event/edit/{id}", name="event_edit")
     */
    public function addEvent(Event $event = null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, EventRepository $eventRepository)
    {
        if (!$event)
            $event = new Event;

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setStartingAt($form->get('startingAt')->getData());
            $event->setClosingAt($form->get('closingAt')->getData());

            $picture = $form->get('picture')->getData();
            if ($picture) {
                if ($event->getId() == null) {
                    $pictureFileName = $fileUploader->uploadEvent($picture);
                    $event->setPicture($pictureFileName);
                } else {
                    unlink($fileUploader->getTargetDirectoryEvent() . '/' .  $event->getPicture());
                    $pictureFileName = $fileUploader->uploadEvent($picture);
                    $event->setPicture($pictureFileName);
                }
            }

            $entityManager->persist($event);
            $entityManager->flush();

            $response = $this->lastFiveEvents($eventRepository);
            return $response;
        }

        return $this->render('admin/formEvent.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/event/delete/{id}", name="event_delete")
     */
    public function deleteEvent(Event $event, EntityManagerInterface $entityManager, FileUploader $fileUploader, EventRepository $eventRepository, CategoryRepository $categoryRepository)
    {
        $picture = $event->getPicture();
        if ($picture)
            unlink($fileUploader->getTargetDirectoryEvent() . '/' . $picture);


        $entityManager->remove($event);
        $entityManager->flush();

        $response = $this->lastFiveEvents($eventRepository);

        return $response;

        return $this->redirectToRoute('events');
    }

    public function encodeJsonEntity($request)
    {
        $encoders = array(new JsonEncoder());
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object;
            },
        ];
        $normalizers = array(new ObjectNormalizer(null, null, null, null, null, null, $defaultContext));

        $serializer = new Serializer($normalizers, $encoders);
        $requestSerialized = $serializer->serialize($request, 'json');

        $response = new Response();
        $response->setContent($requestSerialized);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/admin/artist/json", name="json_artist")
     */
    public function lastFiveArtists(ArtistRepository $artistRepository)
    {

        return $this->encodeJsonEntity($artistRepository->findBy(array(), array('id' => 'desc'), 5, 0));
    }

    /**
     * @Route("/admin/category/json", name="json_category")
     */
    public function allCategory(CategoryRepository $categoryRepository)
    {

        return $this->encodeJsonEntity($categoryRepository->findAll());
    }

    /**
     * @Route("/admin/event/json", name="json_event")
     */
    public function lastFiveEvents(EventRepository $eventRepository)
    {

        return $this->encodeJsonEntity($eventRepository->findBy(array(), array('id' => 'desc'), 5, 0));
    }

    /**
     * @Route("/admin/music/json", name="json_music")
     */
    public function lastFiveMusics(MusicRepository $musicRepository)
    {

        return $this->encodeJsonEntity($musicRepository->findBy(array(), array('id' => 'desc'), 5, 0));
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('admin');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
