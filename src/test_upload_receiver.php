<?php  
if(sizeof($_REQUEST)){
    print_r(sizeof($_REQUEST));
    foreach($_REQUEST as $key=>$value){
        print_r($key);
    }
}
if(sizeof($_FILES)){
    foreach($_FILES as $key=>$value){
        print_r($key);
    }
}else{
    print_r("no file");
}