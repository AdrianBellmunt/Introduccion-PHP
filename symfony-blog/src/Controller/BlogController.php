<?php


namespace App\Controller;

use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentFormType;
use App\Form\PostFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function about(): Response
    {
        return $this->render('blog/blog.html.twig', []);
    }

    #[Route('/singlepost', name: 'singlepost')]
    public function singlepost(): Response
    {
        return $this->render('blog/singlepost.html.twig', []);
    }

    #[Route('/blog/new', name: 'new_post')]
public function newPost(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
{
    $post = new Post();
    $form = $this->createForm(PostFormType::class, $post);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('Image')->getData();
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
            // Move the file to the directory where images are stored
            try {
                
                $file->move(
                    $this->getParameter('post_image_directory'), $newFilename
                );
               
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $post->setImage($newFilename);
        }
    
        $post = $form->getData();   
        $post->setSlug($slugger->slug($post->getTitle()));
        $post->setPostUser($this->getUser());
        $post->setNumLikes(0);
        $post->setNumComments(0);
        $entityManager = $doctrine->getManager();    
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->render('blog/new_post.html.twig', array(
            'form' => $form->createView()    
        ));

    }
        
    return $this->render('blog/new_post.html.twig', array(
        'form' => $form->createView()    
    ));
}


}
