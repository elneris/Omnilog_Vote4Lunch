<?php


namespace App\Admin;


use App\Entity\Place;
use App\Entity\Voice;
use App\Entity\Vote;
use App\Repository\PlaceRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


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

    protected function configureFormFields(FormMapper $formMapper): void
    {
        /** @var Voice $subject */
        $subject = $this->getSubject();

        //dd($subject);
        $vote = $subject ? $subject->getVote() : null;

        $formMapper
            ->add('pseudo', null, ['label' => 'Pseudo du votant'])
            ->add('email', null, ['label' => 'Email du votant'])
            ->add('vote', EntityType::class, [
                'class' => Vote::class
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'query_builder' => function(PlaceRepository $repository) use ($vote) {
                    $qb = $repository->createQueryBuilder('p');

                    if($vote){
                        $qb
                            ->innerJoin('p.votes', 'v')
                            ->andWhere($qb->expr()->eq('v.id', ':vote'))
                            ->setParameter('vote', $vote)
                        ;
                    }

                    return $qb;
                },
            ])
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


    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }
}
