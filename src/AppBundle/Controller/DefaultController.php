<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use AppBundle\Registry\CalculatorRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/automaton/{model}/change/{amount}", name="automaton_change_route", methods={"GET"})
     * @param string $model
     * @param int $amount
     * @return Response
     */

    public function automatonChangeAction(string $model, int $amount): Response
    {
        $calculator = (new CalculatorRegistry())->getCalculatorFor($model);

        if (!$calculator) {
            //unknown calculator model
            return new Response(null,404);
        }

        $change = $calculator->getChange($amount);

        return $change
            ? new JsonResponse(
                $change,
                200, //possible change
                ['Content-Type' => 'application/json']
            )
            : new Response(
                null,
                204 //not possible change
            );
    }
}
