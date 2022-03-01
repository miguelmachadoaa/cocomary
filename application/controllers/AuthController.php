<?php
class AuthController extends Zend_Controller_Action {
    
    protected $_auth;

    public function init() {

         $this->_helper->layout('layout')->disableLayout();
        
        $this->_auth = Zend_Auth::getInstance();

        $Obj= new Application_Model_DbTable_Identidad();
        
              $this->view->identidad=$Obj->get('1');
        
    }
    
    public function loginAction() {
        
        $loginForm = new Application_Form_Login();
        
        $this->view->loginForm = $loginForm;
        
        if ($this->getRequest()->isPost()) {
            
            if ($loginForm->isValid($this->getRequest()->getPost())) {

                $db = $this->_getParam('db');

                $adapter = new Zend_Auth_Adapter_DbTable(
                        $db, 
                        'hk_admin_users', 
                        'username', 
                        'password', 
                        'MD5(?)'
                );

                $adapter->setIdentity($loginForm->getValue('username'));
                $adapter->setCredential($loginForm->getValue('password'));

                $result = $this->_auth->authenticate($adapter);

                if ($result->isValid()) {
                    
                    $user = $adapter->getResultRowObject(array('id', 'name', 'lastname', 'username', 'role_id', 'state_id'));
                    
                    $roles = new Application_Model_DbTable_Roles();
                    
                    $role = $roles->getRoleName($user->role_id);
                    
                    $identity = new stdClass();
                    $identity->uid = $user->id;
                    $identity->name = $user->name;
                    $identity->lastname = $user->lastname;
                    $identity->username = $user->username;
                    $identity->role = $role;
                    $identity->state = $user->state_id;
                    
                    $this->_auth->getStorage()->write($identity);
                    
                    $this->_redirect('/administrador');
                    return TRUE;
                    
                } else {
                    
                    $this->view->error = 'Nombre de usuario o contraseña incorrecta. Por favor, intente de nuevo.';
                    
                }

            }
            
        }
        
    }
    
    public function logoutAction() {
        
        $this->_auth->clearIdentity();
        $this->_redirect('/auth/login');
        
    }
    
}





