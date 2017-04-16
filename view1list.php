<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "view1info.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$view1_list = NULL; // Initialize page object first

class cview1_list extends cview1 {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{524C14CD-A0E3-4083-AF86-06203077AB82}";

	// Table name
	var $TableName = 'view1';

	// Page object name
	var $PageObjName = 'view1_list';

	// Grid form hidden field names
	var $FormName = 'fview1list';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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
		global $UserTable, $UserTableConn;
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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view1add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view1delete.php";
		$this->MultiUpdateUrl = "view1update.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuarios)
		if (!isset($UserTable)) {
			$UserTable = new cusuarios();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fview1listsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->fecha->AdvancedSearch->ToJSON(), ","); // Field fecha
		$sFilterList = ew_Concat($sFilterList, $this->hora->AdvancedSearch->ToJSON(), ","); // Field hora
		$sFilterList = ew_Concat($sFilterList, $this->temp->AdvancedSearch->ToJSON(), ","); // Field temp
		$sFilterList = ew_Concat($sFilterList, $this->hum->AdvancedSearch->ToJSON(), ","); // Field hum
		$sFilterList = ew_Concat($sFilterList, $this->co2ppm->AdvancedSearch->ToJSON(), ","); // Field co2ppm
		$sFilterList = ew_Concat($sFilterList, $this->higromet->AdvancedSearch->ToJSON(), ","); // Field higromet
		$sFilterList = ew_Concat($sFilterList, $this->luz->AdvancedSearch->ToJSON(), ","); // Field luz
		$sFilterList = ew_Concat($sFilterList, $this->maqhum->AdvancedSearch->ToJSON(), ","); // Field maqhum
		$sFilterList = ew_Concat($sFilterList, $this->maqdesh->AdvancedSearch->ToJSON(), ","); // Field maqdesh
		$sFilterList = ew_Concat($sFilterList, $this->maqcale->AdvancedSearch->ToJSON(), ","); // Field maqcale
		$sFilterList = ew_Concat($sFilterList, $this->modman->AdvancedSearch->ToJSON(), ","); // Field modman
		$sFilterList = ew_Concat($sFilterList, $this->periodo->AdvancedSearch->ToJSON(), ","); // Field periodo
		$sFilterList = ew_Concat($sFilterList, $this->horasluz->AdvancedSearch->ToJSON(), ","); // Field horasluz
		$sFilterList = ew_Concat($sFilterList, $this->fechaini->AdvancedSearch->ToJSON(), ","); // Field fechaini
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field fecha
		$this->fecha->AdvancedSearch->SearchValue = @$filter["x_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator = @$filter["z_fecha"];
		$this->fecha->AdvancedSearch->SearchCondition = @$filter["v_fecha"];
		$this->fecha->AdvancedSearch->SearchValue2 = @$filter["y_fecha"];
		$this->fecha->AdvancedSearch->SearchOperator2 = @$filter["w_fecha"];
		$this->fecha->AdvancedSearch->Save();

		// Field hora
		$this->hora->AdvancedSearch->SearchValue = @$filter["x_hora"];
		$this->hora->AdvancedSearch->SearchOperator = @$filter["z_hora"];
		$this->hora->AdvancedSearch->SearchCondition = @$filter["v_hora"];
		$this->hora->AdvancedSearch->SearchValue2 = @$filter["y_hora"];
		$this->hora->AdvancedSearch->SearchOperator2 = @$filter["w_hora"];
		$this->hora->AdvancedSearch->Save();

		// Field temp
		$this->temp->AdvancedSearch->SearchValue = @$filter["x_temp"];
		$this->temp->AdvancedSearch->SearchOperator = @$filter["z_temp"];
		$this->temp->AdvancedSearch->SearchCondition = @$filter["v_temp"];
		$this->temp->AdvancedSearch->SearchValue2 = @$filter["y_temp"];
		$this->temp->AdvancedSearch->SearchOperator2 = @$filter["w_temp"];
		$this->temp->AdvancedSearch->Save();

		// Field hum
		$this->hum->AdvancedSearch->SearchValue = @$filter["x_hum"];
		$this->hum->AdvancedSearch->SearchOperator = @$filter["z_hum"];
		$this->hum->AdvancedSearch->SearchCondition = @$filter["v_hum"];
		$this->hum->AdvancedSearch->SearchValue2 = @$filter["y_hum"];
		$this->hum->AdvancedSearch->SearchOperator2 = @$filter["w_hum"];
		$this->hum->AdvancedSearch->Save();

		// Field co2ppm
		$this->co2ppm->AdvancedSearch->SearchValue = @$filter["x_co2ppm"];
		$this->co2ppm->AdvancedSearch->SearchOperator = @$filter["z_co2ppm"];
		$this->co2ppm->AdvancedSearch->SearchCondition = @$filter["v_co2ppm"];
		$this->co2ppm->AdvancedSearch->SearchValue2 = @$filter["y_co2ppm"];
		$this->co2ppm->AdvancedSearch->SearchOperator2 = @$filter["w_co2ppm"];
		$this->co2ppm->AdvancedSearch->Save();

		// Field higromet
		$this->higromet->AdvancedSearch->SearchValue = @$filter["x_higromet"];
		$this->higromet->AdvancedSearch->SearchOperator = @$filter["z_higromet"];
		$this->higromet->AdvancedSearch->SearchCondition = @$filter["v_higromet"];
		$this->higromet->AdvancedSearch->SearchValue2 = @$filter["y_higromet"];
		$this->higromet->AdvancedSearch->SearchOperator2 = @$filter["w_higromet"];
		$this->higromet->AdvancedSearch->Save();

		// Field luz
		$this->luz->AdvancedSearch->SearchValue = @$filter["x_luz"];
		$this->luz->AdvancedSearch->SearchOperator = @$filter["z_luz"];
		$this->luz->AdvancedSearch->SearchCondition = @$filter["v_luz"];
		$this->luz->AdvancedSearch->SearchValue2 = @$filter["y_luz"];
		$this->luz->AdvancedSearch->SearchOperator2 = @$filter["w_luz"];
		$this->luz->AdvancedSearch->Save();

		// Field maqhum
		$this->maqhum->AdvancedSearch->SearchValue = @$filter["x_maqhum"];
		$this->maqhum->AdvancedSearch->SearchOperator = @$filter["z_maqhum"];
		$this->maqhum->AdvancedSearch->SearchCondition = @$filter["v_maqhum"];
		$this->maqhum->AdvancedSearch->SearchValue2 = @$filter["y_maqhum"];
		$this->maqhum->AdvancedSearch->SearchOperator2 = @$filter["w_maqhum"];
		$this->maqhum->AdvancedSearch->Save();

		// Field maqdesh
		$this->maqdesh->AdvancedSearch->SearchValue = @$filter["x_maqdesh"];
		$this->maqdesh->AdvancedSearch->SearchOperator = @$filter["z_maqdesh"];
		$this->maqdesh->AdvancedSearch->SearchCondition = @$filter["v_maqdesh"];
		$this->maqdesh->AdvancedSearch->SearchValue2 = @$filter["y_maqdesh"];
		$this->maqdesh->AdvancedSearch->SearchOperator2 = @$filter["w_maqdesh"];
		$this->maqdesh->AdvancedSearch->Save();

		// Field maqcale
		$this->maqcale->AdvancedSearch->SearchValue = @$filter["x_maqcale"];
		$this->maqcale->AdvancedSearch->SearchOperator = @$filter["z_maqcale"];
		$this->maqcale->AdvancedSearch->SearchCondition = @$filter["v_maqcale"];
		$this->maqcale->AdvancedSearch->SearchValue2 = @$filter["y_maqcale"];
		$this->maqcale->AdvancedSearch->SearchOperator2 = @$filter["w_maqcale"];
		$this->maqcale->AdvancedSearch->Save();

		// Field modman
		$this->modman->AdvancedSearch->SearchValue = @$filter["x_modman"];
		$this->modman->AdvancedSearch->SearchOperator = @$filter["z_modman"];
		$this->modman->AdvancedSearch->SearchCondition = @$filter["v_modman"];
		$this->modman->AdvancedSearch->SearchValue2 = @$filter["y_modman"];
		$this->modman->AdvancedSearch->SearchOperator2 = @$filter["w_modman"];
		$this->modman->AdvancedSearch->Save();

		// Field periodo
		$this->periodo->AdvancedSearch->SearchValue = @$filter["x_periodo"];
		$this->periodo->AdvancedSearch->SearchOperator = @$filter["z_periodo"];
		$this->periodo->AdvancedSearch->SearchCondition = @$filter["v_periodo"];
		$this->periodo->AdvancedSearch->SearchValue2 = @$filter["y_periodo"];
		$this->periodo->AdvancedSearch->SearchOperator2 = @$filter["w_periodo"];
		$this->periodo->AdvancedSearch->Save();

		// Field horasluz
		$this->horasluz->AdvancedSearch->SearchValue = @$filter["x_horasluz"];
		$this->horasluz->AdvancedSearch->SearchOperator = @$filter["z_horasluz"];
		$this->horasluz->AdvancedSearch->SearchCondition = @$filter["v_horasluz"];
		$this->horasluz->AdvancedSearch->SearchValue2 = @$filter["y_horasluz"];
		$this->horasluz->AdvancedSearch->SearchOperator2 = @$filter["w_horasluz"];
		$this->horasluz->AdvancedSearch->Save();

		// Field fechaini
		$this->fechaini->AdvancedSearch->SearchValue = @$filter["x_fechaini"];
		$this->fechaini->AdvancedSearch->SearchOperator = @$filter["z_fechaini"];
		$this->fechaini->AdvancedSearch->SearchCondition = @$filter["v_fechaini"];
		$this->fechaini->AdvancedSearch->SearchValue2 = @$filter["y_fechaini"];
		$this->fechaini->AdvancedSearch->SearchOperator2 = @$filter["w_fechaini"];
		$this->fechaini->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->luz, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->fecha, $bCtrl); // fecha
			$this->UpdateSort($this->hora, $bCtrl); // hora
			$this->UpdateSort($this->temp, $bCtrl); // temp
			$this->UpdateSort($this->hum, $bCtrl); // hum
			$this->UpdateSort($this->co2ppm, $bCtrl); // co2ppm
			$this->UpdateSort($this->higromet, $bCtrl); // higromet
			$this->UpdateSort($this->luz, $bCtrl); // luz
			$this->UpdateSort($this->maqhum, $bCtrl); // maqhum
			$this->UpdateSort($this->maqdesh, $bCtrl); // maqdesh
			$this->UpdateSort($this->maqcale, $bCtrl); // maqcale
			$this->UpdateSort($this->modman, $bCtrl); // modman
			$this->UpdateSort($this->periodo, $bCtrl); // periodo
			$this->UpdateSort($this->horasluz, $bCtrl); // horasluz
			$this->UpdateSort($this->fechaini, $bCtrl); // fechaini
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->id->setSort("");
				$this->fecha->setSort("");
				$this->hora->setSort("");
				$this->temp->setSort("");
				$this->hum->setSort("");
				$this->co2ppm->setSort("");
				$this->higromet->setSort("");
				$this->luz->setSort("");
				$this->maqhum->setSort("");
				$this->maqdesh->setSort("");
				$this->maqcale->setSort("");
				$this->modman->setSort("");
				$this->periodo->setSort("");
				$this->horasluz->setSort("");
				$this->fechaini->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fview1listsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fview1listsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fview1list}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview1listsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		$this->luz->ViewValue = $this->luz->CurrentValue;
		$this->luz->ViewCustomAttributes = "";

		// maqhum
		$this->maqhum->ViewValue = $this->maqhum->CurrentValue;
		$this->maqhum->ImageAlt = $this->maqhum->FldAlt();
		$this->maqhum->ViewCustomAttributes = "";

		// maqdesh
		$this->maqdesh->ViewValue = $this->maqdesh->CurrentValue;
		$this->maqdesh->ImageAlt = $this->maqdesh->FldAlt();
		$this->maqdesh->ViewCustomAttributes = "";

		// maqcale
		$this->maqcale->ViewValue = $this->maqcale->CurrentValue;
		$this->maqcale->ImageAlt = $this->maqcale->FldAlt();
		$this->maqcale->ViewCustomAttributes = "";

		// modman
		if (strval($this->modman->CurrentValue) <> "") {
			$this->modman->ViewValue = $this->modman->OptionCaption($this->modman->CurrentValue);
		} else {
			$this->modman->ViewValue = NULL;
		}
		$this->modman->ViewCustomAttributes = "";

		// periodo
		if (strval($this->periodo->CurrentValue) <> "") {
			$this->periodo->ViewValue = $this->periodo->OptionCaption($this->periodo->CurrentValue);
		} else {
			$this->periodo->ViewValue = NULL;
		}
		$this->periodo->ViewCustomAttributes = "";

		// horasluz
		if (strval($this->horasluz->CurrentValue) <> "") {
			$this->horasluz->ViewValue = $this->horasluz->OptionCaption($this->horasluz->CurrentValue);
		} else {
			$this->horasluz->ViewValue = NULL;
		}
		$this->horasluz->ViewCustomAttributes = "";

		// fechaini
		$this->fechaini->ViewValue = $this->fechaini->CurrentValue;
		$this->fechaini->ViewValue = ew_FormatDateTime($this->fechaini->ViewValue, 0);
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($view1_list)) $view1_list = new cview1_list();

// Page init
$view1_list->Page_Init();

// Page main
$view1_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fview1list = new ew_Form("fview1list", "list");
fview1list.FormKeyCountName = '<?php echo $view1_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview1list.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1list.ValidateRequired = true;
<?php } else { ?>
fview1list.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fview1list.Lists["x_modman"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1list.Lists["x_modman"].Options = <?php echo json_encode($view1->modman->Options()) ?>;
fview1list.Lists["x_periodo"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1list.Lists["x_periodo"].Options = <?php echo json_encode($view1->periodo->Options()) ?>;
fview1list.Lists["x_horasluz"] = {"LinkField":"","Ajax":false,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fview1list.Lists["x_horasluz"].Options = <?php echo json_encode($view1->horasluz->Options()) ?>;

// Form object for search
var CurrentSearchForm = fview1listsrch = new ew_Form("fview1listsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($view1_list->TotalRecs > 0 && $view1_list->ExportOptions->Visible()) { ?>
<?php $view1_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($view1_list->SearchOptions->Visible()) { ?>
<?php $view1_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($view1_list->FilterOptions->Visible()) { ?>
<?php $view1_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $view1_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($view1_list->TotalRecs <= 0)
			$view1_list->TotalRecs = $view1->SelectRecordCount();
	} else {
		if (!$view1_list->Recordset && ($view1_list->Recordset = $view1_list->LoadRecordset()))
			$view1_list->TotalRecs = $view1_list->Recordset->RecordCount();
	}
	$view1_list->StartRec = 1;
	if ($view1_list->DisplayRecs <= 0 || ($view1->Export <> "" && $view1->ExportAll)) // Display all records
		$view1_list->DisplayRecs = $view1_list->TotalRecs;
	if (!($view1->Export <> "" && $view1->ExportAll))
		$view1_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view1_list->Recordset = $view1_list->LoadRecordset($view1_list->StartRec-1, $view1_list->DisplayRecs);

	// Set no record found message
	if ($view1->CurrentAction == "" && $view1_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view1_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($view1_list->SearchWhere == "0=101")
			$view1_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view1_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view1_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view1->Export == "" && $view1->CurrentAction == "") { ?>
<form name="fview1listsrch" id="fview1listsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view1_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview1listsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view1">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($view1_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($view1_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $view1_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($view1_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($view1_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($view1_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($view1_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $view1_list->ShowPageHeader(); ?>
<?php
$view1_list->ShowMessage();
?>
<?php if ($view1_list->TotalRecs > 0 || $view1->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div class="panel-heading ewGridUpperPanel">
<?php if ($view1->CurrentAction <> "gridadd" && $view1->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view1_list->Pager)) $view1_list->Pager = new cPrevNextPager($view1_list->StartRec, $view1_list->DisplayRecs, $view1_list->TotalRecs) ?>
<?php if ($view1_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view1_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view1_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view1_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view1_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view1_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view1_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fview1list" id="fview1list" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view1_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1">
<div id="gmp_view1" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($view1_list->TotalRecs > 0) { ?>
<table id="tbl_view1list" class="table ewTable">
<?php echo $view1->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$view1_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view1_list->RenderListOptions();

// Render list options (header, left)
$view1_list->ListOptions->Render("header", "left");
?>
<?php if ($view1->id->Visible) { // id ?>
	<?php if ($view1->SortUrl($view1->id) == "") { ?>
		<th data-name="id"><div id="elh_view1_id" class="view1_id"><div class="ewTableHeaderCaption"><?php echo $view1->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->id) ?>',2);"><div id="elh_view1_id" class="view1_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->fecha->Visible) { // fecha ?>
	<?php if ($view1->SortUrl($view1->fecha) == "") { ?>
		<th data-name="fecha"><div id="elh_view1_fecha" class="view1_fecha"><div class="ewTableHeaderCaption"><?php echo $view1->fecha->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->fecha) ?>',2);"><div id="elh_view1_fecha" class="view1_fecha">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->fecha->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->fecha->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->fecha->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->hora->Visible) { // hora ?>
	<?php if ($view1->SortUrl($view1->hora) == "") { ?>
		<th data-name="hora"><div id="elh_view1_hora" class="view1_hora"><div class="ewTableHeaderCaption"><?php echo $view1->hora->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hora"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->hora) ?>',2);"><div id="elh_view1_hora" class="view1_hora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->hora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->hora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->hora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->temp->Visible) { // temp ?>
	<?php if ($view1->SortUrl($view1->temp) == "") { ?>
		<th data-name="temp"><div id="elh_view1_temp" class="view1_temp"><div class="ewTableHeaderCaption"><?php echo $view1->temp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="temp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->temp) ?>',2);"><div id="elh_view1_temp" class="view1_temp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->temp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->temp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->temp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->hum->Visible) { // hum ?>
	<?php if ($view1->SortUrl($view1->hum) == "") { ?>
		<th data-name="hum"><div id="elh_view1_hum" class="view1_hum"><div class="ewTableHeaderCaption"><?php echo $view1->hum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hum"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->hum) ?>',2);"><div id="elh_view1_hum" class="view1_hum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->hum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->hum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->hum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->co2ppm->Visible) { // co2ppm ?>
	<?php if ($view1->SortUrl($view1->co2ppm) == "") { ?>
		<th data-name="co2ppm"><div id="elh_view1_co2ppm" class="view1_co2ppm"><div class="ewTableHeaderCaption"><?php echo $view1->co2ppm->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="co2ppm"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->co2ppm) ?>',2);"><div id="elh_view1_co2ppm" class="view1_co2ppm">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->co2ppm->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->co2ppm->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->co2ppm->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->higromet->Visible) { // higromet ?>
	<?php if ($view1->SortUrl($view1->higromet) == "") { ?>
		<th data-name="higromet"><div id="elh_view1_higromet" class="view1_higromet"><div class="ewTableHeaderCaption"><?php echo $view1->higromet->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="higromet"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->higromet) ?>',2);"><div id="elh_view1_higromet" class="view1_higromet">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->higromet->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->higromet->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->higromet->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->luz->Visible) { // luz ?>
	<?php if ($view1->SortUrl($view1->luz) == "") { ?>
		<th data-name="luz"><div id="elh_view1_luz" class="view1_luz"><div class="ewTableHeaderCaption"><?php echo $view1->luz->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="luz"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->luz) ?>',2);"><div id="elh_view1_luz" class="view1_luz">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->luz->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->luz->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->luz->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->maqhum->Visible) { // maqhum ?>
	<?php if ($view1->SortUrl($view1->maqhum) == "") { ?>
		<th data-name="maqhum"><div id="elh_view1_maqhum" class="view1_maqhum"><div class="ewTableHeaderCaption"><?php echo $view1->maqhum->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="maqhum"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->maqhum) ?>',2);"><div id="elh_view1_maqhum" class="view1_maqhum">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->maqhum->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->maqhum->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->maqhum->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->maqdesh->Visible) { // maqdesh ?>
	<?php if ($view1->SortUrl($view1->maqdesh) == "") { ?>
		<th data-name="maqdesh"><div id="elh_view1_maqdesh" class="view1_maqdesh"><div class="ewTableHeaderCaption"><?php echo $view1->maqdesh->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="maqdesh"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->maqdesh) ?>',2);"><div id="elh_view1_maqdesh" class="view1_maqdesh">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->maqdesh->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->maqdesh->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->maqdesh->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->maqcale->Visible) { // maqcale ?>
	<?php if ($view1->SortUrl($view1->maqcale) == "") { ?>
		<th data-name="maqcale"><div id="elh_view1_maqcale" class="view1_maqcale"><div class="ewTableHeaderCaption"><?php echo $view1->maqcale->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="maqcale"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->maqcale) ?>',2);"><div id="elh_view1_maqcale" class="view1_maqcale">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->maqcale->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->maqcale->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->maqcale->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->modman->Visible) { // modman ?>
	<?php if ($view1->SortUrl($view1->modman) == "") { ?>
		<th data-name="modman"><div id="elh_view1_modman" class="view1_modman"><div class="ewTableHeaderCaption"><?php echo $view1->modman->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="modman"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->modman) ?>',2);"><div id="elh_view1_modman" class="view1_modman">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->modman->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->modman->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->modman->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->periodo->Visible) { // periodo ?>
	<?php if ($view1->SortUrl($view1->periodo) == "") { ?>
		<th data-name="periodo"><div id="elh_view1_periodo" class="view1_periodo"><div class="ewTableHeaderCaption"><?php echo $view1->periodo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="periodo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->periodo) ?>',2);"><div id="elh_view1_periodo" class="view1_periodo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->periodo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->periodo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->periodo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->horasluz->Visible) { // horasluz ?>
	<?php if ($view1->SortUrl($view1->horasluz) == "") { ?>
		<th data-name="horasluz"><div id="elh_view1_horasluz" class="view1_horasluz"><div class="ewTableHeaderCaption"><?php echo $view1->horasluz->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="horasluz"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->horasluz) ?>',2);"><div id="elh_view1_horasluz" class="view1_horasluz">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->horasluz->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->horasluz->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->horasluz->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($view1->fechaini->Visible) { // fechaini ?>
	<?php if ($view1->SortUrl($view1->fechaini) == "") { ?>
		<th data-name="fechaini"><div id="elh_view1_fechaini" class="view1_fechaini"><div class="ewTableHeaderCaption"><?php echo $view1->fechaini->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fechaini"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->fechaini) ?>',2);"><div id="elh_view1_fechaini" class="view1_fechaini">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->fechaini->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->fechaini->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->fechaini->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view1_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view1->ExportAll && $view1->Export <> "") {
	$view1_list->StopRec = $view1_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view1_list->TotalRecs > $view1_list->StartRec + $view1_list->DisplayRecs - 1)
		$view1_list->StopRec = $view1_list->StartRec + $view1_list->DisplayRecs - 1;
	else
		$view1_list->StopRec = $view1_list->TotalRecs;
}
$view1_list->RecCnt = $view1_list->StartRec - 1;
if ($view1_list->Recordset && !$view1_list->Recordset->EOF) {
	$view1_list->Recordset->MoveFirst();
	$bSelectLimit = $view1_list->UseSelectLimit;
	if (!$bSelectLimit && $view1_list->StartRec > 1)
		$view1_list->Recordset->Move($view1_list->StartRec - 1);
} elseif (!$view1->AllowAddDeleteRow && $view1_list->StopRec == 0) {
	$view1_list->StopRec = $view1->GridAddRowCount;
}

// Initialize aggregate
$view1->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view1->ResetAttrs();
$view1_list->RenderRow();
while ($view1_list->RecCnt < $view1_list->StopRec) {
	$view1_list->RecCnt++;
	if (intval($view1_list->RecCnt) >= intval($view1_list->StartRec)) {
		$view1_list->RowCnt++;

		// Set up key count
		$view1_list->KeyCount = $view1_list->RowIndex;

		// Init row class and style
		$view1->ResetAttrs();
		$view1->CssClass = "";
		if ($view1->CurrentAction == "gridadd") {
		} else {
			$view1_list->LoadRowValues($view1_list->Recordset); // Load row values
		}
		$view1->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view1->RowAttrs = array_merge($view1->RowAttrs, array('data-rowindex'=>$view1_list->RowCnt, 'id'=>'r' . $view1_list->RowCnt . '_view1', 'data-rowtype'=>$view1->RowType));

		// Render row
		$view1_list->RenderRow();

		// Render list options
		$view1_list->RenderListOptions();
?>
	<tr<?php echo $view1->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view1_list->ListOptions->Render("body", "left", $view1_list->RowCnt);
?>
	<?php if ($view1->id->Visible) { // id ?>
		<td data-name="id"<?php echo $view1->id->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_id" class="view1_id">
<span<?php echo $view1->id->ViewAttributes() ?>>
<?php echo $view1->id->ListViewValue() ?></span>
</span>
<a id="<?php echo $view1_list->PageObjName . "_row_" . $view1_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($view1->fecha->Visible) { // fecha ?>
		<td data-name="fecha"<?php echo $view1->fecha->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_fecha" class="view1_fecha">
<span<?php echo $view1->fecha->ViewAttributes() ?>>
<?php echo $view1->fecha->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->hora->Visible) { // hora ?>
		<td data-name="hora"<?php echo $view1->hora->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_hora" class="view1_hora">
<span<?php echo $view1->hora->ViewAttributes() ?>>
<?php echo $view1->hora->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->temp->Visible) { // temp ?>
		<td data-name="temp"<?php echo $view1->temp->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_temp" class="view1_temp">
<span<?php echo $view1->temp->ViewAttributes() ?>>
<?php echo $view1->temp->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->hum->Visible) { // hum ?>
		<td data-name="hum"<?php echo $view1->hum->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_hum" class="view1_hum">
<span<?php echo $view1->hum->ViewAttributes() ?>>
<?php echo $view1->hum->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->co2ppm->Visible) { // co2ppm ?>
		<td data-name="co2ppm"<?php echo $view1->co2ppm->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_co2ppm" class="view1_co2ppm">
<span<?php echo $view1->co2ppm->ViewAttributes() ?>>
<?php echo $view1->co2ppm->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->higromet->Visible) { // higromet ?>
		<td data-name="higromet"<?php echo $view1->higromet->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_higromet" class="view1_higromet">
<span<?php echo $view1->higromet->ViewAttributes() ?>>
<?php echo $view1->higromet->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->luz->Visible) { // luz ?>
		<td data-name="luz"<?php echo $view1->luz->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_luz" class="view1_luz">
<span<?php echo $view1->luz->ViewAttributes() ?>>
<?php echo $view1->luz->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->maqhum->Visible) { // maqhum ?>
		<td data-name="maqhum"<?php echo $view1->maqhum->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_maqhum" class="view1_maqhum">
<span>
<?php if (!ew_EmptyStr($view1->maqhum->ListViewValue())) { ?><img src="<?php echo $view1->maqhum->ListViewValue() ?>" alt=""<?php echo $view1->maqhum->ViewAttributes() ?>><?php } ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->maqdesh->Visible) { // maqdesh ?>
		<td data-name="maqdesh"<?php echo $view1->maqdesh->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_maqdesh" class="view1_maqdesh">
<span>
<?php if (!ew_EmptyStr($view1->maqdesh->ListViewValue())) { ?><img src="<?php echo $view1->maqdesh->ListViewValue() ?>" alt=""<?php echo $view1->maqdesh->ViewAttributes() ?>><?php } ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->maqcale->Visible) { // maqcale ?>
		<td data-name="maqcale"<?php echo $view1->maqcale->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_maqcale" class="view1_maqcale">
<span>
<?php if (!ew_EmptyStr($view1->maqcale->ListViewValue())) { ?><img src="<?php echo $view1->maqcale->ListViewValue() ?>" alt=""<?php echo $view1->maqcale->ViewAttributes() ?>><?php } ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->modman->Visible) { // modman ?>
		<td data-name="modman"<?php echo $view1->modman->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_modman" class="view1_modman">
<span<?php echo $view1->modman->ViewAttributes() ?>>
<?php echo $view1->modman->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->periodo->Visible) { // periodo ?>
		<td data-name="periodo"<?php echo $view1->periodo->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_periodo" class="view1_periodo">
<span<?php echo $view1->periodo->ViewAttributes() ?>>
<?php echo $view1->periodo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->horasluz->Visible) { // horasluz ?>
		<td data-name="horasluz"<?php echo $view1->horasluz->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_horasluz" class="view1_horasluz">
<span<?php echo $view1->horasluz->ViewAttributes() ?>>
<?php echo $view1->horasluz->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->fechaini->Visible) { // fechaini ?>
		<td data-name="fechaini"<?php echo $view1->fechaini->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_fechaini" class="view1_fechaini">
<span<?php echo $view1->fechaini->ViewAttributes() ?>>
<?php echo $view1->fechaini->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view1_list->ListOptions->Render("body", "right", $view1_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view1->CurrentAction <> "gridadd")
		$view1_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view1->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view1_list->Recordset)
	$view1_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($view1_list->TotalRecs == 0 && $view1->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fview1listsrch.Init();
fview1listsrch.FilterList = <?php echo $view1_list->GetFilterList() ?>;
fview1list.Init();
</script>
<?php
$view1_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view1_list->Page_Terminate();
?>
