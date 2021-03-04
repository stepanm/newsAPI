<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends AbstractController
{

    public function CreateNews(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $content = $request->getContent();
        $arrcontent=json_decode($content,true);

        $news = new News();
        $news->setTitle( $arrcontent["Title"]);
        $news->setStatus( $arrcontent["Status"]);
        $news->setContent( $arrcontent["Content"]);
        $news->setPublicDate(new \DateTime("now"));

        // tell Doctrine you want to (eventually) save the News (no queries yet)
        $entityManager->persist($news);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $response=new Response();
        $response->setContent(
          json_encode([
            'Created'=> $news->getid()
        ])
        );
        $response->headers->set('Content-Type', 'application/json');
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

        $response = new Response();
        $response->setContent(json_encode([
            "id"=>$news->getid(),
            "Title"=> $news->getTitle(),
            "Content"=> $news->getContent(),
            "Status"=> $news->getStatus(),
            "PublicDate"=> $news->getPublicDate()
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

/*
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
*/

    public function ListNews(Request $request): Response
    {
        $limit = $request->query->get('limit', 10000);
        $page = $request->query->get('page', 1);

        $offset=$limit*($page-1);
        if($limit==10000){
            $offset=0;
        }
        $arrnews = $this->getDoctrine()
            ->getRepository(News::class)
            ->findBy(array(),array('id'=>'ASC'),$limit,$offset);

               if (!$arrnews) {
                   throw $this->createNotFoundException(
                       'News not found'
                   );
               }

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
        $response=new Response();
        $response->setContent(json_encode([
            'Deleted'=> $id
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    public function UpdateNews(int $id,Request $request): Response
    {
        $content = $request->getContent();
        $arrcontent=json_decode($content,true);
        $entityManager = $this->getDoctrine()->getManager();
        $news = $entityManager->getRepository(News::class)->find($id);

        if (!$news) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
       //условие - что если придут не все поля
        $news->setTitle($arrcontent["Title"]);
        $news->setPublicDate(new \DateTime("now"));
        $news->setStatus($arrcontent["Status"]);
        $news->setContent($arrcontent["Content"]);
        $entityManager->flush();

        /*return $this->redirectToRoute('news_get', [
            'id' => $news->getId()
        ]);*/
        $response=new Response();
        $response->setContent(json_encode([
            'Updated'=> $id
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}
