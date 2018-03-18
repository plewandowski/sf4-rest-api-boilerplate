<?php

namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BookType
 * @package App\Form\Type
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
class BookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isbn', IntegerType::class, [ 'required' => true ]);
        $builder->add('title', TextType::class, [ 'required' => true ]);
        $builder->add('price', NumberType::class, [ 'required' => true ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Book::class,
            'csrf_protection' => false,
        ));
    }


}
