<?php

// src/Admin/RepairAdmin.php
namespace App\Admin;

use App\Entity\Equipment;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class RepairAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
        ->add('equipment', EntityType::class, [
            'class' => Equipment::class,
            'choice_label' => 'name',
            ])
        ->add('reportDate', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => true,
        ])
        ->add('startDate', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => true,
        ])
        ->add('endDate', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => true,
        ])
        ->add('description', TextareaType::class, [
            'required' => false,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
        ->add('equipment', null, [
            'field_type' => EntityType::class,
            'field_options' => [
                'class' => Equipment::class,
                'choice_label' => 'name',
            ],
        ])
        ->add('reportDate')
        ->add('startDate')
        ->add('endDate');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
        ->addIdentifier('equipment.name', 'text', [
            'label' => 'Equipment',
        ])
        ->add('reportDate')
        ->add('startDate')
        ->add('endDate');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
        ->add('id')
        ->add('equipment.name', null, [
            'label' => 'Equipment',
        ])
        ->add('reportDate')
        ->add('startDate')
        ->add('endDate')
        ->add('description');
    }
}