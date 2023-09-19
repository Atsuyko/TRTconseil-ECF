<?php

namespace App\Controller\Admin;

use App\Entity\Recruteur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RecruteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Recruteur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Recruteurs')
            ->setEntityLabelInSingular('Recruteur')
            ->setPageTitle('index', 'TRT Conseil - Gestion des Recruteurs');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            TextField::new('company'),
            TextField::new('company_adress'),
            TextField::new('company_postCode'),
            TextField::new('company_city'),
        ];
    }
}
