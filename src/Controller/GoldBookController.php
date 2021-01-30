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
     * @Route("/goldbook", name="goldbook_index", methods="GET")
     * @param GoldbookRepository $goldbookRepository
     * @return Response
     */
    public function index(GoldbookRepository $goldbookRepository): Response
    {
        return $this->render('goldbook/index.html.twig', [
            'goldbook' => $goldbookRepository->findBy( [], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/goldbook/{id}/show",name="goldbook_show", methods="GET")
     * @param Goldbook $goldbook
     * @return Response
     */
    public function show(Goldbook $goldbook = null): Response
    {
        if ($goldbook === null)
        {
            throw $this->createNotFoundException("Ooops ! -_-' Cette page n'existe pas");
        }
        return $this->render('goldbook/show.html.twig',[
            'goldbook' => $goldbook
        ]);
    }

    /**
     * @Route("goldbook/create", name="goldbook_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManagerInterface
     * @return Response
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
     * @param Goldbook $goldbook
     * @param Request $request
     * @param EntityManagerInterface $entityManagerInterface
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Goldbook $goldbook, Request $request, EntityManagerInterface $entityManagerInterface): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        if ($this->isCsrfTokenValid('goldbook_delete' . $goldbook->getId(), $request->request->get('token'))) {
            $entityManagerInterface->remove($goldbook);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Message supprimée avec succès');
        }

        return $this->redirectToRoute('goldbook_index');
    }
}
