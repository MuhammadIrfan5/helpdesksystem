<?php

function checkAdminAndSupAdmin($roleName){

    if(auth()->user()->role->slug === $roleName){
        return true;
    }else{
        return false;
    }

}
