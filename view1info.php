<?php

// Global variable for table object
$view1 = NULL;

//
// Table class for view1
//
class cview1 extends cTable {
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
		$this->TableVar = 'view1';
		$this->TableName = 'view1';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`view1`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('view1', 'view1', 'x_id', 'id', '`id`', '`id`', 20, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// fecha
		$this->fecha = new cField('view1', 'view1', 'x_fecha', 'fecha', '`fecha`', '`fecha`', 200, 7, FALSE, '`fecha`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fecha->FldDefaultErrMsg = str_replace("%s", "", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fecha'] = &$this->fecha;

		// hora
		$this->hora = new cField('view1', 'view1', 'x_hora', 'hora', '`hora`', 'DATE_FORMAT(`hora`, \'\')', 134, 4, FALSE, '`hora`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hora->FldDefaultErrMsg = $Language->Phrase("IncorrectTime");
		$this->fields['hora'] = &$this->hora;

		// temp
		$this->temp = new cField('view1', 'view1', 'x_temp', 'temp', '`temp`', '`temp`', 4, -1, FALSE, '`temp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->temp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['temp'] = &$this->temp;

		// hum
		$this->hum = new cField('view1', 'view1', 'x_hum', 'hum', '`hum`', '`hum`', 4, -1, FALSE, '`hum`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hum->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['hum'] = &$this->hum;

		// co2ppm
		$this->co2ppm = new cField('view1', 'view1', 'x_co2ppm', 'co2ppm', '`co2ppm`', '`co2ppm`', 4, -1, FALSE, '`co2ppm`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->co2ppm->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['co2ppm'] = &$this->co2ppm;

		// higromet
		$this->higromet = new cField('view1', 'view1', 'x_higromet', 'higromet', '`higromet`', '`higromet`', 4, -1, FALSE, '`higromet`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->higromet->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['higromet'] = &$this->higromet;

		// luz
		$this->luz = new cField('view1', 'view1', 'x_luz', 'luz', '`luz`', '`luz`', 200, -1, FALSE, '`luz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['luz'] = &$this->luz;

		// maqhum
		$this->maqhum = new cField('view1', 'view1', 'x_maqhum', 'maqhum', '`maqhum`', '`maqhum`', 16, -1, FALSE, '`maqhum`', FALSE, FALSE, FALSE, 'IMAGE', 'TEXT');
		$this->maqhum->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maqhum'] = &$this->maqhum;

		// maqdesh
		$this->maqdesh = new cField('view1', 'view1', 'x_maqdesh', 'maqdesh', '`maqdesh`', '`maqdesh`', 16, -1, FALSE, '`maqdesh`', FALSE, FALSE, FALSE, 'IMAGE', 'TEXT');
		$this->maqdesh->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maqdesh'] = &$this->maqdesh;

		// maqcale
		$this->maqcale = new cField('view1', 'view1', 'x_maqcale', 'maqcale', '`maqcale`', '`maqcale`', 16, -1, FALSE, '`maqcale`', FALSE, FALSE, FALSE, 'IMAGE', 'TEXT');
		$this->maqcale->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['maqcale'] = &$this->maqcale;

		// modman
		$this->modman = new cField('view1', 'view1', 'x_modman', 'modman', '`modman`', '`modman`', 16, -1, FALSE, '`modman`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->modman->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['modman'] = &$this->modman;

		// periodo
		$this->periodo = new cField('view1', 'view1', 'x_periodo', 'periodo', '`periodo`', '`periodo`', 16, -1, FALSE, '`periodo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->periodo->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['periodo'] = &$this->periodo;

		// horasluz
		$this->horasluz = new cField('view1', 'view1', 'x_horasluz', 'horasluz', '`horasluz`', '`horasluz`', 16, -1, FALSE, '`horasluz`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->horasluz->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['horasluz'] = &$this->horasluz;

		// fechaini
		$this->fechaini = new cField('view1', 'view1', 'x_fechaini', 'fechaini', '`fechaini`', 'DATE_FORMAT(`fechaini`, \'\')', 133, 0, FALSE, '`fechaini`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fechaini->FldDefaultErrMsg = str_replace("%s", "", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fechaini'] = &$this->fechaini;
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view1`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
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

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
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
			return "view1list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view1list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("view1view.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("view1view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "view1add.php?" . $this->UrlParm($parm);
		else
			$url = "view1add.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("view1edit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("view1add.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view1delete.php", $this->UrlParm());
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

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// fecha
		$this->fecha->EditAttrs["class"] = "form-control";
		$this->fecha->EditCustomAttributes = "";
		$this->fecha->EditValue = $this->fecha->CurrentValue;
		$this->fecha->PlaceHolder = ew_RemoveHtml($this->fecha->FldCaption());

		// hora
		$this->hora->EditAttrs["class"] = "form-control";
		$this->hora->EditCustomAttributes = "";
		$this->hora->EditValue = $this->hora->CurrentValue;
		$this->hora->PlaceHolder = ew_RemoveHtml($this->hora->FldCaption());

		// temp
		$this->temp->EditAttrs["class"] = "form-control";
		$this->temp->EditCustomAttributes = "";
		$this->temp->EditValue = $this->temp->CurrentValue;
		$this->temp->PlaceHolder = ew_RemoveHtml($this->temp->FldCaption());
		if (strval($this->temp->EditValue) <> "" && is_numeric($this->temp->EditValue)) $this->temp->EditValue = ew_FormatNumber($this->temp->EditValue, -2, -1, -2, 0);

		// hum
		$this->hum->EditAttrs["class"] = "form-control";
		$this->hum->EditCustomAttributes = "";
		$this->hum->EditValue = $this->hum->CurrentValue;
		$this->hum->PlaceHolder = ew_RemoveHtml($this->hum->FldCaption());
		if (strval($this->hum->EditValue) <> "" && is_numeric($this->hum->EditValue)) $this->hum->EditValue = ew_FormatNumber($this->hum->EditValue, -2, -1, -2, 0);

		// co2ppm
		$this->co2ppm->EditAttrs["class"] = "form-control";
		$this->co2ppm->EditCustomAttributes = "";
		$this->co2ppm->EditValue = $this->co2ppm->CurrentValue;
		$this->co2ppm->PlaceHolder = ew_RemoveHtml($this->co2ppm->FldCaption());
		if (strval($this->co2ppm->EditValue) <> "" && is_numeric($this->co2ppm->EditValue)) $this->co2ppm->EditValue = ew_FormatNumber($this->co2ppm->EditValue, -2, -1, -2, 0);

		// higromet
		$this->higromet->EditAttrs["class"] = "form-control";
		$this->higromet->EditCustomAttributes = "";
		$this->higromet->EditValue = $this->higromet->CurrentValue;
		$this->higromet->PlaceHolder = ew_RemoveHtml($this->higromet->FldCaption());
		if (strval($this->higromet->EditValue) <> "" && is_numeric($this->higromet->EditValue)) $this->higromet->EditValue = ew_FormatNumber($this->higromet->EditValue, -2, -1, -2, 0);

		// luz
		$this->luz->EditAttrs["class"] = "form-control";
		$this->luz->EditCustomAttributes = "";
		$this->luz->EditValue = $this->luz->CurrentValue;
		$this->luz->PlaceHolder = ew_RemoveHtml($this->luz->FldCaption());

		// maqhum
		$this->maqhum->EditAttrs["class"] = "form-control";
		$this->maqhum->EditCustomAttributes = "";
		$this->maqhum->EditValue = $this->maqhum->CurrentValue;
		$this->maqhum->PlaceHolder = ew_RemoveHtml($this->maqhum->FldCaption());

		// maqdesh
		$this->maqdesh->EditAttrs["class"] = "form-control";
		$this->maqdesh->EditCustomAttributes = "";
		$this->maqdesh->EditValue = $this->maqdesh->CurrentValue;
		$this->maqdesh->PlaceHolder = ew_RemoveHtml($this->maqdesh->FldCaption());

		// maqcale
		$this->maqcale->EditAttrs["class"] = "form-control";
		$this->maqcale->EditCustomAttributes = "";
		$this->maqcale->EditValue = $this->maqcale->CurrentValue;
		$this->maqcale->PlaceHolder = ew_RemoveHtml($this->maqcale->FldCaption());

		// modman
		$this->modman->EditAttrs["class"] = "form-control";
		$this->modman->EditCustomAttributes = "";
		$this->modman->EditValue = $this->modman->CurrentValue;
		$this->modman->PlaceHolder = ew_RemoveHtml($this->modman->FldCaption());

		// periodo
		$this->periodo->EditAttrs["class"] = "form-control";
		$this->periodo->EditCustomAttributes = "";
		$this->periodo->EditValue = $this->periodo->CurrentValue;
		$this->periodo->PlaceHolder = ew_RemoveHtml($this->periodo->FldCaption());

		// horasluz
		$this->horasluz->EditAttrs["class"] = "form-control";
		$this->horasluz->EditCustomAttributes = "";
		$this->horasluz->EditValue = $this->horasluz->CurrentValue;
		$this->horasluz->PlaceHolder = ew_RemoveHtml($this->horasluz->FldCaption());

		// fechaini
		$this->fechaini->EditAttrs["class"] = "form-control";
		$this->fechaini->EditCustomAttributes = "";
		$this->fechaini->EditValue = ew_FormatDateTime($this->fechaini->CurrentValue, 8);
		$this->fechaini->PlaceHolder = ew_RemoveHtml($this->fechaini->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->hora->Exportable) $Doc->ExportCaption($this->hora);
					if ($this->temp->Exportable) $Doc->ExportCaption($this->temp);
					if ($this->hum->Exportable) $Doc->ExportCaption($this->hum);
					if ($this->co2ppm->Exportable) $Doc->ExportCaption($this->co2ppm);
					if ($this->higromet->Exportable) $Doc->ExportCaption($this->higromet);
					if ($this->luz->Exportable) $Doc->ExportCaption($this->luz);
					if ($this->maqhum->Exportable) $Doc->ExportCaption($this->maqhum);
					if ($this->maqdesh->Exportable) $Doc->ExportCaption($this->maqdesh);
					if ($this->maqcale->Exportable) $Doc->ExportCaption($this->maqcale);
					if ($this->modman->Exportable) $Doc->ExportCaption($this->modman);
					if ($this->periodo->Exportable) $Doc->ExportCaption($this->periodo);
					if ($this->horasluz->Exportable) $Doc->ExportCaption($this->horasluz);
					if ($this->fechaini->Exportable) $Doc->ExportCaption($this->fechaini);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->fecha->Exportable) $Doc->ExportCaption($this->fecha);
					if ($this->hora->Exportable) $Doc->ExportCaption($this->hora);
					if ($this->temp->Exportable) $Doc->ExportCaption($this->temp);
					if ($this->hum->Exportable) $Doc->ExportCaption($this->hum);
					if ($this->co2ppm->Exportable) $Doc->ExportCaption($this->co2ppm);
					if ($this->higromet->Exportable) $Doc->ExportCaption($this->higromet);
					if ($this->luz->Exportable) $Doc->ExportCaption($this->luz);
					if ($this->maqhum->Exportable) $Doc->ExportCaption($this->maqhum);
					if ($this->maqdesh->Exportable) $Doc->ExportCaption($this->maqdesh);
					if ($this->maqcale->Exportable) $Doc->ExportCaption($this->maqcale);
					if ($this->modman->Exportable) $Doc->ExportCaption($this->modman);
					if ($this->periodo->Exportable) $Doc->ExportCaption($this->periodo);
					if ($this->horasluz->Exportable) $Doc->ExportCaption($this->horasluz);
					if ($this->fechaini->Exportable) $Doc->ExportCaption($this->fechaini);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->hora->Exportable) $Doc->ExportField($this->hora);
						if ($this->temp->Exportable) $Doc->ExportField($this->temp);
						if ($this->hum->Exportable) $Doc->ExportField($this->hum);
						if ($this->co2ppm->Exportable) $Doc->ExportField($this->co2ppm);
						if ($this->higromet->Exportable) $Doc->ExportField($this->higromet);
						if ($this->luz->Exportable) $Doc->ExportField($this->luz);
						if ($this->maqhum->Exportable) $Doc->ExportField($this->maqhum);
						if ($this->maqdesh->Exportable) $Doc->ExportField($this->maqdesh);
						if ($this->maqcale->Exportable) $Doc->ExportField($this->maqcale);
						if ($this->modman->Exportable) $Doc->ExportField($this->modman);
						if ($this->periodo->Exportable) $Doc->ExportField($this->periodo);
						if ($this->horasluz->Exportable) $Doc->ExportField($this->horasluz);
						if ($this->fechaini->Exportable) $Doc->ExportField($this->fechaini);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->fecha->Exportable) $Doc->ExportField($this->fecha);
						if ($this->hora->Exportable) $Doc->ExportField($this->hora);
						if ($this->temp->Exportable) $Doc->ExportField($this->temp);
						if ($this->hum->Exportable) $Doc->ExportField($this->hum);
						if ($this->co2ppm->Exportable) $Doc->ExportField($this->co2ppm);
						if ($this->higromet->Exportable) $Doc->ExportField($this->higromet);
						if ($this->luz->Exportable) $Doc->ExportField($this->luz);
						if ($this->maqhum->Exportable) $Doc->ExportField($this->maqhum);
						if ($this->maqdesh->Exportable) $Doc->ExportField($this->maqdesh);
						if ($this->maqcale->Exportable) $Doc->ExportField($this->maqcale);
						if ($this->modman->Exportable) $Doc->ExportField($this->modman);
						if ($this->periodo->Exportable) $Doc->ExportField($this->periodo);
						if ($this->horasluz->Exportable) $Doc->ExportField($this->horasluz);
						if ($this->fechaini->Exportable) $Doc->ExportField($this->fechaini);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

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
