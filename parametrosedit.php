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

$parametros_edit = NULL; // Initialize page object first

class cparametros_edit extends cparametros {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{524C14CD-A0E3-4083-AF86-06203077AB82}";

	// Table name
	var $TableName = 'parametros';

	// Page object name
	var $PageObjName = 'parametros_edit';

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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parametros', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
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

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate("parametroslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("parametroslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->temp_min->FldIsDetailKey) {
			$this->temp_min->setFormValue($objForm->GetValue("x_temp_min"));
		}
		if (!$this->temp_max->FldIsDetailKey) {
			$this->temp_max->setFormValue($objForm->GetValue("x_temp_max"));
		}
		if (!$this->co_min->FldIsDetailKey) {
			$this->co_min->setFormValue($objForm->GetValue("x_co_min"));
		}
		if (!$this->co_max->FldIsDetailKey) {
			$this->co_max->setFormValue($objForm->GetValue("x_co_max"));
		}
		if (!$this->horas_crecimiento->FldIsDetailKey) {
			$this->horas_crecimiento->setFormValue($objForm->GetValue("x_horas_crecimiento"));
		}
		if (!$this->horas_floracion->FldIsDetailKey) {
			$this->horas_floracion->setFormValue($objForm->GetValue("x_horas_floracion"));
		}
		if (!$this->hum_min->FldIsDetailKey) {
			$this->hum_min->setFormValue($objForm->GetValue("x_hum_min"));
		}
		if (!$this->hum_max->FldIsDetailKey) {
			$this->hum_max->setFormValue($objForm->GetValue("x_hum_max"));
		}
		if (!$this->DnsHost->FldIsDetailKey) {
			$this->DnsHost->setFormValue($objForm->GetValue("x_DnsHost"));
		}
		if (!$this->DnsUser->FldIsDetailKey) {
			$this->DnsUser->setFormValue($objForm->GetValue("x_DnsUser"));
		}
		if (!$this->DnsPasswd->FldIsDetailKey) {
			$this->DnsPasswd->setFormValue($objForm->GetValue("x_DnsPasswd"));
		}
		if (!$this->DnsUrl_Update->FldIsDetailKey) {
			$this->DnsUrl_Update->setFormValue($objForm->GetValue("x_DnsUrl_Update"));
		}
		if (!$this->WifiSSID->FldIsDetailKey) {
			$this->WifiSSID->setFormValue($objForm->GetValue("x_WifiSSID"));
		}
		if (!$this->WifiPasswd->FldIsDetailKey) {
			$this->WifiPasswd->setFormValue($objForm->GetValue("x_WifiPasswd"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->temp_min->CurrentValue = $this->temp_min->FormValue;
		$this->temp_max->CurrentValue = $this->temp_max->FormValue;
		$this->co_min->CurrentValue = $this->co_min->FormValue;
		$this->co_max->CurrentValue = $this->co_max->FormValue;
		$this->horas_crecimiento->CurrentValue = $this->horas_crecimiento->FormValue;
		$this->horas_floracion->CurrentValue = $this->horas_floracion->FormValue;
		$this->hum_min->CurrentValue = $this->hum_min->FormValue;
		$this->hum_max->CurrentValue = $this->hum_max->FormValue;
		$this->DnsHost->CurrentValue = $this->DnsHost->FormValue;
		$this->DnsUser->CurrentValue = $this->DnsUser->FormValue;
		$this->DnsPasswd->CurrentValue = $this->DnsPasswd->FormValue;
		$this->DnsUrl_Update->CurrentValue = $this->DnsUrl_Update->FormValue;
		$this->WifiSSID->CurrentValue = $this->WifiSSID->FormValue;
		$this->WifiPasswd->CurrentValue = $this->WifiPasswd->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// temp_min
			$this->temp_min->EditAttrs["class"] = "form-control";
			$this->temp_min->EditCustomAttributes = "";
			$this->temp_min->EditValue = ew_HtmlEncode($this->temp_min->CurrentValue);
			$this->temp_min->PlaceHolder = ew_RemoveHtml($this->temp_min->FldCaption());
			if (strval($this->temp_min->EditValue) <> "" && is_numeric($this->temp_min->EditValue)) $this->temp_min->EditValue = ew_FormatNumber($this->temp_min->EditValue, -2, -1, -2, 0);

			// temp_max
			$this->temp_max->EditAttrs["class"] = "form-control";
			$this->temp_max->EditCustomAttributes = "";
			$this->temp_max->EditValue = ew_HtmlEncode($this->temp_max->CurrentValue);
			$this->temp_max->PlaceHolder = ew_RemoveHtml($this->temp_max->FldCaption());
			if (strval($this->temp_max->EditValue) <> "" && is_numeric($this->temp_max->EditValue)) $this->temp_max->EditValue = ew_FormatNumber($this->temp_max->EditValue, -2, -1, -2, 0);

			// co_min
			$this->co_min->EditAttrs["class"] = "form-control";
			$this->co_min->EditCustomAttributes = "";
			$this->co_min->EditValue = ew_HtmlEncode($this->co_min->CurrentValue);
			$this->co_min->PlaceHolder = ew_RemoveHtml($this->co_min->FldCaption());

			// co_max
			$this->co_max->EditAttrs["class"] = "form-control";
			$this->co_max->EditCustomAttributes = "";
			$this->co_max->EditValue = ew_HtmlEncode($this->co_max->CurrentValue);
			$this->co_max->PlaceHolder = ew_RemoveHtml($this->co_max->FldCaption());

			// horas_crecimiento
			$this->horas_crecimiento->EditAttrs["class"] = "form-control";
			$this->horas_crecimiento->EditCustomAttributes = "";
			$this->horas_crecimiento->EditValue = ew_HtmlEncode($this->horas_crecimiento->CurrentValue);
			$this->horas_crecimiento->PlaceHolder = ew_RemoveHtml($this->horas_crecimiento->FldCaption());

			// horas_floracion
			$this->horas_floracion->EditAttrs["class"] = "form-control";
			$this->horas_floracion->EditCustomAttributes = "";
			$this->horas_floracion->EditValue = ew_HtmlEncode($this->horas_floracion->CurrentValue);
			$this->horas_floracion->PlaceHolder = ew_RemoveHtml($this->horas_floracion->FldCaption());

			// hum_min
			$this->hum_min->EditAttrs["class"] = "form-control";
			$this->hum_min->EditCustomAttributes = "";
			$this->hum_min->EditValue = ew_HtmlEncode($this->hum_min->CurrentValue);
			$this->hum_min->PlaceHolder = ew_RemoveHtml($this->hum_min->FldCaption());

			// hum_max
			$this->hum_max->EditAttrs["class"] = "form-control";
			$this->hum_max->EditCustomAttributes = "";
			$this->hum_max->EditValue = ew_HtmlEncode($this->hum_max->CurrentValue);
			$this->hum_max->PlaceHolder = ew_RemoveHtml($this->hum_max->FldCaption());

			// DnsHost
			$this->DnsHost->EditAttrs["class"] = "form-control";
			$this->DnsHost->EditCustomAttributes = "";
			$this->DnsHost->EditValue = ew_HtmlEncode($this->DnsHost->CurrentValue);
			$this->DnsHost->PlaceHolder = ew_RemoveHtml($this->DnsHost->FldCaption());

			// DnsUser
			$this->DnsUser->EditAttrs["class"] = "form-control";
			$this->DnsUser->EditCustomAttributes = "";
			$this->DnsUser->EditValue = ew_HtmlEncode($this->DnsUser->CurrentValue);
			$this->DnsUser->PlaceHolder = ew_RemoveHtml($this->DnsUser->FldCaption());

			// DnsPasswd
			$this->DnsPasswd->EditAttrs["class"] = "form-control";
			$this->DnsPasswd->EditCustomAttributes = "";
			$this->DnsPasswd->EditValue = ew_HtmlEncode($this->DnsPasswd->CurrentValue);
			$this->DnsPasswd->PlaceHolder = ew_RemoveHtml($this->DnsPasswd->FldCaption());

			// DnsUrl_Update
			$this->DnsUrl_Update->EditAttrs["class"] = "form-control";
			$this->DnsUrl_Update->EditCustomAttributes = "";
			$this->DnsUrl_Update->EditValue = ew_HtmlEncode($this->DnsUrl_Update->CurrentValue);
			$this->DnsUrl_Update->PlaceHolder = ew_RemoveHtml($this->DnsUrl_Update->FldCaption());

			// WifiSSID
			$this->WifiSSID->EditAttrs["class"] = "form-control";
			$this->WifiSSID->EditCustomAttributes = "";
			$this->WifiSSID->EditValue = ew_HtmlEncode($this->WifiSSID->CurrentValue);
			$this->WifiSSID->PlaceHolder = ew_RemoveHtml($this->WifiSSID->FldCaption());

			// WifiPasswd
			$this->WifiPasswd->EditAttrs["class"] = "form-control";
			$this->WifiPasswd->EditCustomAttributes = "";
			$this->WifiPasswd->EditValue = ew_HtmlEncode($this->WifiPasswd->CurrentValue);
			$this->WifiPasswd->PlaceHolder = ew_RemoveHtml($this->WifiPasswd->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// temp_min
			$this->temp_min->HrefValue = "";

			// temp_max
			$this->temp_max->HrefValue = "";

			// co_min
			$this->co_min->HrefValue = "";

			// co_max
			$this->co_max->HrefValue = "";

			// horas_crecimiento
			$this->horas_crecimiento->HrefValue = "";

			// horas_floracion
			$this->horas_floracion->HrefValue = "";

			// hum_min
			$this->hum_min->HrefValue = "";

			// hum_max
			$this->hum_max->HrefValue = "";

			// DnsHost
			$this->DnsHost->HrefValue = "";

			// DnsUser
			$this->DnsUser->HrefValue = "";

			// DnsPasswd
			$this->DnsPasswd->HrefValue = "";

			// DnsUrl_Update
			$this->DnsUrl_Update->HrefValue = "";

			// WifiSSID
			$this->WifiSSID->HrefValue = "";

			// WifiPasswd
			$this->WifiPasswd->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->temp_min->FldIsDetailKey && !is_null($this->temp_min->FormValue) && $this->temp_min->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->temp_min->FldCaption(), $this->temp_min->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->temp_min->FormValue)) {
			ew_AddMessage($gsFormError, $this->temp_min->FldErrMsg());
		}
		if (!$this->temp_max->FldIsDetailKey && !is_null($this->temp_max->FormValue) && $this->temp_max->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->temp_max->FldCaption(), $this->temp_max->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->temp_max->FormValue)) {
			ew_AddMessage($gsFormError, $this->temp_max->FldErrMsg());
		}
		if (!$this->co_min->FldIsDetailKey && !is_null($this->co_min->FormValue) && $this->co_min->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->co_min->FldCaption(), $this->co_min->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->co_min->FormValue)) {
			ew_AddMessage($gsFormError, $this->co_min->FldErrMsg());
		}
		if (!$this->co_max->FldIsDetailKey && !is_null($this->co_max->FormValue) && $this->co_max->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->co_max->FldCaption(), $this->co_max->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->co_max->FormValue)) {
			ew_AddMessage($gsFormError, $this->co_max->FldErrMsg());
		}
		if (!$this->horas_crecimiento->FldIsDetailKey && !is_null($this->horas_crecimiento->FormValue) && $this->horas_crecimiento->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->horas_crecimiento->FldCaption(), $this->horas_crecimiento->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->horas_crecimiento->FormValue)) {
			ew_AddMessage($gsFormError, $this->horas_crecimiento->FldErrMsg());
		}
		if (!$this->horas_floracion->FldIsDetailKey && !is_null($this->horas_floracion->FormValue) && $this->horas_floracion->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->horas_floracion->FldCaption(), $this->horas_floracion->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->horas_floracion->FormValue)) {
			ew_AddMessage($gsFormError, $this->horas_floracion->FldErrMsg());
		}
		if (!$this->hum_min->FldIsDetailKey && !is_null($this->hum_min->FormValue) && $this->hum_min->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hum_min->FldCaption(), $this->hum_min->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hum_min->FormValue)) {
			ew_AddMessage($gsFormError, $this->hum_min->FldErrMsg());
		}
		if (!$this->hum_max->FldIsDetailKey && !is_null($this->hum_max->FormValue) && $this->hum_max->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hum_max->FldCaption(), $this->hum_max->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hum_max->FormValue)) {
			ew_AddMessage($gsFormError, $this->hum_max->FldErrMsg());
		}
		if (!$this->DnsHost->FldIsDetailKey && !is_null($this->DnsHost->FormValue) && $this->DnsHost->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DnsHost->FldCaption(), $this->DnsHost->ReqErrMsg));
		}
		if (!$this->DnsUser->FldIsDetailKey && !is_null($this->DnsUser->FormValue) && $this->DnsUser->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DnsUser->FldCaption(), $this->DnsUser->ReqErrMsg));
		}
		if (!$this->DnsPasswd->FldIsDetailKey && !is_null($this->DnsPasswd->FormValue) && $this->DnsPasswd->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DnsPasswd->FldCaption(), $this->DnsPasswd->ReqErrMsg));
		}
		if (!$this->DnsUrl_Update->FldIsDetailKey && !is_null($this->DnsUrl_Update->FormValue) && $this->DnsUrl_Update->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->DnsUrl_Update->FldCaption(), $this->DnsUrl_Update->ReqErrMsg));
		}
		if (!$this->WifiSSID->FldIsDetailKey && !is_null($this->WifiSSID->FormValue) && $this->WifiSSID->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->WifiSSID->FldCaption(), $this->WifiSSID->ReqErrMsg));
		}
		if (!$this->WifiPasswd->FldIsDetailKey && !is_null($this->WifiPasswd->FormValue) && $this->WifiPasswd->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->WifiPasswd->FldCaption(), $this->WifiPasswd->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// temp_min
			$this->temp_min->SetDbValueDef($rsnew, $this->temp_min->CurrentValue, 0, $this->temp_min->ReadOnly);

			// temp_max
			$this->temp_max->SetDbValueDef($rsnew, $this->temp_max->CurrentValue, 0, $this->temp_max->ReadOnly);

			// co_min
			$this->co_min->SetDbValueDef($rsnew, $this->co_min->CurrentValue, 0, $this->co_min->ReadOnly);

			// co_max
			$this->co_max->SetDbValueDef($rsnew, $this->co_max->CurrentValue, 0, $this->co_max->ReadOnly);

			// horas_crecimiento
			$this->horas_crecimiento->SetDbValueDef($rsnew, $this->horas_crecimiento->CurrentValue, 0, $this->horas_crecimiento->ReadOnly);

			// horas_floracion
			$this->horas_floracion->SetDbValueDef($rsnew, $this->horas_floracion->CurrentValue, 0, $this->horas_floracion->ReadOnly);

			// hum_min
			$this->hum_min->SetDbValueDef($rsnew, $this->hum_min->CurrentValue, 0, $this->hum_min->ReadOnly);

			// hum_max
			$this->hum_max->SetDbValueDef($rsnew, $this->hum_max->CurrentValue, 0, $this->hum_max->ReadOnly);

			// DnsHost
			$this->DnsHost->SetDbValueDef($rsnew, $this->DnsHost->CurrentValue, "", $this->DnsHost->ReadOnly);

			// DnsUser
			$this->DnsUser->SetDbValueDef($rsnew, $this->DnsUser->CurrentValue, "", $this->DnsUser->ReadOnly);

			// DnsPasswd
			$this->DnsPasswd->SetDbValueDef($rsnew, $this->DnsPasswd->CurrentValue, "", $this->DnsPasswd->ReadOnly);

			// DnsUrl_Update
			$this->DnsUrl_Update->SetDbValueDef($rsnew, $this->DnsUrl_Update->CurrentValue, "", $this->DnsUrl_Update->ReadOnly);

			// WifiSSID
			$this->WifiSSID->SetDbValueDef($rsnew, $this->WifiSSID->CurrentValue, "", $this->WifiSSID->ReadOnly);

			// WifiPasswd
			$this->WifiPasswd->SetDbValueDef($rsnew, $this->WifiPasswd->CurrentValue, "", $this->WifiPasswd->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "parametroslist.php", "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($parametros_edit)) $parametros_edit = new cparametros_edit();

// Page init
$parametros_edit->Page_Init();

// Page main
$parametros_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parametros_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fparametrosedit = new ew_Form("fparametrosedit", "edit");

// Validate form
fparametrosedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_temp_min");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->temp_min->FldCaption(), $parametros->temp_min->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_temp_min");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->temp_min->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_temp_max");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->temp_max->FldCaption(), $parametros->temp_max->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_temp_max");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->temp_max->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_co_min");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->co_min->FldCaption(), $parametros->co_min->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_co_min");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->co_min->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_co_max");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->co_max->FldCaption(), $parametros->co_max->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_co_max");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->co_max->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horas_crecimiento");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->horas_crecimiento->FldCaption(), $parametros->horas_crecimiento->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_horas_crecimiento");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->horas_crecimiento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horas_floracion");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->horas_floracion->FldCaption(), $parametros->horas_floracion->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_horas_floracion");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->horas_floracion->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hum_min");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->hum_min->FldCaption(), $parametros->hum_min->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hum_min");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->hum_min->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hum_max");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->hum_max->FldCaption(), $parametros->hum_max->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hum_max");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parametros->hum_max->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_DnsHost");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->DnsHost->FldCaption(), $parametros->DnsHost->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DnsUser");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->DnsUser->FldCaption(), $parametros->DnsUser->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DnsPasswd");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->DnsPasswd->FldCaption(), $parametros->DnsPasswd->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_DnsUrl_Update");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->DnsUrl_Update->FldCaption(), $parametros->DnsUrl_Update->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_WifiSSID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->WifiSSID->FldCaption(), $parametros->WifiSSID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_WifiPasswd");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $parametros->WifiPasswd->FldCaption(), $parametros->WifiPasswd->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fparametrosedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparametrosedit.ValidateRequired = true;
<?php } else { ?>
fparametrosedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $parametros_edit->ShowPageHeader(); ?>
<?php
$parametros_edit->ShowMessage();
?>
<form name="fparametrosedit" id="fparametrosedit" class="<?php echo $parametros_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parametros_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parametros_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parametros">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($parametros->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_parametros_id" class="col-sm-2 control-label ewLabel"><?php echo $parametros->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->id->CellAttributes() ?>>
<span id="el_parametros_id">
<span<?php echo $parametros->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $parametros->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="parametros" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($parametros->id->CurrentValue) ?>">
<?php echo $parametros->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->temp_min->Visible) { // temp_min ?>
	<div id="r_temp_min" class="form-group">
		<label id="elh_parametros_temp_min" for="x_temp_min" class="col-sm-2 control-label ewLabel"><?php echo $parametros->temp_min->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->temp_min->CellAttributes() ?>>
<span id="el_parametros_temp_min">
<input type="text" data-table="parametros" data-field="x_temp_min" name="x_temp_min" id="x_temp_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_min->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_min->EditValue ?>"<?php echo $parametros->temp_min->EditAttributes() ?>>
</span>
<?php echo $parametros->temp_min->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->temp_max->Visible) { // temp_max ?>
	<div id="r_temp_max" class="form-group">
		<label id="elh_parametros_temp_max" for="x_temp_max" class="col-sm-2 control-label ewLabel"><?php echo $parametros->temp_max->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->temp_max->CellAttributes() ?>>
<span id="el_parametros_temp_max">
<input type="text" data-table="parametros" data-field="x_temp_max" name="x_temp_max" id="x_temp_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_max->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_max->EditValue ?>"<?php echo $parametros->temp_max->EditAttributes() ?>>
</span>
<?php echo $parametros->temp_max->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->co_min->Visible) { // co_min ?>
	<div id="r_co_min" class="form-group">
		<label id="elh_parametros_co_min" for="x_co_min" class="col-sm-2 control-label ewLabel"><?php echo $parametros->co_min->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->co_min->CellAttributes() ?>>
<span id="el_parametros_co_min">
<input type="text" data-table="parametros" data-field="x_co_min" name="x_co_min" id="x_co_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_min->getPlaceHolder()) ?>" value="<?php echo $parametros->co_min->EditValue ?>"<?php echo $parametros->co_min->EditAttributes() ?>>
</span>
<?php echo $parametros->co_min->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->co_max->Visible) { // co_max ?>
	<div id="r_co_max" class="form-group">
		<label id="elh_parametros_co_max" for="x_co_max" class="col-sm-2 control-label ewLabel"><?php echo $parametros->co_max->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->co_max->CellAttributes() ?>>
<span id="el_parametros_co_max">
<input type="text" data-table="parametros" data-field="x_co_max" name="x_co_max" id="x_co_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_max->getPlaceHolder()) ?>" value="<?php echo $parametros->co_max->EditValue ?>"<?php echo $parametros->co_max->EditAttributes() ?>>
</span>
<?php echo $parametros->co_max->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
	<div id="r_horas_crecimiento" class="form-group">
		<label id="elh_parametros_horas_crecimiento" for="x_horas_crecimiento" class="col-sm-2 control-label ewLabel"><?php echo $parametros->horas_crecimiento->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->horas_crecimiento->CellAttributes() ?>>
<span id="el_parametros_horas_crecimiento">
<input type="text" data-table="parametros" data-field="x_horas_crecimiento" name="x_horas_crecimiento" id="x_horas_crecimiento" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_crecimiento->EditValue ?>"<?php echo $parametros->horas_crecimiento->EditAttributes() ?>>
</span>
<?php echo $parametros->horas_crecimiento->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
	<div id="r_horas_floracion" class="form-group">
		<label id="elh_parametros_horas_floracion" for="x_horas_floracion" class="col-sm-2 control-label ewLabel"><?php echo $parametros->horas_floracion->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->horas_floracion->CellAttributes() ?>>
<span id="el_parametros_horas_floracion">
<input type="text" data-table="parametros" data-field="x_horas_floracion" name="x_horas_floracion" id="x_horas_floracion" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_floracion->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_floracion->EditValue ?>"<?php echo $parametros->horas_floracion->EditAttributes() ?>>
</span>
<?php echo $parametros->horas_floracion->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->hum_min->Visible) { // hum_min ?>
	<div id="r_hum_min" class="form-group">
		<label id="elh_parametros_hum_min" for="x_hum_min" class="col-sm-2 control-label ewLabel"><?php echo $parametros->hum_min->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->hum_min->CellAttributes() ?>>
<span id="el_parametros_hum_min">
<input type="text" data-table="parametros" data-field="x_hum_min" name="x_hum_min" id="x_hum_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_min->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_min->EditValue ?>"<?php echo $parametros->hum_min->EditAttributes() ?>>
</span>
<?php echo $parametros->hum_min->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->hum_max->Visible) { // hum_max ?>
	<div id="r_hum_max" class="form-group">
		<label id="elh_parametros_hum_max" for="x_hum_max" class="col-sm-2 control-label ewLabel"><?php echo $parametros->hum_max->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->hum_max->CellAttributes() ?>>
<span id="el_parametros_hum_max">
<input type="text" data-table="parametros" data-field="x_hum_max" name="x_hum_max" id="x_hum_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_max->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_max->EditValue ?>"<?php echo $parametros->hum_max->EditAttributes() ?>>
</span>
<?php echo $parametros->hum_max->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
	<div id="r_DnsHost" class="form-group">
		<label id="elh_parametros_DnsHost" for="x_DnsHost" class="col-sm-2 control-label ewLabel"><?php echo $parametros->DnsHost->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->DnsHost->CellAttributes() ?>>
<span id="el_parametros_DnsHost">
<input type="text" data-table="parametros" data-field="x_DnsHost" name="x_DnsHost" id="x_DnsHost" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsHost->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsHost->EditValue ?>"<?php echo $parametros->DnsHost->EditAttributes() ?>>
</span>
<?php echo $parametros->DnsHost->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
	<div id="r_DnsUser" class="form-group">
		<label id="elh_parametros_DnsUser" for="x_DnsUser" class="col-sm-2 control-label ewLabel"><?php echo $parametros->DnsUser->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->DnsUser->CellAttributes() ?>>
<span id="el_parametros_DnsUser">
<input type="text" data-table="parametros" data-field="x_DnsUser" name="x_DnsUser" id="x_DnsUser" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUser->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUser->EditValue ?>"<?php echo $parametros->DnsUser->EditAttributes() ?>>
</span>
<?php echo $parametros->DnsUser->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
	<div id="r_DnsPasswd" class="form-group">
		<label id="elh_parametros_DnsPasswd" for="x_DnsPasswd" class="col-sm-2 control-label ewLabel"><?php echo $parametros->DnsPasswd->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->DnsPasswd->CellAttributes() ?>>
<span id="el_parametros_DnsPasswd">
<input type="text" data-table="parametros" data-field="x_DnsPasswd" name="x_DnsPasswd" id="x_DnsPasswd" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsPasswd->EditValue ?>"<?php echo $parametros->DnsPasswd->EditAttributes() ?>>
</span>
<?php echo $parametros->DnsPasswd->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
	<div id="r_DnsUrl_Update" class="form-group">
		<label id="elh_parametros_DnsUrl_Update" for="x_DnsUrl_Update" class="col-sm-2 control-label ewLabel"><?php echo $parametros->DnsUrl_Update->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->DnsUrl_Update->CellAttributes() ?>>
<span id="el_parametros_DnsUrl_Update">
<input type="text" data-table="parametros" data-field="x_DnsUrl_Update" name="x_DnsUrl_Update" id="x_DnsUrl_Update" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUrl_Update->EditValue ?>"<?php echo $parametros->DnsUrl_Update->EditAttributes() ?>>
</span>
<?php echo $parametros->DnsUrl_Update->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
	<div id="r_WifiSSID" class="form-group">
		<label id="elh_parametros_WifiSSID" for="x_WifiSSID" class="col-sm-2 control-label ewLabel"><?php echo $parametros->WifiSSID->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->WifiSSID->CellAttributes() ?>>
<span id="el_parametros_WifiSSID">
<input type="text" data-table="parametros" data-field="x_WifiSSID" name="x_WifiSSID" id="x_WifiSSID" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($parametros->WifiSSID->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiSSID->EditValue ?>"<?php echo $parametros->WifiSSID->EditAttributes() ?>>
</span>
<?php echo $parametros->WifiSSID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
	<div id="r_WifiPasswd" class="form-group">
		<label id="elh_parametros_WifiPasswd" for="x_WifiPasswd" class="col-sm-2 control-label ewLabel"><?php echo $parametros->WifiPasswd->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $parametros->WifiPasswd->CellAttributes() ?>>
<span id="el_parametros_WifiPasswd">
<input type="text" data-table="parametros" data-field="x_WifiPasswd" name="x_WifiPasswd" id="x_WifiPasswd" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->WifiPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiPasswd->EditValue ?>"<?php echo $parametros->WifiPasswd->EditAttributes() ?>>
</span>
<?php echo $parametros->WifiPasswd->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $parametros_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fparametrosedit.Init();
</script>
<?php
$parametros_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parametros_edit->Page_Terminate();
?>
