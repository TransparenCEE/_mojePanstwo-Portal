<?php

class PismaController extends AppController
{

    public $helpers = array('Form');
    public $uses = array('Pisma.Pismo');

    /*
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->deny();
        $this->Auth->allow( 'home' );

        $this->API->setOptions( array(
            'passExceptions' => array(
                403 => 'ForbiddenException',
                404 => 'NotFoundException'
            )
        ) );
        $this->api = $this->API->Pisma();
    }
    */


    public function my()
    {
		
		$q = false;
		
		$params = array(
			'page' => (
				isset($this->request->query['page']) && 
				is_numeric($this->request->query['page'])
			) ? $this->request->query['page'] : 1,
		);
		
		if(
			isset( $this->request->query['q'] ) &&
			$this->request->query['q']
		) 
			$q = $params['q'] = $this->request->query['q'];
		
		if( $user = $this->Auth->user() ) {
			
		} else {
			
			$params['anonymous_user_id'] = session_id();
			
		}
		
        $search = $this->API->Pisma()->search($params);
        $this->set('search', $search);
        $this->set('q', $q);

    }

    public function home()
    {
			
		
        $API = $this->API->Pisma();

        $templatesGroups = $API->getTemplatesGrouped();
        $this->set('templatesGroups', $templatesGroups);

        $query = array_merge($this->request->query, $this->request->params);

        $pismo = array(
            'szablon_id' => isset($query['szablon_id']) ? $query['szablon_id'] : false,
            'adresat_id' => isset($query['adresat_id']) ? $query['adresat_id'] : false,
        );

        if ($session = $this->Session->read('Pisma.unsaved')) {
            $this->set('pismo', $session);
            $this->Session->delete('Pisma.unsaved');
        }

        $this->set('pismo_init', $pismo);

    }


    public function view($id, $slug='')
    {

        $pismo = $this->API->Pisma()->document_read($id);        
        $this->set('title_for_layout', $pismo['nazwa']);
        $this->set('pismo', $pismo);

    }

    public function share($id, $slug = '')
    {

        $pismo = $this->API->Pisma()->document_read($id);
        $this->set('title_for_layout', $pismo['nazwa']);
        $this->set('pismo', $pismo);

    }


    /**
     * Saves sent data
     */
    public function save()
    {

        if (isset($this->request->data['send'])) {


            $pismo = $this->API->Pisma()->save($this->request->data);
            if ($pismo && isset($pismo['id']) && $pismo['id']) {

                $this->redirect($pismo['url']);

            }

        } elseif (isset($this->request->data['save'])) {

            $pismo = $this->Pismo->save($this->request->data);
            if ($pismo && isset($pismo['id']) && $pismo['id']) {

                $this->redirect($pismo['url']);

            }

        } elseif (isset($this->request->data['print'])) {

            $pismo = $this->Pismo->generatePDF($this->request->data);

        }

    }

    /**
     * Show form for new Document
     */
    public function create()
    {
		
		$pismo = array();
				
		if( $user = $this->Auth->user() ) {
			
			$pismo = array_merge($pismo, array(
				'from_user_type' => 'account',
				'from_user_id' => $user['id'],
			));
			
			/*
	        $pismo = array(
	            'from_name' => $this->Auth->user('username'),
	            'from_email' => $this->Auth->user('email')
	        );
	        */
			
		} else {
			
			$pismo = array_merge($pismo, array(
				'from_user_type' => 'anonymous',
				'from_user_id' => session_id(),
			));
			
		}
		
        if (isset($this->request->data['adresat_id']))
            $pismo['adresat_id'] = $this->request->data['adresat_id'];
            
        if (isset($this->request->data['szablon_id']))
            $pismo['szablon_id'] = $this->request->data['szablon_id'];
			
		
        $status = $this->API->Pisma()->document_create($pismo);
        return $this->redirect( $status['url'] . '/edit' );
    }

    public function edit($id)
    {

        $pismo = $this->API->Pisma()->document_read($id);
        $this->set('title_for_layout', $pismo['nazwa']);
        $this->set('pismo', $pismo);

        /*
        if ($this->request->is('get')) {
            $doc = $this->api->document_get($id);
            $this->set('doc', $doc);

        } else {
            $data = $this->request->data;
            $data['id'] = $id;

            if ($doc = $this->saveForm($data)) {
                $this->set('doc', $doc);
            }
        }
        */
    }

    public function delete($id)
    {
        // TODO czy jesteś pewien, if is('get')
        $this->api->document_delete($id);
        $this->Session->setFlash('Skasowano pismo');

        $this->redirect(array('action' => 'home', '[method]' => 'GET'));
    }

    private function saveForm($data)
    {
        try {
            $doc = $this->api->document_save($data);

        } catch (MP\ApiValidationException $ex) {

            // TODO nie widać flash w layoucie
            $this->Session->setFlash('Wystąpiły błędy walidacji', null, array('class' => 'alert-error'));
            $this->set('verr', $ex->getValidationErrors());
            $this->set('doc', $data);
            $this->render('edit');

            return null;
        }

        if (isset($data['saveAndSend'])) {
            $this->api->document_send($doc['id']);
        }

        return $doc;
    }
}