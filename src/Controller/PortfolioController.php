<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesType;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PortfolioController extends AbstractController
{
    
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    /**
     * @Route("/portfolio/couleur",name="portfolio_color")
     */
    public function indexColor(ImagesRepository $imagesRepository)
    {
        return $this->render('portfolio/index.html.twig', [
            'images' => $imagesRepository->findBy(['category' => 'color'])
        ]);
    }

    /**
     * @Route("/portfolio/calligraphie",name="portfolio_cali")
     */
    public function indexCali(ImagesRepository $imagesRepository)
    {
        return $this->render('portfolio/index.html.twig', [
            'images' => $imagesRepository->findBy(['category' => 'cali'])
        ]);
    }

    /**
     * @Route("/portfolio/noir&blanc",name="portfolio_blackandwhite")
     */
    public function indexBlackandwhite(ImagesRepository $imagesRepository)
    {
        return $this->render('portfolio/index.html.twig', [
            'images' => $imagesRepository->findBy(['category' => 'blackandwhite'])
        ]);
    }

    /**
     * @Route("/portfolio/realisme",name="portfolio_realism")
     */
    public function indexRealism(ImagesRepository $imagesRepository)
    {
        return $this->render('portfolio/index.html.twig', [
            'images' => $imagesRepository->findBy(['category' => 'realism'])
        ]);
    }
    
    /**
     * @Route("/portfolio/image/{id}",name="portfolioImageShow")
     */
    public function show(Images $image)
    {
        return $this->render('portfolio/show.html.twig', compact('image'));
    }
    
    /**
     * @Route("/admin/portfolio/image/add",name="addImages")
     */
    public function addImage(Request $request) : Response 
    {
        $images = new Images;

        $form = $this->createForm(ImagesType::class, $images);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerInterface->persist($images);
            $this->entityManagerInterface->flush();



            $this->addFlash('success', 'Photo ajoutée avec succès');
            return $this->redirectToRoute('addImages');
        }
        return $this->render('admin/portfolio/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/admin/portfolio/image/{id}/edit",name="editImage")
     */
    public function EditImage(Images $images,CacheManager $cacheManager,UploaderHelper $helper, Request $request) : Response 
    {

        $form = $this->createForm(ImagesType::class, $images, [
            'method' => 'PUT'
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($images->getImageFile() instanceof UploadedFile) {
                $cacheManager->remove($helper->asset($images, 'imageFile'));
            }
            $this->entityManagerInterface->flush();



            $this->addFlash('success', 'Photo modifiée avec succès');
            return $this->redirectToRoute('portfolio_' . $images->getCategory());
        }
        return $this->render('admin/portfolio/edit.html.twig', [
            'images' => $images,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/portfolio/image/{id}/delete",name="deleteImage")
     */
    public function deleteImage(Images $images,  Request $request, CacheManager $cacheManager, UploaderHelper $helper)
    {
        if ($this->isCsrfTokenValid('image_delete' . $images->getId(), $request->request->get('token'))) {
            $cacheManager->remove($helper->asset($images, 'imageFile'));
            $this->entityManagerInterface->remove($images);
            $this->entityManagerInterface->flush();

            $this->addFlash('success', 'image supprimée avec succès');
        }

        return $this->redirectToRoute('portfolio_' . $images->getCategory());
    }

}
