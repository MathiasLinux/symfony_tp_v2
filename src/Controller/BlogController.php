<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/blog/{id}/{slug}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Post $post, Security $security, EntityManagerInterface $entityManager, CommentRepository $commentRepository, LikeRepository $likeRepository): Response
    {
        $comment = new Comment();
        $comments = $commentRepository->findBy(
            ["post" => $post]
        );
        $likes = $likeRepository->findBy(
            ["comment" => $comments]
        );
        $user = $security->getUser();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
            $content = $commentForm->get("content")->getData();
            $date = new \DateTimeImmutable();
            $createdAt = $date->setTimestamp(time());

            $comment->setPost($post)
                ->setAuthor($user)
                ->setContent($content)
                ->setCreatedAt($createdAt);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute("app_blog_show", ["id" => $post->getId(), "slug" => $post->getSlug()]);
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $commentForm,
            'likes' => $likes
        ]);
    }

    #[Route('/blog/{id}/like/{commentId}', name: 'app_blog_like_comment', methods: ['GET'])]
    public function likeComment(CommentRepository $commentRepository, EntityManagerInterface $entityManager, Security $security, int $commentId, Post $post, LikeRepository $likeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $like = new Like();
        $user = $security->getUser();
        $currentComment = $commentRepository->find($commentId);
        if (!($likeRepository->findBy(
            ['user' => $user,
                'comment' => $currentComment]
        ))) {
            $like->setUser($user);
            $like->setComment($currentComment);
            $like->setDislike(false);

            $entityManager->persist($like);
            $entityManager->flush();
        } elseif ($likeRepository->findBy(
            ['user' => $user,
                'comment' => $currentComment,
                'dislike' => true]
        )) {
            $currentLike = $likeRepository->findOneBy(
                ['user' => $user,
                    'comment' => $currentComment,
                    'dislike' => true]
            );

            $entityManager->remove($currentLike);
            $entityManager->flush();

            $like->setUser($user);
            $like->setComment($currentComment);
            $like->setDislike(false);

            $entityManager->persist($like);
            $entityManager->flush();
        } else {
            $currentLike = $likeRepository->findOneBy(
                ['user' => $user,
                    'comment' => $currentComment]
            );

            $entityManager->remove($currentLike);
            $entityManager->flush();
        }


        return $this->redirectToRoute("app_blog_show", ["id" => $post->getId(), "slug" => $post->getSlug()]);

    }

    #[Route('/blog/{id}/dislike/{commentId}', name: 'app_blog_dislike_comment', methods: ['GET'])]
    public function dislikeComment(CommentRepository $commentRepository, EntityManagerInterface $entityManager, Security $security, int $commentId, Post $post, LikeRepository $likeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $like = new Like();
        $user = $security->getUser();
        $currentComment = $commentRepository->find($commentId);
        if (!($likeRepository->findBy(
            ['user' => $user,
                'comment' => $currentComment]
        ))) {
            $like->setUser($user);
            $like->setComment($currentComment);
            $like->setDislike(true);

            $entityManager->persist($like);
            $entityManager->flush();
        } elseif ($likeRepository->findBy(
            ['user' => $user,
                'comment' => $currentComment,
                'dislike' => false]
        )) {
            $currentLike = $likeRepository->findOneBy(
                ['user' => $user,
                    'comment' => $currentComment,
                    'dislike' => false]
            );

            $entityManager->remove($currentLike);
            $entityManager->flush();

            $like->setUser($user);
            $like->setComment($currentComment);
            $like->setDislike(true);

            $entityManager->persist($like);
            $entityManager->flush();
        } else {
            $currentLike = $likeRepository->findOneBy(
                ['user' => $user,
                    'comment' => $currentComment]
            );

            $entityManager->remove($currentLike);
            $entityManager->flush();
        }


        return $this->redirectToRoute("app_blog_show", ["id" => $post->getId(), "slug" => $post->getSlug()]);
    }
}
