<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "tempeinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$tempe_edit = NULL; // Initialize page object first

class ctempe_edit extends ctempe {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{032690A3-4B26-49FF-B1A0-E08477B5B2A3}";

	// Table name
	var $TableName = 'tempe';

	// Page object name
	var $PageObjName = 'tempe_edit';

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

		// Table object (tempe)
		if (!isset($GLOBALS["tempe"]) || get_class($GLOBALS["tempe"]) == "ctempe") {
			$GLOBALS["tempe"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tempe"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tempe', TRUE);

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
		global $EW_EXPORT, $tempe;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($tempe);
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
			$this->Page_Terminate("tempelist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("tempelist.php"); // No matching record, return to list
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
		if (!$this->fecha->FldIsDetailKey) {
			$this->fecha->setFormValue($objForm->GetValue("x_fecha"));
			$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 5);
		}
		if (!$this->hora->FldIsDetailKey) {
			$this->hora->setFormValue($objForm->GetValue("x_hora"));
		}
		if (!$this->temp->FldIsDetailKey) {
			$this->temp->setFormValue($objForm->GetValue("x_temp"));
		}
		if (!$this->hum->FldIsDetailKey) {
			$this->hum->setFormValue($objForm->GetValue("x_hum"));
		}
		if (!$this->co2ppm->FldIsDetailKey) {
			$this->co2ppm->setFormValue($objForm->GetValue("x_co2ppm"));
		}
		if (!$this->higromet->FldIsDetailKey) {
			$this->higromet->setFormValue($objForm->GetValue("x_higromet"));
		}
		if (!$this->luz->FldIsDetailKey) {
			$this->luz->setFormValue($objForm->GetValue("x_luz"));
		}
		if (!$this->maqhum->FldIsDetailKey) {
			$this->maqhum->setFormValue($objForm->GetValue("x_maqhum"));
		}
		if (!$this->maqdesh->FldIsDetailKey) {
			$this->maqdesh->setFormValue($objForm->GetValue("x_maqdesh"));
		}
		if (!$this->maqcale->FldIsDetailKey) {
			$this->maqcale->setFormValue($objForm->GetValue("x_maqcale"));
		}
		if (!$this->modman->FldIsDetailKey) {
			$this->modman->setFormValue($objForm->GetValue("x_modman"));
		}
		if (!$this->periodo->FldIsDetailKey) {
			$this->periodo->setFormValue($objForm->GetValue("x_periodo"));
		}
		if (!$this->horasluz->FldIsDetailKey) {
			$this->horasluz->setFormValue($objForm->GetValue("x_horasluz"));
		}
		if (!$this->fechaini->FldIsDetailKey) {
			$this->fechaini->setFormValue($objForm->GetValue("x_fechaini"));
			$this->fechaini->CurrentValue = ew_UnFormatDateTime($this->fechaini->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->fecha->CurrentValue = $this->fecha->FormValue;
		$this->fecha->CurrentValue = ew_UnFormatDateTime($this->fecha->CurrentValue, 5);
		$this->hora->CurrentValue = $this->hora->FormValue;
		$this->temp->CurrentValue = $this->temp->FormValue;
		$this->hum->CurrentValue = $this->hum->FormValue;
		$this->co2ppm->CurrentValue = $this->co2ppm->FormValue;
		$this->higromet->CurrentValue = $this->higromet->FormValue;
		$this->luz->CurrentValue = $this->luz->FormValue;
		$this->maqhum->CurrentValue = $this->maqhum->FormValue;
		$this->maqdesh->CurrentValue = $this->maqdesh->FormValue;
		$this->maqcale->CurrentValue = $this->maqcale->FormValue;
		$this->modman->CurrentValue = $this->modman->FormValue;
		$this->periodo->CurrentValue = $this->periodo->FormValue;
		$this->horasluz->CurrentValue = $this->horasluz->FormValue;
		$this->fechaini->CurrentValue = $this->fechaini->FormValue;
		$this->fechaini->CurrentValue = ew_UnFormatDateTime($this->fechaini->CurrentValue, 5);
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
		$this->fecha->setDbValue($rs->fields('fecha'));
		$this->hora->setDbValue($rs->fields('hora'));
		$this->temp->setDbValue($rs->fields('temp'));
		$this->hum->setDbValue($rs->fields('hum'));
		$this->co2ppm->setDbValue($rs->fields('co2ppm'));
		$this->higromet->setDbValue($rs->fields('higromet'));
		$this->luz->setDbValue($rs->fields('luz'));
		$this->maqhum->setDbValue($rs->fields('maqhum'));
		$this->maqdesh->setDbValue($rs->fields('maqdesh'));
		$this->maqcale->setDbValue($rs->fields('maqcale'));
		$this->modman->setDbValue($rs->fields('modman'));
		$this->periodo->setDbValue($rs->fields('periodo'));
		$this->horasluz->setDbValue($rs->fields('horasluz'));
		$this->fechaini->setDbValue($rs->fields('fechaini'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->fecha->DbValue = $row['fecha'];
		$this->hora->DbValue = $row['hora'];
		$this->temp->DbValue = $row['temp'];
		$this->hum->DbValue = $row['hum'];
		$this->co2ppm->DbValue = $row['co2ppm'];
		$this->higromet->DbValue = $row['higromet'];
		$this->luz->DbValue = $row['luz'];
		$this->maqhum->DbValue = $row['maqhum'];
		$this->maqdesh->DbValue = $row['maqdesh'];
		$this->maqcale->DbValue = $row['maqcale'];
		$this->modman->DbValue = $row['modman'];
		$this->periodo->DbValue = $row['periodo'];
		$this->horasluz->DbValue = $row['horasluz'];
		$this->fechaini->DbValue = $row['fechaini'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->temp->FormValue == $this->temp->CurrentValue && is_numeric(ew_StrToFloat($this->temp->CurrentValue)))
			$this->temp->CurrentValue = ew_StrToFloat($this->temp->CurrentValue);

		// Convert decimal values if posted back
		if ($this->hum->FormValue == $this->hum->CurrentValue && is_numeric(ew_StrToFloat($this->hum->CurrentValue)))
			$this->hum->CurrentValue = ew_StrToFloat($this->hum->CurrentValue);

		// Convert decimal values if posted back
		if ($this->co2ppm->FormValue == $this->co2ppm->CurrentValue && is_numeric(ew_StrToFloat($this->co2ppm->CurrentValue)))
			$this->co2ppm->CurrentValue = ew_StrToFloat($this->co2ppm->CurrentValue);

		// Convert decimal values if posted back
		if ($this->higromet->FormValue == $this->higromet->CurrentValue && is_numeric(ew_StrToFloat($this->higromet->CurrentValue)))
			$this->higromet->CurrentValue = ew_StrToFloat($this->higromet->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// fecha
		// hora
		// temp
		// hum
		// co2ppm
		// higromet
		// luz
		// maqhum
		// maqdesh
		// maqcale
		// modman
		// periodo
		// horasluz
		// fechaini

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->CellCssStyle .= "text-align: center;";
		$this->id->ViewCustomAttributes = "";

		// fecha
		$this->fecha->ViewValue = $this->fecha->CurrentValue;
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 5);
		$this->fecha->ViewCustomAttributes = "";

		// hora
		$this->hora->ViewValue = $this->hora->CurrentValue;
		$this->hora->ViewCustomAttributes = "";

		// temp
		$this->temp->ViewValue = $this->temp->CurrentValue;
		$this->temp->ViewCustomAttributes = "";

		// hum
		$this->hum->ViewValue = $this->hum->CurrentValue;
		$this->hum->ViewCustomAttributes = "";

		// co2ppm
		$this->co2ppm->ViewValue = $this->co2ppm->CurrentValue;
		$this->co2ppm->ViewCustomAttributes = "";

		// higromet
		$this->higromet->ViewValue = $this->higromet->CurrentValue;
		$this->higromet->ViewCustomAttributes = "";

		// luz
		$this->luz->ViewValue = $this->luz->CurrentValue;
		$this->luz->ViewCustomAttributes = "";

		// maqhum
		$this->maqhum->ViewValue = $this->maqhum->CurrentValue;
		$this->maqhum->ViewCustomAttributes = "";

		// maqdesh
		$this->maqdesh->ViewValue = $this->maqdesh->CurrentValue;
		$this->maqdesh->ViewCustomAttributes = "";

		// maqcale
		$this->maqcale->ViewValue = $this->maqcale->CurrentValue;
		$this->maqcale->ViewCustomAttributes = "";

		// modman
		$this->modman->ViewValue = $this->modman->CurrentValue;
		$this->modman->ViewCustomAttributes = "";

		// periodo
		$this->periodo->ViewValue = $this->periodo->CurrentValue;
		$this->periodo->ViewCustomAttributes = "";

		// horasluz
		$this->horasluz->ViewValue = $this->horasluz->CurrentValue;
		$this->horasluz->ViewCustomAttributes = "";

		// fechaini
		$this->fechaini->ViewValue = $this->fechaini->CurrentValue;
		$this->fechaini->ViewValue = ew_FormatDateTime($this->fechaini->ViewValue, 5);
		$this->fechaini->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// fecha
			$this->fecha->LinkCustomAttributes = "";
			$this->fecha->HrefValue = "";
			$this->fecha->TooltipValue = "";

			// hora
			$this->hora->LinkCustomAttributes = "";
			$this->hora->HrefValue = "";
			$this->hora->TooltipValue = "";

			// temp
			$this->temp->LinkCustomAttributes = "";
			$this->temp->HrefValue = "";
			$this->temp->TooltipValue = "";

			// hum
			$this->hum->LinkCustomAttributes = "";
			$this->hum->HrefValue = "";
			$this->hum->TooltipValue = "";

			// co2ppm
			$this->co2ppm->LinkCustomAttributes = "";
			$this->co2ppm->HrefValue = "";
			$this->co2ppm->TooltipValue = "";

			// higromet
			$this->higromet->LinkCustomAttributes = "";
			$this->higromet->HrefValue = "";
			$this->higromet->TooltipValue = "";

			// luz
			$this->luz->LinkCustomAttributes = "";
			$this->luz->HrefValue = "";
			$this->luz->TooltipValue = "";

			// maqhum
			$this->maqhum->LinkCustomAttributes = "";
			$this->maqhum->HrefValue = "";
			$this->maqhum->TooltipValue = "";

			// maqdesh
			$this->maqdesh->LinkCustomAttributes = "";
			$this->maqdesh->HrefValue = "";
			$this->maqdesh->TooltipValue = "";

			// maqcale
			$this->maqcale->LinkCustomAttributes = "";
			$this->maqcale->HrefValue = "";
			$this->maqcale->TooltipValue = "";

			// modman
			$this->modman->LinkCustomAttributes = "";
			$this->modman->HrefValue = "";
			$this->modman->TooltipValue = "";

			// periodo
			$this->periodo->LinkCustomAttributes = "";
			$this->periodo->HrefValue = "";
			$this->periodo->TooltipValue = "";

			// horasluz
			$this->horasluz->LinkCustomAttributes = "";
			$this->horasluz->HrefValue = "";
			$this->horasluz->TooltipValue = "";

			// fechaini
			$this->fechaini->LinkCustomAttributes = "";
			$this->fechaini->HrefValue = "";
			$this->fechaini->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->CellCssStyle .= "text-align: center;";
			$this->id->ViewCustomAttributes = "";

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fecha->CurrentValue, 5));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// hora
			$this->hora->EditAttrs["class"] = "form-control";
			$this->hora->EditCustomAttributes = "";
			$this->hora->EditValue = ew_HtmlEncode($this->hora->CurrentValue);
			$this->hora->PlaceHolder = ew_RemoveHtml($this->hora->FldCaption());

			// temp
			$this->temp->EditAttrs["class"] = "form-control";
			$this->temp->EditCustomAttributes = "";
			$this->temp->EditValue = ew_HtmlEncode($this->temp->CurrentValue);
			$this->temp->PlaceHolder = ew_RemoveHtml($this->temp->FldCaption());
			if (strval($this->temp->EditValue) <> "" && is_numeric($this->temp->EditValue)) $this->temp->EditValue = ew_FormatNumber($this->temp->EditValue, -2, -1, -2, 0);

			// hum
			$this->hum->EditAttrs["class"] = "form-control";
			$this->hum->EditCustomAttributes = "";
			$this->hum->EditValue = ew_HtmlEncode($this->hum->CurrentValue);
			$this->hum->PlaceHolder = ew_RemoveHtml($this->hum->FldCaption());
			if (strval($this->hum->EditValue) <> "" && is_numeric($this->hum->EditValue)) $this->hum->EditValue = ew_FormatNumber($this->hum->EditValue, -2, -1, -2, 0);

			// co2ppm
			$this->co2ppm->EditAttrs["class"] = "form-control";
			$this->co2ppm->EditCustomAttributes = "";
			$this->co2ppm->EditValue = ew_HtmlEncode($this->co2ppm->CurrentValue);
			$this->co2ppm->PlaceHolder = ew_RemoveHtml($this->co2ppm->FldCaption());
			if (strval($this->co2ppm->EditValue) <> "" && is_numeric($this->co2ppm->EditValue)) $this->co2ppm->EditValue = ew_FormatNumber($this->co2ppm->EditValue, -2, -1, -2, 0);

			// higromet
			$this->higromet->EditAttrs["class"] = "form-control";
			$this->higromet->EditCustomAttributes = "";
			$this->higromet->EditValue = ew_HtmlEncode($this->higromet->CurrentValue);
			$this->higromet->PlaceHolder = ew_RemoveHtml($this->higromet->FldCaption());
			if (strval($this->higromet->EditValue) <> "" && is_numeric($this->higromet->EditValue)) $this->higromet->EditValue = ew_FormatNumber($this->higromet->EditValue, -2, -1, -2, 0);

			// luz
			$this->luz->EditAttrs["class"] = "form-control";
			$this->luz->EditCustomAttributes = "";
			$this->luz->EditValue = ew_HtmlEncode($this->luz->CurrentValue);
			$this->luz->PlaceHolder = ew_RemoveHtml($this->luz->FldCaption());

			// maqhum
			$this->maqhum->EditAttrs["class"] = "form-control";
			$this->maqhum->EditCustomAttributes = "";
			$this->maqhum->EditValue = ew_HtmlEncode($this->maqhum->CurrentValue);
			$this->maqhum->PlaceHolder = ew_RemoveHtml($this->maqhum->FldCaption());

			// maqdesh
			$this->maqdesh->EditAttrs["class"] = "form-control";
			$this->maqdesh->EditCustomAttributes = "";
			$this->maqdesh->EditValue = ew_HtmlEncode($this->maqdesh->CurrentValue);
			$this->maqdesh->PlaceHolder = ew_RemoveHtml($this->maqdesh->FldCaption());

			// maqcale
			$this->maqcale->EditAttrs["class"] = "form-control";
			$this->maqcale->EditCustomAttributes = "";
			$this->maqcale->EditValue = ew_HtmlEncode($this->maqcale->CurrentValue);
			$this->maqcale->PlaceHolder = ew_RemoveHtml($this->maqcale->FldCaption());

			// modman
			$this->modman->EditAttrs["class"] = "form-control";
			$this->modman->EditCustomAttributes = "";
			$this->modman->EditValue = ew_HtmlEncode($this->modman->CurrentValue);
			$this->modman->PlaceHolder = ew_RemoveHtml($this->modman->FldCaption());

			// periodo
			$this->periodo->EditAttrs["class"] = "form-control";
			$this->periodo->EditCustomAttributes = "";
			$this->periodo->EditValue = ew_HtmlEncode($this->periodo->CurrentValue);
			$this->periodo->PlaceHolder = ew_RemoveHtml($this->periodo->FldCaption());

			// horasluz
			$this->horasluz->EditAttrs["class"] = "form-control";
			$this->horasluz->EditCustomAttributes = "";
			$this->horasluz->EditValue = ew_HtmlEncode($this->horasluz->CurrentValue);
			$this->horasluz->PlaceHolder = ew_RemoveHtml($this->horasluz->FldCaption());

			// fechaini
			$this->fechaini->EditAttrs["class"] = "form-control";
			$this->fechaini->EditCustomAttributes = "";
			$this->fechaini->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->fechaini->CurrentValue, 5));
			$this->fechaini->PlaceHolder = ew_RemoveHtml($this->fechaini->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// fecha
			$this->fecha->HrefValue = "";

			// hora
			$this->hora->HrefValue = "";

			// temp
			$this->temp->HrefValue = "";

			// hum
			$this->hum->HrefValue = "";

			// co2ppm
			$this->co2ppm->HrefValue = "";

			// higromet
			$this->higromet->HrefValue = "";

			// luz
			$this->luz->HrefValue = "";

			// maqhum
			$this->maqhum->HrefValue = "";

			// maqdesh
			$this->maqdesh->HrefValue = "";

			// maqcale
			$this->maqcale->HrefValue = "";

			// modman
			$this->modman->HrefValue = "";

			// periodo
			$this->periodo->HrefValue = "";

			// horasluz
			$this->horasluz->HrefValue = "";

			// fechaini
			$this->fechaini->HrefValue = "";
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
		if (!ew_CheckDate($this->fecha->FormValue)) {
			ew_AddMessage($gsFormError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckTime($this->hora->FormValue)) {
			ew_AddMessage($gsFormError, $this->hora->FldErrMsg());
		}
		if (!ew_CheckNumber($this->temp->FormValue)) {
			ew_AddMessage($gsFormError, $this->temp->FldErrMsg());
		}
		if (!ew_CheckNumber($this->hum->FormValue)) {
			ew_AddMessage($gsFormError, $this->hum->FldErrMsg());
		}
		if (!ew_CheckNumber($this->co2ppm->FormValue)) {
			ew_AddMessage($gsFormError, $this->co2ppm->FldErrMsg());
		}
		if (!ew_CheckNumber($this->higromet->FormValue)) {
			ew_AddMessage($gsFormError, $this->higromet->FldErrMsg());
		}
		if (!ew_CheckInteger($this->maqhum->FormValue)) {
			ew_AddMessage($gsFormError, $this->maqhum->FldErrMsg());
		}
		if (!ew_CheckInteger($this->maqdesh->FormValue)) {
			ew_AddMessage($gsFormError, $this->maqdesh->FldErrMsg());
		}
		if (!ew_CheckInteger($this->maqcale->FormValue)) {
			ew_AddMessage($gsFormError, $this->maqcale->FldErrMsg());
		}
		if (!ew_CheckInteger($this->modman->FormValue)) {
			ew_AddMessage($gsFormError, $this->modman->FldErrMsg());
		}
		if (!ew_CheckInteger($this->periodo->FormValue)) {
			ew_AddMessage($gsFormError, $this->periodo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->horasluz->FormValue)) {
			ew_AddMessage($gsFormError, $this->horasluz->FldErrMsg());
		}
		if (!ew_CheckDate($this->fechaini->FormValue)) {
			ew_AddMessage($gsFormError, $this->fechaini->FldErrMsg());
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

			// fecha
			$this->fecha->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fecha->CurrentValue, 5), NULL, $this->fecha->ReadOnly);

			// hora
			$this->hora->SetDbValueDef($rsnew, $this->hora->CurrentValue, NULL, $this->hora->ReadOnly);

			// temp
			$this->temp->SetDbValueDef($rsnew, $this->temp->CurrentValue, NULL, $this->temp->ReadOnly);

			// hum
			$this->hum->SetDbValueDef($rsnew, $this->hum->CurrentValue, NULL, $this->hum->ReadOnly);

			// co2ppm
			$this->co2ppm->SetDbValueDef($rsnew, $this->co2ppm->CurrentValue, NULL, $this->co2ppm->ReadOnly);

			// higromet
			$this->higromet->SetDbValueDef($rsnew, $this->higromet->CurrentValue, NULL, $this->higromet->ReadOnly);

			// luz
			$this->luz->SetDbValueDef($rsnew, $this->luz->CurrentValue, NULL, $this->luz->ReadOnly);

			// maqhum
			$this->maqhum->SetDbValueDef($rsnew, $this->maqhum->CurrentValue, NULL, $this->maqhum->ReadOnly);

			// maqdesh
			$this->maqdesh->SetDbValueDef($rsnew, $this->maqdesh->CurrentValue, NULL, $this->maqdesh->ReadOnly);

			// maqcale
			$this->maqcale->SetDbValueDef($rsnew, $this->maqcale->CurrentValue, NULL, $this->maqcale->ReadOnly);

			// modman
			$this->modman->SetDbValueDef($rsnew, $this->modman->CurrentValue, NULL, $this->modman->ReadOnly);

			// periodo
			$this->periodo->SetDbValueDef($rsnew, $this->periodo->CurrentValue, NULL, $this->periodo->ReadOnly);

			// horasluz
			$this->horasluz->SetDbValueDef($rsnew, $this->horasluz->CurrentValue, NULL, $this->horasluz->ReadOnly);

			// fechaini
			$this->fechaini->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->fechaini->CurrentValue, 5), NULL, $this->fechaini->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, "tempelist.php", "", $this->TableVar, TRUE);
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tempe_edit)) $tempe_edit = new ctempe_edit();

// Page init
$tempe_edit->Page_Init();

// Page main
$tempe_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tempe_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ftempeedit = new ew_Form("ftempeedit", "edit");

// Validate form
ftempeedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_fecha");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->fecha->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hora");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->hora->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_temp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->temp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hum");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->hum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_co2ppm");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->co2ppm->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_higromet");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->higromet->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maqhum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->maqhum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maqdesh");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->maqdesh->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_maqcale");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->maqcale->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_modman");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->modman->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periodo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->periodo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_horasluz");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->horasluz->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fechaini");
			if (elm && !ew_CheckDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tempe->fechaini->FldErrMsg()) ?>");

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
ftempeedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftempeedit.ValidateRequired = true;
<?php } else { ?>
ftempeedit.ValidateRequired = false; 
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
<?php $tempe_edit->ShowPageHeader(); ?>
<?php
$tempe_edit->ShowMessage();
?>
<form name="ftempeedit" id="ftempeedit" class="<?php echo $tempe_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tempe_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tempe_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tempe">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($tempe->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label id="elh_tempe_id" class="col-sm-2 control-label ewLabel"><?php echo $tempe->id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->id->CellAttributes() ?>>
<span id="el_tempe_id">
<span<?php echo $tempe->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $tempe->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="tempe" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($tempe->id->CurrentValue) ?>">
<?php echo $tempe->id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label id="elh_tempe_fecha" for="x_fecha" class="col-sm-2 control-label ewLabel"><?php echo $tempe->fecha->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->fecha->CellAttributes() ?>>
<span id="el_tempe_fecha">
<input type="text" data-table="tempe" data-field="x_fecha" data-format="5" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($tempe->fecha->getPlaceHolder()) ?>" value="<?php echo $tempe->fecha->EditValue ?>"<?php echo $tempe->fecha->EditAttributes() ?>>
</span>
<?php echo $tempe->fecha->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->hora->Visible) { // hora ?>
	<div id="r_hora" class="form-group">
		<label id="elh_tempe_hora" for="x_hora" class="col-sm-2 control-label ewLabel"><?php echo $tempe->hora->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->hora->CellAttributes() ?>>
<span id="el_tempe_hora">
<input type="text" data-table="tempe" data-field="x_hora" name="x_hora" id="x_hora" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->hora->getPlaceHolder()) ?>" value="<?php echo $tempe->hora->EditValue ?>"<?php echo $tempe->hora->EditAttributes() ?>>
</span>
<?php echo $tempe->hora->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->temp->Visible) { // temp ?>
	<div id="r_temp" class="form-group">
		<label id="elh_tempe_temp" for="x_temp" class="col-sm-2 control-label ewLabel"><?php echo $tempe->temp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->temp->CellAttributes() ?>>
<span id="el_tempe_temp">
<input type="text" data-table="tempe" data-field="x_temp" name="x_temp" id="x_temp" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->temp->getPlaceHolder()) ?>" value="<?php echo $tempe->temp->EditValue ?>"<?php echo $tempe->temp->EditAttributes() ?>>
</span>
<?php echo $tempe->temp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->hum->Visible) { // hum ?>
	<div id="r_hum" class="form-group">
		<label id="elh_tempe_hum" for="x_hum" class="col-sm-2 control-label ewLabel"><?php echo $tempe->hum->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->hum->CellAttributes() ?>>
<span id="el_tempe_hum">
<input type="text" data-table="tempe" data-field="x_hum" name="x_hum" id="x_hum" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->hum->getPlaceHolder()) ?>" value="<?php echo $tempe->hum->EditValue ?>"<?php echo $tempe->hum->EditAttributes() ?>>
</span>
<?php echo $tempe->hum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->co2ppm->Visible) { // co2ppm ?>
	<div id="r_co2ppm" class="form-group">
		<label id="elh_tempe_co2ppm" for="x_co2ppm" class="col-sm-2 control-label ewLabel"><?php echo $tempe->co2ppm->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->co2ppm->CellAttributes() ?>>
<span id="el_tempe_co2ppm">
<input type="text" data-table="tempe" data-field="x_co2ppm" name="x_co2ppm" id="x_co2ppm" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->co2ppm->getPlaceHolder()) ?>" value="<?php echo $tempe->co2ppm->EditValue ?>"<?php echo $tempe->co2ppm->EditAttributes() ?>>
</span>
<?php echo $tempe->co2ppm->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->higromet->Visible) { // higromet ?>
	<div id="r_higromet" class="form-group">
		<label id="elh_tempe_higromet" for="x_higromet" class="col-sm-2 control-label ewLabel"><?php echo $tempe->higromet->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->higromet->CellAttributes() ?>>
<span id="el_tempe_higromet">
<input type="text" data-table="tempe" data-field="x_higromet" name="x_higromet" id="x_higromet" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->higromet->getPlaceHolder()) ?>" value="<?php echo $tempe->higromet->EditValue ?>"<?php echo $tempe->higromet->EditAttributes() ?>>
</span>
<?php echo $tempe->higromet->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->luz->Visible) { // luz ?>
	<div id="r_luz" class="form-group">
		<label id="elh_tempe_luz" for="x_luz" class="col-sm-2 control-label ewLabel"><?php echo $tempe->luz->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->luz->CellAttributes() ?>>
<span id="el_tempe_luz">
<input type="text" data-table="tempe" data-field="x_luz" name="x_luz" id="x_luz" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($tempe->luz->getPlaceHolder()) ?>" value="<?php echo $tempe->luz->EditValue ?>"<?php echo $tempe->luz->EditAttributes() ?>>
</span>
<?php echo $tempe->luz->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->maqhum->Visible) { // maqhum ?>
	<div id="r_maqhum" class="form-group">
		<label id="elh_tempe_maqhum" for="x_maqhum" class="col-sm-2 control-label ewLabel"><?php echo $tempe->maqhum->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->maqhum->CellAttributes() ?>>
<span id="el_tempe_maqhum">
<input type="text" data-table="tempe" data-field="x_maqhum" name="x_maqhum" id="x_maqhum" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->maqhum->getPlaceHolder()) ?>" value="<?php echo $tempe->maqhum->EditValue ?>"<?php echo $tempe->maqhum->EditAttributes() ?>>
</span>
<?php echo $tempe->maqhum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->maqdesh->Visible) { // maqdesh ?>
	<div id="r_maqdesh" class="form-group">
		<label id="elh_tempe_maqdesh" for="x_maqdesh" class="col-sm-2 control-label ewLabel"><?php echo $tempe->maqdesh->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->maqdesh->CellAttributes() ?>>
<span id="el_tempe_maqdesh">
<input type="text" data-table="tempe" data-field="x_maqdesh" name="x_maqdesh" id="x_maqdesh" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->maqdesh->getPlaceHolder()) ?>" value="<?php echo $tempe->maqdesh->EditValue ?>"<?php echo $tempe->maqdesh->EditAttributes() ?>>
</span>
<?php echo $tempe->maqdesh->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->maqcale->Visible) { // maqcale ?>
	<div id="r_maqcale" class="form-group">
		<label id="elh_tempe_maqcale" for="x_maqcale" class="col-sm-2 control-label ewLabel"><?php echo $tempe->maqcale->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->maqcale->CellAttributes() ?>>
<span id="el_tempe_maqcale">
<input type="text" data-table="tempe" data-field="x_maqcale" name="x_maqcale" id="x_maqcale" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->maqcale->getPlaceHolder()) ?>" value="<?php echo $tempe->maqcale->EditValue ?>"<?php echo $tempe->maqcale->EditAttributes() ?>>
</span>
<?php echo $tempe->maqcale->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->modman->Visible) { // modman ?>
	<div id="r_modman" class="form-group">
		<label id="elh_tempe_modman" for="x_modman" class="col-sm-2 control-label ewLabel"><?php echo $tempe->modman->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->modman->CellAttributes() ?>>
<span id="el_tempe_modman">
<input type="text" data-table="tempe" data-field="x_modman" name="x_modman" id="x_modman" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->modman->getPlaceHolder()) ?>" value="<?php echo $tempe->modman->EditValue ?>"<?php echo $tempe->modman->EditAttributes() ?>>
</span>
<?php echo $tempe->modman->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->periodo->Visible) { // periodo ?>
	<div id="r_periodo" class="form-group">
		<label id="elh_tempe_periodo" for="x_periodo" class="col-sm-2 control-label ewLabel"><?php echo $tempe->periodo->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->periodo->CellAttributes() ?>>
<span id="el_tempe_periodo">
<input type="text" data-table="tempe" data-field="x_periodo" name="x_periodo" id="x_periodo" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->periodo->getPlaceHolder()) ?>" value="<?php echo $tempe->periodo->EditValue ?>"<?php echo $tempe->periodo->EditAttributes() ?>>
</span>
<?php echo $tempe->periodo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->horasluz->Visible) { // horasluz ?>
	<div id="r_horasluz" class="form-group">
		<label id="elh_tempe_horasluz" for="x_horasluz" class="col-sm-2 control-label ewLabel"><?php echo $tempe->horasluz->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->horasluz->CellAttributes() ?>>
<span id="el_tempe_horasluz">
<input type="text" data-table="tempe" data-field="x_horasluz" name="x_horasluz" id="x_horasluz" size="30" placeholder="<?php echo ew_HtmlEncode($tempe->horasluz->getPlaceHolder()) ?>" value="<?php echo $tempe->horasluz->EditValue ?>"<?php echo $tempe->horasluz->EditAttributes() ?>>
</span>
<?php echo $tempe->horasluz->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($tempe->fechaini->Visible) { // fechaini ?>
	<div id="r_fechaini" class="form-group">
		<label id="elh_tempe_fechaini" for="x_fechaini" class="col-sm-2 control-label ewLabel"><?php echo $tempe->fechaini->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $tempe->fechaini->CellAttributes() ?>>
<span id="el_tempe_fechaini">
<input type="text" data-table="tempe" data-field="x_fechaini" data-format="5" name="x_fechaini" id="x_fechaini" placeholder="<?php echo ew_HtmlEncode($tempe->fechaini->getPlaceHolder()) ?>" value="<?php echo $tempe->fechaini->EditValue ?>"<?php echo $tempe->fechaini->EditAttributes() ?>>
</span>
<?php echo $tempe->fechaini->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tempe_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
ftempeedit.Init();
</script>
<?php
$tempe_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tempe_edit->Page_Terminate();
?>
