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

$parametros_add = NULL; // Initialize page object first

class cparametros_add extends cparametros {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{28C31B56-5507-4BCF-B1AE-F273C6345D9C}";

	// Table name
	var $TableName = 'parametros';

	// Page object name
	var $PageObjName = 'parametros_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("parametroslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "parametrosview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->temp_min->CurrentValue = NULL;
		$this->temp_min->OldValue = $this->temp_min->CurrentValue;
		$this->temp_max->CurrentValue = NULL;
		$this->temp_max->OldValue = $this->temp_max->CurrentValue;
		$this->co_min->CurrentValue = NULL;
		$this->co_min->OldValue = $this->co_min->CurrentValue;
		$this->co_max->CurrentValue = NULL;
		$this->co_max->OldValue = $this->co_max->CurrentValue;
		$this->horas_crecimiento->CurrentValue = NULL;
		$this->horas_crecimiento->OldValue = $this->horas_crecimiento->CurrentValue;
		$this->horas_floracion->CurrentValue = NULL;
		$this->horas_floracion->OldValue = $this->horas_floracion->CurrentValue;
		$this->hum_min->CurrentValue = NULL;
		$this->hum_min->OldValue = $this->hum_min->CurrentValue;
		$this->hum_max->CurrentValue = NULL;
		$this->hum_max->OldValue = $this->hum_max->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->temp_min->CurrentValue = $this->temp_min->FormValue;
		$this->temp_max->CurrentValue = $this->temp_max->FormValue;
		$this->co_min->CurrentValue = $this->co_min->FormValue;
		$this->co_max->CurrentValue = $this->co_max->FormValue;
		$this->horas_crecimiento->CurrentValue = $this->horas_crecimiento->FormValue;
		$this->horas_floracion->CurrentValue = $this->horas_floracion->FormValue;
		$this->hum_min->CurrentValue = $this->hum_min->FormValue;
		$this->hum_max->CurrentValue = $this->hum_max->FormValue;
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
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Edit refer script
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// temp_min
		$this->temp_min->SetDbValueDef($rsnew, $this->temp_min->CurrentValue, 0, FALSE);

		// temp_max
		$this->temp_max->SetDbValueDef($rsnew, $this->temp_max->CurrentValue, 0, FALSE);

		// co_min
		$this->co_min->SetDbValueDef($rsnew, $this->co_min->CurrentValue, 0, FALSE);

		// co_max
		$this->co_max->SetDbValueDef($rsnew, $this->co_max->CurrentValue, 0, FALSE);

		// horas_crecimiento
		$this->horas_crecimiento->SetDbValueDef($rsnew, $this->horas_crecimiento->CurrentValue, 0, FALSE);

		// horas_floracion
		$this->horas_floracion->SetDbValueDef($rsnew, $this->horas_floracion->CurrentValue, 0, FALSE);

		// hum_min
		$this->hum_min->SetDbValueDef($rsnew, $this->hum_min->CurrentValue, 0, FALSE);

		// hum_max
		$this->hum_max->SetDbValueDef($rsnew, $this->hum_max->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "parametroslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($parametros_add)) $parametros_add = new cparametros_add();

// Page init
$parametros_add->Page_Init();

// Page main
$parametros_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parametros_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fparametrosadd = new ew_Form("fparametrosadd", "add");

// Validate form
fparametrosadd.Validate = function() {
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
fparametrosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparametrosadd.ValidateRequired = true;
<?php } else { ?>
fparametrosadd.ValidateRequired = false; 
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
<?php $parametros_add->ShowPageHeader(); ?>
<?php
$parametros_add->ShowMessage();
?>
<form name="fparametrosadd" id="fparametrosadd" class="<?php echo $parametros_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parametros_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parametros_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parametros">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
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
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $parametros_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fparametrosadd.Init();
</script>
<?php
$parametros_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parametros_add->Page_Terminate();
?>
