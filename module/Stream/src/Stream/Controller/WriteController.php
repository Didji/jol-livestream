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

        // On crée un nouvel objet Channel qu'on associe au formulaire.
        $channel = new Channel();

        // On associe le formulaire à son filtre
        $form->setInputFilter(new ChannelFilter());

        // Si le requête est de type POST...
        if ($request->isPost()) {

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

        $form->bind($channel);

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

        //\Zend\Debug\Debug::dump(curl_version());
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

            \Zend\Debug\Debug::dump(htmlspecialchars($verboseLog));

            $exception = new \Exception('Curl Exception : ' . curl_errno($curl) . ' - ' . htmlspecialchars(curl_error($curl)));
            curl_close($curl);
            throw $exception;
        }

        die(\Zend\Debug\Debug::dump($result));

        return json_decode($result);
    }

    public function testAction()
    {
        //URL de l'API à requêter
        $url = "https://api.twitch.tv/kraken/streams/";

        //Création du fichier de log curl
        $verbose = fopen(dirname(__DIR__).'/errorlog.txt', 'w+');

        $curl = curl_init();

        //Paramétrage de CURL : 
        // - Url à lire
        // - Retour direct de la réponse de l'API (au lieu de true ou false)
        // - Vérification du certificat
        //      + N.B : Même en désactivant cette option, impossible d'accéder à l'URL de l'API,
        //              l'exception levée est la même que si on veut vérifier le certificat (exception 77)
        // - Chemin du certificat (chemin absolu), le certicifat se trouve bien dans le dossier /config à la racine du site livestream
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_CAINFO, '/config/cacert.pem');

        //Déclaration du fichier de log
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $verbose);

        $result = curl_exec($curl);

        // En cas d'erreur, on affiche ce qui s'est passé
        if (curl_errno($curl)) {

            // On remet le pointeur au début du fichier de log, on lit et on affiche
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            \Zend\Debug\Debug::dump(htmlspecialchars($verboseLog));

            // On affiche l'exception levée
            $exception = new \Exception('Curl Exception : ' . curl_errno($curl) . ' - ' . htmlspecialchars(curl_error($curl)));
            curl_close($curl);
            throw $exception;
        }

        return json_decode($result);
    }
}