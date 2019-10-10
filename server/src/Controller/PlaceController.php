<?php


namespace App\Controller;


use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;


class PlaceController extends AbstractController
{
    private $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'ne_lat' => 0,
            'ne_lng' => 0,
            'sw_lat' => 0,
            'sw_lng' => 0,
        ]);

        $this->options = $resolver->resolve($options);
    }

    /**
     * @Route("/api/place/list", name="list_place", methods={"get"})
     * @param Request $request
     * @param PlaceRepository $placeRepository
     * @return Response
     */
    public function list(Request $request, PlaceRepository $placeRepository): Response
    {
        if (!($request->query->get('ne_lat') && $request->query->get('ne_lng') && $request->query->get('sw_lat') && $request->query->get('sw_lng'))) {
            return new Response('Aucun restaurant trouvé', 400);
        }

        $neLat = $request->query->get('ne_lat') ?: $this->options['ne_lat'];
        $swLat = $request->query->get('sw_lat') ?: $this->options['sw_lat'];
        $neLng = $request->query->get('ne_lng') ?: $this->options['ne_lng'];
        $swLng = $request->query->get('sw_lng') ?: $this->options['sw_lng'];

        $places = $placeRepository->findByCoordonate($neLat, $neLng, $swLat, $swLng);

        if ($places === 0) {
            return new JsonResponse([]);
        }

        return new JsonResponse($places);

    }
}
