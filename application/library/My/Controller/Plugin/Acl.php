<?php

class My_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        $auth = Zend_Auth::getInstance();
        $acl = Zend_Registry::get('acl');
        
        $role = ($auth->hasIdentity()) ? $auth->getIdentity()->role : 'invitado';
        $resource = $request->getControllerName();
        $privilege = $request->getActionName();
        
        if (!$acl->isAllowed($role, $resource, $privilege)) {
            
            $request->setControllerName('Error');
            $request->setActionName('error');
            
        }
        
        if (!$auth->hasIdentity() && !in_array($resource, array('ajax', 'index', 'servicios', 'portafolio', 'solicitud', 'contacto', 'blog', 'nosotros', 'productos')) && !in_array($privilege, array('index',  'notificacion','notificacionin', 'categoria', 'ver', 'tags', 'ingresos', 'neobux', 'gracias'))) {
            
            $request->setControllerName('auth');
            $request->setActionName('login');
            
        }
        
        if ($auth->hasIdentity() && $request->getActionName() === 'login') {
            
            $request->setControllerName('Index');
            $request->setActionName('index');
            
        }
        
        if ($auth->hasIdentity() && in_array($privilege, array('add','edit','del','approve','reject','pending','export', 'index', 'adddetalle'))) {
            
         
        }
        
    }
    
}
