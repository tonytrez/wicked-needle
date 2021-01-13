<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImagesType;
use App\Repository\ImagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PortfolioController extends AbstractController
{
    /**
     * Display index of each category
     * 
     * @Route("/portfolio/{category}",name="portfolioCategory", methods="GET")
     */
    public function indexByCategory(ImagesRepository $imagesRepository, Request $request)
    {
        return $this->render('portfolio/index.html.twig', [
            'images' => $imagesRepository->findBy(['category' => $request->attributes->get('category')])
        ]);
    }
    
    /**
     * Display one image
     * 
     * @Route("/portfolio/image/{id}",name="portfolioImageShow", methods="GET")
     */
    public function show(Images $image = null)
    {
        if($image === null)
        {
            throw $this->createNotFoundException('Cette image est inexistante');
        }
        return $this->render('portfolio/show.html.twig', compact('image'));
    }
    
    /**
     * Add one image
     * 
     * @Route("/admin/portfolio/image/add",name="addImages", methods={"GET","POST"})
     */
    public function addImage(Request $request, EntityManagerInterface $entityManagerInterface) : Response 
    {
        $images = new Images;

        $form = $this->createForm(ImagesType::class, $images);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManagerInterface->persist($images);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Photo ajoutée avec succès');
            return $this->redirectToRoute('addImages');
        }
        return $this->render('admin/portfolio/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Edit one image
     * 
     * @Route("/admin/portfolio/image/{id}/edit",name="editImage", methods="PUT")
     */
    public function editImage(Images $image = null, Request $request, EntityManagerInterface $entityManagerInterface) : Response 
    {
        if($image === null)
        {
            throw $this->createNotFoundException('Cette image est inexistante');
        }

        $form = $this->createForm(ImagesType::class, $image, [
            'method' => 'PUT'
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
    
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Photo modifiée avec succès');
            return $this->redirectToRoute('portfolio_' . $image->getCategory());
        }
        return $this->render('admin/portfolio/edit.html.twig', [
            'images' => $image,
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete one image
     * 
     * @Route("/admin/portfolio/image/{id}/delete",name="deleteImage", methods="DELETE")
     */
    public function deleteImage(Images $image = null, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        if($image === null)
        {
            throw $this->createNotFoundException('Cette image est inexistante');
        }


        if ($this->isCsrfTokenValid('image_delete' . $image->getId(), $request->request->get('token')))
        {
            $entityManagerInterface->remove($image);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'image supprimée avec succès');
        }

        return $this->redirectToRoute('portfolioCategory', ['category' => $image->getCategory()]);
    }

}
