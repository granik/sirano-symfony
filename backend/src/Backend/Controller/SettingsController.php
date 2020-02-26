<?php


namespace App\Backend\Controller;


use App\Domain\Interactor\SettingsInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final class SettingsController extends AbstractController
{
    const UPLOAD_DIRECTORY = 'main_page';
    const MAIN_PAGE_VIDEO  = 'main_page_video.mp4';
    
    /**
     * @var string
     */
    private $fileUrlPrefix;
    /**
     * @var string
     */
    private $targetDirectory;
    /**
     * @var SettingsInterface
     */
    private $settings;
    
    /**
     * SettingsController constructor.
     *
     * @param string            $targetDirectory
     * @param string            $fileUrlPrefix
     * @param SettingsInterface $settings
     */
    public function __construct(string $targetDirectory, string $fileUrlPrefix, SettingsInterface $settings)
    {
        $this->fileUrlPrefix   = $fileUrlPrefix;
        $this->targetDirectory = $targetDirectory;
        $this->settings        = $settings;
    }
    
    public function mainPage(Request $request)
    {
        $parameters = [];
        
        if ($request->files->has('main_page_video')) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('main_page_video');
            $fileName     = self::MAIN_PAGE_VIDEO;
            
            $uploadedFile->move($this->targetDirectory . '/' . self::UPLOAD_DIRECTORY, $fileName);
        }
        
        if (is_readable($this->targetDirectory . '/' . self::UPLOAD_DIRECTORY . '/' . self::MAIN_PAGE_VIDEO)) {
            $parameters['mainPageVideoUrl'] = $this->fileUrlPrefix . '/' . self::UPLOAD_DIRECTORY . '/' . self::MAIN_PAGE_VIDEO;
        }
        
        return $this->render('backend/settings/mainPage.html.twig', $parameters);
    }
    
    public function emailSchedule(Request $request)
    {
        if ($request->request->has('maxTries') && $request->request->has('hoursToConfirm')) {
            $this->settings->setMaxTries($request->request->get('maxTries'));
            $this->settings->setHoursToConfirm($request->request->get('hoursToConfirm'));
        }
        
        $maxTries       = $this->settings->getMaxTries();
        $hoursToConfirm = $this->settings->getHoursToConfirm();
        
        return $this->render(
            'backend/settings/emailSchedule.html.twig',
            [
                'maxTries'       => $maxTries,
                'hoursToConfirm' => $hoursToConfirm,
            ]
        );
    }
}