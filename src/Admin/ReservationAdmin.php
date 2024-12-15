<?php
// src/Admin/ReservationAdmin.php
namespace App\Admin;

use App\Entity\Equipment;
use App\Entity\User;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

final class ReservationAdmin extends AbstractAdmin
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
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
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
            ->add('startDate')
            ->add('endDate');
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
            ->add('user.email', null, [
                'label' => 'User',
            ])
            ->add('startDate')
            ->add('endDate');
    }
}
