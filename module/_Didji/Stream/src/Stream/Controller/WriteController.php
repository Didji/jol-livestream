<?php
namespace Stream\Controller;

use Stream\Entity\Channel;
use Stream\InputFilter\ChannelFilter;
use Stream\Service\ChannelServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Uri\UriFactory;

class WriteController extends AbstractActionController
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var ChannelServiceInterface
     */
    protected $channelService;

    /**
     * @param FormInterface $form
     * @param ChannelServiceInterface $channelService
     */
    public function __construct(
        FormInterface $form,
        ChannelServiceInterface $channelService
    ) {
        $this->form           = $form;
        $this->channelService = $channelService;
    }

    /**
     * Ajoute une salutation dans la base de données.
     */
    public function addAction()
    {
        $form = $this->form;
        $request = $this->getRequest();

        // On crée un nouvel objet Channel et on le lie au formulaire
        $channel = new Channel();
        $form->bind($channel);

        // On associe le formulaire à son filtre
        $form->setInputFilter(new ChannelFilter());

        // Si la requête est de type POST, c'est que l'utilisateur a saisi des informations
        // et qu'il faut donc enregistrer sa chaîne
        if ($request->isPost()) {
            die(\Zend\Debug\Debug::dump($request->getPost()));

            $urlInfos = $this->decodeUrl($request->getPost('url'));
            $urlInfos['description'] = $request->getPost('description');
            $externalInfos = $this->getExternalInfos($urlInfos['name']);

            // On transmet son contenu au formulaire.
            $form->setData($request->getPost());

            // Si ce contenu est bien validé par le formulaire...
            if ($form->isValid()) {
                // On enregistre la nouvelle salutation.
                $this->channelService->save($channel);

                // Et on revient sur la page d'accueil Hello World.
                return $this->redirect()->toRoute('streams');
            }
        }

        // On transmet le formulaire à la vue.
        return new ViewModel([
            'form' => $form,
        ]);
    }

    private function decodeUrl($url)
    {
        $uri = UriFactory::factory($url);

        $type = preg_match('/^(www\.)?(\w*)(\.\S*)?$/i', $uri->getHost(), $matches);
        $type = $matches[2];

        return array(
            'type' => $type,
            'name' => trim($uri->getPath(), '/')
        );
    }

    private function getExternalInfos($name)
    {
        $url = "https://api.twitch.tv/kraken/channels/" . $name;
        $verbose = fopen(dirname(__DIR__).'/errorlog.txt', 'w+');
        
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, '/config/cacert.pem');

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);

            $exception = new \Exception('Curl Exception : ' . curl_errno($curl) . ' - ' . htmlspecialchars(curl_error($curl)));
            curl_close($curl);
            throw $exception;
        }

        die(\Zend\Debug\Debug::dump(json_decode($result)));

        return json_decode($result);
    }
}
