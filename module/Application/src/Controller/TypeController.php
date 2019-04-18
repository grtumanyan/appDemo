<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\User;
use User\Entity\Main;
use User\Entity\Type;
use User\Form\TypeForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * This is the main controller class of the User Demo application. It contains
 * site-wide actions such as Home or About.
 */
class TypeController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $typeManager;
    
    /**
     * Constructor. Its purpose is to inject dependencies into the controller.
     */
    public function __construct($entityManager, $typeManager)
    {
       $this->entityManager = $entityManager;
       $this->typeManager = $typeManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Home page.
     */
    public function indexAction() 
    {
        $page = $this->params()->fromQuery('page', 1);

        $query = $this->entityManager->getRepository(Type::class)
            ->findAllTypes();

        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'types' => $paginator
        ]);
    }

    /**
     * This action displays a page allowing to add a new user.
     */
    public function addAction()
    {
        // Create user form
        $form = new TypeForm($this->entityManager);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Add type.
                $type = $this->typeManager->addType($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('type');
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
        $type = $this->entityManager->getRepository(Type::class)
            ->find($id);

        if ($type == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'type' => $type
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

        $type = $this->entityManager->getRepository(Type::class)
            ->find($id);

        if ($type == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create user form
        $form = new TypeForm($this->entityManager);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Update the user.
                $this->typeManager->updateType($type, $data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('type');
            }
        } else {

            $form->setData(array(
                'text'=>$type->getText()
            ));
        }

        return new ViewModel(array(
            'type' => $type,
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

        $type = $this->entityManager->getRepository(Type::class)
            ->find($id);

        if ($type == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Delete permission.
        $this->typeManager->deleteType($type);

        // Redirect to "index" page
        return $this->redirect()->toRoute('type');
    }
}

