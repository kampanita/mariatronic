<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "parametrosinfo.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$parametros_list = NULL; // Initialize page object first

class cparametros_list extends cparametros {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{524C14CD-A0E3-4083-AF86-06203077AB82}";

	// Table name
	var $TableName = 'parametros';

	// Page object name
	var $PageObjName = 'parametros_list';

	// Grid form hidden field names
	var $FormName = 'fparametroslist';
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

		// Table object (parametros)
		if (!isset($GLOBALS["parametros"]) || get_class($GLOBALS["parametros"]) == "cparametros") {
			$GLOBALS["parametros"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parametros"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "parametrosadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "parametrosdelete.php";
		$this->MultiUpdateUrl = "parametrosupdate.php";

		// Table object (usuarios)
		if (!isset($GLOBALS['usuarios'])) $GLOBALS['usuarios'] = new cusuarios();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parametros', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fparametroslistsrch";

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

		// Create form object
		$objForm = new cFormObj();
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("id", ""); // Clear inline edit key
		$this->temp_min->FormValue = ""; // Clear form value
		$this->temp_max->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("id", $this->id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("id")) <> strval($this->id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_temp_min") && $objForm->HasValue("o_temp_min") && $this->temp_min->CurrentValue <> $this->temp_min->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_temp_max") && $objForm->HasValue("o_temp_max") && $this->temp_max->CurrentValue <> $this->temp_max->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_co_min") && $objForm->HasValue("o_co_min") && $this->co_min->CurrentValue <> $this->co_min->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_co_max") && $objForm->HasValue("o_co_max") && $this->co_max->CurrentValue <> $this->co_max->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_horas_crecimiento") && $objForm->HasValue("o_horas_crecimiento") && $this->horas_crecimiento->CurrentValue <> $this->horas_crecimiento->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_horas_floracion") && $objForm->HasValue("o_horas_floracion") && $this->horas_floracion->CurrentValue <> $this->horas_floracion->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_hum_min") && $objForm->HasValue("o_hum_min") && $this->hum_min->CurrentValue <> $this->hum_min->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_hum_max") && $objForm->HasValue("o_hum_max") && $this->hum_max->CurrentValue <> $this->hum_max->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_DnsHost") && $objForm->HasValue("o_DnsHost") && $this->DnsHost->CurrentValue <> $this->DnsHost->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_DnsUser") && $objForm->HasValue("o_DnsUser") && $this->DnsUser->CurrentValue <> $this->DnsUser->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_DnsPasswd") && $objForm->HasValue("o_DnsPasswd") && $this->DnsPasswd->CurrentValue <> $this->DnsPasswd->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_DnsUrl_Update") && $objForm->HasValue("o_DnsUrl_Update") && $this->DnsUrl_Update->CurrentValue <> $this->DnsUrl_Update->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_WifiSSID") && $objForm->HasValue("o_WifiSSID") && $this->WifiSSID->CurrentValue <> $this->WifiSSID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_WifiPasswd") && $objForm->HasValue("o_WifiPasswd") && $this->WifiPasswd->CurrentValue <> $this->WifiPasswd->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJSON(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->temp_min->AdvancedSearch->ToJSON(), ","); // Field temp_min
		$sFilterList = ew_Concat($sFilterList, $this->temp_max->AdvancedSearch->ToJSON(), ","); // Field temp_max
		$sFilterList = ew_Concat($sFilterList, $this->co_min->AdvancedSearch->ToJSON(), ","); // Field co_min
		$sFilterList = ew_Concat($sFilterList, $this->co_max->AdvancedSearch->ToJSON(), ","); // Field co_max
		$sFilterList = ew_Concat($sFilterList, $this->horas_crecimiento->AdvancedSearch->ToJSON(), ","); // Field horas_crecimiento
		$sFilterList = ew_Concat($sFilterList, $this->horas_floracion->AdvancedSearch->ToJSON(), ","); // Field horas_floracion
		$sFilterList = ew_Concat($sFilterList, $this->hum_min->AdvancedSearch->ToJSON(), ","); // Field hum_min
		$sFilterList = ew_Concat($sFilterList, $this->hum_max->AdvancedSearch->ToJSON(), ","); // Field hum_max
		$sFilterList = ew_Concat($sFilterList, $this->DnsHost->AdvancedSearch->ToJSON(), ","); // Field DnsHost
		$sFilterList = ew_Concat($sFilterList, $this->DnsUser->AdvancedSearch->ToJSON(), ","); // Field DnsUser
		$sFilterList = ew_Concat($sFilterList, $this->DnsPasswd->AdvancedSearch->ToJSON(), ","); // Field DnsPasswd
		$sFilterList = ew_Concat($sFilterList, $this->DnsUrl_Update->AdvancedSearch->ToJSON(), ","); // Field DnsUrl_Update
		$sFilterList = ew_Concat($sFilterList, $this->WifiSSID->AdvancedSearch->ToJSON(), ","); // Field WifiSSID
		$sFilterList = ew_Concat($sFilterList, $this->WifiPasswd->AdvancedSearch->ToJSON(), ","); // Field WifiPasswd
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

		// Field temp_min
		$this->temp_min->AdvancedSearch->SearchValue = @$filter["x_temp_min"];
		$this->temp_min->AdvancedSearch->SearchOperator = @$filter["z_temp_min"];
		$this->temp_min->AdvancedSearch->SearchCondition = @$filter["v_temp_min"];
		$this->temp_min->AdvancedSearch->SearchValue2 = @$filter["y_temp_min"];
		$this->temp_min->AdvancedSearch->SearchOperator2 = @$filter["w_temp_min"];
		$this->temp_min->AdvancedSearch->Save();

		// Field temp_max
		$this->temp_max->AdvancedSearch->SearchValue = @$filter["x_temp_max"];
		$this->temp_max->AdvancedSearch->SearchOperator = @$filter["z_temp_max"];
		$this->temp_max->AdvancedSearch->SearchCondition = @$filter["v_temp_max"];
		$this->temp_max->AdvancedSearch->SearchValue2 = @$filter["y_temp_max"];
		$this->temp_max->AdvancedSearch->SearchOperator2 = @$filter["w_temp_max"];
		$this->temp_max->AdvancedSearch->Save();

		// Field co_min
		$this->co_min->AdvancedSearch->SearchValue = @$filter["x_co_min"];
		$this->co_min->AdvancedSearch->SearchOperator = @$filter["z_co_min"];
		$this->co_min->AdvancedSearch->SearchCondition = @$filter["v_co_min"];
		$this->co_min->AdvancedSearch->SearchValue2 = @$filter["y_co_min"];
		$this->co_min->AdvancedSearch->SearchOperator2 = @$filter["w_co_min"];
		$this->co_min->AdvancedSearch->Save();

		// Field co_max
		$this->co_max->AdvancedSearch->SearchValue = @$filter["x_co_max"];
		$this->co_max->AdvancedSearch->SearchOperator = @$filter["z_co_max"];
		$this->co_max->AdvancedSearch->SearchCondition = @$filter["v_co_max"];
		$this->co_max->AdvancedSearch->SearchValue2 = @$filter["y_co_max"];
		$this->co_max->AdvancedSearch->SearchOperator2 = @$filter["w_co_max"];
		$this->co_max->AdvancedSearch->Save();

		// Field horas_crecimiento
		$this->horas_crecimiento->AdvancedSearch->SearchValue = @$filter["x_horas_crecimiento"];
		$this->horas_crecimiento->AdvancedSearch->SearchOperator = @$filter["z_horas_crecimiento"];
		$this->horas_crecimiento->AdvancedSearch->SearchCondition = @$filter["v_horas_crecimiento"];
		$this->horas_crecimiento->AdvancedSearch->SearchValue2 = @$filter["y_horas_crecimiento"];
		$this->horas_crecimiento->AdvancedSearch->SearchOperator2 = @$filter["w_horas_crecimiento"];
		$this->horas_crecimiento->AdvancedSearch->Save();

		// Field horas_floracion
		$this->horas_floracion->AdvancedSearch->SearchValue = @$filter["x_horas_floracion"];
		$this->horas_floracion->AdvancedSearch->SearchOperator = @$filter["z_horas_floracion"];
		$this->horas_floracion->AdvancedSearch->SearchCondition = @$filter["v_horas_floracion"];
		$this->horas_floracion->AdvancedSearch->SearchValue2 = @$filter["y_horas_floracion"];
		$this->horas_floracion->AdvancedSearch->SearchOperator2 = @$filter["w_horas_floracion"];
		$this->horas_floracion->AdvancedSearch->Save();

		// Field hum_min
		$this->hum_min->AdvancedSearch->SearchValue = @$filter["x_hum_min"];
		$this->hum_min->AdvancedSearch->SearchOperator = @$filter["z_hum_min"];
		$this->hum_min->AdvancedSearch->SearchCondition = @$filter["v_hum_min"];
		$this->hum_min->AdvancedSearch->SearchValue2 = @$filter["y_hum_min"];
		$this->hum_min->AdvancedSearch->SearchOperator2 = @$filter["w_hum_min"];
		$this->hum_min->AdvancedSearch->Save();

		// Field hum_max
		$this->hum_max->AdvancedSearch->SearchValue = @$filter["x_hum_max"];
		$this->hum_max->AdvancedSearch->SearchOperator = @$filter["z_hum_max"];
		$this->hum_max->AdvancedSearch->SearchCondition = @$filter["v_hum_max"];
		$this->hum_max->AdvancedSearch->SearchValue2 = @$filter["y_hum_max"];
		$this->hum_max->AdvancedSearch->SearchOperator2 = @$filter["w_hum_max"];
		$this->hum_max->AdvancedSearch->Save();

		// Field DnsHost
		$this->DnsHost->AdvancedSearch->SearchValue = @$filter["x_DnsHost"];
		$this->DnsHost->AdvancedSearch->SearchOperator = @$filter["z_DnsHost"];
		$this->DnsHost->AdvancedSearch->SearchCondition = @$filter["v_DnsHost"];
		$this->DnsHost->AdvancedSearch->SearchValue2 = @$filter["y_DnsHost"];
		$this->DnsHost->AdvancedSearch->SearchOperator2 = @$filter["w_DnsHost"];
		$this->DnsHost->AdvancedSearch->Save();

		// Field DnsUser
		$this->DnsUser->AdvancedSearch->SearchValue = @$filter["x_DnsUser"];
		$this->DnsUser->AdvancedSearch->SearchOperator = @$filter["z_DnsUser"];
		$this->DnsUser->AdvancedSearch->SearchCondition = @$filter["v_DnsUser"];
		$this->DnsUser->AdvancedSearch->SearchValue2 = @$filter["y_DnsUser"];
		$this->DnsUser->AdvancedSearch->SearchOperator2 = @$filter["w_DnsUser"];
		$this->DnsUser->AdvancedSearch->Save();

		// Field DnsPasswd
		$this->DnsPasswd->AdvancedSearch->SearchValue = @$filter["x_DnsPasswd"];
		$this->DnsPasswd->AdvancedSearch->SearchOperator = @$filter["z_DnsPasswd"];
		$this->DnsPasswd->AdvancedSearch->SearchCondition = @$filter["v_DnsPasswd"];
		$this->DnsPasswd->AdvancedSearch->SearchValue2 = @$filter["y_DnsPasswd"];
		$this->DnsPasswd->AdvancedSearch->SearchOperator2 = @$filter["w_DnsPasswd"];
		$this->DnsPasswd->AdvancedSearch->Save();

		// Field DnsUrl_Update
		$this->DnsUrl_Update->AdvancedSearch->SearchValue = @$filter["x_DnsUrl_Update"];
		$this->DnsUrl_Update->AdvancedSearch->SearchOperator = @$filter["z_DnsUrl_Update"];
		$this->DnsUrl_Update->AdvancedSearch->SearchCondition = @$filter["v_DnsUrl_Update"];
		$this->DnsUrl_Update->AdvancedSearch->SearchValue2 = @$filter["y_DnsUrl_Update"];
		$this->DnsUrl_Update->AdvancedSearch->SearchOperator2 = @$filter["w_DnsUrl_Update"];
		$this->DnsUrl_Update->AdvancedSearch->Save();

		// Field WifiSSID
		$this->WifiSSID->AdvancedSearch->SearchValue = @$filter["x_WifiSSID"];
		$this->WifiSSID->AdvancedSearch->SearchOperator = @$filter["z_WifiSSID"];
		$this->WifiSSID->AdvancedSearch->SearchCondition = @$filter["v_WifiSSID"];
		$this->WifiSSID->AdvancedSearch->SearchValue2 = @$filter["y_WifiSSID"];
		$this->WifiSSID->AdvancedSearch->SearchOperator2 = @$filter["w_WifiSSID"];
		$this->WifiSSID->AdvancedSearch->Save();

		// Field WifiPasswd
		$this->WifiPasswd->AdvancedSearch->SearchValue = @$filter["x_WifiPasswd"];
		$this->WifiPasswd->AdvancedSearch->SearchOperator = @$filter["z_WifiPasswd"];
		$this->WifiPasswd->AdvancedSearch->SearchCondition = @$filter["v_WifiPasswd"];
		$this->WifiPasswd->AdvancedSearch->SearchValue2 = @$filter["y_WifiPasswd"];
		$this->WifiPasswd->AdvancedSearch->SearchOperator2 = @$filter["w_WifiPasswd"];
		$this->WifiPasswd->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->DnsHost, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->DnsUser, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->DnsPasswd, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->DnsUrl_Update, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->WifiSSID, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->WifiPasswd, $arKeywords, $type);
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
			$this->UpdateSort($this->temp_min, $bCtrl); // temp_min
			$this->UpdateSort($this->temp_max, $bCtrl); // temp_max
			$this->UpdateSort($this->co_min, $bCtrl); // co_min
			$this->UpdateSort($this->co_max, $bCtrl); // co_max
			$this->UpdateSort($this->horas_crecimiento, $bCtrl); // horas_crecimiento
			$this->UpdateSort($this->horas_floracion, $bCtrl); // horas_floracion
			$this->UpdateSort($this->hum_min, $bCtrl); // hum_min
			$this->UpdateSort($this->hum_max, $bCtrl); // hum_max
			$this->UpdateSort($this->DnsHost, $bCtrl); // DnsHost
			$this->UpdateSort($this->DnsUser, $bCtrl); // DnsUser
			$this->UpdateSort($this->DnsPasswd, $bCtrl); // DnsPasswd
			$this->UpdateSort($this->DnsUrl_Update, $bCtrl); // DnsUrl_Update
			$this->UpdateSort($this->WifiSSID, $bCtrl); // WifiSSID
			$this->UpdateSort($this->WifiPasswd, $bCtrl); // WifiPasswd
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
				$this->temp_min->setSort("");
				$this->temp_max->setSort("");
				$this->co_min->setSort("");
				$this->co_max->setSort("");
				$this->horas_crecimiento->setSort("");
				$this->horas_floracion->setSort("");
				$this->hum_min->setSort("");
				$this->hum_max->setSort("");
				$this->DnsHost->setSort("");
				$this->DnsUser->setSort("");
				$this->DnsPasswd->setSort("");
				$this->DnsUrl_Update->setSort("");
				$this->WifiSSID->setSort("");
				$this->WifiPasswd->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;

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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->AddMasterUrl($this->PageName()) . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->AddMasterUrl($this->PageName()), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fparametroslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fparametroslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fparametroslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->AddMasterUrl($this->PageName()) . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->AddMasterUrl($this->PageName()) . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fparametroslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
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
		$this->DnsHost->CurrentValue = NULL;
		$this->DnsHost->OldValue = $this->DnsHost->CurrentValue;
		$this->DnsUser->CurrentValue = NULL;
		$this->DnsUser->OldValue = $this->DnsUser->CurrentValue;
		$this->DnsPasswd->CurrentValue = NULL;
		$this->DnsPasswd->OldValue = $this->DnsPasswd->CurrentValue;
		$this->DnsUrl_Update->CurrentValue = NULL;
		$this->DnsUrl_Update->OldValue = $this->DnsUrl_Update->CurrentValue;
		$this->WifiSSID->CurrentValue = NULL;
		$this->WifiSSID->OldValue = $this->WifiSSID->CurrentValue;
		$this->WifiPasswd->CurrentValue = NULL;
		$this->WifiPasswd->OldValue = $this->WifiPasswd->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->temp_min->FldIsDetailKey) {
			$this->temp_min->setFormValue($objForm->GetValue("x_temp_min"));
		}
		$this->temp_min->setOldValue($objForm->GetValue("o_temp_min"));
		if (!$this->temp_max->FldIsDetailKey) {
			$this->temp_max->setFormValue($objForm->GetValue("x_temp_max"));
		}
		$this->temp_max->setOldValue($objForm->GetValue("o_temp_max"));
		if (!$this->co_min->FldIsDetailKey) {
			$this->co_min->setFormValue($objForm->GetValue("x_co_min"));
		}
		$this->co_min->setOldValue($objForm->GetValue("o_co_min"));
		if (!$this->co_max->FldIsDetailKey) {
			$this->co_max->setFormValue($objForm->GetValue("x_co_max"));
		}
		$this->co_max->setOldValue($objForm->GetValue("o_co_max"));
		if (!$this->horas_crecimiento->FldIsDetailKey) {
			$this->horas_crecimiento->setFormValue($objForm->GetValue("x_horas_crecimiento"));
		}
		$this->horas_crecimiento->setOldValue($objForm->GetValue("o_horas_crecimiento"));
		if (!$this->horas_floracion->FldIsDetailKey) {
			$this->horas_floracion->setFormValue($objForm->GetValue("x_horas_floracion"));
		}
		$this->horas_floracion->setOldValue($objForm->GetValue("o_horas_floracion"));
		if (!$this->hum_min->FldIsDetailKey) {
			$this->hum_min->setFormValue($objForm->GetValue("x_hum_min"));
		}
		$this->hum_min->setOldValue($objForm->GetValue("o_hum_min"));
		if (!$this->hum_max->FldIsDetailKey) {
			$this->hum_max->setFormValue($objForm->GetValue("x_hum_max"));
		}
		$this->hum_max->setOldValue($objForm->GetValue("o_hum_max"));
		if (!$this->DnsHost->FldIsDetailKey) {
			$this->DnsHost->setFormValue($objForm->GetValue("x_DnsHost"));
		}
		$this->DnsHost->setOldValue($objForm->GetValue("o_DnsHost"));
		if (!$this->DnsUser->FldIsDetailKey) {
			$this->DnsUser->setFormValue($objForm->GetValue("x_DnsUser"));
		}
		$this->DnsUser->setOldValue($objForm->GetValue("o_DnsUser"));
		if (!$this->DnsPasswd->FldIsDetailKey) {
			$this->DnsPasswd->setFormValue($objForm->GetValue("x_DnsPasswd"));
		}
		$this->DnsPasswd->setOldValue($objForm->GetValue("o_DnsPasswd"));
		if (!$this->DnsUrl_Update->FldIsDetailKey) {
			$this->DnsUrl_Update->setFormValue($objForm->GetValue("x_DnsUrl_Update"));
		}
		$this->DnsUrl_Update->setOldValue($objForm->GetValue("o_DnsUrl_Update"));
		if (!$this->WifiSSID->FldIsDetailKey) {
			$this->WifiSSID->setFormValue($objForm->GetValue("x_WifiSSID"));
		}
		$this->WifiSSID->setOldValue($objForm->GetValue("o_WifiSSID"));
		if (!$this->WifiPasswd->FldIsDetailKey) {
			$this->WifiPasswd->setFormValue($objForm->GetValue("x_WifiPasswd"));
		}
		$this->WifiPasswd->setOldValue($objForm->GetValue("o_WifiPasswd"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			// temp_min

			$this->temp_min->EditAttrs["class"] = "form-control";
			$this->temp_min->EditCustomAttributes = "";
			$this->temp_min->EditValue = ew_HtmlEncode($this->temp_min->CurrentValue);
			$this->temp_min->PlaceHolder = ew_RemoveHtml($this->temp_min->FldCaption());
			if (strval($this->temp_min->EditValue) <> "" && is_numeric($this->temp_min->EditValue)) {
			$this->temp_min->EditValue = ew_FormatNumber($this->temp_min->EditValue, -2, -1, -2, 0);
			$this->temp_min->OldValue = $this->temp_min->EditValue;
			}

			// temp_max
			$this->temp_max->EditAttrs["class"] = "form-control";
			$this->temp_max->EditCustomAttributes = "";
			$this->temp_max->EditValue = ew_HtmlEncode($this->temp_max->CurrentValue);
			$this->temp_max->PlaceHolder = ew_RemoveHtml($this->temp_max->FldCaption());
			if (strval($this->temp_max->EditValue) <> "" && is_numeric($this->temp_max->EditValue)) {
			$this->temp_max->EditValue = ew_FormatNumber($this->temp_max->EditValue, -2, -1, -2, 0);
			$this->temp_max->OldValue = $this->temp_max->EditValue;
			}

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
			if (strval($this->temp_min->EditValue) <> "" && is_numeric($this->temp_min->EditValue)) {
			$this->temp_min->EditValue = ew_FormatNumber($this->temp_min->EditValue, -2, -1, -2, 0);
			$this->temp_min->OldValue = $this->temp_min->EditValue;
			}

			// temp_max
			$this->temp_max->EditAttrs["class"] = "form-control";
			$this->temp_max->EditCustomAttributes = "";
			$this->temp_max->EditValue = ew_HtmlEncode($this->temp_max->CurrentValue);
			$this->temp_max->PlaceHolder = ew_RemoveHtml($this->temp_max->FldCaption());
			if (strval($this->temp_max->EditValue) <> "" && is_numeric($this->temp_max->EditValue)) {
			$this->temp_max->EditValue = ew_FormatNumber($this->temp_max->EditValue, -2, -1, -2, 0);
			$this->temp_max->OldValue = $this->temp_max->EditValue;
			}

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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
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
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
			$this->horas_crecimiento->SetDbValueDef($rsnew, $this->horas_crecimiento->CurrentValue, "", $this->horas_crecimiento->ReadOnly);

			// horas_floracion
			$this->horas_floracion->SetDbValueDef($rsnew, $this->horas_floracion->CurrentValue, "", $this->horas_floracion->ReadOnly);

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
		$this->horas_crecimiento->SetDbValueDef($rsnew, $this->horas_crecimiento->CurrentValue, "", FALSE);

		// horas_floracion
		$this->horas_floracion->SetDbValueDef($rsnew, $this->horas_floracion->CurrentValue, "", FALSE);

		// hum_min
		$this->hum_min->SetDbValueDef($rsnew, $this->hum_min->CurrentValue, 0, FALSE);

		// hum_max
		$this->hum_max->SetDbValueDef($rsnew, $this->hum_max->CurrentValue, 0, FALSE);

		// DnsHost
		$this->DnsHost->SetDbValueDef($rsnew, $this->DnsHost->CurrentValue, "", FALSE);

		// DnsUser
		$this->DnsUser->SetDbValueDef($rsnew, $this->DnsUser->CurrentValue, "", FALSE);

		// DnsPasswd
		$this->DnsPasswd->SetDbValueDef($rsnew, $this->DnsPasswd->CurrentValue, "", FALSE);

		// DnsUrl_Update
		$this->DnsUrl_Update->SetDbValueDef($rsnew, $this->DnsUrl_Update->CurrentValue, "", FALSE);

		// WifiSSID
		$this->WifiSSID->SetDbValueDef($rsnew, $this->WifiSSID->CurrentValue, "", FALSE);

		// WifiPasswd
		$this->WifiPasswd->SetDbValueDef($rsnew, $this->WifiPasswd->CurrentValue, "", FALSE);

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
if (!isset($parametros_list)) $parametros_list = new cparametros_list();

// Page init
$parametros_list->Page_Init();

// Page main
$parametros_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parametros_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fparametroslist = new ew_Form("fparametroslist", "list");
fparametroslist.FormKeyCountName = '<?php echo $parametros_list->FormKeyCountName ?>';

// Validate form
fparametroslist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
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
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fparametroslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "temp_min", false)) return false;
	if (ew_ValueChanged(fobj, infix, "temp_max", false)) return false;
	if (ew_ValueChanged(fobj, infix, "co_min", false)) return false;
	if (ew_ValueChanged(fobj, infix, "co_max", false)) return false;
	if (ew_ValueChanged(fobj, infix, "horas_crecimiento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "horas_floracion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hum_min", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hum_max", false)) return false;
	if (ew_ValueChanged(fobj, infix, "DnsHost", false)) return false;
	if (ew_ValueChanged(fobj, infix, "DnsUser", false)) return false;
	if (ew_ValueChanged(fobj, infix, "DnsPasswd", false)) return false;
	if (ew_ValueChanged(fobj, infix, "DnsUrl_Update", false)) return false;
	if (ew_ValueChanged(fobj, infix, "WifiSSID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "WifiPasswd", false)) return false;
	return true;
}

// Form_CustomValidate event
fparametroslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparametroslist.ValidateRequired = true;
<?php } else { ?>
fparametroslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fparametroslistsrch = new ew_Form("fparametroslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($parametros_list->TotalRecs > 0 && $parametros_list->ExportOptions->Visible()) { ?>
<?php $parametros_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($parametros_list->SearchOptions->Visible()) { ?>
<?php $parametros_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($parametros_list->FilterOptions->Visible()) { ?>
<?php $parametros_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
if ($parametros->CurrentAction == "gridadd") {
	$parametros->CurrentFilter = "0=1";
	$parametros_list->StartRec = 1;
	$parametros_list->DisplayRecs = $parametros->GridAddRowCount;
	$parametros_list->TotalRecs = $parametros_list->DisplayRecs;
	$parametros_list->StopRec = $parametros_list->DisplayRecs;
} else {
	$bSelectLimit = $parametros_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($parametros_list->TotalRecs <= 0)
			$parametros_list->TotalRecs = $parametros->SelectRecordCount();
	} else {
		if (!$parametros_list->Recordset && ($parametros_list->Recordset = $parametros_list->LoadRecordset()))
			$parametros_list->TotalRecs = $parametros_list->Recordset->RecordCount();
	}
	$parametros_list->StartRec = 1;
	if ($parametros_list->DisplayRecs <= 0 || ($parametros->Export <> "" && $parametros->ExportAll)) // Display all records
		$parametros_list->DisplayRecs = $parametros_list->TotalRecs;
	if (!($parametros->Export <> "" && $parametros->ExportAll))
		$parametros_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$parametros_list->Recordset = $parametros_list->LoadRecordset($parametros_list->StartRec-1, $parametros_list->DisplayRecs);

	// Set no record found message
	if ($parametros->CurrentAction == "" && $parametros_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$parametros_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($parametros_list->SearchWhere == "0=101")
			$parametros_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$parametros_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$parametros_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($parametros->Export == "" && $parametros->CurrentAction == "") { ?>
<form name="fparametroslistsrch" id="fparametroslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($parametros_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fparametroslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="parametros">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($parametros_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($parametros_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $parametros_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($parametros_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($parametros_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($parametros_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($parametros_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $parametros_list->ShowPageHeader(); ?>
<?php
$parametros_list->ShowMessage();
?>
<?php if ($parametros_list->TotalRecs > 0 || $parametros->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div class="panel-heading ewGridUpperPanel">
<?php if ($parametros->CurrentAction <> "gridadd" && $parametros->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($parametros_list->Pager)) $parametros_list->Pager = new cPrevNextPager($parametros_list->StartRec, $parametros_list->DisplayRecs, $parametros_list->TotalRecs) ?>
<?php if ($parametros_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($parametros_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $parametros_list->PageUrl() ?>start=<?php echo $parametros_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($parametros_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $parametros_list->PageUrl() ?>start=<?php echo $parametros_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $parametros_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($parametros_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $parametros_list->PageUrl() ?>start=<?php echo $parametros_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($parametros_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $parametros_list->PageUrl() ?>start=<?php echo $parametros_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $parametros_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $parametros_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $parametros_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $parametros_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($parametros_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<form name="fparametroslist" id="fparametroslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($parametros_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $parametros_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="parametros">
<div id="gmp_parametros" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($parametros_list->TotalRecs > 0 || $parametros->CurrentAction == "add" || $parametros->CurrentAction == "copy") { ?>
<table id="tbl_parametroslist" class="table ewTable">
<?php echo $parametros->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$parametros_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$parametros_list->RenderListOptions();

// Render list options (header, left)
$parametros_list->ListOptions->Render("header", "left");
?>
<?php if ($parametros->id->Visible) { // id ?>
	<?php if ($parametros->SortUrl($parametros->id) == "") { ?>
		<th data-name="id"><div id="elh_parametros_id" class="parametros_id"><div class="ewTableHeaderCaption"><?php echo $parametros->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->id) ?>',2);"><div id="elh_parametros_id" class="parametros_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->temp_min->Visible) { // temp_min ?>
	<?php if ($parametros->SortUrl($parametros->temp_min) == "") { ?>
		<th data-name="temp_min"><div id="elh_parametros_temp_min" class="parametros_temp_min"><div class="ewTableHeaderCaption"><?php echo $parametros->temp_min->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="temp_min"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->temp_min) ?>',2);"><div id="elh_parametros_temp_min" class="parametros_temp_min">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->temp_min->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->temp_min->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->temp_min->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->temp_max->Visible) { // temp_max ?>
	<?php if ($parametros->SortUrl($parametros->temp_max) == "") { ?>
		<th data-name="temp_max"><div id="elh_parametros_temp_max" class="parametros_temp_max"><div class="ewTableHeaderCaption"><?php echo $parametros->temp_max->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="temp_max"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->temp_max) ?>',2);"><div id="elh_parametros_temp_max" class="parametros_temp_max">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->temp_max->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->temp_max->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->temp_max->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->co_min->Visible) { // co_min ?>
	<?php if ($parametros->SortUrl($parametros->co_min) == "") { ?>
		<th data-name="co_min"><div id="elh_parametros_co_min" class="parametros_co_min"><div class="ewTableHeaderCaption"><?php echo $parametros->co_min->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="co_min"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->co_min) ?>',2);"><div id="elh_parametros_co_min" class="parametros_co_min">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->co_min->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->co_min->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->co_min->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->co_max->Visible) { // co_max ?>
	<?php if ($parametros->SortUrl($parametros->co_max) == "") { ?>
		<th data-name="co_max"><div id="elh_parametros_co_max" class="parametros_co_max"><div class="ewTableHeaderCaption"><?php echo $parametros->co_max->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="co_max"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->co_max) ?>',2);"><div id="elh_parametros_co_max" class="parametros_co_max">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->co_max->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->co_max->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->co_max->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
	<?php if ($parametros->SortUrl($parametros->horas_crecimiento) == "") { ?>
		<th data-name="horas_crecimiento"><div id="elh_parametros_horas_crecimiento" class="parametros_horas_crecimiento"><div class="ewTableHeaderCaption"><?php echo $parametros->horas_crecimiento->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="horas_crecimiento"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->horas_crecimiento) ?>',2);"><div id="elh_parametros_horas_crecimiento" class="parametros_horas_crecimiento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->horas_crecimiento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->horas_crecimiento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->horas_crecimiento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
	<?php if ($parametros->SortUrl($parametros->horas_floracion) == "") { ?>
		<th data-name="horas_floracion"><div id="elh_parametros_horas_floracion" class="parametros_horas_floracion"><div class="ewTableHeaderCaption"><?php echo $parametros->horas_floracion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="horas_floracion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->horas_floracion) ?>',2);"><div id="elh_parametros_horas_floracion" class="parametros_horas_floracion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->horas_floracion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->horas_floracion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->horas_floracion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->hum_min->Visible) { // hum_min ?>
	<?php if ($parametros->SortUrl($parametros->hum_min) == "") { ?>
		<th data-name="hum_min"><div id="elh_parametros_hum_min" class="parametros_hum_min"><div class="ewTableHeaderCaption"><?php echo $parametros->hum_min->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hum_min"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->hum_min) ?>',2);"><div id="elh_parametros_hum_min" class="parametros_hum_min">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->hum_min->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->hum_min->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->hum_min->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->hum_max->Visible) { // hum_max ?>
	<?php if ($parametros->SortUrl($parametros->hum_max) == "") { ?>
		<th data-name="hum_max"><div id="elh_parametros_hum_max" class="parametros_hum_max"><div class="ewTableHeaderCaption"><?php echo $parametros->hum_max->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hum_max"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->hum_max) ?>',2);"><div id="elh_parametros_hum_max" class="parametros_hum_max">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->hum_max->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parametros->hum_max->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->hum_max->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
	<?php if ($parametros->SortUrl($parametros->DnsHost) == "") { ?>
		<th data-name="DnsHost"><div id="elh_parametros_DnsHost" class="parametros_DnsHost"><div class="ewTableHeaderCaption"><?php echo $parametros->DnsHost->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DnsHost"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->DnsHost) ?>',2);"><div id="elh_parametros_DnsHost" class="parametros_DnsHost">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->DnsHost->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($parametros->DnsHost->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->DnsHost->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
	<?php if ($parametros->SortUrl($parametros->DnsUser) == "") { ?>
		<th data-name="DnsUser"><div id="elh_parametros_DnsUser" class="parametros_DnsUser"><div class="ewTableHeaderCaption"><?php echo $parametros->DnsUser->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DnsUser"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->DnsUser) ?>',2);"><div id="elh_parametros_DnsUser" class="parametros_DnsUser">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->DnsUser->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($parametros->DnsUser->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->DnsUser->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
	<?php if ($parametros->SortUrl($parametros->DnsPasswd) == "") { ?>
		<th data-name="DnsPasswd"><div id="elh_parametros_DnsPasswd" class="parametros_DnsPasswd"><div class="ewTableHeaderCaption"><?php echo $parametros->DnsPasswd->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DnsPasswd"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->DnsPasswd) ?>',2);"><div id="elh_parametros_DnsPasswd" class="parametros_DnsPasswd">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->DnsPasswd->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($parametros->DnsPasswd->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->DnsPasswd->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
	<?php if ($parametros->SortUrl($parametros->DnsUrl_Update) == "") { ?>
		<th data-name="DnsUrl_Update"><div id="elh_parametros_DnsUrl_Update" class="parametros_DnsUrl_Update"><div class="ewTableHeaderCaption"><?php echo $parametros->DnsUrl_Update->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="DnsUrl_Update"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->DnsUrl_Update) ?>',2);"><div id="elh_parametros_DnsUrl_Update" class="parametros_DnsUrl_Update">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->DnsUrl_Update->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($parametros->DnsUrl_Update->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->DnsUrl_Update->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
	<?php if ($parametros->SortUrl($parametros->WifiSSID) == "") { ?>
		<th data-name="WifiSSID"><div id="elh_parametros_WifiSSID" class="parametros_WifiSSID"><div class="ewTableHeaderCaption"><?php echo $parametros->WifiSSID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="WifiSSID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->WifiSSID) ?>',2);"><div id="elh_parametros_WifiSSID" class="parametros_WifiSSID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->WifiSSID->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($parametros->WifiSSID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->WifiSSID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
	<?php if ($parametros->SortUrl($parametros->WifiPasswd) == "") { ?>
		<th data-name="WifiPasswd"><div id="elh_parametros_WifiPasswd" class="parametros_WifiPasswd"><div class="ewTableHeaderCaption"><?php echo $parametros->WifiPasswd->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="WifiPasswd"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parametros->SortUrl($parametros->WifiPasswd) ?>',2);"><div id="elh_parametros_WifiPasswd" class="parametros_WifiPasswd">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parametros->WifiPasswd->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($parametros->WifiPasswd->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parametros->WifiPasswd->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$parametros_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($parametros->CurrentAction == "add" || $parametros->CurrentAction == "copy") {
		$parametros_list->RowIndex = 0;
		$parametros_list->KeyCount = $parametros_list->RowIndex;
		if ($parametros->CurrentAction == "copy" && !$parametros_list->LoadRow())
				$parametros->CurrentAction = "add";
		if ($parametros->CurrentAction == "add")
			$parametros_list->LoadDefaultValues();
		if ($parametros->EventCancelled) // Insert failed
			$parametros_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$parametros->ResetAttrs();
		$parametros->RowAttrs = array_merge($parametros->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_parametros', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$parametros->RowType = EW_ROWTYPE_ADD;

		// Render row
		$parametros_list->RenderRow();

		// Render list options
		$parametros_list->RenderListOptions();
		$parametros_list->StartRowCnt = 0;
?>
	<tr<?php echo $parametros->RowAttributes() ?>>
<?php

// Render list options (body, left)
$parametros_list->ListOptions->Render("body", "left", $parametros_list->RowCnt);
?>
	<?php if ($parametros->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="parametros" data-field="x_id" name="o<?php echo $parametros_list->RowIndex ?>_id" id="o<?php echo $parametros_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($parametros->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->temp_min->Visible) { // temp_min ?>
		<td data-name="temp_min">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_min" class="form-group parametros_temp_min">
<input type="text" data-table="parametros" data-field="x_temp_min" name="x<?php echo $parametros_list->RowIndex ?>_temp_min" id="x<?php echo $parametros_list->RowIndex ?>_temp_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_min->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_min->EditValue ?>"<?php echo $parametros->temp_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_temp_min" name="o<?php echo $parametros_list->RowIndex ?>_temp_min" id="o<?php echo $parametros_list->RowIndex ?>_temp_min" value="<?php echo ew_HtmlEncode($parametros->temp_min->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->temp_max->Visible) { // temp_max ?>
		<td data-name="temp_max">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_max" class="form-group parametros_temp_max">
<input type="text" data-table="parametros" data-field="x_temp_max" name="x<?php echo $parametros_list->RowIndex ?>_temp_max" id="x<?php echo $parametros_list->RowIndex ?>_temp_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_max->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_max->EditValue ?>"<?php echo $parametros->temp_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_temp_max" name="o<?php echo $parametros_list->RowIndex ?>_temp_max" id="o<?php echo $parametros_list->RowIndex ?>_temp_max" value="<?php echo ew_HtmlEncode($parametros->temp_max->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->co_min->Visible) { // co_min ?>
		<td data-name="co_min">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_min" class="form-group parametros_co_min">
<input type="text" data-table="parametros" data-field="x_co_min" name="x<?php echo $parametros_list->RowIndex ?>_co_min" id="x<?php echo $parametros_list->RowIndex ?>_co_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_min->getPlaceHolder()) ?>" value="<?php echo $parametros->co_min->EditValue ?>"<?php echo $parametros->co_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_co_min" name="o<?php echo $parametros_list->RowIndex ?>_co_min" id="o<?php echo $parametros_list->RowIndex ?>_co_min" value="<?php echo ew_HtmlEncode($parametros->co_min->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->co_max->Visible) { // co_max ?>
		<td data-name="co_max">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_max" class="form-group parametros_co_max">
<input type="text" data-table="parametros" data-field="x_co_max" name="x<?php echo $parametros_list->RowIndex ?>_co_max" id="x<?php echo $parametros_list->RowIndex ?>_co_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_max->getPlaceHolder()) ?>" value="<?php echo $parametros->co_max->EditValue ?>"<?php echo $parametros->co_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_co_max" name="o<?php echo $parametros_list->RowIndex ?>_co_max" id="o<?php echo $parametros_list->RowIndex ?>_co_max" value="<?php echo ew_HtmlEncode($parametros->co_max->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
		<td data-name="horas_crecimiento">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_crecimiento" class="form-group parametros_horas_crecimiento">
<input type="text" data-table="parametros" data-field="x_horas_crecimiento" name="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_crecimiento->EditValue ?>"<?php echo $parametros->horas_crecimiento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_horas_crecimiento" name="o<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="o<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" value="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
		<td data-name="horas_floracion">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_floracion" class="form-group parametros_horas_floracion">
<input type="text" data-table="parametros" data-field="x_horas_floracion" name="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_floracion->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_floracion->EditValue ?>"<?php echo $parametros->horas_floracion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_horas_floracion" name="o<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="o<?php echo $parametros_list->RowIndex ?>_horas_floracion" value="<?php echo ew_HtmlEncode($parametros->horas_floracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->hum_min->Visible) { // hum_min ?>
		<td data-name="hum_min">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_min" class="form-group parametros_hum_min">
<input type="text" data-table="parametros" data-field="x_hum_min" name="x<?php echo $parametros_list->RowIndex ?>_hum_min" id="x<?php echo $parametros_list->RowIndex ?>_hum_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_min->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_min->EditValue ?>"<?php echo $parametros->hum_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_hum_min" name="o<?php echo $parametros_list->RowIndex ?>_hum_min" id="o<?php echo $parametros_list->RowIndex ?>_hum_min" value="<?php echo ew_HtmlEncode($parametros->hum_min->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->hum_max->Visible) { // hum_max ?>
		<td data-name="hum_max">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_max" class="form-group parametros_hum_max">
<input type="text" data-table="parametros" data-field="x_hum_max" name="x<?php echo $parametros_list->RowIndex ?>_hum_max" id="x<?php echo $parametros_list->RowIndex ?>_hum_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_max->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_max->EditValue ?>"<?php echo $parametros->hum_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_hum_max" name="o<?php echo $parametros_list->RowIndex ?>_hum_max" id="o<?php echo $parametros_list->RowIndex ?>_hum_max" value="<?php echo ew_HtmlEncode($parametros->hum_max->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
		<td data-name="DnsHost">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsHost" class="form-group parametros_DnsHost">
<input type="text" data-table="parametros" data-field="x_DnsHost" name="x<?php echo $parametros_list->RowIndex ?>_DnsHost" id="x<?php echo $parametros_list->RowIndex ?>_DnsHost" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsHost->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsHost->EditValue ?>"<?php echo $parametros->DnsHost->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsHost" name="o<?php echo $parametros_list->RowIndex ?>_DnsHost" id="o<?php echo $parametros_list->RowIndex ?>_DnsHost" value="<?php echo ew_HtmlEncode($parametros->DnsHost->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
		<td data-name="DnsUser">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUser" class="form-group parametros_DnsUser">
<input type="text" data-table="parametros" data-field="x_DnsUser" name="x<?php echo $parametros_list->RowIndex ?>_DnsUser" id="x<?php echo $parametros_list->RowIndex ?>_DnsUser" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUser->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUser->EditValue ?>"<?php echo $parametros->DnsUser->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsUser" name="o<?php echo $parametros_list->RowIndex ?>_DnsUser" id="o<?php echo $parametros_list->RowIndex ?>_DnsUser" value="<?php echo ew_HtmlEncode($parametros->DnsUser->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
		<td data-name="DnsPasswd">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsPasswd" class="form-group parametros_DnsPasswd">
<input type="text" data-table="parametros" data-field="x_DnsPasswd" name="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsPasswd->EditValue ?>"<?php echo $parametros->DnsPasswd->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsPasswd" name="o<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="o<?php echo $parametros_list->RowIndex ?>_DnsPasswd" value="<?php echo ew_HtmlEncode($parametros->DnsPasswd->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
		<td data-name="DnsUrl_Update">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUrl_Update" class="form-group parametros_DnsUrl_Update">
<input type="text" data-table="parametros" data-field="x_DnsUrl_Update" name="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUrl_Update->EditValue ?>"<?php echo $parametros->DnsUrl_Update->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsUrl_Update" name="o<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="o<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" value="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
		<td data-name="WifiSSID">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiSSID" class="form-group parametros_WifiSSID">
<input type="text" data-table="parametros" data-field="x_WifiSSID" name="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($parametros->WifiSSID->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiSSID->EditValue ?>"<?php echo $parametros->WifiSSID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_WifiSSID" name="o<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="o<?php echo $parametros_list->RowIndex ?>_WifiSSID" value="<?php echo ew_HtmlEncode($parametros->WifiSSID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
		<td data-name="WifiPasswd">
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiPasswd" class="form-group parametros_WifiPasswd">
<input type="text" data-table="parametros" data-field="x_WifiPasswd" name="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->WifiPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiPasswd->EditValue ?>"<?php echo $parametros->WifiPasswd->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_WifiPasswd" name="o<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="o<?php echo $parametros_list->RowIndex ?>_WifiPasswd" value="<?php echo ew_HtmlEncode($parametros->WifiPasswd->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$parametros_list->ListOptions->Render("body", "right", $parametros_list->RowCnt);
?>
<script type="text/javascript">
fparametroslist.UpdateOpts(<?php echo $parametros_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($parametros->ExportAll && $parametros->Export <> "") {
	$parametros_list->StopRec = $parametros_list->TotalRecs;
} else {

	// Set the last record to display
	if ($parametros_list->TotalRecs > $parametros_list->StartRec + $parametros_list->DisplayRecs - 1)
		$parametros_list->StopRec = $parametros_list->StartRec + $parametros_list->DisplayRecs - 1;
	else
		$parametros_list->StopRec = $parametros_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($parametros_list->FormKeyCountName) && ($parametros->CurrentAction == "gridadd" || $parametros->CurrentAction == "gridedit" || $parametros->CurrentAction == "F")) {
		$parametros_list->KeyCount = $objForm->GetValue($parametros_list->FormKeyCountName);
		$parametros_list->StopRec = $parametros_list->StartRec + $parametros_list->KeyCount - 1;
	}
}
$parametros_list->RecCnt = $parametros_list->StartRec - 1;
if ($parametros_list->Recordset && !$parametros_list->Recordset->EOF) {
	$parametros_list->Recordset->MoveFirst();
	$bSelectLimit = $parametros_list->UseSelectLimit;
	if (!$bSelectLimit && $parametros_list->StartRec > 1)
		$parametros_list->Recordset->Move($parametros_list->StartRec - 1);
} elseif (!$parametros->AllowAddDeleteRow && $parametros_list->StopRec == 0) {
	$parametros_list->StopRec = $parametros->GridAddRowCount;
}

// Initialize aggregate
$parametros->RowType = EW_ROWTYPE_AGGREGATEINIT;
$parametros->ResetAttrs();
$parametros_list->RenderRow();
$parametros_list->EditRowCnt = 0;
if ($parametros->CurrentAction == "edit")
	$parametros_list->RowIndex = 1;
if ($parametros->CurrentAction == "gridadd")
	$parametros_list->RowIndex = 0;
if ($parametros->CurrentAction == "gridedit")
	$parametros_list->RowIndex = 0;
while ($parametros_list->RecCnt < $parametros_list->StopRec) {
	$parametros_list->RecCnt++;
	if (intval($parametros_list->RecCnt) >= intval($parametros_list->StartRec)) {
		$parametros_list->RowCnt++;
		if ($parametros->CurrentAction == "gridadd" || $parametros->CurrentAction == "gridedit" || $parametros->CurrentAction == "F") {
			$parametros_list->RowIndex++;
			$objForm->Index = $parametros_list->RowIndex;
			if ($objForm->HasValue($parametros_list->FormActionName))
				$parametros_list->RowAction = strval($objForm->GetValue($parametros_list->FormActionName));
			elseif ($parametros->CurrentAction == "gridadd")
				$parametros_list->RowAction = "insert";
			else
				$parametros_list->RowAction = "";
		}

		// Set up key count
		$parametros_list->KeyCount = $parametros_list->RowIndex;

		// Init row class and style
		$parametros->ResetAttrs();
		$parametros->CssClass = "";
		if ($parametros->CurrentAction == "gridadd") {
			$parametros_list->LoadDefaultValues(); // Load default values
		} else {
			$parametros_list->LoadRowValues($parametros_list->Recordset); // Load row values
		}
		$parametros->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($parametros->CurrentAction == "gridadd") // Grid add
			$parametros->RowType = EW_ROWTYPE_ADD; // Render add
		if ($parametros->CurrentAction == "gridadd" && $parametros->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$parametros_list->RestoreCurrentRowFormValues($parametros_list->RowIndex); // Restore form values
		if ($parametros->CurrentAction == "edit") {
			if ($parametros_list->CheckInlineEditKey() && $parametros_list->EditRowCnt == 0) { // Inline edit
				$parametros->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($parametros->CurrentAction == "gridedit") { // Grid edit
			if ($parametros->EventCancelled) {
				$parametros_list->RestoreCurrentRowFormValues($parametros_list->RowIndex); // Restore form values
			}
			if ($parametros_list->RowAction == "insert")
				$parametros->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$parametros->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($parametros->CurrentAction == "edit" && $parametros->RowType == EW_ROWTYPE_EDIT && $parametros->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$parametros_list->RestoreFormValues(); // Restore form values
		}
		if ($parametros->CurrentAction == "gridedit" && ($parametros->RowType == EW_ROWTYPE_EDIT || $parametros->RowType == EW_ROWTYPE_ADD) && $parametros->EventCancelled) // Update failed
			$parametros_list->RestoreCurrentRowFormValues($parametros_list->RowIndex); // Restore form values
		if ($parametros->RowType == EW_ROWTYPE_EDIT) // Edit row
			$parametros_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$parametros->RowAttrs = array_merge($parametros->RowAttrs, array('data-rowindex'=>$parametros_list->RowCnt, 'id'=>'r' . $parametros_list->RowCnt . '_parametros', 'data-rowtype'=>$parametros->RowType));

		// Render row
		$parametros_list->RenderRow();

		// Render list options
		$parametros_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($parametros_list->RowAction <> "delete" && $parametros_list->RowAction <> "insertdelete" && !($parametros_list->RowAction == "insert" && $parametros->CurrentAction == "F" && $parametros_list->EmptyRow())) {
?>
	<tr<?php echo $parametros->RowAttributes() ?>>
<?php

// Render list options (body, left)
$parametros_list->ListOptions->Render("body", "left", $parametros_list->RowCnt);
?>
	<?php if ($parametros->id->Visible) { // id ?>
		<td data-name="id"<?php echo $parametros->id->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="parametros" data-field="x_id" name="o<?php echo $parametros_list->RowIndex ?>_id" id="o<?php echo $parametros_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($parametros->id->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_id" class="form-group parametros_id">
<span<?php echo $parametros->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $parametros->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="parametros" data-field="x_id" name="x<?php echo $parametros_list->RowIndex ?>_id" id="x<?php echo $parametros_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($parametros->id->CurrentValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_id" class="parametros_id">
<span<?php echo $parametros->id->ViewAttributes() ?>>
<?php echo $parametros->id->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $parametros_list->PageObjName . "_row_" . $parametros_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parametros->temp_min->Visible) { // temp_min ?>
		<td data-name="temp_min"<?php echo $parametros->temp_min->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_min" class="form-group parametros_temp_min">
<input type="text" data-table="parametros" data-field="x_temp_min" name="x<?php echo $parametros_list->RowIndex ?>_temp_min" id="x<?php echo $parametros_list->RowIndex ?>_temp_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_min->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_min->EditValue ?>"<?php echo $parametros->temp_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_temp_min" name="o<?php echo $parametros_list->RowIndex ?>_temp_min" id="o<?php echo $parametros_list->RowIndex ?>_temp_min" value="<?php echo ew_HtmlEncode($parametros->temp_min->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_min" class="form-group parametros_temp_min">
<input type="text" data-table="parametros" data-field="x_temp_min" name="x<?php echo $parametros_list->RowIndex ?>_temp_min" id="x<?php echo $parametros_list->RowIndex ?>_temp_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_min->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_min->EditValue ?>"<?php echo $parametros->temp_min->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_min" class="parametros_temp_min">
<span<?php echo $parametros->temp_min->ViewAttributes() ?>>
<?php echo $parametros->temp_min->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->temp_max->Visible) { // temp_max ?>
		<td data-name="temp_max"<?php echo $parametros->temp_max->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_max" class="form-group parametros_temp_max">
<input type="text" data-table="parametros" data-field="x_temp_max" name="x<?php echo $parametros_list->RowIndex ?>_temp_max" id="x<?php echo $parametros_list->RowIndex ?>_temp_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_max->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_max->EditValue ?>"<?php echo $parametros->temp_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_temp_max" name="o<?php echo $parametros_list->RowIndex ?>_temp_max" id="o<?php echo $parametros_list->RowIndex ?>_temp_max" value="<?php echo ew_HtmlEncode($parametros->temp_max->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_max" class="form-group parametros_temp_max">
<input type="text" data-table="parametros" data-field="x_temp_max" name="x<?php echo $parametros_list->RowIndex ?>_temp_max" id="x<?php echo $parametros_list->RowIndex ?>_temp_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_max->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_max->EditValue ?>"<?php echo $parametros->temp_max->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_temp_max" class="parametros_temp_max">
<span<?php echo $parametros->temp_max->ViewAttributes() ?>>
<?php echo $parametros->temp_max->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->co_min->Visible) { // co_min ?>
		<td data-name="co_min"<?php echo $parametros->co_min->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_min" class="form-group parametros_co_min">
<input type="text" data-table="parametros" data-field="x_co_min" name="x<?php echo $parametros_list->RowIndex ?>_co_min" id="x<?php echo $parametros_list->RowIndex ?>_co_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_min->getPlaceHolder()) ?>" value="<?php echo $parametros->co_min->EditValue ?>"<?php echo $parametros->co_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_co_min" name="o<?php echo $parametros_list->RowIndex ?>_co_min" id="o<?php echo $parametros_list->RowIndex ?>_co_min" value="<?php echo ew_HtmlEncode($parametros->co_min->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_min" class="form-group parametros_co_min">
<input type="text" data-table="parametros" data-field="x_co_min" name="x<?php echo $parametros_list->RowIndex ?>_co_min" id="x<?php echo $parametros_list->RowIndex ?>_co_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_min->getPlaceHolder()) ?>" value="<?php echo $parametros->co_min->EditValue ?>"<?php echo $parametros->co_min->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_min" class="parametros_co_min">
<span<?php echo $parametros->co_min->ViewAttributes() ?>>
<?php echo $parametros->co_min->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->co_max->Visible) { // co_max ?>
		<td data-name="co_max"<?php echo $parametros->co_max->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_max" class="form-group parametros_co_max">
<input type="text" data-table="parametros" data-field="x_co_max" name="x<?php echo $parametros_list->RowIndex ?>_co_max" id="x<?php echo $parametros_list->RowIndex ?>_co_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_max->getPlaceHolder()) ?>" value="<?php echo $parametros->co_max->EditValue ?>"<?php echo $parametros->co_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_co_max" name="o<?php echo $parametros_list->RowIndex ?>_co_max" id="o<?php echo $parametros_list->RowIndex ?>_co_max" value="<?php echo ew_HtmlEncode($parametros->co_max->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_max" class="form-group parametros_co_max">
<input type="text" data-table="parametros" data-field="x_co_max" name="x<?php echo $parametros_list->RowIndex ?>_co_max" id="x<?php echo $parametros_list->RowIndex ?>_co_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_max->getPlaceHolder()) ?>" value="<?php echo $parametros->co_max->EditValue ?>"<?php echo $parametros->co_max->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_co_max" class="parametros_co_max">
<span<?php echo $parametros->co_max->ViewAttributes() ?>>
<?php echo $parametros->co_max->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
		<td data-name="horas_crecimiento"<?php echo $parametros->horas_crecimiento->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_crecimiento" class="form-group parametros_horas_crecimiento">
<input type="text" data-table="parametros" data-field="x_horas_crecimiento" name="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_crecimiento->EditValue ?>"<?php echo $parametros->horas_crecimiento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_horas_crecimiento" name="o<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="o<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" value="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_crecimiento" class="form-group parametros_horas_crecimiento">
<input type="text" data-table="parametros" data-field="x_horas_crecimiento" name="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_crecimiento->EditValue ?>"<?php echo $parametros->horas_crecimiento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_crecimiento" class="parametros_horas_crecimiento">
<span<?php echo $parametros->horas_crecimiento->ViewAttributes() ?>>
<?php echo $parametros->horas_crecimiento->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
		<td data-name="horas_floracion"<?php echo $parametros->horas_floracion->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_floracion" class="form-group parametros_horas_floracion">
<input type="text" data-table="parametros" data-field="x_horas_floracion" name="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_floracion->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_floracion->EditValue ?>"<?php echo $parametros->horas_floracion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_horas_floracion" name="o<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="o<?php echo $parametros_list->RowIndex ?>_horas_floracion" value="<?php echo ew_HtmlEncode($parametros->horas_floracion->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_floracion" class="form-group parametros_horas_floracion">
<input type="text" data-table="parametros" data-field="x_horas_floracion" name="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_floracion->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_floracion->EditValue ?>"<?php echo $parametros->horas_floracion->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_horas_floracion" class="parametros_horas_floracion">
<span<?php echo $parametros->horas_floracion->ViewAttributes() ?>>
<?php echo $parametros->horas_floracion->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->hum_min->Visible) { // hum_min ?>
		<td data-name="hum_min"<?php echo $parametros->hum_min->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_min" class="form-group parametros_hum_min">
<input type="text" data-table="parametros" data-field="x_hum_min" name="x<?php echo $parametros_list->RowIndex ?>_hum_min" id="x<?php echo $parametros_list->RowIndex ?>_hum_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_min->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_min->EditValue ?>"<?php echo $parametros->hum_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_hum_min" name="o<?php echo $parametros_list->RowIndex ?>_hum_min" id="o<?php echo $parametros_list->RowIndex ?>_hum_min" value="<?php echo ew_HtmlEncode($parametros->hum_min->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_min" class="form-group parametros_hum_min">
<input type="text" data-table="parametros" data-field="x_hum_min" name="x<?php echo $parametros_list->RowIndex ?>_hum_min" id="x<?php echo $parametros_list->RowIndex ?>_hum_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_min->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_min->EditValue ?>"<?php echo $parametros->hum_min->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_min" class="parametros_hum_min">
<span<?php echo $parametros->hum_min->ViewAttributes() ?>>
<?php echo $parametros->hum_min->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->hum_max->Visible) { // hum_max ?>
		<td data-name="hum_max"<?php echo $parametros->hum_max->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_max" class="form-group parametros_hum_max">
<input type="text" data-table="parametros" data-field="x_hum_max" name="x<?php echo $parametros_list->RowIndex ?>_hum_max" id="x<?php echo $parametros_list->RowIndex ?>_hum_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_max->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_max->EditValue ?>"<?php echo $parametros->hum_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_hum_max" name="o<?php echo $parametros_list->RowIndex ?>_hum_max" id="o<?php echo $parametros_list->RowIndex ?>_hum_max" value="<?php echo ew_HtmlEncode($parametros->hum_max->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_max" class="form-group parametros_hum_max">
<input type="text" data-table="parametros" data-field="x_hum_max" name="x<?php echo $parametros_list->RowIndex ?>_hum_max" id="x<?php echo $parametros_list->RowIndex ?>_hum_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_max->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_max->EditValue ?>"<?php echo $parametros->hum_max->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_hum_max" class="parametros_hum_max">
<span<?php echo $parametros->hum_max->ViewAttributes() ?>>
<?php echo $parametros->hum_max->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
		<td data-name="DnsHost"<?php echo $parametros->DnsHost->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsHost" class="form-group parametros_DnsHost">
<input type="text" data-table="parametros" data-field="x_DnsHost" name="x<?php echo $parametros_list->RowIndex ?>_DnsHost" id="x<?php echo $parametros_list->RowIndex ?>_DnsHost" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsHost->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsHost->EditValue ?>"<?php echo $parametros->DnsHost->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsHost" name="o<?php echo $parametros_list->RowIndex ?>_DnsHost" id="o<?php echo $parametros_list->RowIndex ?>_DnsHost" value="<?php echo ew_HtmlEncode($parametros->DnsHost->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsHost" class="form-group parametros_DnsHost">
<input type="text" data-table="parametros" data-field="x_DnsHost" name="x<?php echo $parametros_list->RowIndex ?>_DnsHost" id="x<?php echo $parametros_list->RowIndex ?>_DnsHost" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsHost->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsHost->EditValue ?>"<?php echo $parametros->DnsHost->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsHost" class="parametros_DnsHost">
<span<?php echo $parametros->DnsHost->ViewAttributes() ?>>
<?php echo $parametros->DnsHost->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
		<td data-name="DnsUser"<?php echo $parametros->DnsUser->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUser" class="form-group parametros_DnsUser">
<input type="text" data-table="parametros" data-field="x_DnsUser" name="x<?php echo $parametros_list->RowIndex ?>_DnsUser" id="x<?php echo $parametros_list->RowIndex ?>_DnsUser" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUser->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUser->EditValue ?>"<?php echo $parametros->DnsUser->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsUser" name="o<?php echo $parametros_list->RowIndex ?>_DnsUser" id="o<?php echo $parametros_list->RowIndex ?>_DnsUser" value="<?php echo ew_HtmlEncode($parametros->DnsUser->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUser" class="form-group parametros_DnsUser">
<input type="text" data-table="parametros" data-field="x_DnsUser" name="x<?php echo $parametros_list->RowIndex ?>_DnsUser" id="x<?php echo $parametros_list->RowIndex ?>_DnsUser" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUser->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUser->EditValue ?>"<?php echo $parametros->DnsUser->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUser" class="parametros_DnsUser">
<span<?php echo $parametros->DnsUser->ViewAttributes() ?>>
<?php echo $parametros->DnsUser->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
		<td data-name="DnsPasswd"<?php echo $parametros->DnsPasswd->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsPasswd" class="form-group parametros_DnsPasswd">
<input type="text" data-table="parametros" data-field="x_DnsPasswd" name="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsPasswd->EditValue ?>"<?php echo $parametros->DnsPasswd->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsPasswd" name="o<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="o<?php echo $parametros_list->RowIndex ?>_DnsPasswd" value="<?php echo ew_HtmlEncode($parametros->DnsPasswd->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsPasswd" class="form-group parametros_DnsPasswd">
<input type="text" data-table="parametros" data-field="x_DnsPasswd" name="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsPasswd->EditValue ?>"<?php echo $parametros->DnsPasswd->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsPasswd" class="parametros_DnsPasswd">
<span<?php echo $parametros->DnsPasswd->ViewAttributes() ?>>
<?php echo $parametros->DnsPasswd->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
		<td data-name="DnsUrl_Update"<?php echo $parametros->DnsUrl_Update->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUrl_Update" class="form-group parametros_DnsUrl_Update">
<input type="text" data-table="parametros" data-field="x_DnsUrl_Update" name="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUrl_Update->EditValue ?>"<?php echo $parametros->DnsUrl_Update->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsUrl_Update" name="o<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="o<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" value="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUrl_Update" class="form-group parametros_DnsUrl_Update">
<input type="text" data-table="parametros" data-field="x_DnsUrl_Update" name="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUrl_Update->EditValue ?>"<?php echo $parametros->DnsUrl_Update->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_DnsUrl_Update" class="parametros_DnsUrl_Update">
<span<?php echo $parametros->DnsUrl_Update->ViewAttributes() ?>>
<?php echo $parametros->DnsUrl_Update->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
		<td data-name="WifiSSID"<?php echo $parametros->WifiSSID->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiSSID" class="form-group parametros_WifiSSID">
<input type="text" data-table="parametros" data-field="x_WifiSSID" name="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($parametros->WifiSSID->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiSSID->EditValue ?>"<?php echo $parametros->WifiSSID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_WifiSSID" name="o<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="o<?php echo $parametros_list->RowIndex ?>_WifiSSID" value="<?php echo ew_HtmlEncode($parametros->WifiSSID->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiSSID" class="form-group parametros_WifiSSID">
<input type="text" data-table="parametros" data-field="x_WifiSSID" name="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($parametros->WifiSSID->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiSSID->EditValue ?>"<?php echo $parametros->WifiSSID->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiSSID" class="parametros_WifiSSID">
<span<?php echo $parametros->WifiSSID->ViewAttributes() ?>>
<?php echo $parametros->WifiSSID->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
		<td data-name="WifiPasswd"<?php echo $parametros->WifiPasswd->CellAttributes() ?>>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiPasswd" class="form-group parametros_WifiPasswd">
<input type="text" data-table="parametros" data-field="x_WifiPasswd" name="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->WifiPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiPasswd->EditValue ?>"<?php echo $parametros->WifiPasswd->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_WifiPasswd" name="o<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="o<?php echo $parametros_list->RowIndex ?>_WifiPasswd" value="<?php echo ew_HtmlEncode($parametros->WifiPasswd->OldValue) ?>">
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiPasswd" class="form-group parametros_WifiPasswd">
<input type="text" data-table="parametros" data-field="x_WifiPasswd" name="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->WifiPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiPasswd->EditValue ?>"<?php echo $parametros->WifiPasswd->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parametros->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $parametros_list->RowCnt ?>_parametros_WifiPasswd" class="parametros_WifiPasswd">
<span<?php echo $parametros->WifiPasswd->ViewAttributes() ?>>
<?php echo $parametros->WifiPasswd->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$parametros_list->ListOptions->Render("body", "right", $parametros_list->RowCnt);
?>
	</tr>
<?php if ($parametros->RowType == EW_ROWTYPE_ADD || $parametros->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fparametroslist.UpdateOpts(<?php echo $parametros_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($parametros->CurrentAction <> "gridadd")
		if (!$parametros_list->Recordset->EOF) $parametros_list->Recordset->MoveNext();
}
?>
<?php
	if ($parametros->CurrentAction == "gridadd" || $parametros->CurrentAction == "gridedit") {
		$parametros_list->RowIndex = '$rowindex$';
		$parametros_list->LoadDefaultValues();

		// Set row properties
		$parametros->ResetAttrs();
		$parametros->RowAttrs = array_merge($parametros->RowAttrs, array('data-rowindex'=>$parametros_list->RowIndex, 'id'=>'r0_parametros', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($parametros->RowAttrs["class"], "ewTemplate");
		$parametros->RowType = EW_ROWTYPE_ADD;

		// Render row
		$parametros_list->RenderRow();

		// Render list options
		$parametros_list->RenderListOptions();
		$parametros_list->StartRowCnt = 0;
?>
	<tr<?php echo $parametros->RowAttributes() ?>>
<?php

// Render list options (body, left)
$parametros_list->ListOptions->Render("body", "left", $parametros_list->RowIndex);
?>
	<?php if ($parametros->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="parametros" data-field="x_id" name="o<?php echo $parametros_list->RowIndex ?>_id" id="o<?php echo $parametros_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($parametros->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->temp_min->Visible) { // temp_min ?>
		<td data-name="temp_min">
<span id="el$rowindex$_parametros_temp_min" class="form-group parametros_temp_min">
<input type="text" data-table="parametros" data-field="x_temp_min" name="x<?php echo $parametros_list->RowIndex ?>_temp_min" id="x<?php echo $parametros_list->RowIndex ?>_temp_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_min->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_min->EditValue ?>"<?php echo $parametros->temp_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_temp_min" name="o<?php echo $parametros_list->RowIndex ?>_temp_min" id="o<?php echo $parametros_list->RowIndex ?>_temp_min" value="<?php echo ew_HtmlEncode($parametros->temp_min->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->temp_max->Visible) { // temp_max ?>
		<td data-name="temp_max">
<span id="el$rowindex$_parametros_temp_max" class="form-group parametros_temp_max">
<input type="text" data-table="parametros" data-field="x_temp_max" name="x<?php echo $parametros_list->RowIndex ?>_temp_max" id="x<?php echo $parametros_list->RowIndex ?>_temp_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->temp_max->getPlaceHolder()) ?>" value="<?php echo $parametros->temp_max->EditValue ?>"<?php echo $parametros->temp_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_temp_max" name="o<?php echo $parametros_list->RowIndex ?>_temp_max" id="o<?php echo $parametros_list->RowIndex ?>_temp_max" value="<?php echo ew_HtmlEncode($parametros->temp_max->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->co_min->Visible) { // co_min ?>
		<td data-name="co_min">
<span id="el$rowindex$_parametros_co_min" class="form-group parametros_co_min">
<input type="text" data-table="parametros" data-field="x_co_min" name="x<?php echo $parametros_list->RowIndex ?>_co_min" id="x<?php echo $parametros_list->RowIndex ?>_co_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_min->getPlaceHolder()) ?>" value="<?php echo $parametros->co_min->EditValue ?>"<?php echo $parametros->co_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_co_min" name="o<?php echo $parametros_list->RowIndex ?>_co_min" id="o<?php echo $parametros_list->RowIndex ?>_co_min" value="<?php echo ew_HtmlEncode($parametros->co_min->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->co_max->Visible) { // co_max ?>
		<td data-name="co_max">
<span id="el$rowindex$_parametros_co_max" class="form-group parametros_co_max">
<input type="text" data-table="parametros" data-field="x_co_max" name="x<?php echo $parametros_list->RowIndex ?>_co_max" id="x<?php echo $parametros_list->RowIndex ?>_co_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->co_max->getPlaceHolder()) ?>" value="<?php echo $parametros->co_max->EditValue ?>"<?php echo $parametros->co_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_co_max" name="o<?php echo $parametros_list->RowIndex ?>_co_max" id="o<?php echo $parametros_list->RowIndex ?>_co_max" value="<?php echo ew_HtmlEncode($parametros->co_max->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->horas_crecimiento->Visible) { // horas_crecimiento ?>
		<td data-name="horas_crecimiento">
<span id="el$rowindex$_parametros_horas_crecimiento" class="form-group parametros_horas_crecimiento">
<input type="text" data-table="parametros" data-field="x_horas_crecimiento" name="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="x<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_crecimiento->EditValue ?>"<?php echo $parametros->horas_crecimiento->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_horas_crecimiento" name="o<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" id="o<?php echo $parametros_list->RowIndex ?>_horas_crecimiento" value="<?php echo ew_HtmlEncode($parametros->horas_crecimiento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->horas_floracion->Visible) { // horas_floracion ?>
		<td data-name="horas_floracion">
<span id="el$rowindex$_parametros_horas_floracion" class="form-group parametros_horas_floracion">
<input type="text" data-table="parametros" data-field="x_horas_floracion" name="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="x<?php echo $parametros_list->RowIndex ?>_horas_floracion" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->horas_floracion->getPlaceHolder()) ?>" value="<?php echo $parametros->horas_floracion->EditValue ?>"<?php echo $parametros->horas_floracion->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_horas_floracion" name="o<?php echo $parametros_list->RowIndex ?>_horas_floracion" id="o<?php echo $parametros_list->RowIndex ?>_horas_floracion" value="<?php echo ew_HtmlEncode($parametros->horas_floracion->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->hum_min->Visible) { // hum_min ?>
		<td data-name="hum_min">
<span id="el$rowindex$_parametros_hum_min" class="form-group parametros_hum_min">
<input type="text" data-table="parametros" data-field="x_hum_min" name="x<?php echo $parametros_list->RowIndex ?>_hum_min" id="x<?php echo $parametros_list->RowIndex ?>_hum_min" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_min->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_min->EditValue ?>"<?php echo $parametros->hum_min->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_hum_min" name="o<?php echo $parametros_list->RowIndex ?>_hum_min" id="o<?php echo $parametros_list->RowIndex ?>_hum_min" value="<?php echo ew_HtmlEncode($parametros->hum_min->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->hum_max->Visible) { // hum_max ?>
		<td data-name="hum_max">
<span id="el$rowindex$_parametros_hum_max" class="form-group parametros_hum_max">
<input type="text" data-table="parametros" data-field="x_hum_max" name="x<?php echo $parametros_list->RowIndex ?>_hum_max" id="x<?php echo $parametros_list->RowIndex ?>_hum_max" size="30" placeholder="<?php echo ew_HtmlEncode($parametros->hum_max->getPlaceHolder()) ?>" value="<?php echo $parametros->hum_max->EditValue ?>"<?php echo $parametros->hum_max->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_hum_max" name="o<?php echo $parametros_list->RowIndex ?>_hum_max" id="o<?php echo $parametros_list->RowIndex ?>_hum_max" value="<?php echo ew_HtmlEncode($parametros->hum_max->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsHost->Visible) { // DnsHost ?>
		<td data-name="DnsHost">
<span id="el$rowindex$_parametros_DnsHost" class="form-group parametros_DnsHost">
<input type="text" data-table="parametros" data-field="x_DnsHost" name="x<?php echo $parametros_list->RowIndex ?>_DnsHost" id="x<?php echo $parametros_list->RowIndex ?>_DnsHost" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsHost->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsHost->EditValue ?>"<?php echo $parametros->DnsHost->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsHost" name="o<?php echo $parametros_list->RowIndex ?>_DnsHost" id="o<?php echo $parametros_list->RowIndex ?>_DnsHost" value="<?php echo ew_HtmlEncode($parametros->DnsHost->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsUser->Visible) { // DnsUser ?>
		<td data-name="DnsUser">
<span id="el$rowindex$_parametros_DnsUser" class="form-group parametros_DnsUser">
<input type="text" data-table="parametros" data-field="x_DnsUser" name="x<?php echo $parametros_list->RowIndex ?>_DnsUser" id="x<?php echo $parametros_list->RowIndex ?>_DnsUser" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUser->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUser->EditValue ?>"<?php echo $parametros->DnsUser->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsUser" name="o<?php echo $parametros_list->RowIndex ?>_DnsUser" id="o<?php echo $parametros_list->RowIndex ?>_DnsUser" value="<?php echo ew_HtmlEncode($parametros->DnsUser->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsPasswd->Visible) { // DnsPasswd ?>
		<td data-name="DnsPasswd">
<span id="el$rowindex$_parametros_DnsPasswd" class="form-group parametros_DnsPasswd">
<input type="text" data-table="parametros" data-field="x_DnsPasswd" name="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="x<?php echo $parametros_list->RowIndex ?>_DnsPasswd" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($parametros->DnsPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsPasswd->EditValue ?>"<?php echo $parametros->DnsPasswd->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsPasswd" name="o<?php echo $parametros_list->RowIndex ?>_DnsPasswd" id="o<?php echo $parametros_list->RowIndex ?>_DnsPasswd" value="<?php echo ew_HtmlEncode($parametros->DnsPasswd->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->DnsUrl_Update->Visible) { // DnsUrl_Update ?>
		<td data-name="DnsUrl_Update">
<span id="el$rowindex$_parametros_DnsUrl_Update" class="form-group parametros_DnsUrl_Update">
<input type="text" data-table="parametros" data-field="x_DnsUrl_Update" name="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="x<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->getPlaceHolder()) ?>" value="<?php echo $parametros->DnsUrl_Update->EditValue ?>"<?php echo $parametros->DnsUrl_Update->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_DnsUrl_Update" name="o<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" id="o<?php echo $parametros_list->RowIndex ?>_DnsUrl_Update" value="<?php echo ew_HtmlEncode($parametros->DnsUrl_Update->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->WifiSSID->Visible) { // WifiSSID ?>
		<td data-name="WifiSSID">
<span id="el$rowindex$_parametros_WifiSSID" class="form-group parametros_WifiSSID">
<input type="text" data-table="parametros" data-field="x_WifiSSID" name="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="x<?php echo $parametros_list->RowIndex ?>_WifiSSID" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($parametros->WifiSSID->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiSSID->EditValue ?>"<?php echo $parametros->WifiSSID->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_WifiSSID" name="o<?php echo $parametros_list->RowIndex ?>_WifiSSID" id="o<?php echo $parametros_list->RowIndex ?>_WifiSSID" value="<?php echo ew_HtmlEncode($parametros->WifiSSID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parametros->WifiPasswd->Visible) { // WifiPasswd ?>
		<td data-name="WifiPasswd">
<span id="el$rowindex$_parametros_WifiPasswd" class="form-group parametros_WifiPasswd">
<input type="text" data-table="parametros" data-field="x_WifiPasswd" name="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="x<?php echo $parametros_list->RowIndex ?>_WifiPasswd" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($parametros->WifiPasswd->getPlaceHolder()) ?>" value="<?php echo $parametros->WifiPasswd->EditValue ?>"<?php echo $parametros->WifiPasswd->EditAttributes() ?>>
</span>
<input type="hidden" data-table="parametros" data-field="x_WifiPasswd" name="o<?php echo $parametros_list->RowIndex ?>_WifiPasswd" id="o<?php echo $parametros_list->RowIndex ?>_WifiPasswd" value="<?php echo ew_HtmlEncode($parametros->WifiPasswd->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$parametros_list->ListOptions->Render("body", "right", $parametros_list->RowCnt);
?>
<script type="text/javascript">
fparametroslist.UpdateOpts(<?php echo $parametros_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($parametros->CurrentAction == "add" || $parametros->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $parametros_list->FormKeyCountName ?>" id="<?php echo $parametros_list->FormKeyCountName ?>" value="<?php echo $parametros_list->KeyCount ?>">
<?php } ?>
<?php if ($parametros->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $parametros_list->FormKeyCountName ?>" id="<?php echo $parametros_list->FormKeyCountName ?>" value="<?php echo $parametros_list->KeyCount ?>">
<?php echo $parametros_list->MultiSelectKey ?>
<?php } ?>
<?php if ($parametros->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $parametros_list->FormKeyCountName ?>" id="<?php echo $parametros_list->FormKeyCountName ?>" value="<?php echo $parametros_list->KeyCount ?>">
<?php } ?>
<?php if ($parametros->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $parametros_list->FormKeyCountName ?>" id="<?php echo $parametros_list->FormKeyCountName ?>" value="<?php echo $parametros_list->KeyCount ?>">
<?php echo $parametros_list->MultiSelectKey ?>
<?php } ?>
<?php if ($parametros->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($parametros_list->Recordset)
	$parametros_list->Recordset->Close();
?>
</div>
<?php } ?>
<?php if ($parametros_list->TotalRecs == 0 && $parametros->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($parametros_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fparametroslistsrch.Init();
fparametroslistsrch.FilterList = <?php echo $parametros_list->GetFilterList() ?>;
fparametroslist.Init();
</script>
<?php
$parametros_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parametros_list->Page_Terminate();
?>
