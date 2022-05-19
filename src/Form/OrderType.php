<?php

namespace App\Form;

use App\Entity\Size;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('size', EntityType::class , [
                'class' => Size::class,
                'choice_label' => function(Size $size){
                return $size->getName();
                }])
            ->add('fname', TextType::class , ['label' => 'Voornaam'])
            ->add('sname', TextType::class , ['label' => 'Achternaam'])
            ->add('Address', TextType::class , ['label' => 'Adres'])
            ->add('city', TextType::class , ['label' => 'City'])
            ->add('zipcode', TextType::class , ['label' => 'Postcode'])
            ->add('save', SubmitType::class , ['label' => 'Bestellen'])

            ;
    }

}