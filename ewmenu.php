<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(3, "mi__kepa_php", $Language->MenuPhrase("3", "MenuText"), "_kepa.php", -1, "", IsLoggedIn() || AllowListMenu('{28C31B56-5507-4BCF-B1AE-F273C6345D9C}_kepa.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mi_grafico_php", $Language->MenuPhrase("5", "MenuText"), "grafico.php", -1, "", IsLoggedIn() || AllowListMenu('{28C31B56-5507-4BCF-B1AE-F273C6345D9C}grafico.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(4, "mi_camera_php", $Language->MenuPhrase("4", "MenuText"), "camera.php", -1, "", IsLoggedIn() || AllowListMenu('{28C31B56-5507-4BCF-B1AE-F273C6345D9C}camera.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_view1", $Language->MenuPhrase("2", "MenuText"), "view1list.php", -1, "", IsLoggedIn() || AllowListMenu('{28C31B56-5507-4BCF-B1AE-F273C6345D9C}view1'), FALSE, FALSE);
$RootMenu->AddMenuItem(6, "mi_parametros", $Language->MenuPhrase("6", "MenuText"), "parametroslist.php", -1, "", IsLoggedIn() || AllowListMenu('{28C31B56-5507-4BCF-B1AE-F273C6345D9C}parametros'), FALSE, FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
