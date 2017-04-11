<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "view1info.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$view1_search = NULL; // Initialize page object first

class cview1_search extends cview1 {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = "{032690A3-4B26-49FF-B1A0-E08477B5B2A3}";

	// Table name
	var $TableName = 'view1';

	// Page object name
	var $PageObjName = 'view1_search';

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

		// Table object (view1)
		if (!isset($GLOBALS["view1"]) || get_class($GLOBALS["view1"]) == "cview1") {
			$GLOBALS["view1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1', TRUE);

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
		global $EW_EXPORT, $view1;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view1);
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
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $SearchLabelClass = "col-sm-3 control-label ewLabel";
	var $SearchRightColumnClass = "col-sm-9";

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "view1list.php" . "?" . $sSrchStr;
						if ($this->IsModal) {
							$row = array();
							$row["url"] = $sSrchStr;
							echo ew_ArrayToJson(array($row));
							$this->Page_Terminate();
							exit();
						} else {
							$this->Page_Terminate($sSrchStr); // Go to list page
						}
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->id); // id
		$this->BuildSearchUrl($sSrchUrl, $this->fecha); // fecha
		$this->BuildSearchUrl($sSrchUrl, $this->hora); // hora
		$this->BuildSearchUrl($sSrchUrl, $this->temp); // temp
		$this->BuildSearchUrl($sSrchUrl, $this->hum); // hum
		$this->BuildSearchUrl($sSrchUrl, $this->co2ppm); // co2ppm
		$this->BuildSearchUrl($sSrchUrl, $this->higromet); // higromet
		$this->BuildSearchUrl($sSrchUrl, $this->luz); // luz
		$this->BuildSearchUrl($sSrchUrl, $this->maqhum); // maqhum
		$this->BuildSearchUrl($sSrchUrl, $this->maqdesh); // maqdesh
		$this->BuildSearchUrl($sSrchUrl, $this->maqcale); // maqcale
		$this->BuildSearchUrl($sSrchUrl, $this->modman); // modman
		$this->BuildSearchUrl($sSrchUrl, $this->periodo); // periodo
		$this->BuildSearchUrl($sSrchUrl, $this->horasluz); // horasluz
		$this->BuildSearchUrl($sSrchUrl, $this->fechaini); // fechaini
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_id"));
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// fecha
		$this->fecha->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fecha"));
		$this->fecha->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fecha");

		// hora
		$this->hora->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_hora"));
		$this->hora->AdvancedSearch->SearchOperator = $objForm->GetValue("z_hora");

		// temp
		$this->temp->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_temp"));
		$this->temp->AdvancedSearch->SearchOperator = $objForm->GetValue("z_temp");

		// hum
		$this->hum->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_hum"));
		$this->hum->AdvancedSearch->SearchOperator = $objForm->GetValue("z_hum");

		// co2ppm
		$this->co2ppm->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_co2ppm"));
		$this->co2ppm->AdvancedSearch->SearchOperator = $objForm->GetValue("z_co2ppm");

		// higromet
		$this->higromet->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_higromet"));
		$this->higromet->AdvancedSearch->SearchOperator = $objForm->GetValue("z_higromet");

		// luz
		$this->luz->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_luz"));
		$this->luz->AdvancedSearch->SearchOperator = $objForm->GetValue("z_luz");

		// maqhum
		$this->maqhum->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_maqhum"));
		$this->maqhum->AdvancedSearch->SearchOperator = $objForm->GetValue("z_maqhum");

		// maqdesh
		$this->maqdesh->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_maqdesh"));
		$this->maqdesh->AdvancedSearch->SearchOperator = $objForm->GetValue("z_maqdesh");

		// maqcale
		$this->maqcale->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_maqcale"));
		$this->maqcale->AdvancedSearch->SearchOperator = $objForm->GetValue("z_maqcale");

		// modman
		$this->modman->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_modman"));
		$this->modman->AdvancedSearch->SearchOperator = $objForm->GetValue("z_modman");

		// periodo
		$this->periodo->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_periodo"));
		$this->periodo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_periodo");

		// horasluz
		$this->horasluz->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_horasluz"));
		$this->horasluz->AdvancedSearch->SearchOperator = $objForm->GetValue("z_horasluz");

		// fechaini
		$this->fechaini->AdvancedSearch->SearchValue = ew_StripSlashes($objForm->GetValue("x_fechaini"));
		$this->fechaini->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fechaini");
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
		$this->fecha->ViewValue = ew_FormatDateTime($this->fecha->ViewValue, 7);
		$this->fecha->ViewCustomAttributes = "";

		// hora
		$this->hora->ViewValue = $this->hora->CurrentValue;
		$this->hora->ViewValue = ew_FormatDateTime($this->hora->ViewValue, 4);
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
		if (strval($this->luz->CurrentValue) <> "") {
			$this->luz->ViewValue = $this->luz->OptionCaption($this->luz->CurrentValue);
		} else {
			$this->luz->ViewValue = NULL;
		}
		$this->luz->ViewCustomAttributes = "";

		// maqhum
		if (strval($this->maqhum->CurrentValue) <> "") {
			$this->maqhum->ViewValue = $this->maqhum->OptionCaption($this->maqhum->CurrentValue);
		} else {
			$this->maqhum->ViewValue = NULL;
		}
		$this->maqhum->ViewCustomAttributes = "";

		// maqdesh
		if (strval($this->maqdesh->CurrentValue) <> "") {
			$this->maqdesh->ViewValue = $this->maqdesh->OptionCaption($this->maqdesh->CurrentValue);
		} else {
			$this->maqdesh->ViewValue = NULL;
		}
		$this->maqdesh->ViewCustomAttributes = "";

		// maqcale
		if (strval($this->maqcale->CurrentValue) <> "") {
			$this->maqcale->ViewValue = $this->maqcale->OptionCaption($this->maqcale->CurrentValue);
		} else {
			$this->maqcale->ViewValue = NULL;
		}
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
		$this->fechaini->ViewValue = ew_FormatDateTime($this->fechaini->ViewValue, 7);
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// fecha
			$this->fecha->EditAttrs["class"] = "form-control";
			$this->fecha->EditCustomAttributes = "";
			$this->fecha->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fecha->AdvancedSearch->SearchValue, 7), 7));
			$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

			// hora
			$this->hora->EditAttrs["class"] = "form-control";
			$this->hora->EditCustomAttributes = "";
			$this->hora->EditValue = ew_HtmlEncode($this->hora->AdvancedSearch->SearchValue);
			$this->hora->PlaceHolder = ew_RemoveHtml($this->hora->FldCaption());

			// temp
			$this->temp->EditAttrs["class"] = "form-control";
			$this->temp->EditCustomAttributes = "";
			$this->temp->EditValue = ew_HtmlEncode($this->temp->AdvancedSearch->SearchValue);
			$this->temp->PlaceHolder = ew_RemoveHtml($this->temp->FldCaption());

			// hum
			$this->hum->EditAttrs["class"] = "form-control";
			$this->hum->EditCustomAttributes = "";
			$this->hum->EditValue = ew_HtmlEncode($this->hum->AdvancedSearch->SearchValue);
			$this->hum->PlaceHolder = ew_RemoveHtml($this->hum->FldCaption());

			// co2ppm
			$this->co2ppm->EditAttrs["class"] = "form-control";
			$this->co2ppm->EditCustomAttributes = "";
			$this->co2ppm->EditValue = ew_HtmlEncode($this->co2ppm->AdvancedSearch->SearchValue);
			$this->co2ppm->PlaceHolder = ew_RemoveHtml($this->co2ppm->FldCaption());

			// higromet
			$this->higromet->EditAttrs["class"] = "form-control";
			$this->higromet->EditCustomAttributes = "";
			$this->higromet->EditValue = ew_HtmlEncode($this->higromet->AdvancedSearch->SearchValue);
			$this->higromet->PlaceHolder = ew_RemoveHtml($this->higromet->FldCaption());

			// luz
			$this->luz->EditCustomAttributes = "";
			$this->luz->EditValue = $this->luz->Options(TRUE);

			// maqhum
			$this->maqhum->EditCustomAttributes = "";
			$this->maqhum->EditValue = $this->maqhum->Options(TRUE);

			// maqdesh
			$this->maqdesh->EditCustomAttributes = "";
			$this->maqdesh->EditValue = $this->maqdesh->Options(TRUE);

			// maqcale
			$this->maqcale->EditCustomAttributes = "";
			$this->maqcale->EditValue = $this->maqcale->Options(TRUE);

			// modman
			$this->modman->EditAttrs["class"] = "form-control";
			$this->modman->EditCustomAttributes = "";
			$this->modman->EditValue = ew_HtmlEncode($this->modman->AdvancedSearch->SearchValue);
			$this->modman->PlaceHolder = ew_RemoveHtml($this->modman->FldCaption());

			// periodo
			$this->periodo->EditAttrs["class"] = "form-control";
			$this->periodo->EditCustomAttributes = "";
			$this->periodo->EditValue = ew_HtmlEncode($this->periodo->AdvancedSearch->SearchValue);
			$this->periodo->PlaceHolder = ew_RemoveHtml($this->periodo->FldCaption());

			// horasluz
			$this->horasluz->EditAttrs["class"] = "form-control";
			$this->horasluz->EditCustomAttributes = "";
			$this->horasluz->EditValue = ew_HtmlEncode($this->horasluz->AdvancedSearch->SearchValue);
			$this->horasluz->PlaceHolder = ew_RemoveHtml($this->horasluz->FldCaption());

			// fechaini
			$this->fechaini->EditAttrs["class"] = "form-control";
			$this->fechaini->EditCustomAttributes = "";
			$this->fechaini->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->fechaini->AdvancedSearch->SearchValue, 7), 7));
			$this->fechaini->PlaceHolder = ew_RemoveHtml($this->fechaini->FldCaption());
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fecha->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fecha->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->hora->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->hora->FldErrMsg());
		}
		if (!ew_CheckNumber($this->temp->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->temp->FldErrMsg());
		}
		if (!ew_CheckNumber($this->hum->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->hum->FldErrMsg());
		}
		if (!ew_CheckNumber($this->co2ppm->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->co2ppm->FldErrMsg());
		}
		if (!ew_CheckNumber($this->higromet->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->higromet->FldErrMsg());
		}
		if (!ew_CheckInteger($this->modman->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->modman->FldErrMsg());
		}
		if (!ew_CheckInteger($this->periodo->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->periodo->FldErrMsg());
		}
		if (!ew_CheckInteger($this->horasluz->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->horasluz->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->fechaini->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->fechaini->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->fecha->AdvancedSearch->Load();
		$this->hora->AdvancedSearch->Load();
		$this->temp->AdvancedSearch->Load();
		$this->hum->AdvancedSearch->Load();
		$this->co2ppm->AdvancedSearch->Load();
		$this->higromet->AdvancedSearch->Load();
		$this->luz->AdvancedSearch->Load();
		$this->maqhum->AdvancedSearch->Load();
		$this->maqdesh->AdvancedSearch->Load();
		$this->maqcale->AdvancedSearch->Load();
		$this->modman->AdvancedSearch->Load();
		$this->periodo->AdvancedSearch->Load();
		$this->horasluz->AdvancedSearch->Load();
		$this->fechaini->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "view1list.php", "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($view1_search)) $view1_search = new cview1_search();

// Page init
$view1_search->Page_Init();

// Page main
$view1_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($view1_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fview1search = new ew_Form("fview1search", "search");
<?php } else { ?>
var CurrentForm = fview1search = new ew_Form("fview1search", "search");
<?php } ?>

// Form_CustomValidate event
fview1search.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1search.ValidateRequired = true;
<?php } else { ?>
fview1search.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview1search.Lists["x_luz"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1search.Lists["x_luz"].Options = <?php echo json_encode($view1->luz->Options()) ?>;
fview1search.Lists["x_maqhum"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1search.Lists["x_maqhum"].Options = <?php echo json_encode($view1->maqhum->Options()) ?>;
fview1search.Lists["x_maqdesh"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1search.Lists["x_maqdesh"].Options = <?php echo json_encode($view1->maqdesh->Options()) ?>;
fview1search.Lists["x_maqcale"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1search.Lists["x_maqcale"].Options = <?php echo json_encode($view1->maqcale->Options()) ?>;

// Form object for search
// Validate function for search

fview1search.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fecha");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->fecha->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_hora");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->hora->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_temp");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->temp->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_hum");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->hum->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_co2ppm");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->co2ppm->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_higromet");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->higromet->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_modman");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->modman->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_periodo");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->periodo->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_horasluz");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->horasluz->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_fechaini");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($view1->fechaini->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$view1_search->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $view1_search->ShowPageHeader(); ?>
<?php
$view1_search->ShowMessage();
?>
<form name="fview1search" id="fview1search" class="<?php echo $view1_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view1_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1">
<input type="hidden" name="a_search" id="a_search" value="S">
<?php if ($view1_search->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($view1->id->Visible) { // id ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_id"><?php echo $view1->id->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->id->CellAttributes() ?>>
			<span id="el_view1_id">
<input type="text" data-table="view1" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($view1->id->getPlaceHolder()) ?>" value="<?php echo $view1->id->EditValue ?>"<?php echo $view1->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->fecha->Visible) { // fecha ?>
	<div id="r_fecha" class="form-group">
		<label for="x_fecha" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_fecha"><?php echo $view1->fecha->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fecha" id="z_fecha" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->fecha->CellAttributes() ?>>
			<span id="el_view1_fecha">
<input type="text" data-table="view1" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" placeholder="<?php echo ew_HtmlEncode($view1->fecha->getPlaceHolder()) ?>" value="<?php echo $view1->fecha->EditValue ?>"<?php echo $view1->fecha->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->hora->Visible) { // hora ?>
	<div id="r_hora" class="form-group">
		<label for="x_hora" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_hora"><?php echo $view1->hora->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_hora" id="z_hora" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->hora->CellAttributes() ?>>
			<span id="el_view1_hora">
<input type="text" data-table="view1" data-field="x_hora" name="x_hora" id="x_hora" size="30" placeholder="<?php echo ew_HtmlEncode($view1->hora->getPlaceHolder()) ?>" value="<?php echo $view1->hora->EditValue ?>"<?php echo $view1->hora->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->temp->Visible) { // temp ?>
	<div id="r_temp" class="form-group">
		<label for="x_temp" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_temp"><?php echo $view1->temp->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_temp" id="z_temp" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->temp->CellAttributes() ?>>
			<span id="el_view1_temp">
<input type="text" data-table="view1" data-field="x_temp" name="x_temp" id="x_temp" size="30" placeholder="<?php echo ew_HtmlEncode($view1->temp->getPlaceHolder()) ?>" value="<?php echo $view1->temp->EditValue ?>"<?php echo $view1->temp->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->hum->Visible) { // hum ?>
	<div id="r_hum" class="form-group">
		<label for="x_hum" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_hum"><?php echo $view1->hum->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_hum" id="z_hum" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->hum->CellAttributes() ?>>
			<span id="el_view1_hum">
<input type="text" data-table="view1" data-field="x_hum" name="x_hum" id="x_hum" size="30" placeholder="<?php echo ew_HtmlEncode($view1->hum->getPlaceHolder()) ?>" value="<?php echo $view1->hum->EditValue ?>"<?php echo $view1->hum->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->co2ppm->Visible) { // co2ppm ?>
	<div id="r_co2ppm" class="form-group">
		<label for="x_co2ppm" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_co2ppm"><?php echo $view1->co2ppm->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_co2ppm" id="z_co2ppm" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->co2ppm->CellAttributes() ?>>
			<span id="el_view1_co2ppm">
<input type="text" data-table="view1" data-field="x_co2ppm" name="x_co2ppm" id="x_co2ppm" size="30" placeholder="<?php echo ew_HtmlEncode($view1->co2ppm->getPlaceHolder()) ?>" value="<?php echo $view1->co2ppm->EditValue ?>"<?php echo $view1->co2ppm->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->higromet->Visible) { // higromet ?>
	<div id="r_higromet" class="form-group">
		<label for="x_higromet" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_higromet"><?php echo $view1->higromet->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_higromet" id="z_higromet" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->higromet->CellAttributes() ?>>
			<span id="el_view1_higromet">
<input type="text" data-table="view1" data-field="x_higromet" name="x_higromet" id="x_higromet" size="30" placeholder="<?php echo ew_HtmlEncode($view1->higromet->getPlaceHolder()) ?>" value="<?php echo $view1->higromet->EditValue ?>"<?php echo $view1->higromet->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->luz->Visible) { // luz ?>
	<div id="r_luz" class="form-group">
		<label for="x_luz" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_luz"><?php echo $view1->luz->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_luz" id="z_luz" value="LIKE"></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->luz->CellAttributes() ?>>
			<span id="el_view1_luz">
<div class="ewDropdownList has-feedback">
	<span class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $view1->luz->AdvancedSearch->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_luz" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $view1->luz->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view1->luz->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<input type="radio" data-table="view1" data-field="x_luz" name="x_luz" id="x_luz_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view1->luz->EditAttributes() ?>><?php echo $view1->luz->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
	}
	if ($emptywrk && strval($view1->luz->CurrentValue) <> "") {
?>
<input type="radio" data-table="view1" data-field="x_luz" name="x_luz" id="x_luz_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($view1->luz->CurrentValue) ?>" checked<?php echo $view1->luz->EditAttributes() ?>><?php echo $view1->luz->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_luz" class="ewTemplate"><input type="radio" data-table="view1" data-field="x_luz" data-value-separator="<?php echo ew_HtmlEncode(is_array($view1->luz->DisplayValueSeparator) ? json_encode($view1->luz->DisplayValueSeparator) : $view1->luz->DisplayValueSeparator) ?>" name="x_luz" id="x_luz" value="{value}"<?php echo $view1->luz->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->maqhum->Visible) { // maqhum ?>
	<div id="r_maqhum" class="form-group">
		<label for="x_maqhum" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_maqhum"><?php echo $view1->maqhum->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maqhum" id="z_maqhum" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->maqhum->CellAttributes() ?>>
			<span id="el_view1_maqhum">
<div class="ewDropdownList has-feedback">
	<span class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $view1->maqhum->AdvancedSearch->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_maqhum" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $view1->maqhum->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view1->maqhum->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<input type="radio" data-table="view1" data-field="x_maqhum" name="x_maqhum" id="x_maqhum_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view1->maqhum->EditAttributes() ?>><?php echo $view1->maqhum->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
	}
	if ($emptywrk && strval($view1->maqhum->CurrentValue) <> "") {
?>
<input type="radio" data-table="view1" data-field="x_maqhum" name="x_maqhum" id="x_maqhum_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($view1->maqhum->CurrentValue) ?>" checked<?php echo $view1->maqhum->EditAttributes() ?>><?php echo $view1->maqhum->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_maqhum" class="ewTemplate"><input type="radio" data-table="view1" data-field="x_maqhum" data-value-separator="<?php echo ew_HtmlEncode(is_array($view1->maqhum->DisplayValueSeparator) ? json_encode($view1->maqhum->DisplayValueSeparator) : $view1->maqhum->DisplayValueSeparator) ?>" name="x_maqhum" id="x_maqhum" value="{value}"<?php echo $view1->maqhum->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->maqdesh->Visible) { // maqdesh ?>
	<div id="r_maqdesh" class="form-group">
		<label for="x_maqdesh" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_maqdesh"><?php echo $view1->maqdesh->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maqdesh" id="z_maqdesh" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->maqdesh->CellAttributes() ?>>
			<span id="el_view1_maqdesh">
<div class="ewDropdownList has-feedback">
	<span class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $view1->maqdesh->AdvancedSearch->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_maqdesh" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $view1->maqdesh->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view1->maqdesh->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<input type="radio" data-table="view1" data-field="x_maqdesh" name="x_maqdesh" id="x_maqdesh_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view1->maqdesh->EditAttributes() ?>><?php echo $view1->maqdesh->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
	}
	if ($emptywrk && strval($view1->maqdesh->CurrentValue) <> "") {
?>
<input type="radio" data-table="view1" data-field="x_maqdesh" name="x_maqdesh" id="x_maqdesh_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($view1->maqdesh->CurrentValue) ?>" checked<?php echo $view1->maqdesh->EditAttributes() ?>><?php echo $view1->maqdesh->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_maqdesh" class="ewTemplate"><input type="radio" data-table="view1" data-field="x_maqdesh" data-value-separator="<?php echo ew_HtmlEncode(is_array($view1->maqdesh->DisplayValueSeparator) ? json_encode($view1->maqdesh->DisplayValueSeparator) : $view1->maqdesh->DisplayValueSeparator) ?>" name="x_maqdesh" id="x_maqdesh" value="{value}"<?php echo $view1->maqdesh->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->maqcale->Visible) { // maqcale ?>
	<div id="r_maqcale" class="form-group">
		<label for="x_maqcale" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_maqcale"><?php echo $view1->maqcale->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_maqcale" id="z_maqcale" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->maqcale->CellAttributes() ?>>
			<span id="el_view1_maqcale">
<div class="ewDropdownList has-feedback">
	<span class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $view1->maqcale->AdvancedSearch->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_maqcale" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php
$arwrk = $view1->maqcale->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($view1->maqcale->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked" : "";
		if ($selwrk <> "")
			$emptywrk = FALSE;
?>
<input type="radio" data-table="view1" data-field="x_maqcale" name="x_maqcale" id="x_maqcale_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $view1->maqcale->EditAttributes() ?>><?php echo $view1->maqcale->DisplayValue($arwrk[$rowcntwrk]) ?>
<?php
	}
	if ($emptywrk && strval($view1->maqcale->CurrentValue) <> "") {
?>
<input type="radio" data-table="view1" data-field="x_maqcale" name="x_maqcale" id="x_maqcale_<?php echo $rowswrk ?>" value="<?php echo ew_HtmlEncode($view1->maqcale->CurrentValue) ?>" checked<?php echo $view1->maqcale->EditAttributes() ?>><?php echo $view1->maqcale->CurrentValue ?>
<?php
    }
}
?>
		</div>
	</div>
	<div id="tp_x_maqcale" class="ewTemplate"><input type="radio" data-table="view1" data-field="x_maqcale" data-value-separator="<?php echo ew_HtmlEncode(is_array($view1->maqcale->DisplayValueSeparator) ? json_encode($view1->maqcale->DisplayValueSeparator) : $view1->maqcale->DisplayValueSeparator) ?>" name="x_maqcale" id="x_maqcale" value="{value}"<?php echo $view1->maqcale->EditAttributes() ?>></div>
</div>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->modman->Visible) { // modman ?>
	<div id="r_modman" class="form-group">
		<label for="x_modman" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_modman"><?php echo $view1->modman->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_modman" id="z_modman" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->modman->CellAttributes() ?>>
			<span id="el_view1_modman">
<input type="text" data-table="view1" data-field="x_modman" name="x_modman" id="x_modman" size="30" placeholder="<?php echo ew_HtmlEncode($view1->modman->getPlaceHolder()) ?>" value="<?php echo $view1->modman->EditValue ?>"<?php echo $view1->modman->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->periodo->Visible) { // periodo ?>
	<div id="r_periodo" class="form-group">
		<label for="x_periodo" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_periodo"><?php echo $view1->periodo->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_periodo" id="z_periodo" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->periodo->CellAttributes() ?>>
			<span id="el_view1_periodo">
<input type="text" data-table="view1" data-field="x_periodo" name="x_periodo" id="x_periodo" size="30" placeholder="<?php echo ew_HtmlEncode($view1->periodo->getPlaceHolder()) ?>" value="<?php echo $view1->periodo->EditValue ?>"<?php echo $view1->periodo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->horasluz->Visible) { // horasluz ?>
	<div id="r_horasluz" class="form-group">
		<label for="x_horasluz" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_horasluz"><?php echo $view1->horasluz->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_horasluz" id="z_horasluz" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->horasluz->CellAttributes() ?>>
			<span id="el_view1_horasluz">
<input type="text" data-table="view1" data-field="x_horasluz" name="x_horasluz" id="x_horasluz" size="30" placeholder="<?php echo ew_HtmlEncode($view1->horasluz->getPlaceHolder()) ?>" value="<?php echo $view1->horasluz->EditValue ?>"<?php echo $view1->horasluz->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
<?php if ($view1->fechaini->Visible) { // fechaini ?>
	<div id="r_fechaini" class="form-group">
		<label for="x_fechaini" class="<?php echo $view1_search->SearchLabelClass ?>"><span id="elh_view1_fechaini"><?php echo $view1->fechaini->FldCaption() ?></span>	
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_fechaini" id="z_fechaini" value="="></p>
		</label>
		<div class="<?php echo $view1_search->SearchRightColumnClass ?>"><div<?php echo $view1->fechaini->CellAttributes() ?>>
			<span id="el_view1_fechaini">
<input type="text" data-table="view1" data-field="x_fechaini" data-format="7" name="x_fechaini" id="x_fechaini" placeholder="<?php echo ew_HtmlEncode($view1->fechaini->getPlaceHolder()) ?>" value="<?php echo $view1->fechaini->EditValue ?>"<?php echo $view1->fechaini->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } ?>
</div>
<?php if (!$view1_search->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fview1search.Init();
</script>
<?php
$view1_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view1_search->Page_Terminate();
?>
