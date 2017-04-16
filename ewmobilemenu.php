<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mmi__kepa_php", $Language->MenuPhrase("3", "MenuText"), "_kepa.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mmci_Gre1ficas", $Language->MenuPhrase("17", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mmi_grafico_php", $Language->MenuPhrase("5", "MenuText"), "grafico.php", 17, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, "mmci_Gre1fica_horaria", $Language->MenuPhrase("18", "MenuText"), "grafico2.php", 17, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_view1", $Language->MenuPhrase("2", "MenuText"), "view1list.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, "mmci_Mantenimientos", $Language->MenuPhrase("16", "MenuText"), "", -1, "", IsLoggedIn(), FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mmi_parametros", $Language->MenuPhrase("6", "MenuText"), "parametroslist.php", 16, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, "mmi_usuarios", $Language->MenuPhrase("8", "MenuText"), "usuarioslist.php", 16, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_ip_php", $Language->MenuPhrase("7", "MenuText"), "ip.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_camera_php", $Language->MenuPhrase("4", "MenuText"), "camera.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
