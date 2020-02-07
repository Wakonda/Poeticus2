<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Repository\LanguageRepository;
use App\Entity\Language;
use App\Entity\Collection;

class CollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$locale = $options["locale"];

        $builder
            ->add('title', TextType::class, array(
                'constraints' => new Assert\NotBlank(), "label" => "admin.collection.Title"
            ))
			->add('text', TextareaType::class, array(
                "required" => false, "label" => "admin.collection.Text", 'attr' => array('class' => 'redactor')
            ))
			->add('releasedDate', IntegerType::class, array(
                'label' => 'admin.collection.PublicationDate'
            ))
			
			->add('unknownReleasedDate', CheckboxType::class, array(
                'mapped' => false, 'label' => 'admin.collection.UnknownDate'
            ))

            ->add('biography', BiographySelectorType::class, array(
                'label' => 'admin.collection.Biography',
				'constraints' => array(new Assert\NotBlank())
            ))
			
			->add('widgetProduct', TextareaType::class, array('required' => false, 'label' => 'admin.collection.ProductCode'))
			
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
			->add('fileManagement', FileManagementSelectorType::class, ["label" => "admin.collection.Image", "required" => false, "folder" => Collection::FOLDER])
            ->add('save', SubmitType::class, array('label' => 'admin.main.Save', 'attr' => array('class' => 'btn btn-success')))
			;
    }

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			"data_class" => Collection::class,
			"locale" => null
		));
	}
	
    public function getName()
    {
        return 'collection';
    }
}