<?php
/**
 * Created by PhpStorm.
 * User: nuand
 * Date: 06/02/19
 * Time: 16:07
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class DailyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('resume')
            ->add('file', FileType::class)
            ;
    }

}