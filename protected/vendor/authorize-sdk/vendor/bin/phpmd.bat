@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../phpmd/phpmd/src/bin/phpmd
php "%BIN_TARGET%" %*
