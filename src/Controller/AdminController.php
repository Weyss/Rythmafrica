<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Category;
use App\Form\ArtistType;
use App\Form\CategoryType;
use App\Service\FileUploader;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
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
            'lastArtists' => $artistRepository->findBy( array(), array('id' => 'desc'), 5, 0),
        ]);
    }

    /**
     * @Route("/admin/artists", name="artists")
     */
    public function artists(ArtistRepository $artistRepository)
    {
        return $this->render('admin/artists.html.twig', [
            'allArtists' => $artistRepository->findBy( array(), array('id' => 'desc'))
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
    public function categories(CategoryRepository $categoryRepository)
    {
        return $this->render('admin/categories.html.twig',[
            'allCategories' => $categoryRepository->findAll()
        ]);
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
                    $pictureFileName = $fileUploader->uploadArtist($picture);
                    $artist->setPicture($pictureFileName);
                }else{
                    unlink($fileUploader->getTargetDirectoryArtist().'/'.  $artist->getPicture());
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
        unlink($fileUploader->getTargetDirectoryArtist().'/'. $picture);

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
    public function addCategory(Category $category=null, Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, CategoryRepository $categoryRepository)
    {
        if(!$category)
            $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $picture = $form->get('picture')->getData();

            if ($picture) {
                if($category->getId() == null){
                    $pictureFileName = $fileUploader->uploadCategory($picture);
                    $category->setPicture($pictureFileName);
                }else{
                    unlink($fileUploader->getTargetDirectoryCategory().'/'.  $category->getPicture());
                    $pictureFileName = $fileUploader->uploadCategory($picture);
                    $category->setPicture($pictureFileName);
                }
            }

            $entityManager->persist($category);
            $entityManager->flush(); 

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
            unlink($fileUploader->getTargetDirectoryCategory().'/'. $picture);

        $entityManager->remove($category);
        $entityManager->flush();

        $response = $this->allCategory($categoryRepository);
            
        return $response;
    
        return $this->redirectToRoute('categories');  
    }
    
    public function encodeJsonEntity($request){
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

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
    public function lastFiveArtists(ArtistRepository $artistRepository){

        return $this->encodeJsonEntity($artistRepository->findBy( array(), array('id' => 'desc'), 5, 0));
    }

    /**
     * @Route("/admin/category/json", name="json_category")
     */
    public function allCategory(CategoryRepository $categoryRepository){

        return $this->encodeJsonEntity($categoryRepository->findAll());
    }
}
