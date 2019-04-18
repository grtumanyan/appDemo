<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use User\Entity\Main;
use User\Entity\Type;
use User\Form\MainForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class MainController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $postManager;
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $postManager)
    {
       $this->entityManager = $entityManager;
       $this->postManager = $postManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Home page.
     */
    public function indexAction() 
    {
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Main::class)
            ->findAllPosts();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'posts' => $paginator
        ]);
    }

    /**
     * This action displays a page allowing to add a new user.
     */
    public function addAction()
    {
        // Create user form
        $form = new MainForm('create', $this->entityManager);

        // Get the list of all available roles (sorted by name).
        $allTypes = $this->entityManager->getRepository(Type::class)
            ->findBy([], ['id'=>'ASC']);
        $typeList = [];
        foreach ($allTypes as $one) {
            $typeList[$one->getId()] = $one->getText();
        }

        $form->get('type')->setValueOptions($typeList);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();
                $data['image'] = $data['image']['name'];
                $data['file'] = $data['file']['name'];

                // Add post.
                $post = $this->postManager->addPost($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('main',
                    ['action'=>'view', 'id'=>$post->getId()]);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * The "view" action displays a page allowing to view user's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a user with such ID.
        $post = $this->entityManager->getRepository(Main::class)
            ->find($id);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'post' => $post
        ]);
    }

    /**
     * The "edit" action displays a page allowing to edit user.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $post = $this->entityManager->getRepository(Main::class)
            ->find($id);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create user form
        $form = new MainForm('update', $this->entityManager);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Make certain to merge the files info!
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();
                $data['image'] = $data['image']['name'];
                $data['file'] = $data['file']['name'];

                // Update the user.
                $this->postManager->updatePost($post, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('main');
            }
        } else {

            $form->setData(array(
                'text'=>$post->getText(),
                'title'=>$post->getTitle()
            ));
        }

        return new ViewModel(array(
            'post' => $post,
            'form' => $form
        ));
    }

    /**
     * This action deletes a permission.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $post = $this->entityManager->getRepository(Main::class)
            ->find($id);

        if ($post == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Delete permission.
        $this->postManager->deletePost($post);

        // Redirect to "index" page
        return $this->redirect()->toRoute('main');
    }
}

