<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogPostController extends AbstractController
{
    /**
     * @Route("/blog/list", name="blog_list")
     */
    public function listPostsAction()
    {
        $blogPost = $this->getDoctrine()
            ->getManager()
            ->getRepository(BlogPost ::class);
        $blogPostList = $blogPost->findAll();

        if (!$blogPost) {
            throw $this->createNotFoundException('Unable to find posts.');
        }

        return $this->render('blog_post/index.html.twig', [
            'blogPostList' => $blogPostList,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="editPost", requirements = {"id"="\d+"})
     * @Route("/blog/slug", name="slug", requirements = {"slug"="[a-zA-Z0-9_-]+"})
     * @Route("/blog/date/slug", name="date_slug", requirements = {"date"="\d\d\d\d"})
     */
    public function showPostAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $blogPost = $repository->find($id);

        if (!$blogPost) {
            throw $this->createNotFoundException(
                'No post found for id ' . $id
            );
        }

        return $this->render('blog_post/editPost.html.twig', [
            'blogPost' => $blogPost,
        ]);
    }

    /**
     * @Route("/admin/new-post", name="newPost")
     */

    public function newPostAction(Request $request)
    {
        $blogPost = new BlogPost();

        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('editPost', [
                'id' => $blogPost->getId()
            ]);
        }

        return $this->render('admin/new.html.twig',
            ['form' => $form->createView(),

            ]);
    }

    /**
     * @Route("/admin/update-post/{id}", name="updatePost", requirements ={"id"="\d+"})
     */

    public function updatePostAction($id, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $blogPost = $entityManager->getRepository(BlogPost::class)->find($id);


        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();


            return $this->redirectToRoute('editPost', array('id' => $blogPost->getId())
            );
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function createDeleteForm(BlogPost $blogPost)
    {
        return $this->createFormBuilder($blogPost)
            ->setAction($this->generateUrl('delete-post', array('id' => $blogPost->getId())))
            ->setMethod('DELETE')
            ->add('title', TextType::class)
            ->add('content', TextType::class)
            ->add('category', TextType :: class)
            ->add('delete', SubmitType::class)
            ->getForm();
    }
    /**
     * @Route("/admin/delete-post/{id}", name="delete-post", requirements={"id" = "\d+"})
     */
    public function deletePostAction($id, Request $request) {
        $blogPost = $this->getDoctrine()
            ->getRepository(BlogPost::class)
            ->find($id);
        $form = $this->createDeleteForm($blogPost);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blogPost);
            $em->flush();
            return $this->redirectToRoute('blog_list');
        }
        return $this->render('admin/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/feat", name="posts_feat_list")
     */
    public function editPostsFeat() {

        $blogPost = $this->getDoctrine()
            ->getManager()
            ->getRepository(BlogPost ::class);
        $blogPostList = $blogPost->findBy(['featured' => 1]);

        return $this->render('blog_post/featured.html.twig', [
            'blogPostList' => $blogPostList,
        ]);
    }
}
