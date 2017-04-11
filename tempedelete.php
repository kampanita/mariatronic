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

$tempe_delete = NULL; // Initialize page object first

class ctempe_delete extends ctempe {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{032690A3-4B26-49FF-B1A0-E08477B5B2A3}";

	// Table name
	var $TableName = 'tempe';

	// Page object name
	var $PageObjName = 'tempe_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
			$this->Page_Terminate("tempelist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tempe class, tempeinfo.php

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
		$Breadcrumb->Add("list", $this->TableVar, "tempelist.php", "", $this->TableVar, TRUE);
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
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tempe_delete)) $tempe_delete = new ctempe_delete();

// Page init
$tempe_delete->Page_Init();

// Page main
$tempe_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tempe_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ftempedelete = new ew_Form("ftempedelete", "delete");

// Form_CustomValidate event
ftempedelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftempedelete.ValidateRequired = true;
<?php } else { ?>
ftempedelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tempe_delete->Recordset = $tempe_delete->LoadRecordset())
	$tempe_deleteTotalRecs = $tempe_delete->Recordset->RecordCount(); // Get record count
if ($tempe_deleteTotalRecs <= 0) { // No record found, exit
	if ($tempe_delete->Recordset)
		$tempe_delete->Recordset->Close();
	$tempe_delete->Page_Terminate("tempelist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $tempe_delete->ShowPageHeader(); ?>
<?php
$tempe_delete->ShowMessage();
?>
<form name="ftempedelete" id="ftempedelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($tempe_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $tempe_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="tempe">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tempe_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $tempe->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($tempe->id->Visible) { // id ?>
		<th><span id="elh_tempe_id" class="tempe_id"><?php echo $tempe->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->fecha->Visible) { // fecha ?>
		<th><span id="elh_tempe_fecha" class="tempe_fecha"><?php echo $tempe->fecha->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->hora->Visible) { // hora ?>
		<th><span id="elh_tempe_hora" class="tempe_hora"><?php echo $tempe->hora->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->temp->Visible) { // temp ?>
		<th><span id="elh_tempe_temp" class="tempe_temp"><?php echo $tempe->temp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->hum->Visible) { // hum ?>
		<th><span id="elh_tempe_hum" class="tempe_hum"><?php echo $tempe->hum->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->co2ppm->Visible) { // co2ppm ?>
		<th><span id="elh_tempe_co2ppm" class="tempe_co2ppm"><?php echo $tempe->co2ppm->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->higromet->Visible) { // higromet ?>
		<th><span id="elh_tempe_higromet" class="tempe_higromet"><?php echo $tempe->higromet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->luz->Visible) { // luz ?>
		<th><span id="elh_tempe_luz" class="tempe_luz"><?php echo $tempe->luz->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->maqhum->Visible) { // maqhum ?>
		<th><span id="elh_tempe_maqhum" class="tempe_maqhum"><?php echo $tempe->maqhum->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->maqdesh->Visible) { // maqdesh ?>
		<th><span id="elh_tempe_maqdesh" class="tempe_maqdesh"><?php echo $tempe->maqdesh->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->maqcale->Visible) { // maqcale ?>
		<th><span id="elh_tempe_maqcale" class="tempe_maqcale"><?php echo $tempe->maqcale->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->modman->Visible) { // modman ?>
		<th><span id="elh_tempe_modman" class="tempe_modman"><?php echo $tempe->modman->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->periodo->Visible) { // periodo ?>
		<th><span id="elh_tempe_periodo" class="tempe_periodo"><?php echo $tempe->periodo->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->horasluz->Visible) { // horasluz ?>
		<th><span id="elh_tempe_horasluz" class="tempe_horasluz"><?php echo $tempe->horasluz->FldCaption() ?></span></th>
<?php } ?>
<?php if ($tempe->fechaini->Visible) { // fechaini ?>
		<th><span id="elh_tempe_fechaini" class="tempe_fechaini"><?php echo $tempe->fechaini->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$tempe_delete->RecCnt = 0;
$i = 0;
while (!$tempe_delete->Recordset->EOF) {
	$tempe_delete->RecCnt++;
	$tempe_delete->RowCnt++;

	// Set row properties
	$tempe->ResetAttrs();
	$tempe->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tempe_delete->LoadRowValues($tempe_delete->Recordset);

	// Render row
	$tempe_delete->RenderRow();
?>
	<tr<?php echo $tempe->RowAttributes() ?>>
<?php if ($tempe->id->Visible) { // id ?>
		<td<?php echo $tempe->id->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_id" class="tempe_id">
<span<?php echo $tempe->id->ViewAttributes() ?>>
<?php echo $tempe->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->fecha->Visible) { // fecha ?>
		<td<?php echo $tempe->fecha->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_fecha" class="tempe_fecha">
<span<?php echo $tempe->fecha->ViewAttributes() ?>>
<?php echo $tempe->fecha->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->hora->Visible) { // hora ?>
		<td<?php echo $tempe->hora->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_hora" class="tempe_hora">
<span<?php echo $tempe->hora->ViewAttributes() ?>>
<?php echo $tempe->hora->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->temp->Visible) { // temp ?>
		<td<?php echo $tempe->temp->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_temp" class="tempe_temp">
<span<?php echo $tempe->temp->ViewAttributes() ?>>
<?php echo $tempe->temp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->hum->Visible) { // hum ?>
		<td<?php echo $tempe->hum->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_hum" class="tempe_hum">
<span<?php echo $tempe->hum->ViewAttributes() ?>>
<?php echo $tempe->hum->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->co2ppm->Visible) { // co2ppm ?>
		<td<?php echo $tempe->co2ppm->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_co2ppm" class="tempe_co2ppm">
<span<?php echo $tempe->co2ppm->ViewAttributes() ?>>
<?php echo $tempe->co2ppm->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->higromet->Visible) { // higromet ?>
		<td<?php echo $tempe->higromet->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_higromet" class="tempe_higromet">
<span<?php echo $tempe->higromet->ViewAttributes() ?>>
<?php echo $tempe->higromet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->luz->Visible) { // luz ?>
		<td<?php echo $tempe->luz->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_luz" class="tempe_luz">
<span<?php echo $tempe->luz->ViewAttributes() ?>>
<?php echo $tempe->luz->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->maqhum->Visible) { // maqhum ?>
		<td<?php echo $tempe->maqhum->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_maqhum" class="tempe_maqhum">
<span<?php echo $tempe->maqhum->ViewAttributes() ?>>
<?php echo $tempe->maqhum->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->maqdesh->Visible) { // maqdesh ?>
		<td<?php echo $tempe->maqdesh->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_maqdesh" class="tempe_maqdesh">
<span<?php echo $tempe->maqdesh->ViewAttributes() ?>>
<?php echo $tempe->maqdesh->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->maqcale->Visible) { // maqcale ?>
		<td<?php echo $tempe->maqcale->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_maqcale" class="tempe_maqcale">
<span<?php echo $tempe->maqcale->ViewAttributes() ?>>
<?php echo $tempe->maqcale->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->modman->Visible) { // modman ?>
		<td<?php echo $tempe->modman->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_modman" class="tempe_modman">
<span<?php echo $tempe->modman->ViewAttributes() ?>>
<?php echo $tempe->modman->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->periodo->Visible) { // periodo ?>
		<td<?php echo $tempe->periodo->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_periodo" class="tempe_periodo">
<span<?php echo $tempe->periodo->ViewAttributes() ?>>
<?php echo $tempe->periodo->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->horasluz->Visible) { // horasluz ?>
		<td<?php echo $tempe->horasluz->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_horasluz" class="tempe_horasluz">
<span<?php echo $tempe->horasluz->ViewAttributes() ?>>
<?php echo $tempe->horasluz->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($tempe->fechaini->Visible) { // fechaini ?>
		<td<?php echo $tempe->fechaini->CellAttributes() ?>>
<span id="el<?php echo $tempe_delete->RowCnt ?>_tempe_fechaini" class="tempe_fechaini">
<span<?php echo $tempe->fechaini->ViewAttributes() ?>>
<?php echo $tempe->fechaini->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$tempe_delete->Recordset->MoveNext();
}
$tempe_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $tempe_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ftempedelete.Init();
</script>
<?php
$tempe_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tempe_delete->Page_Terminate();
?>
