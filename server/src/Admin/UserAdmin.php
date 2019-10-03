<?php


namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserAdmin extends AbstractAdmin
{
    private $encoder;

    public function __construct($code, $class, $baseControllerName, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->encoder = $encoder;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('pseudo')
            ->add('email')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
            ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('pseudo')
            ->add('email')
            ->add('roles')
            ->add('votes', [], ['associated_property' => 'title', 'label' => 'Liste des votes'])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $rolesChoices = self::flattenRoles($roles);
        $form
            ->add('pseudo')
            ->add('email')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit être identique',
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $rolesChoices,
                'multiple' => true
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('pseudo')
            ->add('email')
        ;
    }

    protected static function flattenRoles($rolesHierarchy)
    {
        $flatRoles = array();
        foreach($rolesHierarchy as $roles) {

            if(empty($roles)) {
                continue;
            }

            foreach($roles as $role) {
                if(!isset($flatRoles[$role])) {
                    $flatRoles[$role] = $role;
                }
            }
        }

        return $flatRoles;
    }

    public function preUpdate($object) {
        $uniqid = $this->getRequest()->query->get('uniqid');
        $formData = $this->getRequest()->request->get($uniqid);

        if(array_key_exists('password', $formData) && $formData['password'] !== null && strlen($formData['password']['first' ]) > 0) {
            $object->setPassword($this->encoder->encodePassword($object, $formData['password']['first' ]));
        }
    }

    public function prePersist($object) {
        $uniqid = $this->getRequest()->query->get('uniqid');
        $formData = $this->getRequest()->request->get($uniqid);

        if(array_key_exists('password', $formData) && $formData['password'] !== null && strlen($formData['password']['first' ]) > 0) {
            $object->setPassword($this->encoder->encodePassword($object, $formData['password']['first' ]));
        }
    }
}
