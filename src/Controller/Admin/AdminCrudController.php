<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Iterable_;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminCrudController extends AbstractCrudController
{

	public function __construct(public UserPasswordHasherInterface $userPasswordHasher) {

	}

    public static function getEntityFqcn(): string
    {
        return Admin::class;
    }

	public function configureActions(Actions $actions): Actions
	{
		return $actions
			->add(Crud::PAGE_EDIT, Action::INDEX)
			->add(Crud::PAGE_INDEX, Action::DETAIL)
			->add(Crud::PAGE_EDIT, Action::DETAIL)
			;
	}

	public function configureFields(string $pageName): iterable
	{
		$fields = [
			IdField::new('id')->hideOnForm(),
			TextField::new('username'),
		];

		$password = TextField::new('password')
		                     ->setFormType(PasswordType::class)
		                     ->setFormTypeOptions([
			                     'label' => 'Password',
			                     'mapped' => false,
		                     ])
		                     ->setRequired($pageName === Crud::PAGE_NEW)
		                     ->onlyOnForms()
		;
		$fields[] = $password;

		return $fields;
	}

	public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore
	$formOptions, AdminContext $context): FormBuilderInterface
	{
		$formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
		return $this->addPasswordEventListener($formBuilder);
	}

	public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
	{
		$formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
		return $this->addPasswordEventListener($formBuilder);
	}

	private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
	{
		return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
	}

	private function hashPassword() {
		return function($event) {
			$form = $event->getForm();
			if (!$form->isValid()) {
				return;
			}
			$password = $form->get('password')->getData();
			if ($password === null) {
				return;
			}

			$hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
			$form->getData()->setPassword($hash);
		};
	}

}
