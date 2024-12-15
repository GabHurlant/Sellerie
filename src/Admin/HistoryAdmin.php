<?php

// src/Admin/HistoryAdmin.php
namespace App\Admin;

use App\Entity\User;
use App\Entity\Movement;
use App\Entity\Equipment;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class HistoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('equipment', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'name',
            ])
            ->add('movement', EntityType::class, [
                'class' => Movement::class,
                'choice_label' => function (Movement $movement) {
                    return $movement->getMovementType()->getName();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->innerJoin('m.movementType', 'mt')
                        ->addSelect('mt')
                        ->groupBy('mt.id') 
                        ->orderBy('mt.name', 'ASC');
                },
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('eventDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Event Date',
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'Comment',
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('equipment.name', null, [
                'label' => 'Equipment',
            ])
            ->add('movement.movementType.name', null, [
                'label' => 'Movement',
            ])
            ->add('user.email', null, [
                'label' => 'User',
            ])
            ->add('eventDate', null, [
                'label' => 'Event Date',
            ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
        ->addIdentifier('equipment.name', 'text', [
            'label' => 'Equipment',
        ])
        ->add('movement.movementType.name', 'text', [
            'label' => 'Movement Type',
        ])
        ->add('user.email', 'text', [
            'label' => 'User',
        ])
        ->add('eventDate', 'datetime', [
            'label' => 'Event Date',
        ])
        ->add('comment', 'text', [
            'label' => 'Comment',
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('equipment.name', null, [
                'label' => 'Equipment',
            ])
            ->add('movement.movementType.name', null, [
                'label' => 'Movement Type',
            ])
            ->add('user.email', null, [
                'label' => 'User',
            ])
            ->add('eventDate', null, [
                'label' => 'Event Date',
            ])
            ->add('comment', null, [
                'label' => 'Comment',
            ]);
    }
}