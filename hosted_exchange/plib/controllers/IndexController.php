<?php

class IndexController extends pm_Controller_Action
{
	public function init()
	{
		parent::init();

		$this->view->pageTitle = 'Hosted Exchange';
		$this->success = 'les données ont été sauvegardées avec succès.';

		$this->view->tabs = array(
			array(
				'title' => 'Créer mail',
				'action' => 'addmail',
			),
			array(
				'title' => 'Supprimer mail',
				'action' => 'delmail',
			),
			array(
				'title' => 'Gérer les droits',
				'action' => 'rights',
			),
			array(
				'title' => 'Ajouter une redirection',
				'action' => 'addredirection',
			),
			array(
				'title' => 'Supprimer une redirection',
				'action' => 'delredirection',
			),
			array(
				'title' => 'Ajouter un membre à un groupe',
				'action' => 'addgroupmember'
			),
			array(
				'title' => 'Supprimer un membre à un groupe',
				'action' => 'delgroupmember'
			),
			array(
				'title' => 'Créer un groupe',
				'action' => 'addgroup'
			),
			array(
				'title' => 'Supprimer un groupe',
				'action' => 'delgroup'
			),
		);
	}

	public function indexAction()
	{
		$this->_forward('addmail');
	}

	private function getUser() {
		return "administrator@dom.local";
	}

	private function getPassword() {
		return "DL#dzspme4f9e";
	}

	private function rqstAddMail($form)
	{
		if ($form->getValue('password') != $form->getValue('confirm_password')) {
			return 1;
		}
		$jsonObj->User = $this->getUser
		$jsonObj->Pass = $this->getPassword
		$jsonObj->FistName = $form->getValue('first_name');
		$jsonObj->Initial = $form->getValue('initial');
		$jsonObj->LastName = $form->getValue('last_name');
		$jsonObj->CompleteName = $form->getValue('complete_name');
		$jsonObj->Connection = $form->getValue('connection');
		$jsonObj->Domain = $form->getValue('domain');
		$jsonObj->Plan = $form->getValue('plan');
		$jsonObj->OrganisationalUnits = $form->getValue('orga_unit');
		$jsonObj->MailPassword = $form->getValue('password');

		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/mail/add';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function addmailAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Ajouter un mail";
		$form->addElement('text', 'first_name', array(
			'label' => 'Prénom',
			'value' => pm_Settings::get('first_name'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('text', 'initital', array(
			'label' => 'Initiale',
			'value' => pm_Settings::get('initial'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('text', 'last_name', array(
			'label' => 'Nom de famille',
			'value' => pm_Settings::get('last_name'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('text', 'complete_name', array(
			'label' => 'Nom Complet',
			'value' => pm_Settings::get('complete_name'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('text', 'connection', array(
			'label' => 'Nom de connection utilisateur',
			'value' => pm_Settings::get('connection_name'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('select', 'domain', array(
			'label' => 'domaine',
			'multiOptions' => array('opt-0' => '@domain0.fr', 'opt-1' => '@domain1.fr'),
			'value' => pm_Settings::get('domain'),
			'required' => true,
		));
		$form->addElement('password', 'password', array(
			'label' => 'Mot de passe',
			'description' => 'Doit contenir une lettre, une majuscule, un charactere speciaux, un numero et 8 characteres au minimum',
			'validators' => array(
				array('StringLength', true, array(5, 255)),
			),
		));
		$form->addElement('password', 'confirm_password', array(
			'label' => 'Confirmer le mot de passe',
			'validators' => array(
				array('StringLength', true, array(5, 255)),
			),
		));
		$form->addElement('select', 'plan', array(
			'label' => 'domaine',
			'multiOptions' => array('opt-0' => 'plan1', 'opt-1' => 'plan2'),
			'value' => pm_Settings::get('plan'),
			'required' => true,
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));

		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('first_name', $form->getValue('first_name'));
			pm_Settings::set('initial', $form->getValue('initial'));
			pm_Settings::set('last_name', $form->getValue('last_name'));
			pm_Settings::set('complete_name', $form->getValue('complete_name'));
			pm_Settings::set('connection', $form->getValue('connection'));
			pm_Settings::set('domain', $form->getValue('domain'));
			if ($form->getValue('password')) {
				pm_Settings::set('password', $form->getValue('password'));
			}
			if ($form->getValue('confirm_password')) {
				pm_Settings::set('confirm_password', $form->getValue('confirm_password'));
			}
			pm_Settings::set('plan', $form->getValue('plan'));
			if rqstAddMail($form) {
				// TODO CATCH ERREUR
			} else {
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
				$this->_status->addMessage('info', $json);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstDelMail($form) {
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this->getPassword();
		$jsonObj->Identity = $form->getValue('mail');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/mail/delete';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;

	}

	public function  delmailAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Supprimer une boite mail";
		$form->addElement('select', 'mail', array(
			'label' => 'Mail',
			'multiOptions' => array('opt-0' => 'mail1', 'opt-1' => 'mail2'),
			'value' => pm_Settings::get('mail'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('mail', $form->getValue('mail'));

			if ($this->rqstDelMail($form) {
				// TODO CATCH ERREUR
				$this->_status->addError("Error");
			} else {
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
				$this->_status->addMessage('info', $json);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstAddGroupMember($form)
	{
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this->getPassword();
		$jsonObj->IdentityGroup = $form->getValue('group');
		$jsonObj->Member = $form->getValue('user');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/groupmember/add';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function  addgroupmemberAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Ajouter membre à un groupe";
		$form->addElement('select', 'user', array(
			'label' => 'Utilisateur',
			'multiOptions' => array('opt-0' => 'user1', 'opt-1' => 'user2'),
			'value' => pm_Settings::get('user'),
		));
		$form->addElement('select', 'group', array(
			'label' => 'Groupe',
			'multiOptions' => array('opt-0' => 'user11', 'opt-1' => 'user22'),
			'value' => pm_Settings::get('group'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('user', $form->getValue('user'));
			pm_Settings::set('group', $form->getValue('group'));

			if (rqstAddGroupMember($form)) {
				// TODO CATCH ERREUR
			} else {
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstDelGroupMember($form)
	{
		$jsontmp->User = $this->getUser();
		$jsontmp->Pass = $this-getPassword();
		$jsonObj->IdentityGroup = $form->getValue('group');
		$jsonObj->Member = $form->getValue('user');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/groupmember/del';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function  delgroupmemberAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Retirer un membre d'un groupe";
		$form->addElement('select', 'user', array(
			'label' => 'Utilisateur',
			'multiOptions' => array('opt-0' => 'user1', 'opt-1' => 'user2'),
			'value' => pm_Settings::get('user'),
		));
		$form->addElement('select', 'group', array(
			'label' => 'Groupe',
			'multiOptions' => array('opt-0' => 'user11', 'opt-1' => 'user22'),
			'value' => pm_Settings::get('group'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('user', $form->getValue('user'));
			pm_Settings::set('group', $form->getValue('group'));
			if rqstDelGroupMember($form) {
			} else {
				// TODO CATCH ERREUR
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstRights($form)
	{
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this-getPassword();
		$jsonObj->User1 = $form->getValue('group');
		$jsonObj->User2 = $form->getValue('user');
		$jsonObj->Mode = $form->getValue('opt');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/rights/modify';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function rightsAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Gérer les droits";
		$rights = "A tout les droits sur";
		$no_rights = "N'a aucun droit sur";
		$form->addElement('select', 'user1', array(
			'label' => 'Utilisateur1',
			'multiOptions' => array('opt-0' => 'user11', 'opt-1' => 'user22'),
			'value' => pm_Settings::get('user1'),
		));
		$form->addElement('select', 'opt', array(
			'label' => 'domaine',
			'multiOptions' => array('opt-0' => $rights, 'opt-1' => $no_rights),
			'value' => pm_Settings::get('opt'),
		));
		$form->addElement('select', 'user2', array(
			'label' => 'Utilisateur2',
			'multiOptions' => array('opt-0' => 'user21', 'opt-1' => 'user22'),
			'value' => pm_Settings::get('user2'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('user1', $form->getValue('user1'));
			pm_Settings::set('opt', $form->getValue('opt'));
			pm_Settings::set('user2', $form->getValue('user2'));

			if (rqstRights($form)) {
				// TODO CATCH ERREUR
			} else {
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstAddRedirection($form)
	{
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this-getPassword();
		$jsonObj->User1 = $form->getValue('group');
		$jsonObj->User2 = $form->getValue('user');
		$jsonObj->Mode = $form->"redirection";
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/mail/redirect';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function addredirectionAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Ajouter une redirection";
		$form->addElement('select', 'user1', array(
			'label' => 'Utilisateur',
			'multiOptions' => array('opt-0' => 'user11', 'opt-1' => 'user22'),
			'value' => pm_Settings::get('user1'),
		));
		$form->addElement('SimpleText', 'text', array(
			'value' => "Sera rediriger vers",
		));
		$form->addElement('select', 'user2', array(
			'label' => 'Utilisateur',
			'multiOptions' => array('opt-0' => 'user21', 'opt-1' => 'user22'),
			'value' => pm_Settings::get('user2'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('user1', $form->getValue('user1'));
			pm_Settings::set('user2', $form->getValue('user2'));

			if (rqstAddRedirection($form)) {
				// TODO CATCH ERREUR
			} else {
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstDelRedirection($form)
	{
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this-getPassword();
		$jsonObj->User1 = $form->getValue('group');
		$jsonObj->User2 = $form->getValue('user');
		$jsonObj->Mode = $form->getValue('opt');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/mail/redirect';
		// and send request
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function delredirectionAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Supprimer les redirections";
		$form->addElement('select', 'user', array(
			'label' => 'Utilisateur',
			'multiOptions' => array('opt-0' => 'user1', 'opt-1' => 'user2'),
			'value' => pm_Settings::get('user1'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('user', $form->getValue('user1'));

			if rqstDelRedirection($form) {
				// TODO CATCH ERREUR
			} else {
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstAddGroup($form)
	{
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this-getPassword();
		$jsonObj->Name = $form->getValue('name');
		$jsonObj->Alias = $form->getValue('alias');
		$jsonObj->Connection = $form->getValue('connection');
		$jsonObj->Domain = $form->getValue('domain');
		$jsonObj->Notes = $form->getValue('description');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/mail/redirect';
		// and send request
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function addgroupAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Créer un groupe";
		$form->addElement('text', 'name', array(
			'label' => 'Nom',
			'value' => pm_Settings::get('nom'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('text', 'alias', array(
			'label' => 'Alias',
			'value' => pm_Settings::get('alias'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('text', 'connection', array(
			'label' => 'Nom de connection utilisateur',
			'value' => pm_Settings::get('connection_name'),
			'required' => true,
			'validators' => array(
				array('NotEmpty', true),
			),
		));
		$form->addElement('select', 'domain', array(
			'label' => 'domaine',
			'multiOptions' => array('opt-0' => '@domain0.fr', 'opt-1' => '@domain1.fr'),
			'value' => pm_Settings::get('domain'),
			'required' => true,
		));
		$form->addElement('textarea', 'description', array(
			'label' => 'Description de groupe',
			'value' => pm_Settings::get('description'),
			'class' => 'f-middle-size',
			'rows' => 4,
			'required' => true,
			'validators' => array(
				array('StringLength', true, array(0, 255)),
			),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));

		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('name', $form->getValue('name'));
			pm_Settings::set('alias', $form->getValue('alias'));
			pm_Settings::set('connection', $form->getValue('connection'));
			pm_Settings::set('domain', $form->getValue('domain'));
			pm_Settings::set('description', $form->getValue('description'));

			if (rqstAddGroup($form)) {
				// TODO CATCH ERREUR
			} else {
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}

	private function rqstDelGroup($form)
	{
		$jsonObj->User = $this->getUser();
		$jsonObj->Pass = $this-getPassword();
		$jsonObj->Name = $form->getValue('group');
		$json = json_encode($jsonObj);

		$url = 'https://example.com/a2com/api/v1.0/hostedexchange/mail/redirect';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

		$result = curl_exec($ch);
		// TODO RESULT REQUEST
		// check le result
		// return code d'error et msg d'error
		return 0;
	}

	public function  delgroupAction()
	{
		$form = new pm_Form_Simple();
		$this->view->title = "Supprimer un groupe";
		$form->addElement('select', 'group', array(
			'label' => 'Mail',
			'multiOptions' => array('opt-0' => 'group1', 'opt-1' => 'group2'),
			'value' => pm_Settings::get('mail'),
		));
		$form->addControlButtons(array(
			'cancelLink' => pm_Context::getModulesListUrl(),
		));
		if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
			pm_Settings::set('group', $form->getValue('group'));

			if (rqstDelGroup($form) {
				// TODO CATCH ERREUR
			} else {
				$this->_status->addMessage('info', $json);
				// TODO LOG SQL
				// $this->_status->addMessage('info', $this->success);
			}
			$this->_helper->json(array('redirect' => pm_Context::getBaseUrl()));
		}
		$this->view->form = $form;
	}
}
