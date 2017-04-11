<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php

// Global variable for table object
$Report1 = NULL;

//
// Table class for Report1
//
class cReport1 extends cTableBase {
	var $id;
	var $fecha;
	var $hora;
	var $temp;
	var $hum;
	var $co2ppm;
	var $higromet;
	var $luz;
	var $maqhum;
	var $maqdesh;
	var $maqcale;
	var $modman;
	var $periodo;
	var $horasluz;
	var $fechaini;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Report1';
		$this->TableName = 'Report1';
		$this->TableType = 'REPORT';

		// Update Table
		$this->UpdateTable = "`view1`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// id
		$this->id = new cField('Report1', 'Report1', 'x_id', 'id', '`id`', '`id`', 20, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// fecha
		$this->fecha = new cField('Report1', 'Report1', 'x_fecha', 'fecha', '`fecha`', 'DATE_FORMAT(`fecha`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fecha'] = &$this->fecha;

		// hora
		$this->hora = new cField('Report1', 'Report1', 'x_hora', 'hora', '`hora`', 'DATE_FORMAT(`hora`, \'%d/%m/%Y\')', 134, 7, FALSE, '`hora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hora->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['hora'] = &$this->hora;

		// temp
		$this->temp = new cField('Report1', 'Report1', 'x_temp', 'temp', '`temp`', '`temp`', 4, -1, FALSE, '`temp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->temp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['temp'] = &$this->temp;

		// hum
		$this->hum = new cField('Report1', 'Report1', 'x_hum', 'hum', '`hum`', '`hum`', 4, -1, FALSE, '`hum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hum->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['hum'] = &$this->hum;

		// co2ppm
		$this->co2ppm = new cField('Report1', 'Report1', 'x_co2ppm', 'co2ppm', '`co2ppm`', '`co2ppm`', 4, -1, FALSE, '`co2ppm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->co2ppm->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['co2ppm'] = &$this->co2ppm;

		// higromet
		$this->higromet = new cField('Report1', 'Report1', 'x_higromet', 'higromet', '`higromet`', '`higromet`', 4, -1, FALSE, '`higromet`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->higromet->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['higromet'] = &$this->higromet;

		// luz
		$this->luz = new cField('Report1', 'Report1', 'x_luz', 'luz', '`luz`', '`luz`', 200, -1, FALSE, '`luz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['luz'] = &$this->luz;

		// maqhum
		$this->maqhum = new cField('Report1', 'Report1', 'x_maqhum', 'maqhum', '`maqhum`', '`maqhum`', 16, -1, FALSE, '`maqhum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->maqhum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maqhum'] = &$this->maqhum;

		// maqdesh
		$this->maqdesh = new cField('Report1', 'Report1', 'x_maqdesh', 'maqdesh', '`maqdesh`', '`maqdesh`', 16, -1, FALSE, '`maqdesh`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->maqdesh->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maqdesh'] = &$this->maqdesh;

		// maqcale
		$this->maqcale = new cField('Report1', 'Report1', 'x_maqcale', 'maqcale', '`maqcale`', '`maqcale`', 16, -1, FALSE, '`maqcale`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->maqcale->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maqcale'] = &$this->maqcale;

		// modman
		$this->modman = new cField('Report1', 'Report1', 'x_modman', 'modman', '`modman`', '`modman`', 16, -1, FALSE, '`modman`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modman->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modman'] = &$this->modman;

		// periodo
		$this->periodo = new cField('Report1', 'Report1', 'x_periodo', 'periodo', '`periodo`', '`periodo`', 16, -1, FALSE, '`periodo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periodo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['periodo'] = &$this->periodo;

		// horasluz
		$this->horasluz = new cField('Report1', 'Report1', 'x_horasluz', 'horasluz', '`horasluz`', '`horasluz`', 16, -1, FALSE, '`horasluz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->horasluz->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['horasluz'] = &$this->horasluz;

		// fechaini
		$this->fechaini = new cField('Report1', 'Report1', 'x_fechaini', 'fechaini', '`fechaini`', 'DATE_FORMAT(`fechaini`, \'%d/%m/%Y\')', 133, 7, FALSE, '`fechaini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaini->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['fechaini'] = &$this->fechaini;
	}

	// Report detail level SQL
	var $_SqlDetailSelect = "";

	function getSqlDetailSelect() { // Select
		return ($this->_SqlDetailSelect <> "") ? $this->_SqlDetailSelect : "SELECT * FROM `view1`";
	}

	function SqlDetailSelect() { // For backward compatibility
    	return $this->getSqlDetailSelect();
	}

	function setSqlDetailSelect($v) {
    	$this->_SqlDetailSelect = $v;
	}
	var $_SqlDetailWhere = "";

	function getSqlDetailWhere() { // Where
		return ($this->_SqlDetailWhere <> "") ? $this->_SqlDetailWhere : "";
	}

	function SqlDetailWhere() { // For backward compatibility
    	return $this->getSqlDetailWhere();
	}

	function setSqlDetailWhere($v) {
    	$this->_SqlDetailWhere = $v;
	}
	var $_SqlDetailGroupBy = "";

	function getSqlDetailGroupBy() { // Group By
		return ($this->_SqlDetailGroupBy <> "") ? $this->_SqlDetailGroupBy : "";
	}

	function SqlDetailGroupBy() { // For backward compatibility
    	return $this->getSqlDetailGroupBy();
	}

	function setSqlDetailGroupBy($v) {
    	$this->_SqlDetailGroupBy = $v;
	}
	var $_SqlDetailHaving = "";

	function getSqlDetailHaving() { // Having
		return ($this->_SqlDetailHaving <> "") ? $this->_SqlDetailHaving : "";
	}

	function SqlDetailHaving() { // For backward compatibility
    	return $this->getSqlDetailHaving();
	}

	function setSqlDetailHaving($v) {
    	$this->_SqlDetailHaving = $v;
	}
	var $_SqlDetailOrderBy = "";

	function getSqlDetailOrderBy() { // Order By
		return ($this->_SqlDetailOrderBy <> "") ? $this->_SqlDetailOrderBy : "";
	}

	function SqlDetailOrderBy() { // For backward compatibility
    	return $this->getSqlDetailOrderBy();
	}

	function setSqlDetailOrderBy($v) {
    	$this->_SqlDetailOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->getSqlDetailSelect(), $this->getSqlDetailWhere(),
			$this->getSqlDetailGroupBy(), $this->getSqlDetailHaving(),
			$this->getSqlDetailOrderBy(), $sFilter, $sSort);
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "report1report.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "report1report.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "?" . $this->UrlParm($parm);
		else
			$url = "";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["id"]) : ew_StripSlashes(@$_GET["id"]); // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$Report1_report = NULL; // Initialize page object first

class cReport1_report extends cReport1 {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{032690A3-4B26-49FF-B1A0-E08477B5B2A3}";

	// Table name
	var $TableName = 'Report1';

	// Page object name
	var $PageObjName = 'Report1_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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

		// Table object (Report1)
		if (!isset($GLOBALS["Report1"]) || get_class($GLOBALS["Report1"]) == "cReport1") {
			$GLOBALS["Report1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Report1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Report1', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();

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
		global $EW_EXPORT_REPORT;
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
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
	var $RecCnt = 0;
	var $RowCnt = 0; // For custom view tag
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;
	var $RecordExists;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(1, NULL);
		$this->ReportCounts = &ew_InitArray(1, 0);
		$this->LevelBreak = &ew_InitArray(1, FALSE);
		$this->ReportTotals = &ew_Init2DArray(1, 16, 0);
		$this->ReportMaxs = &ew_Init2DArray(1, 16, 0);
		$this->ReportMins = &ew_Init2DArray(1, 16, 0);

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
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
		$this->hora->ViewValue = ew_FormatDateTime($this->hora->ViewValue, 7);
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("report", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Export report to EXCEL
	function ExportReportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EW_CHARSET <> '' ? ';charset=' . EW_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
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
if (!isset($Report1_report)) $Report1_report = new cReport1_report();

// Page init
$Report1_report->Page_Init();

// Page main
$Report1_report->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Report1_report->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($Report1->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<div class="ewToolbar">
<?php if ($Report1->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($Report1->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
$Report1_report->RecCnt = 1; // No grouping
if ($Report1_report->DbDetailFilter <> "") {
	if ($Report1_report->ReportFilter <> "") $Report1_report->ReportFilter .= " AND ";
	$Report1_report->ReportFilter .= "(" . $Report1_report->DbDetailFilter . ")";
}
$ReportConn = &$Report1_report->Connection();

// Set up detail SQL
$Report1->CurrentFilter = $Report1_report->ReportFilter;
$Report1_report->ReportSql = $Report1->DetailSQL();

// Load recordset
$Report1_report->Recordset = $ReportConn->Execute($Report1_report->ReportSql);
$Report1_report->RecordExists = !$Report1_report->Recordset->EOF;
?>
<?php if ($Report1->Export == "") { ?>
<?php if ($Report1_report->RecordExists) { ?>
<div class="ewViewExportOptions"><?php $Report1_report->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php } ?>
<?php $Report1_report->ShowPageHeader(); ?>
<table class="ewReportTable">
<?php

	// Get detail records
	$Report1_report->ReportFilter = $Report1_report->DefaultFilter;
	if ($Report1_report->DbDetailFilter <> "") {
		if ($Report1_report->ReportFilter <> "")
			$Report1_report->ReportFilter .= " AND ";
		$Report1_report->ReportFilter .= "(" . $Report1_report->DbDetailFilter . ")";
	}

	// Set up detail SQL
	$Report1->CurrentFilter = $Report1_report->ReportFilter;
	$Report1_report->ReportSql = $Report1->DetailSQL();

	// Load detail records
	$Report1_report->DetailRecordset = $ReportConn->Execute($Report1_report->ReportSql);
	$Report1_report->DtlRecordCount = $Report1_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Report1_report->DetailRecordset->EOF) {
		$Report1_report->RecCnt++;
	}
	if ($Report1_report->RecCnt == 1) {
		$Report1_report->ReportCounts[0] = 0;
	}
	$Report1_report->ReportCounts[0] += $Report1_report->DtlRecordCount;
	if ($Report1_report->RecordExists) {
?>
	<tr>
		<td class="ewGroupHeader"><?php echo $Report1->id->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->fecha->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->hora->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->temp->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->hum->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->co2ppm->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->higromet->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->luz->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->maqhum->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->maqdesh->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->maqcale->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->modman->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->periodo->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->horasluz->FldCaption() ?></td>
		<td class="ewGroupHeader"><?php echo $Report1->fechaini->FldCaption() ?></td>
	</tr>
<?php
	}
	while (!$Report1_report->DetailRecordset->EOF) {
		$Report1_report->RowCnt++;
		$Report1->id->setDbValue($Report1_report->DetailRecordset->fields('id'));
		$Report1->fecha->setDbValue($Report1_report->DetailRecordset->fields('fecha'));
		$Report1->hora->setDbValue($Report1_report->DetailRecordset->fields('hora'));
		$Report1->temp->setDbValue($Report1_report->DetailRecordset->fields('temp'));
		$Report1->hum->setDbValue($Report1_report->DetailRecordset->fields('hum'));
		$Report1->co2ppm->setDbValue($Report1_report->DetailRecordset->fields('co2ppm'));
		$Report1->higromet->setDbValue($Report1_report->DetailRecordset->fields('higromet'));
		$Report1->luz->setDbValue($Report1_report->DetailRecordset->fields('luz'));
		$Report1->maqhum->setDbValue($Report1_report->DetailRecordset->fields('maqhum'));
		$Report1->maqdesh->setDbValue($Report1_report->DetailRecordset->fields('maqdesh'));
		$Report1->maqcale->setDbValue($Report1_report->DetailRecordset->fields('maqcale'));
		$Report1->modman->setDbValue($Report1_report->DetailRecordset->fields('modman'));
		$Report1->periodo->setDbValue($Report1_report->DetailRecordset->fields('periodo'));
		$Report1->horasluz->setDbValue($Report1_report->DetailRecordset->fields('horasluz'));
		$Report1->fechaini->setDbValue($Report1_report->DetailRecordset->fields('fechaini'));

		// Render for view
		$Report1->RowType = EW_ROWTYPE_VIEW;
		$Report1->ResetAttrs();
		$Report1_report->RenderRow();
?>
	<tr>
		<td<?php echo $Report1->id->CellAttributes() ?>>
<span<?php echo $Report1->id->ViewAttributes() ?>>
<?php echo $Report1->id->ViewValue ?></span>
</td>
		<td<?php echo $Report1->fecha->CellAttributes() ?>>
<span<?php echo $Report1->fecha->ViewAttributes() ?>>
<?php echo $Report1->fecha->ViewValue ?></span>
</td>
		<td<?php echo $Report1->hora->CellAttributes() ?>>
<span<?php echo $Report1->hora->ViewAttributes() ?>>
<?php echo $Report1->hora->ViewValue ?></span>
</td>
		<td<?php echo $Report1->temp->CellAttributes() ?>>
<span<?php echo $Report1->temp->ViewAttributes() ?>>
<?php echo $Report1->temp->ViewValue ?></span>
</td>
		<td<?php echo $Report1->hum->CellAttributes() ?>>
<span<?php echo $Report1->hum->ViewAttributes() ?>>
<?php echo $Report1->hum->ViewValue ?></span>
</td>
		<td<?php echo $Report1->co2ppm->CellAttributes() ?>>
<span<?php echo $Report1->co2ppm->ViewAttributes() ?>>
<?php echo $Report1->co2ppm->ViewValue ?></span>
</td>
		<td<?php echo $Report1->higromet->CellAttributes() ?>>
<span<?php echo $Report1->higromet->ViewAttributes() ?>>
<?php echo $Report1->higromet->ViewValue ?></span>
</td>
		<td<?php echo $Report1->luz->CellAttributes() ?>>
<span<?php echo $Report1->luz->ViewAttributes() ?>>
<?php echo $Report1->luz->ViewValue ?></span>
</td>
		<td<?php echo $Report1->maqhum->CellAttributes() ?>>
<span<?php echo $Report1->maqhum->ViewAttributes() ?>>
<?php echo $Report1->maqhum->ViewValue ?></span>
</td>
		<td<?php echo $Report1->maqdesh->CellAttributes() ?>>
<span<?php echo $Report1->maqdesh->ViewAttributes() ?>>
<?php echo $Report1->maqdesh->ViewValue ?></span>
</td>
		<td<?php echo $Report1->maqcale->CellAttributes() ?>>
<span<?php echo $Report1->maqcale->ViewAttributes() ?>>
<?php echo $Report1->maqcale->ViewValue ?></span>
</td>
		<td<?php echo $Report1->modman->CellAttributes() ?>>
<span<?php echo $Report1->modman->ViewAttributes() ?>>
<?php echo $Report1->modman->ViewValue ?></span>
</td>
		<td<?php echo $Report1->periodo->CellAttributes() ?>>
<span<?php echo $Report1->periodo->ViewAttributes() ?>>
<?php echo $Report1->periodo->ViewValue ?></span>
</td>
		<td<?php echo $Report1->horasluz->CellAttributes() ?>>
<span<?php echo $Report1->horasluz->ViewAttributes() ?>>
<?php echo $Report1->horasluz->ViewValue ?></span>
</td>
		<td<?php echo $Report1->fechaini->CellAttributes() ?>>
<span<?php echo $Report1->fechaini->ViewAttributes() ?>>
<?php echo $Report1->fechaini->ViewValue ?></span>
</td>
	</tr>
<?php
		$Report1_report->DetailRecordset->MoveNext();
	}
	$Report1_report->DetailRecordset->Close();
?>
<?php if ($Report1_report->RecordExists) { ?>
	<tr><td colspan=15>&nbsp;<br></td></tr>
	<tr><td colspan=15 class="ewGrandSummary"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Report1_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</td></tr>
<?php } ?>
<?php if ($Report1_report->RecordExists) { ?>
	<tr><td colspan=15>&nbsp;<br></td></tr>
<?php } else { ?>
	<tr><td><?php echo $Language->Phrase("NoRecord") ?></td></tr>
<?php } ?>
</table>
<?php
$Report1_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Report1->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Report1_report->Page_Terminate();
?>
