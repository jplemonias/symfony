<?php
// src/Controller/TestController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function randomNumber(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}