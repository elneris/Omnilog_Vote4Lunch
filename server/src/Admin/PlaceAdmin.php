<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PlaceAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name', null, ['label' => 'Nom du restaurant'])
            ->add('type', null, ['label' => 'Type de l\'établissement'])
            ->add('cuisine', null, ['label' => 'Type de cuisine'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ],
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name', null, ['label' => 'Nom du restaurant'])
            ->add('type', null, ['label' => 'Type de l\'établissement'])
            ->add('cuisine', null, ['label' => 'Type de cuisine'])
            ->add('address', null, ['label' => 'Adresse'])
            ->add('phone', null, ['label' => 'Numéro de téléphone'])
            ->add('email', null, ['label' => 'Email'])
            ->add('website', null, ['label' => 'Site internet'])
            ->add('openingHours', null, ['label' => 'Heures d\'ouverture'])
            ->add('city', null, ['label' => 'Ville'])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', null, ['label' => 'Nom du restaurant'])
            ->add('type', null, ['label' => 'Type de l\'établissement'])
            ->add('cuisine', null, ['label' => 'Type de cuisine'])
            ->add('address', null, ['label' => 'Adresse'])
            ->add('phone', null, ['label' => 'Numéro de téléphone'])
            ->add('email', null, ['label' => 'Email'])
            ->add('website', null, ['label' => 'Site internet'])
            ->add('openingHours', null, ['label' => 'Heures d\'ouverture'])
            ->add('city', null, ['label' => 'Ville'])
            ->add('lat', null, ['label' => 'Latitude'])
            ->add('lng', null, ['label' => 'Longitude'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('name', null, ['label' => 'Nom du restaurant'])
            ->add('type', null, ['label' => 'Type de l\'établissement'])
            ->add('cuisine', null, ['label' => 'Type de cuisine'])
            ->add('address', null, ['label' => 'Adresse'])
            ->add('phone', null, ['label' => 'Numéro de téléphone'])
            ->add('email', null, ['label' => 'Email'])
            ->add('website', null, ['label' => 'Site internet'])
            ->add('openingHours', null, ['label' => 'Heures d\'ouverture'])
            ->add('city', null, ['label' => 'Ville'])
        ;
    }
}
