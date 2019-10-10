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
    private $resolver;

    public function __construct()
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->resolver = $resolver;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
            'ne_lat' => null,
            'ne_lng' => null,
            'sw_lat' => null,
            'sw_lng' => null,
        ])
            ->setAllowedTypes('ne_lat', 'string')
            ->setAllowedTypes('sw_lat', 'string')
            ->setAllowedTypes('ne_lng', 'string')
            ->setAllowedTypes('sw_lng', 'string')
        ;
    }

    /**
     * @Route("/api/place/list", name="list_place", methods={"get"})
     * @param Request $request
     * @param PlaceRepository $placeRepository
     * @return Response
     */
    public function list(Request $request, PlaceRepository $placeRepository): Response
    {
        $options = $this->resolver->resolve($request->query->all());

        $neLat = $options['ne_lat'];
        $swLat = $options['sw_lat'];
        $neLng = $options['ne_lng'];
        $swLng = $options['sw_lng'];

        $places = $placeRepository->findByCoordonate($neLat, $neLng, $swLat, $swLng);

        if ($places === 0) {
            return new JsonResponse([]);
        }

        return new JsonResponse($places);

    }
}
