<?php

// src/Admin/UserAdmin.php
namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
        ->add('email', EmailType::class, [
                'required' => true,
            ])
        ->add('password', PasswordType::class, [
            'required' => true,
        ])
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
            ],
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
        ->add('email')
        ->add('isVerified');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
        ->addIdentifier('email')
        ->add('roles', null, [
            'label' => 'Roles',
            'associated_property' => function (User $user) {
                return implode(', ', $user->getRoles());
            },
        ])
        ->add('isVerified', 'boolean', [
            'label' => 'Verified',
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
        ->add('id')
        ->add('email')
        ->add('roles', null, [
            'label' => 'Roles',
            'associated_property' => function (User $user) {
                return implode(', ', $user->getRoles());
            },
        ])
        ->add('isVerified', null, [
            'label' => 'Verified',
        ]);
    }
}