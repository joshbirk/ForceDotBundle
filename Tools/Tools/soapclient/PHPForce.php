<?php
class SforceBaseClient {
	protected $sforce;
	protected $sessionId;
	protected $location;
	protected $version = '11.0';

	protected $namespace;

	// Header Options
	protected $callOptions;
	protected $assignmentRuleHeader;
	protected $emailHeader;
	protected $loginScopeHeader;
	protected $mruHeader;
	protected $queryHeader;
	protected $userTerritoryDeleteHeader;
	protected $sessionHeader;

	public function getNamespace() {
		return $this->namespace;
	}


	// clientId specifies which application or toolkit is accessing the
	// salesforce.com API. For applications that are certified salesforce.com
	// solutions, replace this with the value provided by salesforce.com.
	// Otherwise, leave this value as 'phpClient/1.0'.
	protected $client_id;

	public function printDebugInfo() {
		echo "PHP Toolkit Version: $this->version\r\n";
		echo 'Current PHP version: ' . phpversion();
		echo "\r\n";
		echo 'SOAP enabled: ';
		if (extension_loaded('soap')) {
			echo 'True';
		} else {
			echo 'False';
		}
		echo "\r\n";
		echo 'OpenSSL enabled: ';
		if (extension_loaded('openssl')) {
			echo 'True';
		} else {
			echo 'False';
		}
	}

	/**
	 * Connect method to www.salesforce.com
	 *
	 * @param string $wsdl   Salesforce.com Partner WSDL
	 */
	public function createConnection($wsdl, $proxy=null) {
		$_SERVER['HTTP_USER_AGENT'] = 'Salesforce/PHPToolkit/1.0';

		$soapClientArray = null;
		if (phpversion() > '5.1.2') {
			$soapClientArray = array (
          'encoding' => 'utf-8',
          'trace' => 1,
          'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
			);
		} else {
			$soapClientArray = array (
          'encoding' => 'utf-8',
          'trace' => 1
			);
		}

		if ($proxy != null) {
  		$proxySettings = array();
	    $proxySettings['proxy_host'] = $proxy->host;
		  $proxySettings['proxy_port'] = $proxy->port; // Use an integer, not a string
  		$proxySettings['proxy_login'] = $proxy->login; 
      $proxySettings['proxy_password'] = $proxy->password;

  		$soapClientArray = array_merge($soapClientArray, $proxySettings);
		}

		$this->sforce = new SoapClient($wsdl, $soapClientArray);
		return $this->sforce;
	}

	public function setCallOptions($header) {
		if ($header != NULL) {
			$this->callOptions = new SoapHeader($this->namespace, 'CallOptions', array (
          'client' => $header->client,
          'defaultNamespace' => $header->defaultNamespace
			));
		} else {
			$this->callOptions = NULL;
		}
	}

	/**
	 * Login to Salesforce.com and starts a client session.
	 *
	 * @param string $username   Username
	 * @param string $password   Password
	 *
	 * @return LoginResult
	 */
	public function login($username, $password) {
		$this->sforce->__setSoapHeaders(NULL);
		if ($this->callOptions != NULL) {
			$this->sforce->__setSoapHeaders(array($this->callOptions));
		}
		if ($this->loginScopeHeader != NULL) {
			$this->sforce->__setSoapHeaders(array($this->loginScopeHeader));
		}
		$result = $this->sforce->login(array (
         'username' => $username,
         'password' => $password
		));
		$result = $result->result;
		$this->_setLoginHeader($result);
		return $result;
	}

	/**
	 * Specifies the session ID returned from the login server after a successful
	 * login.
	 */
	protected function _setLoginHeader($loginResult) {
		$this->sessionId = $loginResult->sessionId;
		$this->setSessionHeader($this->sessionId);
		$serverURL = $loginResult->serverUrl;
		$this->setEndPoint($serverURL);
	}

	/**
	 * Set the endpoint.
	 *
	 * @param string $location   Location
	 */
	public function setEndpoint($location) {
		$this->location = $location;
		$this->sforce->__setLocation($location);
	}

	private function setHeaders($call=NULL) {
		$this->sforce->__setSoapHeaders(NULL);
		$header_array = array (
		$this->sessionHeader
		);

		$header = $this->callOptions;
		if ($header != NULL) {
			array_push($header_array, $header);
		}

		if ($call == "create" ||
		$call == "merge" ||
		$call == "update" ||
		$call == "upsert"
		) {
			$header = $this->assignmentRuleHeader;
			if ($header != NULL) {
				array_push($header_array, $header);
			}
		}

		if ($call == "login") {
			$header = $this->loginScopeHeader;
			if ($header != NULL) {
				array_push($header_array, $header);
			}
		}

		if ($call == "create" ||
		$call == "resetPassword" ||
		$call == "update" ||
		$call == "upsert"
		) {
			$header = $this->emailHeader;
			if ($header != NULL) {
				array_push($header_array, $header);
			}
		}

		if ($call == "create" ||
		$call == "merge" ||
		$call == "query" ||
		$call == "retrieve" ||
		$call == "update" ||
		$call == "upsert"
		) {
			$header = $this->mruHeader;
			if ($header != NULL) {
				array_push($header_array, $header);
			}
		}

		if ($call == "delete") {
			$header = $this->userTerritoryDeleteHeader;
			if ($header != NULL) {
				array_push($header_array, $header);
			}
		}

		if ($call == "query" ||
		$call == "queryMore" ||
		$call == "retrieve") {
			$header = $this->queryHeader;
			if ($header != NULL) {
				array_push($header_array, $header);
			}
		}
		$this->sforce->__setSoapHeaders($header_array);
	}

	public function setAssignmentRuleHeader($header) {
		if ($header != NULL) {
			$this->assignmentRuleHeader = new SoapHeader($this->namespace, 'AssignmentRuleHeader', array (
             'assignmentRuleId' => $header->assignmentRuleId,
             'useDefaultRule' => $header->useDefaultRuleFlag
			));
		} else {
			$this->assignmentRuleHeader = NULL;
		}
	}

	public function setEmailHeader($header) {
		if ($header != NULL) {
			$this->emailHeader = new SoapHeader($this->namespace, 'EmailHeader', array (
             'triggerAutoResponseEmail' => $header->triggerAutoResponseEmail,
             'triggerOtherEmail' => $header->triggerOtherEmail,
             'triggerUserEmail' => $header->triggerUserEmail
			));
		} else {
			$this->emailHeader = NULL;
		}
	}

	public function setLoginScopeHeader($header) {
		if ($header != NULL) {
			$this->loginScopeHeader = new SoapHeader($this->namespace, 'LoginScopeHeader', array (
        'organizationId' => $header->organizationId,
        'portalId' => $header->portalId
			));
		} else {
			$this->loginScopeHeader = NULL;
		}
		//$this->setHeaders('login');
	}

	public function setMruHeader($header) {
		if ($header != NULL) {
			$this->mruHeader = new SoapHeader($this->namespace, 'MruHeader', array (
             'updateMru' => $header->updateMruFlag
			));
		} else {
			$this->mruHeader = NULL;
		}
	}

	public function setSessionHeader($id) {
		if ($id != NULL) {
			$this->sessionHeader = new SoapHeader($this->namespace, 'SessionHeader', array (
             'sessionId' => $id
			));
		} else {
			$this->sessionHeader = NULL;
		}
	}

	public function setUserTerritoryDeleteHeader($header) {
		if ($header != NULL) {
			$this->serTerritoryDeleteHeader = new SoapHeader($this->namespace, 'UserTerritoryDeleteHeader  ', array (
             'transferToUserId  ' => $header->transferToUserId
			));
		} else {
			$this->mruHeader = NULL;
		}
	}

	public function setQueryOptions($header) {
		if ($header != NULL) {
			$this->queryHeader = new SoapHeader($this->namespace, 'QueryOptions', array (
             'batchSize' => $header->batchSize
			));
		} else {
			$this->queryHeader = NULL;
		}
	}

	public function getSessionId() {
		return $this->sessionId;
	}

	public function getLocation() {
		return $this->location;
	}

	public function getConnection() {
		return $this->sforce;
	}

	public function getFunctions() {
		return $this->sforce->__getFunctions();
	}

	public function getTypes() {
		return $this->sforce->__getTypes();
	}

	public function getLastRequest() {
		return $this->sforce->__getLastRequest();
	}

	public function getLastRequestHeaders() {
		return $this->sforce->__getLastRequestHeaders();
	}

	public function getLastResponse() {
		return $this->sforce->__getLastResponse();
	}

	public function getLastResponseHeaders() {
		return $this->sforce->__getLastResponseHeaders();
	}

	protected function _convertToAny($fields) {
		$anyString = '';
		foreach ($fields as $key => $value) {
			$anyString = $anyString . '<' . $key . '>' . $value . '</' . $key . '>';
		}
		return $anyString;
	}

	protected function _create($arg) {
		$this->setHeaders("create");
		return $this->sforce->create($arg)->result;
	}

	protected function _merge($arg) {
		$this->setHeaders("merge");
		return $this->sforce->merge($arg)->result;
	}

	protected function _process($arg) {
		$this->setHeaders();
		return $this->sforce->process($arg)->result;
	}

	protected function _update($arg) {
		$this->setHeaders("update");
		return $this->sforce->update($arg)->result;
	}

	protected function _upsert($arg) {
		$this->setHeaders("upsert");
		return $this->sforce->upsert($arg)->result;
	}

  public function sendSingleEmail($request) {
    if (is_array($request)) {
      $messages = array();
      foreach ($request as $r) {
        $email = new SoapVar($r, SOAP_ENC_OBJECT, 'SingleEmailMessage', $this->namespace);
        array_push($messages, $email);
      }
      $arg->messages = $messages;
      return $this->_sendEmail($arg);
    } else {
      $backtrace = debug_backtrace();
      die('Please pass in array to this function:  '.$backtrace[0]['function']);
    }
  }

  public function sendMassEmail($request) {
    if (is_array($request)) {
      $messages = array();
      foreach ($request as $r) {
        $email = new SoapVar($r, SOAP_ENC_OBJECT, 'MassEmailMessage', $this->namespace);
        array_push($messages, $email);
      }
      $arg->messages = $messages;
      return $this->_sendEmail($arg);
    } else {
      $backtrace = debug_backtrace();
      die('Please pass in array to this function:  '.$backtrace[0]['function']);
    }
  }	
	
	protected function _sendEmail($arg) {
		$this->setHeaders();
		return $this->sforce->sendEmail($arg)->result;
	}

	/**
	 * Converts a Lead into an Account, Contact, or (optionally) an Opportunity.
	 *
	 * @param array $leadConverts    Array of LeadConvert
	 *
	 * @return LeadConvertResult
	 */
	public function convertLead($leadConverts) {
		$this->setHeaders("convertLead");
		$arg = new stdClass;
		$arg->leadConverts = $leadConverts;
		return $this->sforce->convertLead($arg);
	}

	/**
	 * Deletes one or more new individual objects to your organization's data.
	 *
	 * @param array $ids    Array of fields
	 * @return DeleteResult
	 */
	public function delete($ids) {
		$this->setHeaders("delete");
		$arg = new stdClass;
		$arg->ids = $ids;
		return $this->sforce->delete($arg)->result;
	}

	/**
	 * Deletes one or more new individual objects to your organization's data.
	 *
	 * @param array $ids    Array of fields
	 * @return DeleteResult
	 */
	public function undelete($ids) {
		$this->setHeaders("undelete");
		$arg = new stdClass;
		$arg->ids = $ids;
		return $this->sforce->undelete($arg)->result;
	}

	/**
	 * Deletes one or more new individual objects to your organization's data.
	 *
	 * @param array $ids    Array of fields
	 * @return DeleteResult
	 */
	public function emptyRecycleBin($ids) {
		$this->setHeaders();
		$arg = new stdClass;
		$arg->ids = $ids;
		return $this->sforce->emptyRecycleBin($arg)->result;
	}

	/**
	 * Process Submit Request for Approval
	 *
	 * @param array $processRequestArray
	 * @return ProcessResult
	 */
	public function processSubmitRequest($processRequestArray) {
		if (is_array($processRequestArray)) {
			foreach ($processRequestArray as &$process) {
				$process = new SoapVar($process, SOAP_ENC_OBJECT, 'ProcessSubmitRequest', $this->namespace);
			}
			$arg->actions = $processRequestArray;
			return $this->_process($arg);
		} else {
			$backtrace = debug_backtrace();
			die('Please pass in array to this function:  '.$backtrace[0]['function']);
		}
	}

	/**
	 * Process Work Item Request for Approval
	 *
	 * @param array $processRequestArray
	 * @return ProcessResult
	 */
	public function processWorkitemRequest($processRequestArray) {
		if (is_array($processRequestArray)) {
			foreach ($processRequestArray as &$process) {
				$process = new SoapVar($process, SOAP_ENC_OBJECT, 'ProcessWorkitemRequest', $this->namespace);
			}
			$arg->actions = $processRequestArray;
			return $this->_process($arg);
		} else {
			$backtrace = debug_backtrace();
			die('Please pass in array to this function:  '.$backtrace[0]['function']);
		}
	}

	/**
	 * Retrieves a list of available objects for your organization's data.
	 *
	 * @return DescribeGlobalResult
	 */
	public function describeGlobal() {
		$this->setHeaders("describeGlobal");
		return $this->sforce->describeGlobal()->result;
	}

	/**
	 * Use describeLayout to retrieve information about the layout (presentation
	 * of data to users) for a given object type. The describeLayout call returns
	 * metadata about a given page layout, including layouts for edit and
	 * display-only views and record type mappings. Note that field-level security
	 * and layout editability affects which fields appear in a layout.
	 *
	 * @param string Type   Object Type
	 * @return DescribeLayoutResult
	 */
	public function describeLayout($type) {
		$this->setHeaders("describeLayout");
		$arg = new stdClass;
		$arg->sObjectType = new SoapVar($type, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		return $this->sforce->describeLayout($arg)->result;
	}

	/**
	 * Describes metadata (field list and object properties) for the specified
	 * object.
	 *
	 * @param string $type    Object type
	 * @return DescribsSObjectResult
	 */
	public function describeSObject($type) {
		$this->setHeaders("describeSObject");
		$arg = new stdClass;
		$arg->sObjectType = new SoapVar($type, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		return $this->sforce->describeSObject($arg)->result;
	}

	/**
	 * An array-based version of describeSObject; describes metadata (field list
	 * and object properties) for the specified object or array of objects.
	 *
	 * @param array $arrayOfTypes    Array of object types.
	 * @return DescribsSObjectResult
	 */
	public function describeSObjects($arrayOfTypes) {
		$this->setHeaders("describeSObjects");
		return $this->sforce->describeSObjects($arrayOfTypes)->result;
	}

	/**
	 * The describeTabs call returns information about the standard apps and
	 * custom apps, if any, available for the user who sends the call, including
	 * the list of tabs defined for each app.
	 *
	 * @return DescribeTabSetResult
	 */
	public function describeTabs() {
		$this->setHeaders("describeTabs");
		return $this->sforce->describeTabs()->result;
	}

	/**
	 * Retrieves the list of individual objects that have been deleted within the
	 * given timespan for the specified object.
	 *
	 * @param string $type    Ojbect type
	 * @param date $startDate  Start date
	 * @param date $endDate   End Date
	 * @return GetDeletedResult
	 */
	public function getDeleted($type, $startDate, $endDate) {
		$this->setHeaders("getDeleted");
		$arg = new stdClass;
		$arg->sObjectType = new SoapVar($type, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		$arg->startDate = $startDate;
		$arg->endDate = $endDate;
		return $this->sforce->getDeleted($arg)->result;
	}

	/**
	 * Retrieves the list of individual objects that have been updated (added or
	 * changed) within the given timespan for the specified object.
	 *
	 * @param string $type    Ojbect type
	 * @param date $startDate  Start date
	 * @param date $endDate   End Date
	 * @return GetUpdatedResult
	 */
	public function getUpdated($type, $startDate, $endDate) {
		$this->setHeaders("getUpdated");
		$arg = new stdClass;
		$arg->sObjectType = new SoapVar($type, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		$arg->startDate = $startDate;
		$arg->endDate = $endDate;
		return $this->sforce->getUpdated($arg)->result;
	}

	/**
	 * Executes a query against the specified object and returns data that matches
	 * the specified criteria.
	 *
	 * @param String $query Query String
	 * @param QueryOptions $queryOptions  Batch size limit.  OPTIONAL
	 * @return QueryResult
	 */
	public function query($query) {
		$this->setHeaders("query");
		$QueryResult = $this->sforce->query(array (
                      'queryString' => $query
		))->result;
		$this->_handleRecords($QueryResult);
		return $QueryResult;
	}

	/**
	 * Retrieves the next batch of objects from a query.
	 *
	 * @param QueryLocator $queryLocator Represents the server-side cursor that tracks the current processing location in the query result set.
	 * @param QueryOptions $queryOptions  Batch size limit.  OPTIONAL
	 * @return QueryResult
	 */
	public function queryMore($queryLocator) {
		$this->setHeaders("queryMore");
		$arg = new stdClass;
		$arg->queryLocator = $queryLocator;
		$QueryResult = $this->sforce->queryMore($arg)->result;
		$this->_handleRecords($QueryResult);
		return $QueryResult;
	}

	/**
	 * Retrieves data from specified objects, whether or not they have been deleted.
	 *
	 * @param String $query Query String
	 * @param QueryOptions $queryOptions  Batch size limit.  OPTIONAL
	 * @return QueryResult
	 */
	public function queryAll($query, $queryOptions = NULL) {
		$this->setHeaders("queryAll");
		$QueryResult = $this->sforce->queryAll(array (
                        'queryString' => $query
		))->result;
		$this->_handleRecords($QueryResult);
		return $QueryResult;
	}


	private function _handleRecords(& $QueryResult) {
		if ($QueryResult->size > 0) {
			if ($QueryResult->size == 1) {
				$recs = array (
				$QueryResult->records
				);
			} else {
				$recs = $QueryResult->records;
			}
			$QueryResult->records = $recs;
		}
	}

	/**
	 * Retrieves one or more objects based on the specified object IDs.
	 *
	 * @param string $fieldList      One or more fields separated by commas.
	 * @param string $sObjectType    Object from which to retrieve data.
	 * @param array $ids            Array of one or more IDs of the objects to retrieve.
	 * @return sObject[]
	 */
	public function retrieve($fieldList, $sObjectType, $ids) {
		$this->setHeaders("retrieve");
		$arg = new stdClass;
		$arg->fieldList = $fieldList;
		$arg->sObjectType = new SoapVar($sObjectType, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		$arg->ids = $ids;
		return $this->sforce->retrieve($arg)->result;
	}

	/**
	 * Executes a text search in your organization's data.
	 *
	 * @param string $searchString   Search string that specifies the text expression to search for.
	 * @return SearchResult
	 */
	public function search($searchString) {
		$this->setHeaders("search");
		$arg = new stdClass;
		$arg->searchString = new SoapVar($searchString, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		return $this->sforce->search($arg)->result;
	}

	/**
	 * Retrieves the current system timestamp (GMT) from the Web service.
	 *
	 * @return timestamp
	 */
	public function getServerTimestamp() {
		$this->setHeaders("getServerTimestamp");
		return $this->sforce->getServerTimestamp()->result;
	}

	public function getUserInfo() {
		$this->setHeaders("getUserInfo");
		return $this->sforce->getUserInfo()->result;
	}

	/**
	 * Sets the specified user's password to the specified value.
	 *
	 * @param string $userId    ID of the User.
	 * @param string $password  New password
	 */
	public function setPassword($userId, $password) {
		$this->setHeaders("setPassword");
		$arg = new stdClass;
		$arg->userId = new SoapVar($userId, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		$arg->password = $password;
		return $this->sforce->setPassword($arg);
	}

	/**
	 * Changes a user's password to a system-generated value.
	 *
	 * @param string $userId    Id of the User
	 * @return password
	 */
	public function resetPassword($userId) {
		$this->setHeaders("resetPassword");
		$arg = new stdClass;
		$arg->userId = new SoapVar($userId, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		return $this->sforce->resetPassword($arg)->result;
	}
}

<?php
define ("EMAIL_PRIORITY_HIGHEST", 'Highest');
define ("EMAIL_PRIORITY_HIGH", 'High');
define ("EMAIL_PRIORITY_NORMAL", 'Normal');
define ("EMAIL_PRIORITY_LOW", 'Low');
define ("EMAIL_PRIORITY_LOWEST", 'Lowest');

class Email {
  public function setBccSender($bccSender) {
    $this->bccSender = $bccSender;
  }

  public function setEmailPriority($priority) {
    $this->emailPriority = $priority;
  }
   
  public function setSubject($subject) {
    $this->subject = $subject;
  }

  public function setSaveAsActivity($saveAsActivity) {
    $this->saveAsActivity = $saveAsActivity;
  }

  public function setReplyTo($replyTo) {
    $this->replyTo = $replyTo;
  }

  public function setUseSignature($useSignature) {
    $this->useSignature = $useSignature;
  }
}

class SingleEmailMessage extends Email {
  public function __construct() {}


  public function setBccAddresses($addresses) {
    $this->bccAddresses = $addresses;
  }
  public $ccAddresses;

  public function setCcAddresses($addresses) {
    $this->ccAddresses = $addresses;
  }

  public function setCharset($charset) {
    $this->charset = $charset;
  }

  public function setHtmlBody($htmlBody) {
    $this->htmlBody = $htmlBody;
  }

  public function setPlainTextBody($plainTextBody) {
    $this->plainTextBody = $plainTextBody;
  }

  public function setTargetObjectId($targetObjectId) {
    $this->targetObjectId = $targetObjectId;
  }

  public function setTemplateId($templateId) {
    $this->templateId = $templateId;
  }

  public function setToAddresses($array) {
    $this->toAddresses = $array;
  }

  public function setWhatId($whatId) {
    $this->whatId = $whatId;
  }

  public function setFileAttachments($array) {
    $this->fileAttachments = $array;
  }

  public function setDocumentAttachments($array) {
    $this->documentAttachments = $array;
  }
}

class MassEmailMessage extends Email {
  public function setTemplateId($templateId) {
    $this->templateId = $templateId;
  }

  public function setWhatIds($array) {
    $this->whatIds = $array;
  }

  public function setTargetObjectIds($array) {
    $this->targetObjectIds = $array;
  }
}



require_once ('SforceEmail.php');


/**
 * This file contains two classes.
 * @package SalesforceSoapClient
 */
/**
 * SforcePartnerClient class.
 *
 * @package SalesforceSoapClient
 */
class SforcePartnerClient extends SforceBaseClient {
	const PARTNER_NAMESPACE = 'urn:partner.soap.sforce.com';

	function SforcePartnerClient() {
		$this->namespace = self::PARTNER_NAMESPACE;
	}

	/**
	 * Set the Partner Client ID.
	 *
	 * @param string $clientId   Partner Client ID
	 public function setCallOptions($clientId, $defaultNamespace) {
	 $this->callOptions = new SoapHeader($this->namespace, 'CallOptions', array (
	 'client' => $clientId,
	 'defaultNamespace' => $defaultNamespace
	 ));
	 }
	 */

	/**
	 * Adds one or more new individual objects to your organization's data.
	 * @param array $sObjects    Array of one or more sObjects (up to 200) to create.
	 * @param AssignmentRuleHeader $assignment_header is optional.  Defaults to NULL
	 * @param MruHeader $mru_header is optional.  Defaults to NULL
	 * @return SaveResult
	 */
	public function create($sObjects) {
		$arg = new stdClass;
		foreach ($sObjects as $sObject) {
			if (isset ($sObject->fields)) {
				$sObject->any = $this->_convertToAny($sObject->fields);
			}
		}
		$arg->sObjects = $sObjects;
		return parent::_create($arg);
	}

	/**
	 * Merge records
	 *
	 * @param stdclass $mergeRequest
	 * @param String $type
	 * @return unknown
	 */
	public function merge($mergeRequest) {
		if (isset($mergeRequest->masterRecord)) {
			if (isset ($mergeRequest->masterRecord->fields)) {
				$mergeRequest->masterRecord->any = $this->_convertToAny($mergeRequest->masterRecord->fields);
			}
			//return parent::merge($mergeRequest, $type);
			$arg->request = $mergeRequest;
			return $this->_merge($arg);
		}
	}

	public function sendSingleEmail($request) {
		if (is_array($request)) {
			$messages = array();
			foreach ($request as $r) {
				$email = new SoapVar($r, SOAP_ENC_OBJECT, 'SingleEmailMessage', $this->namespace);
				array_push($messages, $email);
			}
			$arg->messages = $messages;
			return parent::_sendEmail($arg);
		} else {
			$backtrace = debug_backtrace();
			die('Please pass in array to this function:  '.$backtrace[0]['function']);
		}
	}

	public function sendMassEmail($request) {
		if (is_array($request)) {
			$messages = array();
			foreach ($request as $r) {
				$email = new SoapVar($r, SOAP_ENC_OBJECT, 'MassEmailMessage', $this->namespace);
				array_push($messages, $email);
			}
			$arg->messages = $messages;
			return parent::_sendEmail($arg);
		} else {
			$backtrace = debug_backtrace();
			die('Please pass in array to this function:  '.$backtrace[0]['function']);
		}
	}

	/**
	 * Updates one or more new individual objects to your organization's data.
	 * @param array sObjects    Array of sObjects
	 * @param AssignmentRuleHeader $assignment_header is optional.  Defaults to NULL
	 * @param MruHeader $mru_header is optional.  Defaults to NULL
	 * @return UpdateResult
	 */
	public function update($sObjects) {
		$arg = new stdClass;
		foreach ($sObjects as $sObject) {
			if (isset ($sObject->fields)) {
				$sObject->any = $this->_convertToAny($sObject->fields);
			}
		}
		$arg->sObjects = $sObjects;
		return parent::_update($arg);
	}

	/**
	 * Creates new objects and updates existing objects; uses a custom field to
	 * determine the presence of existing objects. In most cases, we recommend
	 * that you use upsert instead of create because upsert is idempotent.
	 * Available in the API version 7.0 and later.
	 *
	 * @param string $ext_Id        External Id
	 * @param array  $sObjects  Array of sObjects
	 * @return UpsertResult
	 */
	public function upsert($ext_Id, $sObjects) {
		//		$this->_setSessionHeader();
		$arg = new stdClass;
		$arg->externalIDFieldName = new SoapVar($ext_Id, XSD_STRING, 'string', 'http://www.w3.org/2001/XMLSchema');
		foreach ($sObjects as $sObject) {
			if (isset ($sObject->fields)) {
				$sObject->any = $this->_convertToAny($sObject->fields);
			}
		}
		$arg->sObjects = $sObjects;
		return parent::_upsert($arg);
	}
}

class QueryResult {
	public $queryLocator;
	public $done;
	public $records;
	public $size;

	public function __construct($response) {
		$this->queryLocator = $response->queryLocator;
		$this->done = $response->done;
		$this->size = $response->size;

		$this->records = array();

		if (isset($response->records)) {
			if (is_array($response->records)) {
				foreach ($response->records as $record) {
					$sobject = new SObject($record);
					array_push($this->records, $sobject);
				};
			} else {
				$sobject = new SObject($response->records);
				array_push($this->records, $sobject);
			}
		}
	}
}

/**
 * Salesforce Object
 *
 * @package SalesforceSoapClient
 */
class SObject {
	public $type;
	public $fields;
	//  public $sobject;

	public function __construct($response=NULL) {
		if (isset($response)) {
			if (isset($response->Id)) $this->Id = $response->Id[0];
			if (isset($response->type)) $this->type = $response->type;
			if (isset($response->any)) {
				try {
					//$this->fields = $this->convertFields($response->any);
					// If ANY is an object, instantiate another SObject
					if ($response->any instanceof stdClass) {
						if ($this->isSObject($response->any)) {
							$anArray = array();
							$sobject = new SObject($response->any);
							array_push($anArray, $sobject);
							$this->sobjects = $anArray;
						} else {
							// this is for parent to child relationships
							$this->queryResult = new QueryResult($response->any);
						}

					} else {
						// If ANY is an array
						if (is_array($response->any)) {
							// Loop through each and perform some action.
							$anArray = array();
							foreach ($response->any as $item) {
								if ($item instanceof stdClass) {
									if ($this->isSObject($item)) {
										$sobject = new SObject($item);
										array_push($anArray, $sobject);
									} else {
										// this is for parent to child relationships
										//$this->queryResult = new QueryResult($item);
										if (!isset($this->queryResult)) {
											$this->queryResult = array();
										}
										array_push($this->queryResult, new QueryResult($item));
									}
								} else {
									//$this->fields = $this->convertFields($item);
									if (!isset($fieldsToConvert)) {
										$fieldsToConvert = $item;
									} else {
										$fieldsToConvert .= $item;
									}
								}
								if (isset($fieldsToConvert)) {
									$this->fields = $this->convertFields($fieldsToConvert);
								}
							}
							if (sizeof($anArray) > 0) {
								$this->sobjects = $anArray;
							}

							/*
							 $this->fields = $this->convertFields($response->any[0]);
							 if (isset($response->any[1]->records)) {
							 $anArray = array();
							 if ($response->any[1]->size == 1) {
							 $records = array (
							 $response->any[1]->records
							 );
							 } else {
							 $records = $response->any[1]->records;
							 }
							 foreach ($records as $record) {
							 $sobject = new SObject($record);
							 array_push($anArray, $sobject);
							 }
							 $this->sobjects = $anArray;
							 } else {
							 $anArray = array();
							 $sobject = new SObject($response->any[1]);
							 array_push($anArray, $sobject);
							 $this->sobjects = $anArray;
							 }
							 */
						} else {
							$this->fields = $this->convertFields($response->any);
						}
					}
				} catch (Exception $e) {
					var_dump($e);
				}
			}
		}
	}

	/**
	 * Parse the "any" string from an sObject.  First strip out the sf: and then
	 * enclose string with <Object></Object>.  Load the string using
	 * simplexml_load_string and return an array that can be traversed.
	 */
	function convertFields($any) {
		$new_string = ereg_replace('sf:', '', $any);
		$new_string = '<Object xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'.$new_string.'</Object>';
		$xml = simplexml_load_string($new_string);
		return $xml;
	}

	/*
	 * If the stdClass has a done, we know it is a QueryResult
	 */
	function isQueryResult($param) {
		return isset($param->done);
	}

	/*
	 * If the stdClass has a type, we know it is an SObject
	 */
	function isSObject($param) {
		return isset($param->type);
	}
}


$client = new SforcePartnerClient();
$client->createConnection('partner.wsdl.xml');
$loginResult = $client->login('josh@rest.com','Cassius30');
print $client->getSessionId();
?>