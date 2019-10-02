<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/user")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/add", name="user_add", methods={"post"})
     * @param Request $request
     * @return JsonResponse|null
     */
    public function add(Request $request): ?JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data['password'] !== $data['password_repeat']) {
            return new JsonResponse(['created' => false, 'message' => 'Submitted passwords are differents']);
        }

        if ($data['pseudo'] && $data['email'] && $data['password'] && $data['password_repeat']) {
            $user = new User();
            $user->setPseudo($data['pseudo']);
            $user->setEmail($data['email']);
            $user->setPassword($this->encoder->encodePassword($user, $data['password']));

            $this->manager->persist($user);
            $this->manager->flush();

            return new JsonResponse(['created' => true]);
        }

        throw new BadRequestHttpException('Errors duraing add user','', 400);
    }

    /**
     * @Route("/exists", name="user_exists", methods={"get"})
     * @param Request $request
     * @return JsonResponse|null
     */
    public function exists(Request $request): ?JsonResponse
    {
        $data = $request->query->all();

        if (isset($data['pseudo'])) {
            $users = $this->userRepository->findBy(['pseudo' => $data['pseudo']]);

            if (!$users) {
                return new JsonResponse(['exist' => false]);
            }

            return new JsonResponse(['exist' => true]);
        }

        if (isset($data['email'])) {
            $users = $this->userRepository->findBy(['email' => $data['email']]);

            if (!$users) {
                return new JsonResponse(['exist' => false]);
            }

            return new JsonResponse(['exist' => true]);
        }

        throw new BadRequestHttpException('Errors during check if user exist','', 400);
    }

    /**
     * @Route("/login", name="user_login", methods={"post"})
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data['pseudo']) {
            $user = $this->userRepository->findOneBy(['pseudo' => $data['pseudo']]);

            if (!$user) {
                return new JsonResponse(['login' => false]);
            }

            $validPassword = $this->encoder->isPasswordValid($user, $data['password']);

            if ($validPassword) {
                return new JsonResponse(['login' => true, 'pseudo' => $data['pseudo'], 'email' => $data['password']]);
            }

            return new JsonResponse(['login' => false]);
        }

        throw new BadRequestHttpException('Errors during the login process','', 400);
    }
}
