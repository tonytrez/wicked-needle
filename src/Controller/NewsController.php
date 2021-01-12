<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsController extends AbstractController
{
    /**
     * @Route("/news",name="news_index")
     */
    public function Index(NewsRepository $newsRepository)
    {
        return $this->render('news/index.html.twig', [
            'news' => $newsRepository->findBy( [], ['createdAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/news/{id}/show",name="news_show")
     */
    public function show(News $news)
    {
        return $this->render('news/show.html.twig',[
            'news' => $news
        ]);
    }

    /**
     * @Route("/admin/news/create", name="news_create")
     */
    public function createNews(Request $request, EntityManagerInterface $entityManagerInterface) : Response
    {
        $news = new News;
        $form = $this->createForm(NewsType::class, $news);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($news);
            $entityManagerInterface->flush();



            $this->addFlash('success', 'News ajoutée avec succès');
            return $this->redirectToRoute('news_index');
        }
        return $this->render('admin/news/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/news/{id}/edit",name="news_update")
     */
    public function Update(News $news, Request $request, EntityManagerInterface $entityManagerInterface) : Response 
    {

        $form = $this->createForm(NewsType::class, $news, [
            'method' => 'PUT'
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->flush();



            $this->addFlash('success', 'News modifiée avec succès');
            return $this->redirectToRoute('news_index');
        }
        return $this->render('admin/news/update.html.twig', [
            'news' => $news,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/news/{id}/delete", name="news_delete")
     */
    public function delete(News $news, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        if ($this->isCsrfTokenValid('news_delete' . $news->getId(), $request->request->get('token'))) {
            $entityManagerInterface->remove($news);
            $entityManagerInterface->flush();

            $this->addFlash('success', 'News supprimée avec succès');
        }

        return $this->redirectToRoute('news_index');
    }

}
