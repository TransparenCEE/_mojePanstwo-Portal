<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('HttpSocket', 'Network/Http');
APP::import('Vendor', 'functions');
App::uses('I18n', 'I18n');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $components = array(
        'DebugKit.Toolbar',
        'Session',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'paszport',
                'action' => 'login',
                'plugin' => 'paszport'
            ),
            'loginRedirect' => '/', # After plain login redirect to main page
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email', 'password' => 'password'),
                    'passwordHasher' => array(
                        'className' => 'Simple',
                        'hashType' => 'sha256'
                    ),
                    'userModel' => 'Paszport.User',
//                    'contain' => array('Language', 'Group', 'UserExpand'),
                )
            )
//			,'authenticate' => array(
//				'Paszport'
//			)
        ),
    );

	public $protocol = 'https://';
	public $port = false;
    public $domainMode = 'MP';
    public $appSelected = false;
    public $breadcrumbs = array();
    public $menu = array();
    public $menu_selected = '_default';
    public $chapter_selected = false;
    public $observeOptions = false;
	public $app_menu = array(array(), array());

    public $helpers = array(
        'Html',
        'Form',
        'Paginator',
        'MPaginator',
        'Time',
        'Less.Less',
//        'Minify.Minify',
        'Application',
        'Combinator.Combinator',
    );

    public $statusbarCrumbs = array();
    public $statusbarMode = false;
    public $User = false;
    public $meta = array();

    /**
     * Obiekt określający układ oraz styl dla poszczegółnych stron
     *  header => array(
     *      element => ‘app’ , 'dataset', 'dataobject', 'pk', false
     *  ),
     *  body => array(
     *      theme => ‘default’, 'simple', 'wallpaper'
     *  ),
     *  footer => array(
     *      element => ‘default’, 'minimal;
     *  )
     */
    public $_layout = array(
        'header' => array(
            'element' => 'app',
        ),
        'body' => array(
            'theme' => 'default',
        ),
        'footer' => array(
            'element' => 'default',
        ),
    );

    public $datasets = array(
        'krs' => array(
            'krs_podmioty' => array(
            	'label' => 'Organizacje',
				'searchTitle' => 'Szukaj organizacji...',
				'menu_id' => 'organizacje',
				'autocompletion' => array(
					'dataset' => 'krs_podmioty',
				),
            ),
            'krs_osoby' => array(
            	'label' => 'Osoby',
				'searchTitle' => 'Szukaj osób...',
				'menu_id' => 'osoby',
            ),
            'msig' => array(
            	'label' => 'Monitor Sądowy i Gospodarczy',
				'searchTitle' => 'Szukaj w Monitorze Sądowym i Gospodarczym',
				'menu_id' => 'msig',
            ),
        ),
        'bdl' => array(
            'bdl_wskazniki' => array(
            	'label' => 'Wskaźniki'
            ),
            'bdl_wskazniki_grupy' => array(
            	'label' => 'Grupy wskaźników'
            ),
            'bdl_wskazniki_kategorie' => array(
            	'label' => 'Kategorie wskaźników'
            ),
        ),
        'prawo' => array(
            'prawo' => array(
            	'label' => 'Prawo powszechne',
				'searchTitle' => 'Szukaj w prawie powszechnym...',
				'menu_id' => 'powszechne',
				'autocompletion' => array(
					'dataset' => 'prawo',
				),
            ),
            'prawo_wojewodztwa' => array(
            	'label' => 'Prawo lokalne',
				'searchTitle' => 'Szukaj w prawie lokalnym...',
				'menu_id' => 'lokalne',
            ),
            'prawo_urzedowe' => array(
            	'label' => 'Prawo urzędowe',
				'searchTitle' => 'Szukaj w prawie urzędowym...',
				'menu_id' => 'urzedowe',
            ),
            'prawo_hasla' => array(
            	'label' => 'Tematy w prawie',
				'searchTitle' => 'Szukaj w tematach...',
				'menu_id' => 'tematy',
				'autocompletion' => array(
					'dataset' => 'prawo_hasla',
				),
            ),
        ),
        'orzecznictwo' => array(
            'sa_orzeczenia' => array(
            	'label' => 'Orzeczenia sądów administracyjnych',
				'searchTitle' => 'Szukaj w orzeczeniach sądów administracyjnych...',
				'menu_id' => 'sa',
            ),
            'sp_orzeczenia' => array(
            	'label' => 'Orzeczenia sądów powszechnych',
				'searchTitle' => 'Szukaj w orzeczeniach sądów powszechnych...',
				'menu_id' => 'sp',
           ),
            'sn_orzeczenia' => array(
            	'label' => 'Orzeczenia Sądu Najwyższego',
				'searchTitle' => 'Szukaj w orzeczeniach Sądu Najwyższego...',
				'menu_id' => 'sn',
            ),
        ),
        'ngo' => array(
        ),
        'zamowienia_publiczne' => array(
            'zamowienia_publiczne' => array(
            	'label' => 'Zamówienia publiczne',
            ),
            'zamowienia_publiczne_zamawiajacy' => array(
            	'label' => 'Zamawiający',
            	'menu_id' => 'zamawiajacy',
            ),
        ),
        'moja_gmina' => array(
            'gminy' => array(
            	'label' => 'Gminy',
            	'menu_id' => 'gminy',
            	'autocompletion' => array(
					'dataset' => 'gminy',
				),
            ),
            'powiaty' => array(
            	'label' => 'Powiaty',
            	'menu_id' => 'powiaty',
            ),
            'wojewodztwa' => array(
            	'label' => 'Województwa',
            	'menu_id' => 'wojewodztwa',
            ),
            'miejscowosci' => array(
            	'label' => 'Miejscowości',
            	'menu_id' => 'miejscowosci',
            ),
        ),
        'media' => array(
            'twitter_accounts' => array(
            	'label' => 'Obserwowane konta',
				'searchTitle' => 'Szukaj w kontach Twitter...',
				'menu_id' => 'twitter_konta',
            ),
            'twitter' => array(
            	'label' => 'Tweety',
				'searchTitle' => 'Szukaj w tweetach...',
				'menu_id' => 'tweety',
            ),
        ),
        'sejmometr' => array(
            'poslowie' => array(
            	'label' => 'Posłowie',
				'menu_id' => 'poslowie',
				'autocompletion' => array(
					'dataset' => 'poslowie',
				),
            ),
            'sejm_dezyderaty' => array(
            	'label' => 'Dezyderaty komisji',
				'menu_id' => 'dezyderaty',
            ),
            'sejm_druki' => array(
            	'label' => 'Druki sejmowe',
				'menu_id' => 'druki',
            ),
            'sejm_glosowania' => array(
            	'label' => 'Głosowania',
				'menu_id' => 'glosowania',
            ),
            'sejm_interpelacje' => array(
            	'label' => 'Interpelacje',
				'menu_id' => 'interpelacje',
            ),
            'sejm_kluby' => array(
            	'label' => 'Kluby sejmowe',
				'menu_id' => 'kluby',
            ),
            'sejm_komisje' => array(
            	'label' => 'Komisje sejmowe',
				'menu_id' => 'komisje',
            ),
            'sejm_komunikaty' => array(
            	'label' => 'Komunikaty Kancelarii Sejmu',
				'menu_id' => 'komunikaty',
            ),
            'sejm_posiedzenia' => array(
            	'label' => 'Posiedzenia Sejmu',
				'menu_id' => 'posiedzenia',
            ),
            'sejm_posiedzenia_punkty' => array(
            	'label' => 'Punkty porządku dziennego',
				'menu_id' => 'punkty',
            ),
            'sejm_wystapienia' => array(
            	'label' => 'Wystąpienia podczas posiedzeń Sejmu',
				'menu_id' => 'wystapienia',
            ),
            'sejm_komisje_opinie' => array(
            	'label' => 'Opinie komisji sejmowych',
				'menu_id' => 'komisje_opinie',
            ),
            'sejm_komisje_uchwaly' => array(
            	'label' => 'Uchwały komisji sejmowych',
				'menu_id' => 'komisje_uchwaly',
            ),
            'poslowie_oswiadczenia_majatkowe' => array(
            	'label' => 'Oświadczenia majątkowe posłów',
				'menu_id' => 'poslowie_oswiadczenia',
            ),
            'poslowie_rejestr_korzysci' => array(
            	'label' => 'Rejestr korzyści posłów',
				'menu_id' => 'poslowie_korzysci',
            ),
            'poslowie_wspolpracownicy' => array(
            	'label' => 'Współpracownicy posłów',
				'menu_id' => 'poslowie_wspolpracownicy',
            ),
        ),
        'kto_tu_rzadzi' => array(
            'instytucje' => array(
            	'label' => 'Instytucje',
				'menu_id' => 'instytucje',
				'order' => 'weight desc',
				'autocompletion' => array(
					'dataset' => 'instytucje',
				),
            ),
        ),
    );
    public $applications = array(
        'krs' => array(
            'name' => 'Krajowy Rejestr Sądowy',
            'href' => '/krs',
            'tag' => 1,
            'icon' => '&#xe605;',
        ),
        'ngo' => array(
            'name' => 'NGO',
            'href' => '/ngo',
            'tag' => 1,
            'icon' => '&#xe614;',
        ),
        'prawo' => array(
            'name' => 'Prawo',
            'href' => '/prawo',
            'tag' => 1,
            'icon' => '&#xe60d;',
        ),
        'bdl' => array(
            'name' => 'Bank Danych Lokalnych',
            'href' => '/bdl',
            'tag' => 1,
            'icon' => '&#xe615;',
        ),
        'api' => array(
            'name' => 'API',
            'href' => '/api',
            'tag' => 4,
            'icon' => '&#xe615;',
        ),
        'kto_tu_rzadzi' => array(
            'name' => 'Kto tu rządzi?',
            'href' => '/kto_tu_rzadzi',
            'src' => '/KtoTuRzadzi/icon/kto_tu_rzadzi.svg',
            'tag' => 1,
            'icon' => '&#xe609;',
        ),
        'moja_gmina' => array(
            'name' => 'Moja Gmina',
            'href' => '/moja_gmina',
            'tag' => 1,
            'icon' => '&#xe605;',
        ),
        'media' => array(
            'name' => 'Media',
            'href' => '/media',
            'tag' => 1,
            'icon' => '&#xe608;',
        ),
        'dostep_do_informacji_publicznej' => array(
            'name' => 'Dostęp do Informacji Publicznej',
            'href' => '/dostep_do_informacji_publicznej',
            'tag' => 2,
            'icon' => '&#xe60e;',
        ),
        'finanse_gmin' => array(
            'name' => 'Finanse gmin',
            'href' => '/finanse_gmin',
            'tag' => 2,
            'icon' => '&#xe602;',
        ),
        'zamowienia_publiczne' => array(
            'name' => 'Zamówienia publiczne',
            'href' => '/zamowienia_publiczne',
            'tag' => 1,
            'icon' => '&#xe613;',
        ),
        'handel_zagraniczny' => array(
            'name' => 'Handel zagraniczny',
            'href' => '/handel_zagraniczny',
            'tag' => 1,
            'icon' => '&#xe603;',
        ),


        /*
        'koleje' => array(
            'name' => 'Koleje',
            'href' => '/koleje',
            'src' => '/HandelZagraniczny/icon/handel_zagraniczny.svg',
            'tag' => 1,
        ),
        */
        'dane' => array(
            'name' => 'Szukaj...',
            'href' => '/dane',
            'tag' => 0,
            'icon' => '&#xe616;',
        ),

        /*
        'mapa_prawa' => array(
            'name' => 'Mapa prawa',
            'href' => '/mapa_prawa',
            'tag' => 1,
            'icon' => '&#xe607;',
        ),
        */


        'powiadomienia' => array(
            'name' => 'Powiadomienia',
            'href' => '/powiadomienia',
            'tag' => 3,
            'icon' => '&#xe60a;',
        ),
        'pisma' => array(
            'name' => 'Pisma',
            'href' => '/pisma',
            'tag' => 3,
            'icon' => '&#xe60b;',
        ),
        'orzecznictwo' => array(
            'name' => 'Orzecznictwo',
            'href' => '/orzecznictwo',
            'tag' => 1,
            'icon' => '&#xe617;',
        ),
        'sejmometr' => array(
            'name' => 'Sejmometr',
            'href' => '/dane/instytucje/3214,sejm-rzeczypospolitej-polskiej',
            'tag' => 1,
            'icon' => '&#xe610;',
        ),
        'kody_pocztowe' => array(
            'name' => 'Kody pocztowe',
            'href' => '/kody_pocztowe',
            'tag' => 1,
            'icon' => '&#xe604;',
        ),
        /*
        'patenty' => array(
            'name' => 'Patenty',
            'href' => '/patenty',
            'src' => '/sejmometr/icon/sejmometr.svg',
            'tag' => 1,
        ),
        */
        'paszport' => array(
            'name' => 'Paszport',
            'href' => '/paszport',
            'tag' => 3,
            'icon' => '&#xe60c;',
        ),


        'wydatki_poslow' => array(
            'name' => 'Wydatki Posłów',
            'href' => '/wydatki_poslow',
            'tag' => 2,
            'icon' => '&#xe611;',
        ),
        'wyjazdy_poslow' => array(
            'name' => 'Wyjazdy Posłów',
            'href' => '/wyjazdy_poslow',
            'tag' => 2,
            'icon' => '&#xe612;',
        ),

    );

    public function beforeFilter()
    {

	    $this->protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    $this->port = ($_SERVER['SERVER_PORT'] == 80) ? false : ':' . $_SERVER['SERVER_PORT'];

        if (defined('PORTAL_DOMAIN')) {
            $pieces = parse_url(Router::url($this->here, true));

            if (defined('PK_DOMAIN') && ($pieces['host'] == PK_DOMAIN)) {

                $this->domainMode = 'PK';

                // only certain actions are allowed in this domain
                // for other actions we are immediatly redirecting to PORTAL_DOMAIN

                if (stripos($_SERVER['REQUEST_URI'], '/dane/gminy/903') === 0) {

                    $url = substr($_SERVER['REQUEST_URI'], 15);
                    if ($url[0] == ',') {

                        $p = strpos($url, '/');
                        $url = ($p === false) ? '' : substr($url, $p);

                    }

                    $this->redirect($this->protocol . PK_DOMAIN . $this->port . $url);
                    die();

                }

                if (preg_match('/^(.*?)\,([a-z0-9\-]+)$/', $this->here, $match)) {

                    $this->redirect($this->protocol . PK_DOMAIN . $this->port . $match[1]);
                    die();
                }

				$_id = strtolower($this->request->params['plugin'] . '.' . $this->request->params['controller']);

				$cross_domain = (
					stripos($_SERVER['REQUEST_URI'], '/cross-domain-') === 0 or
					stripos($_SERVER['REQUEST_URI'], '/login') === 0 or
					stripos($_SERVER['REQUEST_URI'], '/logout') === 0
                );

				if(
					!in_array($_id, array(
						'dane.gminy',
						'dane.highstock_browser',
						'powiadomienia.powiadomienia',
						'subscriptions.subscriptions',
						'pisma.pisma',
						'pisma.szablony',
						'zamowieniapubliczne.zamowieniapubliczne',
					)) &&
					!$cross_domain
				) {



					$url = $_SERVER['REQUEST_URI'];
                    if ($url[0] == ',') {
                        $p = strpos($url, '/');
                        $url = ($p === false) ? '' : substr($url, $p);
                    }
                    return $this->redirect($this->protocol . PORTAL_DOMAIN . $this->port . $url);

				}

            } elseif ($pieces['host'] != PORTAL_DOMAIN) {

                $this->redirect($this->protocol . PORTAL_DOMAIN . $this->port . $this->here, 301);
                die();

            }

        }

        $this->response->header('Access-Control-Allow-Origin', $this->request->header('Origin'));
        $this->response->header('Access-Control-Allow-Credentials', true);


		$redirect = false;

        if ($this->Session->read('Auth.User.id') && $this->Session->read('Pisma.transfer_anonymous')) {

            $this->loadModel('Pisma.Pismo');
            $this->Pismo->transfer_anonymous($this->Session->read('previous_id'));
            $this->Session->delete('Pisma.transfer_anonymous');

            $redirect = true;

        }

        if ($redirect)
            return $this->redirect($this->request->here);



        # assigning translations for javascript use
        if ($this->params->plugin) {
            $path = ROOT . DS . APP_DIR . DS . 'Plugin' . DS . Inflector::camelize($this->params->plugin) . DS . 'Locale' . DS . Configure::read('Config.language') . DS . 'LC_MESSAGES' . DS . Inflector::underscore($this->params->plugin) . '.po';
        } else {
            $path = ROOT . DS . APP_DIR . DS . 'Locale' . DS . Configure::read('Config.language') . DS . 'LC_MESSAGES' . DS . 'default.po';
        }
        if (file_exists($path)) {
            $translations = I18n::loadPo($path);
            foreach ($translations as &$item) {
                $item = stripslashes($item);
                $item = preg_replace('/"/', '&quot;', $item);
            }
        } else {
            $translations = array();
        }
        $this->set('translation', $translations);

        parent::beforeFilter();
        $this->Auth->allow();

        $this->set('statusbarCrumbs', $this->statusbarCrumbs);
        $this->set('statusbarMode', $this->statusbarMode);
        $this->set('_APPLICATIONS', $this->getApplications());
        $this->set('_APPLICATION', $this->getApplication());
        $this->set('domainMode', $this->domainMode);

//		// remember path for redirect if necessary
//		if ( Router::url( null ) != '/null' ) { // hack for bug
//			$this->Session->write( 'Auth.loginRedirect', Router::url( null, true ) );
//		}

        // cross domain login
        $this->set('current_host', $_SERVER['HTTP_HOST']);
        if ($this->Session->check('crossdomain_login_token')) {
            $this->set('crossdomain_login_token', $this->Session->read('crossdomain_login_token'));
            $this->Session->delete('crossdomain_login_token');
        }
        if ($this->Session->check('crossdomain_logout')) {
            $this->set('crossdomain_logout', $this->Session->read('crossdomain_logout'));
            $this->Session->delete('crossdomain_logout');
        }

        $this->set('isAdmin', $this->hasUserRole('2'));
    }

    /**
     * Zwraca listę dostępnych aplikacji
     * @return array
     */
    public function getApplications($options = array())
    {
        return $this->applications;
    }

    /**
     * Zwraca aktualną aplikację
     * lub false jeśli nie żadna nie jest aktywna w danej chwili
     * @return array|bool
     */
    public function getApplication($id = false)
    {

        if ($id && array_key_exists($id, $this->applications)) {

            return array_merge($this->applications[$id], array(
	            'id' => $id,
            ));

        } else return false;

    }

    public function getDatasetByAlias($app_id = false, $alias = false)
    {
	    if( $app_id && $alias && array_key_exists($app_id, $this->datasets) ) {
            foreach ($this->datasets[$app_id] as $dataset_id => $dataset_name) {
                if (@$dataset_name['menu_id'] == $alias) {
                    return array(
                        'app_id' => $app_id,
                        'dataset_id' => $dataset_id,
                        'dataset_name' => $dataset_name,
                    );
                }
            }
	    }
    }

    public function getDataset($id = false)
    {
        if ($id) {
            foreach ($this->datasets as $app_id => $datasets) {
                foreach ($datasets as $dataset_id => $dataset_name) {
                    if ($dataset_id == $id) {
                        return array(
                            'app_id' => $app_id,
                            'dataset_id' => $dataset_id,
                            'dataset_name' => $dataset_name,
                        );
                    }
                }
            }
        }
        return false;
    }

    public function beforeRender()
    {

        if( @$this->request->params['ext']!='json' ) {

	        $layout = $this->setLayout();
	        $menu = $this->getMenu();

			if( !empty($menu) ) {

		        if ($this->menu_selected == '_default')
		            $this->menu_selected = $this->request->params['action'];

		        $menu['selected'] = $this->menu_selected;

	        }

	        $this->set('_layout', $layout);
	        $this->set('_breadcrumbs', $this->breadcrumbs);
	        $this->set('_applications', $this->applications);
	        $this->set('_menu', $menu);
	        $this->set('_observeOptions', $this->observeOptions);
	        $this->set('appSelected', $this->appSelected);

        }

    }

    /**
     * Ustawia informację o układzie layoutu strony
     * @param array $layout
     * @return array
     */
    public function setLayout($layout = array())
    {
        if (!empty($layout) && is_array($layout))
            $this->_layout = array_merge($this->_layout, $layout);

        return $this->_layout;
    }

    /**
     * Zwraca listę elementów w menu
     * @return array
     */
    public function getMenu()
    {
        return $this->menu;
    }

    public function addStatusbarCrumb($item)
    {
        $this->statusbarCrumbs[] = $item;
        $this->set('statusbarCrumbs', $this->statusbarCrumbs);
    }

    public function setMetaDesc($val)
    {
        return $this->setMetaDescription($val);
    }

    public function setMetaDescription($val)
    {
        return $this->setMeta('description', $val);
    }

    public function setMeta($key, $val = null)
    {
        if (is_array($key)) {
            foreach ($key as $property => $content)
                $this->meta[$property] = $content;
            $this->set('_META', $this->meta);
            return true;
        }

        if (!$val)
            return false;

        $this->meta[$key] = $val;
        $this->set('_META', $this->meta);

        return $val;
    }

    public function prepareMetaTags()
    {
        $this->setMeta(array(
            'og:url' => Router::url($this->here, true),
            'og:type' => 'website',
            'og:description' => strip_tags(__('LC_MAINHEADER_TEXT')),
            'og:image' => FULL_BASE_URL . '/img/social/share_main.jpg',
            'fb:admins' => '616010705',
            'fb:app_id' => FACEBOOK_appId
        ));
    }

    public function addAppBreadcrumb($app_id = false)
    {
        if ($app = $this->getApplication($app_id)) {

            $this->addBreadcrumb(array(
                'label' => $app['name'],
                'icon' => '<i class="glyphicon" data-icon-applications="' . $app['icon'] . '"></i>',
                'href' => $app['href'],
            ));
        }
    }

    public function addBreadcrumb($params)
    {
        $this->breadcrumbs[] = $params;
        $this->set('_breadcrumbs', $this->breadcrumbs);

    }

    public function getDatasets($app = false)
    {

        if ($app) {

            if (array_key_exists($app, $this->datasets))
                return $this->datasets[$app];
            else
                return false;

        } else return $this->datasets;

    }

    public function getUserRoles() {

	    if( $this->Auth->user() ) {

		    return @array_column($this->Auth->user('UserRole'), 'role_id');

	    } else return array();

    }

    public function hasUserRole($role) {

	    $roles = $this->getUserRoles();
	    if( in_array('2', $roles) )
	    	return true;
	    else
	    	return in_array($role, $roles);

    }
}
