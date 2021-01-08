@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../pdepend/pdepend/src/bin/pdepend
php "%BIN_TARGET%" %*
