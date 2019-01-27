<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('image', FileType::class, array("label" => "admin.imageGenerator.Image", "required" => true, 'constraints' => new Assert\NotBlank()))
			->add('font_size', IntegerType::class, ["label" => "admin.imageGenerator.FontSize", "required" => true, 'constraints' => new Assert\NotBlank(), "data" => 35])
			->add('invert_colors', CheckboxType::class, ["label" => "admin.imageGenerator.InvertColors", "required" => false])
			->add('text', TextareaType::class, array(
                'attr' => array('class' => 'redactor'), 'label' => 'admin.imageGenerator.Text'
            ))
            ->add('save', SubmitType::class, array('label' => 'admin.main.Save', "attr" => array("class" => "btn btn-primary")))
			;
    }

    public function getName()
    {
        return 'image_generator';
    }
}