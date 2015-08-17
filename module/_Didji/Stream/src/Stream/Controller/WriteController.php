<?php
namespace Stream\Controller;

use Stream\Entity\Channel;
use Stream\InputFilter\ChannelFilter;
use Stream\Service\ChannelServiceInterface;

use ApiConsumer\Service\ApiConsumerService;

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
            $formData = $request->getPost();

            // On transmet son contenu au formulaire.
            $form->setData($formData);

            // Si ce contenu est bien validé par le formulaire...
            if ($form->isValid()) {
                // On parse l'URL de la chaîne pour récupérer son nom et sa plateforme d'hébergement
                $urlInfos = $this->decodeUrl($formData['url']);

                // On utilise ces informations pour récupérer l'identifiant externe de la chaîne
                // Et on peuple les informations manquantes
                $externalId = $this->getChannelId($urlInfos['type'], $urlInfos['name']);
                $channel = $channel->setType($urlInfos['type'])->setName($urlInfos['name'])->setExternalId($externalId);

                // On enregistre la nouvelle chaîne.
                $this->channelService->save($channel);

                // Et on revient sur la page d'accueil
                return $this->redirect()->toRoute('streams');
            }
        }

        // On transmet le formulaire à la vue.
        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
    * Décode l'URL renseignée par l'utilisateur pour en extraire la plateforme utilisée et le nom de la chaîne
    *
    * @param string $url URL à parser
    *
    * @return Array Les informations extraites de l'URL
    */
    private function decodeUrl($url)
    {
        // On vérifie que l'URL est valide
        $uri = UriFactory::factory($url);

        // On récupère le nom de l'hôte de l'URL (www.TWITCH.tv/...)
        $type = preg_match('/^(www\.)?(\w*)(\.\S*)?$/i', $uri->getHost(), $matches);
        $type = $matches[2];

        return array(
            'type' => $type,
            'name' => trim($uri->getPath(), '/')
        );
    }

    /**
    * Récupère l'identifiant de la chaîne renseigné par la plateforme externe
    *
    * @param string $type Plateforme externe
    * @param string $name Nom de la chaîne
    *
    * @return string Identifiant externe de la chaîne
    *
    */
    private function getChannelId($type, $name)
    {
        $apiConsumer = ApiConsumerService::getApiConsumer($type);
        $channelData = $apiConsumer->getChannelData($name);

        return $channelData['_id'];
    }
}
