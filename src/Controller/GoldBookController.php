<?php

namespace App\Controller;

use App\Entity\Goldbook;
use App\Form\GoldbookType;
use App\Repository\GoldbookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GoldBookController extends AbstractController
{
    /**
     * @Route("/goldbook", name="goldbook_index")
     */
    public function index(GoldbookRepository $goldbookRepository)
    {
        return $this->render('goldbook/index.html.twig', [
            'goldbook' => $goldbookRepository->findBy( [], ['createdAt' => 'DESC'])
        ]);
    }
    
    /**
     * @Route("/goldbook/{id}/show",name="goldbook_show")
     */
    public function show(Goldbook $goldbook)
    {
        return $this->render('goldbook/show.html.twig',[
            'goldbook' => $goldbook
        ]);
    }

    /**
     * @Route("goldbook/create", name="goldbook_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManagerInterface) : Response
    {
        $goldbook = new Goldbook;
        $form = $this->createForm(GoldbookType::class, $goldbook);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($goldbook);
            $entityManagerInterface->flush();



            $this->addFlash('success', 'Message ajoutée avec succès');
            return $this->redirectToRoute('goldbook_index');
        }
        return $this->render('goldbook/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/goldbook/{id}/delete", name="goldbook_delete")
     */
    public function delete(Goldbook $goldbook, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        if ($this->isCsrfTokenValid('goldbook_delete' . $goldbook->getId(), $request->request->get('token'))) {
            $entityManagerInterface->remove($goldbook);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Message supprimée avec succès');
        }

        return $this->redirectToRoute('goldbook_index');
    }
}
