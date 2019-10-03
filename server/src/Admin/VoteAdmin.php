<?php


namespace App\Admin;


use App\Entity\Place;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


final class VoteAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('title', null, ['label' => 'Titre'])
            ->add('date', null, ['label' => 'Date du repas'])
            ->add('endDate', null, ['label' => 'Date du fin de vote'])
            ->add('user', [], ['associated_property' => 'pseudo', 'label' => 'Créateur du vote'])
            ->add('url')
            ->add('active', null, ['label' => 'Vote actif'])
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
            ->add('title', null, ['label' => 'Titre'])
            ->add('date', null, ['label' => 'Date du repas'])
            ->add('endDate', null, ['label' => 'Date du fin de vote'])
            ->add('user', [], ['associated_property' => 'pseudo', 'label' => 'Créateur du vote'])
            ->add('url')
            ->add('active', null, ['label' => 'Vote actif'])
            ->add('places', [], ['associated_property' => 'name', 'label' => 'liste des restaurants'])
            ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title', null, ['label' => 'Titre'])
            ->add('date', DateTimeType::class, ['label' => 'Date du repas'])
            ->add('endDate', DateTimeType::class, ['label' => 'Date du fin de vote'])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo',
                'label' => 'Créateur du vote',
            ])
            ->add('places', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'ref',
                'label' => 'Liste des restaurants',
                'multiple' => true
            ])
            ->add('active', null, ['label' => 'Vote actif'])
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title', null, ['label' => 'Titre'])
            ->add('active', null, ['label' => 'Vote actif'])
            ;
    }
}
