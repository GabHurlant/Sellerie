<?php
// src/Admin/MovementAdmin.php
namespace App\Admin;

use App\Entity\Equipment;
use App\Entity\User;
use App\Entity\MovementType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class MovementAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('equipment', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'name',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('movementType', EntityType::class, [
                'class' => MovementType::class,
                'choice_label' => 'name',
            ])
            ->add('movementDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Movement Date',
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
            ->add('user.email', null, [
                'label' => 'User',
            ])
            ->add('movementType.name', null, [
                'label' => 'Movement Type',
            ])
            ->add('movementDate', null, [
                'label' => 'Movement Date',
            ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('equipment.name', 'text', [
                'label' => 'Equipment',
            ])
            ->add('user.email', 'text', [
                'label' => 'User',
            ])
            ->add('movementType.name', 'text', [
                'label' => 'Movement Type',
            ])
            ->add('movementDate', 'datetime', [
                'label' => 'Movement Date',
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
            ->add('user.email', null, [
                'label' => 'User',
            ])
            ->add('movementType.name', null, [
                'label' => 'Movement Type',
            ])
            ->add('movementDate', null, [
                'label' => 'Movement Date',
            ])
            ->add('comment', null, [
                'label' => 'Comment',
            ]);
    }
}
