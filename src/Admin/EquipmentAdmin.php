<?php

// src/Admin/EquipmentAdmin.php
namespace App\Admin;

use App\Entity\Category;
use App\Entity\Location;
use App\Entity\Condition;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class EquipmentAdmin extends AbstractAdmin
{

    protected function prePersist($equipment): void
    {
        $equipment->setCreatedAt(new \DateTimeImmutable());
    }

    protected function preUpdate($equipment): void
    {
        $equipment->setLastMovement(new \DateTimeImmutable());
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
        ->add('name', TextType::class)
        ->add('description', TextareaType::class, [
            'required' => false,
        ])
        ->add('price', NumberType::class, [
            'scale' => 2,
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
        ])
        ->add('stat', EntityType::class, [
            'class' => Condition::class,
            'choice_label' => 'name',
        ])
        ->add('location', EntityType::class, [
            'class' => Location::class,
            'choice_label' => function (Location $location) {
                return sprintf('%s - %s', $location->getAisle(), $location->getShelf());
            },
        ])
        ->add('lastMovement', DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
        ])
        ->add('createdAt', DateTimeType::class, [
            'widget' => 'single_text',
            'disabled' => true,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
        ->add('name')
        ->add('category', null, [
            'field_type' => EntityType::class,
            'field_options' => [
                'class' => Category::class,
                'choice_label' => 'name',
            ],
        ])
        ->add('stat', null, [
            'field_type' => EntityType::class,
            'field_options' => [
                'class' => Condition::class,
                'choice_label' => 'name',
            ],
        ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
        ->addIdentifier('name')
        ->add('category.name', 'text', [
            'label' => 'Category',
        ])
        ->add('stat.name', 'text', [
            'label' => 'State',
        ])
        ->add('location.aisle', 'text', [
            'label' => 'Aisle',
        ])
        ->add('location.shelf', 'text', [
            'label' => 'Shelf',
        ])
        ->add('price')
        ->add('lastMovement');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
        ->add('id')
        ->add('name')
        ->add('category.name', null, [
            'label' => 'Category',
        ])
        ->add('stat.name', null, [
            'label' => 'State',
        ])
        ->add('location.aisle', 'text', [
            'label' => 'Aisle',
        ])
        ->add('location.shelf', 'text', [
            'label' => 'Shelf',
        ])
        ->add('description')
        ->add('price')
        ->add('lastMovement')
        ->add('createdAt');
    }

}


