<?php

namespace App\Backend\Form;


use App\Domain\Backend\Interactor\File;
use App\Domain\Backend\Interactor\UploadedFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

final class FileType extends AbstractType
{
    /**
     * @var string
     */
    private $targetDirectory;
    
    /**
     * ImageType constructor.
     *
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if ($value instanceof File) {
                    return new SymfonyFile($this->targetDirectory . '/' . $value->getFilePath(), false);
                }
                
                return null;
            },
            function ($value) {
                if ($value instanceof SymfonyUploadedFile) {
                    return (new File())
                        ->setUploadedFile(
                            new UploadedFile(
                                $value->getClientOriginalName(),
                                $value->getClientMimeType(),
                                $value->getSize(),
                                $value->getPathname(),
                                $value->getError()
                            )
                        );
                }
                
                return null;
            }
        ));
    }

    public function getParent()
    {
        return SymfonyFileType::class;
    }
    
    public function getBlockPrefix()
    {
        return 'sirano_file';
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->getData() instanceof File) {
            $re       = '/\/public(.*)/m';
            $fullPath = $this->targetDirectory . '/' . $form->getData()->getFilePath();
            
            preg_match_all($re, $fullPath, $matches, PREG_SET_ORDER, 0);
            
            $view->vars['file'] = $matches[0][1];
            
            if ($form->getData()->getFilePath() !== null) {
                $view->vars['required'] = false;
            }
        }
    }
}