<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\PoeticFormRepository;
use App\Repository\UserRepository;
use App\Repository\LanguageRepository;
use App\Repository\CollectionRepository;

use App\Entity\Poem;
use App\Entity\Language;
use App\Entity\Biography;
use App\Entity\User;
use App\Entity\Collection;
use App\Entity\PoeticForm;

class PoemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$locale = $options["locale"];

        $builder
            ->add('title', TextType::class, array(
                'constraints' => new Assert\NotBlank(), 'label' => 'admin.poem.Title'
            ))
            ->add('poetic_form', EntityType::class, array(
				'label' => 'admin.poem.PoeticForm', 
				'class' => PoeticForm::class,
				'query_builder' => function (PoeticFormRepository $er) use ($locale) {
					return $er->findAllForChoice($locale);
				},
				'multiple' => false,
				'required' => false,
				'expanded' => false,
				'placeholder' => 'main.field.ChooseAnOption'
			))
			->add('text', TextareaType::class, array(
                'attr' => array('class' => 'redactor'), 'label' => 'admin.poem.Text'
            ))
			->add('released_date', IntegerType::class, array(
                'label' => 'admin.poem.PublicationDate'
            ))
			
			->add('unknown_released_date', CheckboxType::class, array(
                'mapped' => false, 'label' => 'admin.poem.UnknownDate'
            ))
			
            ->add('author_type', ChoiceType::class, array(
				'label' => 'admin.poem.AuthorKind', 
				'multiple' => false, 
				'expanded' => false,
				'constraints' => array(new Assert\NotBlank()),
				'choices' => array("admin.poem.Biography" => "biography", "admin.poem.User" => "user"),
				'attr' => array('class' => 'authorType_select')
			))
			->add('user', EntityType::class, array(
				'label' => 'admin.poem.User', 
				'class' => User::class,
				'query_builder' => function (UserRepository $er) use ($locale) {
					return $er->findAllForChoice($locale);
				},
				'multiple' => false, 
				'expanded' => false,
				'placeholder' => 'main.field.ChooseAnOption'
			))
            ->add('biography', BiographySelectorType::class, array(
                'label' => 'admin.poem.Biography'
            ))
			->add('collection', EntityType::class, array(
				'label' => 'admin.poem.Collection',
				'class' => Collection::class,
				'query_builder' => function (CollectionRepository $er) use ($locale) {
					return $er->findAllForChoice($locale);
				},
				'multiple' => false,
				'required' => false,
				'expanded' => false,
				'placeholder' => 'main.field.ChooseAnOption'
			))
			->add('photo', FileType::class, array('data_class' => null, "label" => "Image", "required" => true))
			->add('language', EntityType::class, array(
				'label' => 'admin.form.Language',
				'class' => Language::class,
				'query_builder' => function (LanguageRepository $er) use ($locale) {
					return $er->findAllForChoice($locale);
				},
				'multiple' => false,
				'required' => false,
				'expanded' => false,
				'placeholder' => 'main.field.ChooseAnOption'
			))
            ->add('save', SubmitType::class, array('label' => 'admin.main.Save', 'attr' => array('class' => 'btn btn-success')));
    }

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			"data_class" => Poem::class,
			"locale" => null
		));
	}
	
    public function getName()
    {
        return 'poem';
    }
}