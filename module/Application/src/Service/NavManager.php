<?php
namespace Application\Service;

use User\Entity\Type;

/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager
{
    /**
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;
    
    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;
    
    /**
     * RBAC manager.
     * @var User\Service\RbacManager
     */
    private $rbacManager;

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;


    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper, $rbacManager, $entityManager)
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
        $this->rbacManager = $rbacManager;
        $this->entityManager = $entityManager;
    }
    
    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems() 
    {
        $url = $this->urlHelper;
        $items = [];
        
        $items[] = [
            'id' => 'home',
            'label' => 'Home',
            'link'  => $url('home')
        ];
        
        $items[] = [
            'id' => 'about',
            'label' => 'About',
            'link'  => $url('about')
        ];

        $items[] = [
            'id' => 'news',
            'label' => 'News',
            'link'  => $url('news')
        ];

        $items[] = [
            'id' => 'whatWeDo',
            'label' => 'What We Do',
            'link'  => $url('whatWeDo')
        ];

        $items[] = [
            'id' => 'media',
            'label' => 'Media',
            'link'  => $url('media')
        ];

        $typeDropdownItems = $this->getRelatedItems();

        if (count($typeDropdownItems)!=0) {
            $items[] = [
                'id' => 'type',
                'label' => 'Related Pages',
                'dropdown' => $typeDropdownItems
            ];
        }

        // Display "Login" menu item for not authorized user only. On the other hand,
        // display "Admin" and "Logout" menu items only for authorized users.
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'label' => 'Sign in',
                'link'  => $url('login'),
                'float' => 'right'
            ];
        } else {
            
            // Determine which items must be displayed in Admin dropdown.
            $adminDropdownItems = [];
            
            if ($this->rbacManager->isGranted(null, 'user.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'users',
                            'label' => 'Manage Users',
                            'link' => $url('users')
                        ];
            }

            if ($this->rbacManager->isGranted(null, 'user.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'admin',
                            'label' => 'Manage Posts',
                            'link' => $url('main')
                        ];
            }
            
            if ($this->rbacManager->isGranted(null, 'user.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'type',
                            'label' => 'Manage Types',
                            'link' => $url('type')
                        ];
            }
//
//            if ($this->rbacManager->isGranted(null, 'role.manage')) {
//                $adminDropdownItems[] = [
//                            'id' => 'roles',
//                            'label' => 'Manage Roles',
//                            'link' => $url('roles')
//                        ];
//            }
            
            if (count($adminDropdownItems)!=0) {
                $items[] = [
                    'id' => 'admin',
                    'label' => 'Admin',
                    'dropdown' => $adminDropdownItems
                ];
            }
            
            $items[] = [
                'id' => 'logout',
                'label' => $this->authService->getIdentity(),
                'float' => 'right',
                'dropdown' => [
                    [
                        'id' => 'settings',
                        'label' => 'Settings',
                        'link' => $url('application', ['action'=>'settings'])
                    ],
                    [
                        'id' => 'logout',
                        'label' => 'Sign out',
                        'link' => $url('logout')
                    ],
                ]
            ];
        }
        
        return $items;
    }

    /**
     * This method returns menu items depending on related pages.
     */
    public function getRelatedItems()
    {
        $url = $this->urlHelper;

        $types = $this->entityManager->getRepository(Type::class)
            ->findAll();

        $typeDropdownItems = [];

        foreach($types as $item){
            $typeDropdownItems[] = [
                'id' => 'users',
                'label' => $item->getText(),
                'link' => $url('postList', ['id'=>$item->getId()])
            ];
        }

        return $typeDropdownItems;
    }
}


