<?php  
if(sizeof($_FILES)){
    print_r($_FILES);
}else{
    print_r("no file");
}