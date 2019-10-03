<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class VoiceAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('pseudo', null, ['label' => 'Pseudo du votant'])
            ->add('email', null, ['label' => 'Email du votant'])
            ->add('vote', null, ['label' => 'Nom du sondage'])
            ->add('place', null, ['label' => 'Restaurant'])
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => []
                ],
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('pseudo', null, ['label' => 'Pseudo du votant'])
            ->add('email', null, ['label' => 'Email du votant'])
            ->add('vote', null, ['label' => 'Nom du sondage'])
            ->add('place', null, ['label' => 'Restaurant'])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('pseudo', null, ['label' => 'Pseudo du votant'])
            ->add('email', null, ['label' => 'Email du votant'])
            ->add('vote', null, ['label' => 'Nom du sondage'])
            ->add('place', null, ['label' => 'Restaurant'])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('pseudo', null, ['label' => 'Pseudo du votant'])
            ->add('email', null, ['label' => 'Email du votant'])
            ->add('vote', null, ['label' => 'Nom du sondage'])
            ->add('place', null, ['label' => 'Restaurant'])
        ;
    }
}
