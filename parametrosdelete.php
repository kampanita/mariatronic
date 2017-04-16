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

$parametros_delete = NULL; // Initialize page object first

class cparametros_delete extends cparametros {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{524C14CD-A0E3-4083-AF86-06203077AB82}";

	// Table name
	var $TableName = 'parametros';

	// Page object name
	var $PageObjName = 'parametros_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("parametroslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in parametros class, parametrosinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "parametroslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(TRUE) ?>
<?php

// Create page object
if (!isset($parametros_delete)) $parametros_delete = new cparametros_delete();

// Page init
$parametros_delete->Page_Init();

// Page main
$parametros_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parametros_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fparametrosdelete = new ew_Form("fparametrosdelete", "delete");

// Form_CustomValidate event
fparametrosdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparametrosdelete.ValidateRequired = true;
<?php } else { ?>
fparametrosdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($parametros_delete->Recordset = $parametros_delete->LoadRecordset())
	$parametros_deleteTotalRecs = $parametros_delete->Recordset->RecordCount(); // Get record count
if ($parametros_deleteTotalRecs <= 0) { // No record found, exit
	if ($parametros_delete->Recordset)
		$parametros_delete->Recordset->Close();
	$parametros_delete->Page_Terminate("parametroslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $parametros_delete->ShowPageHeader(); ?>
<?php
$parametros_delete->ShowMessage();
?>
<form name="fparametrosdelete" id="fparametrosdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parametros_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parametros_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parametros">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($parametros_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $parametros->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($parametros->id->Visible) { // id ?>
		<th><span id="elh_parametros_id" class="parametros_id"><?php echo $parametros->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->temp_min->Visible) { // temp_min ?>
		<th><span id="elh_parametros_temp_min" class="parametros_temp_min"><?php echo $parametros->temp_min->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->temp_max->Visible) { // temp_max ?>
		<th><span id="elh_parametros_temp_max" class="parametros_temp_max"><?php echo $parametros->temp_max->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->co_min->Visible) { // co_min ?>
		<th><span id="elh_parametros_co_min" class="parametros_co_min"><?php echo $parametros->co_min->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->co_max->Visible) { // co_max ?>
		<th><span id="elh_parametros_co_max" class="parametros_co_max"><?php echo $parametros->co_max->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
		<th><span id="elh_parametros_horas_crecimiento" class="parametros_horas_crecimiento"><?php echo $parametros->horas_crecimiento->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
		<th><span id="elh_parametros_horas_floracion" class="parametros_horas_floracion"><?php echo $parametros->horas_floracion->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->hum_min->Visible) { // hum_min ?>
		<th><span id="elh_parametros_hum_min" class="parametros_hum_min"><?php echo $parametros->hum_min->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->hum_max->Visible) { // hum_max ?>
		<th><span id="elh_parametros_hum_max" class="parametros_hum_max"><?php echo $parametros->hum_max->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
		<th><span id="elh_parametros_DnsHost" class="parametros_DnsHost"><?php echo $parametros->DnsHost->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
		<th><span id="elh_parametros_DnsUser" class="parametros_DnsUser"><?php echo $parametros->DnsUser->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
		<th><span id="elh_parametros_DnsPasswd" class="parametros_DnsPasswd"><?php echo $parametros->DnsPasswd->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
		<th><span id="elh_parametros_DnsUrl_Update" class="parametros_DnsUrl_Update"><?php echo $parametros->DnsUrl_Update->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
		<th><span id="elh_parametros_WifiSSID" class="parametros_WifiSSID"><?php echo $parametros->WifiSSID->FldCaption() ?></span></th>
<?php } ?>
<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
		<th><span id="elh_parametros_WifiPasswd" class="parametros_WifiPasswd"><?php echo $parametros->WifiPasswd->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$parametros_delete->RecCnt = 0;
$i = 0;
while (!$parametros_delete->Recordset->EOF) {
	$parametros_delete->RecCnt++;
	$parametros_delete->RowCnt++;

	// Set row properties
	$parametros->ResetAttrs();
	$parametros->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$parametros_delete->LoadRowValues($parametros_delete->Recordset);

	// Render row
	$parametros_delete->RenderRow();
?>
	<tr<?php echo $parametros->RowAttributes() ?>>
<?php if ($parametros->id->Visible) { // id ?>
		<td<?php echo $parametros->id->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_id" class="parametros_id">
<span<?php echo $parametros->id->ViewAttributes() ?>>
<?php echo $parametros->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->temp_min->Visible) { // temp_min ?>
		<td<?php echo $parametros->temp_min->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_temp_min" class="parametros_temp_min">
<span<?php echo $parametros->temp_min->ViewAttributes() ?>>
<?php echo $parametros->temp_min->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->temp_max->Visible) { // temp_max ?>
		<td<?php echo $parametros->temp_max->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_temp_max" class="parametros_temp_max">
<span<?php echo $parametros->temp_max->ViewAttributes() ?>>
<?php echo $parametros->temp_max->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->co_min->Visible) { // co_min ?>
		<td<?php echo $parametros->co_min->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_co_min" class="parametros_co_min">
<span<?php echo $parametros->co_min->ViewAttributes() ?>>
<?php echo $parametros->co_min->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->co_max->Visible) { // co_max ?>
		<td<?php echo $parametros->co_max->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_co_max" class="parametros_co_max">
<span<?php echo $parametros->co_max->ViewAttributes() ?>>
<?php echo $parametros->co_max->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
		<td<?php echo $parametros->horas_crecimiento->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_horas_crecimiento" class="parametros_horas_crecimiento">
<span<?php echo $parametros->horas_crecimiento->ViewAttributes() ?>>
<?php echo $parametros->horas_crecimiento->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
		<td<?php echo $parametros->horas_floracion->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_horas_floracion" class="parametros_horas_floracion">
<span<?php echo $parametros->horas_floracion->ViewAttributes() ?>>
<?php echo $parametros->horas_floracion->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->hum_min->Visible) { // hum_min ?>
		<td<?php echo $parametros->hum_min->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_hum_min" class="parametros_hum_min">
<span<?php echo $parametros->hum_min->ViewAttributes() ?>>
<?php echo $parametros->hum_min->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->hum_max->Visible) { // hum_max ?>
		<td<?php echo $parametros->hum_max->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_hum_max" class="parametros_hum_max">
<span<?php echo $parametros->hum_max->ViewAttributes() ?>>
<?php echo $parametros->hum_max->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
		<td<?php echo $parametros->DnsHost->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_DnsHost" class="parametros_DnsHost">
<span<?php echo $parametros->DnsHost->ViewAttributes() ?>>
<?php echo $parametros->DnsHost->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
		<td<?php echo $parametros->DnsUser->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_DnsUser" class="parametros_DnsUser">
<span<?php echo $parametros->DnsUser->ViewAttributes() ?>>
<?php echo $parametros->DnsUser->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
		<td<?php echo $parametros->DnsPasswd->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_DnsPasswd" class="parametros_DnsPasswd">
<span<?php echo $parametros->DnsPasswd->ViewAttributes() ?>>
<?php echo $parametros->DnsPasswd->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
		<td<?php echo $parametros->DnsUrl_Update->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_DnsUrl_Update" class="parametros_DnsUrl_Update">
<span<?php echo $parametros->DnsUrl_Update->ViewAttributes() ?>>
<?php echo $parametros->DnsUrl_Update->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
		<td<?php echo $parametros->WifiSSID->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_WifiSSID" class="parametros_WifiSSID">
<span<?php echo $parametros->WifiSSID->ViewAttributes() ?>>
<?php echo $parametros->WifiSSID->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
		<td<?php echo $parametros->WifiPasswd->CellAttributes() ?>>
<span id="el<?php echo $parametros_delete->RowCnt ?>_parametros_WifiPasswd" class="parametros_WifiPasswd">
<span<?php echo $parametros->WifiPasswd->ViewAttributes() ?>>
<?php echo $parametros->WifiPasswd->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$parametros_delete->Recordset->MoveNext();
}
$parametros_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $parametros_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fparametrosdelete.Init();
</script>
<?php
$parametros_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parametros_delete->Page_Terminate();
?>
