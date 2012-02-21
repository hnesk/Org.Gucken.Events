<?
include "saddorlibtextcat.php";
$libtext = new SaddorLibTextCat();

$libtext->WhatLang("This is a text in english, so the first option when you
print the array of ranking it has to be english!!!, so is it work???");

print "<pre>";
print_r($libtext->ranking);
print "</pre>";
?>