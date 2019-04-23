<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use User\Entity\Main;
use User\Entity\Type;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class IndexController extends AbstractActionController 
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager) 
    {
       $this->entityManager = $entityManager;
        if(!isset($_SESSION['lang'])){$_SESSION['lang'] = 'eng';}
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Home page.
     */
    public function indexAction() 
    {
        $file = file_get_contents('./data/translations/file.json');
        $file = json_decode($file, true);
        $data = $file[$_SESSION['lang']];

        return new ViewModel([
            'data' => $data
        ]);
    }

    /**
     * This is the "postList" action. It is used to display the "About" page.
     */
    public function postListAction()
    {
        $id = $this->params()->fromRoute('id');

        if ($id!=null) {
            $type = $this->entityManager->getRepository(Type::class)
                ->find($id);
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Main::class)
            ->findAllPostsByTypeId($id);

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'posts' => $paginator
        ]);
    }

    /**
     * This is the "about" action. It is used to display the "About" page.
     */
    public function postAction()
    {
        $id = $this->params()->fromRoute('id');

        if ($id!=null) {
            $post = $this->entityManager->getRepository(Main::class)
                ->find($id);
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'post' => $post,
        ]);
    }

    /**
     * This is the "about" action. It is used to display the "About" page.
     */
    public function aboutAction() 
    {
        $file = file_get_contents('./data/translations/file.json');
        $file = json_decode($file, true);
        $data = $file[$_SESSION['lang']];

        return new ViewModel([
            'data' => $data,
        ]);
    }

    /**
     * This is the "newsAction" action. It is used to display the "newsAction" page.
     */
    public function newsAction()
    {
        $file = file_get_contents('./data/translations/file.json');
        $file = json_decode($file, true);
        $data = $file[$_SESSION['lang']];

        return new ViewModel([
            'data' => $data,
        ]);
    }

    /**
     * This is the "whatWeDo" action. It is used to display the "whatWeDo" page.
     */
    public function whatWeDoAction()
    {
        $file = file_get_contents('./data/translations/file.json');
        $file = json_decode($file, true);
        $data = $file[$_SESSION['lang']];

        return new ViewModel([
            'data' => $data,
        ]);
    }

    /**
     * This is the "media" action. It is used to display the "media" page.
     */
    public function mediaAction()
    {
        $file = file_get_contents('./data/translations/file.json');
        $file = json_decode($file, true);
        $data = $file[$_SESSION['lang']];

        return new ViewModel([
            'data' => $data,
        ]);
    }
    
    /**
     * The "settings" action displays the info about currently logged in user.
     */
    public function settingsAction()
    {
        $id = $this->params()->fromRoute('id');
        
        if ($id!=null) {
            $user = $this->entityManager->getRepository(User::class)
                    ->find($id);
        } else {
            $user = $this->currentUser();
        }
        
        if ($user==null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        if (!$this->access('profile.any.view') && 
            !$this->access('profile.own.view', ['user'=>$user])) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        return new ViewModel([
            'user' => $user
        ]);
    }

    /**
     * The "admin" action.
     */
    public function adminAction()
    {
        $id = $this->params()->fromRoute('id');

        if ($id!=null) {
            $user = $this->entityManager->getRepository(User::class)
                    ->find($id);
        } else {
            $user = $this->currentUser();
        }

        if ($user==null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if (!$this->access('user.manage') ) {
            return $this->redirect()->toRoute('not-authorized');
        }

        return new ViewModel([
            'admin' => $user
        ]);
    }

    /**
     * This is the default "language" action of the controller.
     */
    public function languageAction()
    {
        $lang = $this->params()->fromRoute('lang', 'eng');
        $_SESSION['lang'] = $lang;

        return $this->redirect()->toRoute('home');
    }
}

