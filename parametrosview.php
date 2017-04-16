<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "parametrosinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$parametros_view = NULL; // Initialize page object first

class cparametros_view extends cparametros {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{524C14CD-A0E3-4083-AF86-06203077AB82}";

	// Table name
	var $TableName = 'parametros';

	// Page object name
	var $PageObjName = 'parametros_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (parametros)
		if (!isset($GLOBALS["parametros"]) || get_class($GLOBALS["parametros"]) == "cparametros") {
			$GLOBALS["parametros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parametros"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parametros', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $parametros;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($parametros);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} elseif (@$_POST["id"] <> "") {
				$this->id->setFormValue($_POST["id"]);
				$this->RecKey["id"] = $this->id->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("parametroslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "parametroslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}
		} else {
			$sReturnUrl = "parametroslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->IsLoggedIn());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->IsLoggedIn());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->IsLoggedIn());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->IsLoggedIn());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->temp_min->setDbValue($rs->fields('temp_min'));
		$this->temp_max->setDbValue($rs->fields('temp_max'));
		$this->co_min->setDbValue($rs->fields('co_min'));
		$this->co_max->setDbValue($rs->fields('co_max'));
		$this->horas_crecimiento->setDbValue($rs->fields('horas_crecimiento'));
		$this->horas_floracion->setDbValue($rs->fields('horas_floracion'));
		$this->hum_min->setDbValue($rs->fields('hum_min'));
		$this->hum_max->setDbValue($rs->fields('hum_max'));
		$this->DnsHost->setDbValue($rs->fields('DnsHost'));
		$this->DnsUser->setDbValue($rs->fields('DnsUser'));
		$this->DnsPasswd->setDbValue($rs->fields('DnsPasswd'));
		$this->DnsUrl_Update->setDbValue($rs->fields('DnsUrl_Update'));
		$this->WifiSSID->setDbValue($rs->fields('WifiSSID'));
		$this->WifiPasswd->setDbValue($rs->fields('WifiPasswd'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->temp_min->DbValue = $row['temp_min'];
		$this->temp_max->DbValue = $row['temp_max'];
		$this->co_min->DbValue = $row['co_min'];
		$this->co_max->DbValue = $row['co_max'];
		$this->horas_crecimiento->DbValue = $row['horas_crecimiento'];
		$this->horas_floracion->DbValue = $row['horas_floracion'];
		$this->hum_min->DbValue = $row['hum_min'];
		$this->hum_max->DbValue = $row['hum_max'];
		$this->DnsHost->DbValue = $row['DnsHost'];
		$this->DnsUser->DbValue = $row['DnsUser'];
		$this->DnsPasswd->DbValue = $row['DnsPasswd'];
		$this->DnsUrl_Update->DbValue = $row['DnsUrl_Update'];
		$this->WifiSSID->DbValue = $row['WifiSSID'];
		$this->WifiPasswd->DbValue = $row['WifiPasswd'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Convert decimal values if posted back
		if ($this->temp_min->FormValue == $this->temp_min->CurrentValue && is_numeric(ew_StrToFloat($this->temp_min->CurrentValue)))
			$this->temp_min->CurrentValue = ew_StrToFloat($this->temp_min->CurrentValue);

		// Convert decimal values if posted back
		if ($this->temp_max->FormValue == $this->temp_max->CurrentValue && is_numeric(ew_StrToFloat($this->temp_max->CurrentValue)))
			$this->temp_max->CurrentValue = ew_StrToFloat($this->temp_max->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// temp_min
		// temp_max
		// co_min
		// co_max
		// horas_crecimiento
		// horas_floracion
		// hum_min
		// hum_max
		// DnsHost
		// DnsUser
		// DnsPasswd
		// DnsUrl_Update
		// WifiSSID
		// WifiPasswd

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// temp_min
		$this->temp_min->ViewValue = $this->temp_min->CurrentValue;
		$this->temp_min->ViewCustomAttributes = "";

		// temp_max
		$this->temp_max->ViewValue = $this->temp_max->CurrentValue;
		$this->temp_max->ViewCustomAttributes = "";

		// co_min
		$this->co_min->ViewValue = $this->co_min->CurrentValue;
		$this->co_min->ViewCustomAttributes = "";

		// co_max
		$this->co_max->ViewValue = $this->co_max->CurrentValue;
		$this->co_max->ViewCustomAttributes = "";

		// horas_crecimiento
		$this->horas_crecimiento->ViewValue = $this->horas_crecimiento->CurrentValue;
		$this->horas_crecimiento->ViewCustomAttributes = "";

		// horas_floracion
		$this->horas_floracion->ViewValue = $this->horas_floracion->CurrentValue;
		$this->horas_floracion->ViewCustomAttributes = "";

		// hum_min
		$this->hum_min->ViewValue = $this->hum_min->CurrentValue;
		$this->hum_min->ViewCustomAttributes = "";

		// hum_max
		$this->hum_max->ViewValue = $this->hum_max->CurrentValue;
		$this->hum_max->ViewCustomAttributes = "";

		// DnsHost
		$this->DnsHost->ViewValue = $this->DnsHost->CurrentValue;
		$this->DnsHost->ViewCustomAttributes = "";

		// DnsUser
		$this->DnsUser->ViewValue = $this->DnsUser->CurrentValue;
		$this->DnsUser->ViewCustomAttributes = "";

		// DnsPasswd
		$this->DnsPasswd->ViewValue = $this->DnsPasswd->CurrentValue;
		$this->DnsPasswd->ViewCustomAttributes = "";

		// DnsUrl_Update
		$this->DnsUrl_Update->ViewValue = $this->DnsUrl_Update->CurrentValue;
		$this->DnsUrl_Update->ViewCustomAttributes = "";

		// WifiSSID
		$this->WifiSSID->ViewValue = $this->WifiSSID->CurrentValue;
		$this->WifiSSID->ViewCustomAttributes = "";

		// WifiPasswd
		$this->WifiPasswd->ViewValue = $this->WifiPasswd->CurrentValue;
		$this->WifiPasswd->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// temp_min
			$this->temp_min->LinkCustomAttributes = "";
			$this->temp_min->HrefValue = "";
			$this->temp_min->TooltipValue = "";

			// temp_max
			$this->temp_max->LinkCustomAttributes = "";
			$this->temp_max->HrefValue = "";
			$this->temp_max->TooltipValue = "";

			// co_min
			$this->co_min->LinkCustomAttributes = "";
			$this->co_min->HrefValue = "";
			$this->co_min->TooltipValue = "";

			// co_max
			$this->co_max->LinkCustomAttributes = "";
			$this->co_max->HrefValue = "";
			$this->co_max->TooltipValue = "";

			// horas_crecimiento
			$this->horas_crecimiento->LinkCustomAttributes = "";
			$this->horas_crecimiento->HrefValue = "";
			$this->horas_crecimiento->TooltipValue = "";

			// horas_floracion
			$this->horas_floracion->LinkCustomAttributes = "";
			$this->horas_floracion->HrefValue = "";
			$this->horas_floracion->TooltipValue = "";

			// hum_min
			$this->hum_min->LinkCustomAttributes = "";
			$this->hum_min->HrefValue = "";
			$this->hum_min->TooltipValue = "";

			// hum_max
			$this->hum_max->LinkCustomAttributes = "";
			$this->hum_max->HrefValue = "";
			$this->hum_max->TooltipValue = "";

			// DnsHost
			$this->DnsHost->LinkCustomAttributes = "";
			$this->DnsHost->HrefValue = "";
			$this->DnsHost->TooltipValue = "";

			// DnsUser
			$this->DnsUser->LinkCustomAttributes = "";
			$this->DnsUser->HrefValue = "";
			$this->DnsUser->TooltipValue = "";

			// DnsPasswd
			$this->DnsPasswd->LinkCustomAttributes = "";
			$this->DnsPasswd->HrefValue = "";
			$this->DnsPasswd->TooltipValue = "";

			// DnsUrl_Update
			$this->DnsUrl_Update->LinkCustomAttributes = "";
			$this->DnsUrl_Update->HrefValue = "";
			$this->DnsUrl_Update->TooltipValue = "";

			// WifiSSID
			$this->WifiSSID->LinkCustomAttributes = "";
			$this->WifiSSID->HrefValue = "";
			$this->WifiSSID->TooltipValue = "";

			// WifiPasswd
			$this->WifiPasswd->LinkCustomAttributes = "";
			$this->WifiPasswd->HrefValue = "";
			$this->WifiPasswd->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "parametroslist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($parametros_view)) $parametros_view = new cparametros_view();

// Page init
$parametros_view->Page_Init();

// Page main
$parametros_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parametros_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fparametrosview = new ew_Form("fparametrosview", "view");

// Form_CustomValidate event
fparametrosview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparametrosview.ValidateRequired = true;
<?php } else { ?>
fparametrosview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $parametros_view->ExportOptions->Render("body") ?>
<?php
	foreach ($parametros_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $parametros_view->ShowPageHeader(); ?>
<?php
$parametros_view->ShowMessage();
?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($parametros_view->Pager)) $parametros_view->Pager = new cPrevNextPager($parametros_view->StartRec, $parametros_view->DisplayRecs, $parametros_view->TotalRecs) ?>
<?php if ($parametros_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($parametros_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $parametros_view->PageUrl() ?>start=<?php echo $parametros_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($parametros_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $parametros_view->PageUrl() ?>start=<?php echo $parametros_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $parametros_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($parametros_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $parametros_view->PageUrl() ?>start=<?php echo $parametros_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($parametros_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $parametros_view->PageUrl() ?>start=<?php echo $parametros_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $parametros_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<form name="fparametrosview" id="fparametrosview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parametros_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parametros_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parametros">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($parametros->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_parametros_id"><?php echo $parametros->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $parametros->id->CellAttributes() ?>>
<span id="el_parametros_id">
<span<?php echo $parametros->id->ViewAttributes() ?>>
<?php echo $parametros->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->temp_min->Visible) { // temp_min ?>
	<tr id="r_temp_min">
		<td><span id="elh_parametros_temp_min"><?php echo $parametros->temp_min->FldCaption() ?></span></td>
		<td data-name="temp_min"<?php echo $parametros->temp_min->CellAttributes() ?>>
<span id="el_parametros_temp_min">
<span<?php echo $parametros->temp_min->ViewAttributes() ?>>
<?php echo $parametros->temp_min->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->temp_max->Visible) { // temp_max ?>
	<tr id="r_temp_max">
		<td><span id="elh_parametros_temp_max"><?php echo $parametros->temp_max->FldCaption() ?></span></td>
		<td data-name="temp_max"<?php echo $parametros->temp_max->CellAttributes() ?>>
<span id="el_parametros_temp_max">
<span<?php echo $parametros->temp_max->ViewAttributes() ?>>
<?php echo $parametros->temp_max->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->co_min->Visible) { // co_min ?>
	<tr id="r_co_min">
		<td><span id="elh_parametros_co_min"><?php echo $parametros->co_min->FldCaption() ?></span></td>
		<td data-name="co_min"<?php echo $parametros->co_min->CellAttributes() ?>>
<span id="el_parametros_co_min">
<span<?php echo $parametros->co_min->ViewAttributes() ?>>
<?php echo $parametros->co_min->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->co_max->Visible) { // co_max ?>
	<tr id="r_co_max">
		<td><span id="elh_parametros_co_max"><?php echo $parametros->co_max->FldCaption() ?></span></td>
		<td data-name="co_max"<?php echo $parametros->co_max->CellAttributes() ?>>
<span id="el_parametros_co_max">
<span<?php echo $parametros->co_max->ViewAttributes() ?>>
<?php echo $parametros->co_max->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
	<tr id="r_horas_crecimiento">
		<td><span id="elh_parametros_horas_crecimiento"><?php echo $parametros->horas_crecimiento->FldCaption() ?></span></td>
		<td data-name="horas_crecimiento"<?php echo $parametros->horas_crecimiento->CellAttributes() ?>>
<span id="el_parametros_horas_crecimiento">
<span<?php echo $parametros->horas_crecimiento->ViewAttributes() ?>>
<?php echo $parametros->horas_crecimiento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
	<tr id="r_horas_floracion">
		<td><span id="elh_parametros_horas_floracion"><?php echo $parametros->horas_floracion->FldCaption() ?></span></td>
		<td data-name="horas_floracion"<?php echo $parametros->horas_floracion->CellAttributes() ?>>
<span id="el_parametros_horas_floracion">
<span<?php echo $parametros->horas_floracion->ViewAttributes() ?>>
<?php echo $parametros->horas_floracion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->hum_min->Visible) { // hum_min ?>
	<tr id="r_hum_min">
		<td><span id="elh_parametros_hum_min"><?php echo $parametros->hum_min->FldCaption() ?></span></td>
		<td data-name="hum_min"<?php echo $parametros->hum_min->CellAttributes() ?>>
<span id="el_parametros_hum_min">
<span<?php echo $parametros->hum_min->ViewAttributes() ?>>
<?php echo $parametros->hum_min->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->hum_max->Visible) { // hum_max ?>
	<tr id="r_hum_max">
		<td><span id="elh_parametros_hum_max"><?php echo $parametros->hum_max->FldCaption() ?></span></td>
		<td data-name="hum_max"<?php echo $parametros->hum_max->CellAttributes() ?>>
<span id="el_parametros_hum_max">
<span<?php echo $parametros->hum_max->ViewAttributes() ?>>
<?php echo $parametros->hum_max->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
	<tr id="r_DnsHost">
		<td><span id="elh_parametros_DnsHost"><?php echo $parametros->DnsHost->FldCaption() ?></span></td>
		<td data-name="DnsHost"<?php echo $parametros->DnsHost->CellAttributes() ?>>
<span id="el_parametros_DnsHost">
<span<?php echo $parametros->DnsHost->ViewAttributes() ?>>
<?php echo $parametros->DnsHost->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
	<tr id="r_DnsUser">
		<td><span id="elh_parametros_DnsUser"><?php echo $parametros->DnsUser->FldCaption() ?></span></td>
		<td data-name="DnsUser"<?php echo $parametros->DnsUser->CellAttributes() ?>>
<span id="el_parametros_DnsUser">
<span<?php echo $parametros->DnsUser->ViewAttributes() ?>>
<?php echo $parametros->DnsUser->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
	<tr id="r_DnsPasswd">
		<td><span id="elh_parametros_DnsPasswd"><?php echo $parametros->DnsPasswd->FldCaption() ?></span></td>
		<td data-name="DnsPasswd"<?php echo $parametros->DnsPasswd->CellAttributes() ?>>
<span id="el_parametros_DnsPasswd">
<span<?php echo $parametros->DnsPasswd->ViewAttributes() ?>>
<?php echo $parametros->DnsPasswd->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
	<tr id="r_DnsUrl_Update">
		<td><span id="elh_parametros_DnsUrl_Update"><?php echo $parametros->DnsUrl_Update->FldCaption() ?></span></td>
		<td data-name="DnsUrl_Update"<?php echo $parametros->DnsUrl_Update->CellAttributes() ?>>
<span id="el_parametros_DnsUrl_Update">
<span<?php echo $parametros->DnsUrl_Update->ViewAttributes() ?>>
<?php echo $parametros->DnsUrl_Update->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
	<tr id="r_WifiSSID">
		<td><span id="elh_parametros_WifiSSID"><?php echo $parametros->WifiSSID->FldCaption() ?></span></td>
		<td data-name="WifiSSID"<?php echo $parametros->WifiSSID->CellAttributes() ?>>
<span id="el_parametros_WifiSSID">
<span<?php echo $parametros->WifiSSID->ViewAttributes() ?>>
<?php echo $parametros->WifiSSID->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
	<tr id="r_WifiPasswd">
		<td><span id="elh_parametros_WifiPasswd"><?php echo $parametros->WifiPasswd->FldCaption() ?></span></td>
		<td data-name="WifiPasswd"<?php echo $parametros->WifiPasswd->CellAttributes() ?>>
<span id="el_parametros_WifiPasswd">
<span<?php echo $parametros->WifiPasswd->ViewAttributes() ?>>
<?php echo $parametros->WifiPasswd->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fparametrosview.Init();
</script>
<?php
$parametros_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parametros_view->Page_Terminate();
?>
