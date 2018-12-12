@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../ke-buildrouter/bootstrap.php
php "%BIN_TARGET%" %*
