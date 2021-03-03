<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class LuckyController extends AbstractController
{
    public function number(): Response
    {
        $number = random_int(0, 100);
        /*$em = $this->getDoctrine()->getManager();
        $em->getConnection()->connect();
        $connected = $em->getConnection()->isConnected();*/
        //phpinfo();
        $content_json=json_encode([
"Title"=> "thirdnews",
"Content"=> "sdfsdf",
"Status"=> "active"
]);
        $content_arr=json_decode($content_json,true);//$content_arr['Title']

        return new Response(
            '<html><body>'.$content_arr['Title'].'</body></html>'
        );
    }
}