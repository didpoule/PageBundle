<?php

namespace didpoule\PageBundle\Controller;

use didpoule\PageBundle\Entity\Category;
use didpoule\PageBundle\Entity\Page;
use didpoule\PageBundle\Form\CategoryType;
use didpoule\PageBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    public function viewAction($category, $slug)
    {
        $em = $this->getDoctrine()->getRepository('didpoulePageBundle:Page');

        $page = $em->findOneByCategory($category, $slug);

        if ($page === null) {
            throw new NotFoundHttpException('Page not found: ' . $slug);
        }

        $this->get('didpoule_page_access_checker')->checkAccess($page);
        return $this->render('didpoulePageBundle:Page:view.html.twig', [
            'page' => $page
        ]);
    }

    public function addAction(Request $request)
    {
        $page = new Page();

        $form = $this->createForm(PageType::class, $page, ['user_roles' => $this->get('didpoule_page.roles')]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($page->getRole() === NULL) {
                $page->setRole($this->getParameter('didpoule_page.config')['default_role']);
            }

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('didpoule_page_view', [
                'category' => $page->getCategories()->first()->getTitle(),
                'slug' => $page->getSlug()]);

        }

        return $this->render('didpoulePageBundle:Page:add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function addCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return $this->render('@didpoulePage/Category/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}