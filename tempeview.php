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

$tempe_view = NULL; // Initialize page object first

class ctempe_view extends ctempe {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{032690A3-4B26-49FF-B1A0-E08477B5B2A3}";

	// Table name
	var $TableName = 'tempe';

	// Page object name
	var $PageObjName = 'tempe_view';

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

		// Table object (tempe)
		if (!isset($GLOBALS["tempe"]) || get_class($GLOBALS["tempe"]) == "ctempe") {
			$GLOBALS["tempe"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tempe"];
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
			define("EW_TABLE_NAME", 'tempe', TRUE);

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
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
				$sReturnUrl = "tempelist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tempelist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "tempelist.php"; // Not page request, return to list
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

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$Breadcrumb->Add("list", $this->TableVar, "tempelist.php", "", $this->TableVar, TRUE);
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tempe_view)) $tempe_view = new ctempe_view();

// Page init
$tempe_view->Page_Init();

// Page main
$tempe_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tempe_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = ftempeview = new ew_Form("ftempeview", "view");

// Form_CustomValidate event
ftempeview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftempeview.ValidateRequired = true;
<?php } else { ?>
ftempeview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $tempe_view->ExportOptions->Render("body") ?>
<?php
	foreach ($tempe_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $tempe_view->ShowPageHeader(); ?>
<?php
$tempe_view->ShowMessage();
?>
<form name="ftempeview" id="ftempeview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tempe_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tempe_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tempe">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($tempe->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tempe_id"><?php echo $tempe->id->FldCaption() ?></span></td>
		<td data-name="id"<?php echo $tempe->id->CellAttributes() ?>>
<span id="el_tempe_id">
<span<?php echo $tempe->id->ViewAttributes() ?>>
<?php echo $tempe->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->fecha->Visible) { // fecha ?>
	<tr id="r_fecha">
		<td><span id="elh_tempe_fecha"><?php echo $tempe->fecha->FldCaption() ?></span></td>
		<td data-name="fecha"<?php echo $tempe->fecha->CellAttributes() ?>>
<span id="el_tempe_fecha">
<span<?php echo $tempe->fecha->ViewAttributes() ?>>
<?php echo $tempe->fecha->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->hora->Visible) { // hora ?>
	<tr id="r_hora">
		<td><span id="elh_tempe_hora"><?php echo $tempe->hora->FldCaption() ?></span></td>
		<td data-name="hora"<?php echo $tempe->hora->CellAttributes() ?>>
<span id="el_tempe_hora">
<span<?php echo $tempe->hora->ViewAttributes() ?>>
<?php echo $tempe->hora->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->temp->Visible) { // temp ?>
	<tr id="r_temp">
		<td><span id="elh_tempe_temp"><?php echo $tempe->temp->FldCaption() ?></span></td>
		<td data-name="temp"<?php echo $tempe->temp->CellAttributes() ?>>
<span id="el_tempe_temp">
<span<?php echo $tempe->temp->ViewAttributes() ?>>
<?php echo $tempe->temp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->hum->Visible) { // hum ?>
	<tr id="r_hum">
		<td><span id="elh_tempe_hum"><?php echo $tempe->hum->FldCaption() ?></span></td>
		<td data-name="hum"<?php echo $tempe->hum->CellAttributes() ?>>
<span id="el_tempe_hum">
<span<?php echo $tempe->hum->ViewAttributes() ?>>
<?php echo $tempe->hum->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->co2ppm->Visible) { // co2ppm ?>
	<tr id="r_co2ppm">
		<td><span id="elh_tempe_co2ppm"><?php echo $tempe->co2ppm->FldCaption() ?></span></td>
		<td data-name="co2ppm"<?php echo $tempe->co2ppm->CellAttributes() ?>>
<span id="el_tempe_co2ppm">
<span<?php echo $tempe->co2ppm->ViewAttributes() ?>>
<?php echo $tempe->co2ppm->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->higromet->Visible) { // higromet ?>
	<tr id="r_higromet">
		<td><span id="elh_tempe_higromet"><?php echo $tempe->higromet->FldCaption() ?></span></td>
		<td data-name="higromet"<?php echo $tempe->higromet->CellAttributes() ?>>
<span id="el_tempe_higromet">
<span<?php echo $tempe->higromet->ViewAttributes() ?>>
<?php echo $tempe->higromet->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->luz->Visible) { // luz ?>
	<tr id="r_luz">
		<td><span id="elh_tempe_luz"><?php echo $tempe->luz->FldCaption() ?></span></td>
		<td data-name="luz"<?php echo $tempe->luz->CellAttributes() ?>>
<span id="el_tempe_luz">
<span<?php echo $tempe->luz->ViewAttributes() ?>>
<?php echo $tempe->luz->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->maqhum->Visible) { // maqhum ?>
	<tr id="r_maqhum">
		<td><span id="elh_tempe_maqhum"><?php echo $tempe->maqhum->FldCaption() ?></span></td>
		<td data-name="maqhum"<?php echo $tempe->maqhum->CellAttributes() ?>>
<span id="el_tempe_maqhum">
<span<?php echo $tempe->maqhum->ViewAttributes() ?>>
<?php echo $tempe->maqhum->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->maqdesh->Visible) { // maqdesh ?>
	<tr id="r_maqdesh">
		<td><span id="elh_tempe_maqdesh"><?php echo $tempe->maqdesh->FldCaption() ?></span></td>
		<td data-name="maqdesh"<?php echo $tempe->maqdesh->CellAttributes() ?>>
<span id="el_tempe_maqdesh">
<span<?php echo $tempe->maqdesh->ViewAttributes() ?>>
<?php echo $tempe->maqdesh->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->maqcale->Visible) { // maqcale ?>
	<tr id="r_maqcale">
		<td><span id="elh_tempe_maqcale"><?php echo $tempe->maqcale->FldCaption() ?></span></td>
		<td data-name="maqcale"<?php echo $tempe->maqcale->CellAttributes() ?>>
<span id="el_tempe_maqcale">
<span<?php echo $tempe->maqcale->ViewAttributes() ?>>
<?php echo $tempe->maqcale->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->modman->Visible) { // modman ?>
	<tr id="r_modman">
		<td><span id="elh_tempe_modman"><?php echo $tempe->modman->FldCaption() ?></span></td>
		<td data-name="modman"<?php echo $tempe->modman->CellAttributes() ?>>
<span id="el_tempe_modman">
<span<?php echo $tempe->modman->ViewAttributes() ?>>
<?php echo $tempe->modman->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->periodo->Visible) { // periodo ?>
	<tr id="r_periodo">
		<td><span id="elh_tempe_periodo"><?php echo $tempe->periodo->FldCaption() ?></span></td>
		<td data-name="periodo"<?php echo $tempe->periodo->CellAttributes() ?>>
<span id="el_tempe_periodo">
<span<?php echo $tempe->periodo->ViewAttributes() ?>>
<?php echo $tempe->periodo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->horasluz->Visible) { // horasluz ?>
	<tr id="r_horasluz">
		<td><span id="elh_tempe_horasluz"><?php echo $tempe->horasluz->FldCaption() ?></span></td>
		<td data-name="horasluz"<?php echo $tempe->horasluz->CellAttributes() ?>>
<span id="el_tempe_horasluz">
<span<?php echo $tempe->horasluz->ViewAttributes() ?>>
<?php echo $tempe->horasluz->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tempe->fechaini->Visible) { // fechaini ?>
	<tr id="r_fechaini">
		<td><span id="elh_tempe_fechaini"><?php echo $tempe->fechaini->FldCaption() ?></span></td>
		<td data-name="fechaini"<?php echo $tempe->fechaini->CellAttributes() ?>>
<span id="el_tempe_fechaini">
<span<?php echo $tempe->fechaini->ViewAttributes() ?>>
<?php echo $tempe->fechaini->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ftempeview.Init();
</script>
<?php
$tempe_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tempe_view->Page_Terminate();
?>
