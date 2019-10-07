<?php


namespace App\Admin;


use App\Entity\Place;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DateTimePickerType;


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
            ->add('active', null, ['label' => 'Vote actif'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'voteUrl' => [
                        'template' => 'Sonata/CRUD/list__action_voteUrl.html.twig',
                    ]
                ],
            ])
            ->add('liens du vote', 'string', [
                'template' => 'Sonata/url_row.html.twig'
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
            ->add('active', null, ['label' => 'Vote actif'])
            ->add('places', [], ['associated_property' => 'name', 'label' => 'liste des restaurants'])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->tab('Details')
            ->with('Détail du vote')
            ->add('title', null, ['label' => 'Titre'])
            ->add('date', DateTimePickerType::class, ['label' => 'Date du repas'])
            ->add('endDate', DateTimePickerType::class, ['label' => 'Date du fin de vote'])
            ->add('user', ModelListType::class, [
                'class' => User::class,
                'label' => 'Créateur du vote',
            ])
            ->add('places', ModelAutocompleteType::class, [
                'class' => Place::class,
                'label' => 'Liste des restaurants',
                'property' => 'name',
                'multiple' => true,
            ])
            ->add('active', null, ['label' => 'Vote actif'])
            ->end()
            ->end()

        ;

        if($this->getSubject()->getId()){
            $form
                ->tab('Votes')
                ->with('Liste des votes')
                ->add('voices', CollectionType::class, [
                    'by_reference' => false,
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                ])
                ->end()
            ;
        }
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
