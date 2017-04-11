<?php

// Global variable for table object
$parametros = NULL;

//
// Table class for parametros
//
class cparametros extends cTable {
	var $id;
	var $temp_min;
	var $temp_max;
	var $co_min;
	var $co_max;
	var $horas_crecimiento;
	var $horas_floracion;
	var $hum_min;
	var $hum_max;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'parametros';
		$this->TableName = 'parametros';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`parametros`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('parametros', 'parametros', 'x_id', 'id', '`id`', '`id`', 20, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// temp_min
		$this->temp_min = new cField('parametros', 'parametros', 'x_temp_min', 'temp_min', '`temp_min`', '`temp_min`', 4, -1, FALSE, '`temp_min`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->temp_min->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['temp_min'] = &$this->temp_min;

		// temp_max
		$this->temp_max = new cField('parametros', 'parametros', 'x_temp_max', 'temp_max', '`temp_max`', '`temp_max`', 4, -1, FALSE, '`temp_max`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->temp_max->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['temp_max'] = &$this->temp_max;

		// co_min
		$this->co_min = new cField('parametros', 'parametros', 'x_co_min', 'co_min', '`co_min`', '`co_min`', 3, -1, FALSE, '`co_min`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->co_min->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['co_min'] = &$this->co_min;

		// co_max
		$this->co_max = new cField('parametros', 'parametros', 'x_co_max', 'co_max', '`co_max`', '`co_max`', 3, -1, FALSE, '`co_max`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->co_max->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['co_max'] = &$this->co_max;

		// horas_crecimiento
		$this->horas_crecimiento = new cField('parametros', 'parametros', 'x_horas_crecimiento', 'horas_crecimiento', '`horas_crecimiento`', '`horas_crecimiento`', 3, -1, FALSE, '`horas_crecimiento`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->horas_crecimiento->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['horas_crecimiento'] = &$this->horas_crecimiento;

		// horas_floracion
		$this->horas_floracion = new cField('parametros', 'parametros', 'x_horas_floracion', 'horas_floracion', '`horas_floracion`', '`horas_floracion`', 3, -1, FALSE, '`horas_floracion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->horas_floracion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['horas_floracion'] = &$this->horas_floracion;

		// hum_min
		$this->hum_min = new cField('parametros', 'parametros', 'x_hum_min', 'hum_min', '`hum_min`', '`hum_min`', 3, -1, FALSE, '`hum_min`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hum_min->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hum_min'] = &$this->hum_min;

		// hum_max
		$this->hum_max = new cField('parametros', 'parametros', 'x_hum_max', 'hum_max', '`hum_max`', '`hum_max`', 3, -1, FALSE, '`hum_max`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->hum_max->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hum_max'] = &$this->hum_max;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`parametros`";
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
			return "parametroslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "parametroslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("parametrosview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("parametrosview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "parametrosadd.php?" . $this->UrlParm($parm);
		else
			$url = "parametrosadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("parametrosedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("parametrosadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("parametrosdelete.php", $this->UrlParm());
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
		$this->temp_min->setDbValue($rs->fields('temp_min'));
		$this->temp_max->setDbValue($rs->fields('temp_max'));
		$this->co_min->setDbValue($rs->fields('co_min'));
		$this->co_max->setDbValue($rs->fields('co_max'));
		$this->horas_crecimiento->setDbValue($rs->fields('horas_crecimiento'));
		$this->horas_floracion->setDbValue($rs->fields('horas_floracion'));
		$this->hum_min->setDbValue($rs->fields('hum_min'));
		$this->hum_max->setDbValue($rs->fields('hum_max'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// temp_min
		// temp_max
		// co_min
		// co_max
		// horas_crecimiento
		// horas_floracion
		// hum_min
		// hum_max
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

		// temp_min
		$this->temp_min->EditAttrs["class"] = "form-control";
		$this->temp_min->EditCustomAttributes = "";
		$this->temp_min->EditValue = $this->temp_min->CurrentValue;
		$this->temp_min->PlaceHolder = ew_RemoveHtml($this->temp_min->FldCaption());
		if (strval($this->temp_min->EditValue) <> "" && is_numeric($this->temp_min->EditValue)) $this->temp_min->EditValue = ew_FormatNumber($this->temp_min->EditValue, -2, -1, -2, 0);

		// temp_max
		$this->temp_max->EditAttrs["class"] = "form-control";
		$this->temp_max->EditCustomAttributes = "";
		$this->temp_max->EditValue = $this->temp_max->CurrentValue;
		$this->temp_max->PlaceHolder = ew_RemoveHtml($this->temp_max->FldCaption());
		if (strval($this->temp_max->EditValue) <> "" && is_numeric($this->temp_max->EditValue)) $this->temp_max->EditValue = ew_FormatNumber($this->temp_max->EditValue, -2, -1, -2, 0);

		// co_min
		$this->co_min->EditAttrs["class"] = "form-control";
		$this->co_min->EditCustomAttributes = "";
		$this->co_min->EditValue = $this->co_min->CurrentValue;
		$this->co_min->PlaceHolder = ew_RemoveHtml($this->co_min->FldCaption());

		// co_max
		$this->co_max->EditAttrs["class"] = "form-control";
		$this->co_max->EditCustomAttributes = "";
		$this->co_max->EditValue = $this->co_max->CurrentValue;
		$this->co_max->PlaceHolder = ew_RemoveHtml($this->co_max->FldCaption());

		// horas_crecimiento
		$this->horas_crecimiento->EditAttrs["class"] = "form-control";
		$this->horas_crecimiento->EditCustomAttributes = "";
		$this->horas_crecimiento->EditValue = $this->horas_crecimiento->CurrentValue;
		$this->horas_crecimiento->PlaceHolder = ew_RemoveHtml($this->horas_crecimiento->FldCaption());

		// horas_floracion
		$this->horas_floracion->EditAttrs["class"] = "form-control";
		$this->horas_floracion->EditCustomAttributes = "";
		$this->horas_floracion->EditValue = $this->horas_floracion->CurrentValue;
		$this->horas_floracion->PlaceHolder = ew_RemoveHtml($this->horas_floracion->FldCaption());

		// hum_min
		$this->hum_min->EditAttrs["class"] = "form-control";
		$this->hum_min->EditCustomAttributes = "";
		$this->hum_min->EditValue = $this->hum_min->CurrentValue;
		$this->hum_min->PlaceHolder = ew_RemoveHtml($this->hum_min->FldCaption());

		// hum_max
		$this->hum_max->EditAttrs["class"] = "form-control";
		$this->hum_max->EditCustomAttributes = "";
		$this->hum_max->EditValue = $this->hum_max->CurrentValue;
		$this->hum_max->PlaceHolder = ew_RemoveHtml($this->hum_max->FldCaption());

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
					if ($this->temp_min->Exportable) $Doc->ExportCaption($this->temp_min);
					if ($this->temp_max->Exportable) $Doc->ExportCaption($this->temp_max);
					if ($this->co_min->Exportable) $Doc->ExportCaption($this->co_min);
					if ($this->co_max->Exportable) $Doc->ExportCaption($this->co_max);
					if ($this->horas_crecimiento->Exportable) $Doc->ExportCaption($this->horas_crecimiento);
					if ($this->horas_floracion->Exportable) $Doc->ExportCaption($this->horas_floracion);
					if ($this->hum_min->Exportable) $Doc->ExportCaption($this->hum_min);
					if ($this->hum_max->Exportable) $Doc->ExportCaption($this->hum_max);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->temp_min->Exportable) $Doc->ExportCaption($this->temp_min);
					if ($this->temp_max->Exportable) $Doc->ExportCaption($this->temp_max);
					if ($this->co_min->Exportable) $Doc->ExportCaption($this->co_min);
					if ($this->co_max->Exportable) $Doc->ExportCaption($this->co_max);
					if ($this->horas_crecimiento->Exportable) $Doc->ExportCaption($this->horas_crecimiento);
					if ($this->horas_floracion->Exportable) $Doc->ExportCaption($this->horas_floracion);
					if ($this->hum_min->Exportable) $Doc->ExportCaption($this->hum_min);
					if ($this->hum_max->Exportable) $Doc->ExportCaption($this->hum_max);
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
						if ($this->temp_min->Exportable) $Doc->ExportField($this->temp_min);
						if ($this->temp_max->Exportable) $Doc->ExportField($this->temp_max);
						if ($this->co_min->Exportable) $Doc->ExportField($this->co_min);
						if ($this->co_max->Exportable) $Doc->ExportField($this->co_max);
						if ($this->horas_crecimiento->Exportable) $Doc->ExportField($this->horas_crecimiento);
						if ($this->horas_floracion->Exportable) $Doc->ExportField($this->horas_floracion);
						if ($this->hum_min->Exportable) $Doc->ExportField($this->hum_min);
						if ($this->hum_max->Exportable) $Doc->ExportField($this->hum_max);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->temp_min->Exportable) $Doc->ExportField($this->temp_min);
						if ($this->temp_max->Exportable) $Doc->ExportField($this->temp_max);
						if ($this->co_min->Exportable) $Doc->ExportField($this->co_min);
						if ($this->co_max->Exportable) $Doc->ExportField($this->co_max);
						if ($this->horas_crecimiento->Exportable) $Doc->ExportField($this->horas_crecimiento);
						if ($this->horas_floracion->Exportable) $Doc->ExportField($this->horas_floracion);
						if ($this->hum_min->Exportable) $Doc->ExportField($this->hum_min);
						if ($this->hum_max->Exportable) $Doc->ExportField($this->hum_max);
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
