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
        phpinfo();
        return new Response(
            '<html><body>Connection is : '.$number.'</body></html>'
        );
    }
}