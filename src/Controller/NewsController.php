<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NewsController extends AbstractController
{

    public function CreateNews(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $news = new News();
        $news->setTitle('third news');
        $news->setPublicDate(new \DateTime("now"));
        $news->setStatus('active');
        $news->setContent('sdfsdf');


        // tell Doctrine you want to (eventually) save the News (no queries yet)
        $entityManager->persist($news);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        //return new Response('Saved new product with id '.$news->getId());
        $response=new Response();
        $response->setContent(json_encode([
            'Created'=> $news->getid()
        ]));
        return $response;
    }

    public function GetNews(int $id): Response
    {
        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->find($id);

        if (!$news) {
            throw $this->createNotFoundException(
                'No news found for id '.$id
            );
        }

        //return new Response('Check out this good news everyone: '.$news->getTitle());
        $response = new Response();
        $response->setContent(json_encode([
            'Title'=> $news->getTitle(),
            'Content'=> $news->getContent(),
            'Status'=> $news->getStatus(),
            'PublicDate'=> $news->getPublicDate(),
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    public function ListNews(): Response
    {
        $arrnews = $this->getDoctrine()
            ->getRepository(News::class)
            ->findAll();

        if (!$arrnews) {
            throw $this->createNotFoundException(
                'News not found'
            );
        }
/* $listTitles='';
foreach ($arrnews as $news )
    $listTitles=$listTitles.' \n'.$news->getTitle();
*/
        //return new Response('List news: '.$listTitles);
        $response = new Response();

        $arrNewsJson=[];
        foreach ($arrnews as $news ) {
            $temp=[
                'id'        => $news->getId(),
                'Title'     => $news->getTitle(),
                'Content'   => $news->getContent(),
                'Status'    => $news->getStatus(),
                'PublicDate'    => $news->getPublicDate(),
            ];
            array_push($arrNewsJson,$temp);
            }


            $response->setContent(json_encode(
                $arrNewsJson));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    public function DeleteNews(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(News::class)->find($id);


        if (!$news) {
            throw $this->createNotFoundException(
                'News not found'
            );
        }
        $entityManager->remove($news);
        $entityManager->flush();
        //return new Response('news is deleted. id is: '.$id);
        $response=new Response();
        $response->setContent(json_encode([
            'Deleted'=> $id
        ]));
        return $response;

    }

    public function UpdateNews(int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(News::class)->find($id);

        if (!$news) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $news->setTitle('New product name!');
        $news->setPublicDate(new \DateTime("now"));
        $news->setStatus('active');
        $news->setContent('sdfsdf');
        $entityManager->flush();

        /*return $this->redirectToRoute('news_get', [
            'id' => $news->getId()
        ]);*/
        $response=new Response();
        $response->setContent(json_encode([
            'Updated'=> $id
        ]));
        return $response;

    }
}
