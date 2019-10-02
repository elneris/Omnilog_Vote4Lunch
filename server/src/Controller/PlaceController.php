<?php


namespace App\Controller;


use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PlaceController extends AbstractController
{
    /**
     * @Route("/api/place/list", name="list_place", methods={"get"})
     * @param Request $request
     * @param PlaceRepository $placeRepository
     * @return Response
     */
    public function list(Request $request, PlaceRepository $placeRepository): Response
    {
        if ($request->query->get('ne_lat') && $request->query->get('ne_lng') && $request->query->get('sw_lat') && $request->query->get('sw_lng')) {
            $neLat = $request->query->get('ne_lat');
            $neLng = $request->query->get('ne_lng');
            $swLat = $request->query->get('sw_lat');
            $swLng = $request->query->get('sw_lng');
            $places = $placeRepository->findByCoordonate($neLat, $neLng, $swLat, $swLng);

            if ($places === 0) {
                return new Response([]);
            }

            return new Response(json_encode($places));
        }

        return new Response('Aucun restaurant trouvé', 400);
    }
}
