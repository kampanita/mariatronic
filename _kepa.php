<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$kepa_php = NULL; // Initialize page object first

class ckepa_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{032690A3-4B26-49FF-B1A0-E08477B5B2A3}";

	// Table name
	var $TableName = 'kepa.php';

	// Page object name
	var $PageObjName = 'kepa_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'kepa.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

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

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
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

	//
	// Page main
	//
	function Page_Main() {

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "kepa_php", $url, "", "kepa_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($kepa_php)) $kepa_php = new ckepa_php();

// Page init
$kepa_php->Page_Init();

// Page main
$kepa_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>

<body>
<div class="container-fluid">
        <h3><span class="label label-default">Estado Actual</span></h3>
        <div class="table-responsive">

 <center>
   <table class="table table-stripped">
    
  <?php

    include('abre_conexion.php');
    
    $que = "select horas_crecimiento,horas_floracion from parametros";
    $result = $conexion_db->query($que);
    
    $parametros= $result->fetch_array(MYSQLI_ASSOC);
    
    #print 'crecimiento:'.$parametros['horas_crecimiento'];
    
    $query = "select * from tempe ORDER BY fecha DESC,hora desc LIMIT 1";
    $result = $conexion_db->query($query);
    $registro = $result->fetch_array(MYSQLI_ASSOC);
     
     if ($registro['maqcale']==1)  { $maqcale = "<td><span class='btn btn-success'/>On</td>"; } else {$maqcale="<td><span class='btn btn-danger'/>Off</td>"; }	
     if ($registro['maqhum']==1)   { $maqhum = "<td><span class='btn btn-success'/>On</td>"; } else {$maqhum="<td><span class='btn btn-danger'/>Off</td>"; }	
     if ($registro['maqdesh']==1)   { $maqdesh = "<td><span class='btn btn-success'/>On</td>"; } else {$maqdesh="<td><span class='btn btn-danger'/>Off</td>"; }	
     
     if ($registro['periodo']==1)   { $periodo = "<td><span class='btn btn-info'/>Crecimiento</td>"; } else {$periodo="<td><span class='btn btn-danger'/>Floracion</td>"; }	
  
     if ($registro['horasluz']==1)   { $horas = "<td><span class='btn btn-danger'/>18/6</td>"; } else {$horas="<td><span class='btn btn-info'/>12/12</td>"; }	
     #el valor de riego, menor de 520 que salga con fondo verde, entre 520 y 700 amarillo, a partir de 700 rojo
     
     
     if ($registro['higromet']<520)   { $higro = "<td><span class='btn btn-success'/>".$registro['higromet']."</td>"; } else {
         if ($registro['higromet']>=520 and $registro['higromet']<700)   
             { $higro = "<td><span class='btn btn-warning'/>".$registro['higromet']."</td>"; } 
             else 
             {$higro="<td><span class='btn btn-danger'/>".$registro['higromet']."</td>"; }	
     }
     
     
     
     if ($registro['luz']>=400)   { $luz = "<td><span class='btn btn-success'/>On ( ".$registro['luz']." )</td>"; } else {$luz="<td><span class='btn btn-danger'/>Off ( ".$registro['luz']." )</td>"; }	
     $co2="<td><span class='btn btn-default'/>".$registro['co2ppm']."</td>";
     
     echo "
    <thead>
    <tr>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Temperatura</th>
      <th>Humedad</th>
    </tr>
    </thead>

    <tr>

                <td><span class='w3-btn'/>".$registro['fecha']."</td>
                <td><span class='w3-btn'/>".$registro['hora']."</td>
                <td><span class='w3-btn'/>".$registro['temp']." ºC</td>
                <td><span class='w3-btn'/>".$registro['hum']." %</td>
    </tr>
    <thead>
    <tr>
    
      <th>Luz</th>
      <th>CO2</th>
      <th>Riego</th>
      <th>Horas Luz</th>
    </tr>
    </thead>
      <tr>
  
                ".$luz."
                ".$co2."
                ".$higro."
                ".$horas."
    
    </tr>
    <thead class='thead-inverse'>
    <tr>
      <th>Calefactor</th>
      <th>Humidificador</th>
      <th>Deshumidificador</th>
      <th>Periodo</th>
    </tr>
    </thead>
    <tr>
     
                ".$maqcale."
                ".$maqhum."
                ".$maqdesh."
                ".$periodo."
  
    </tr>";
?>
   </table>
  </center>
</div>

</div>
</body>


<?php
mysqli_close($conexion_db);
?>


<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$kepa_php->Page_Terminate();
?>
