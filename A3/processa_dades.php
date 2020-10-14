<?php
if(isset($_REQUEST["mytext"])){
    print_r("Text: ".$_REQUEST["mytext"]."<br>");
}  

if($_REQUEST["myradio"] == 1){
    print_r("Radiobutton: First Radio<br>");

} else {
    print_r("Radiobutton: Second Radio<br>");
}

if(isset($_REQUEST["mycheckbox"])){
    $i = 0;
    foreach($_REQUEST["mycheckbox"] as $value){
        if(count($_REQUEST["mycheckbox"]) == 1){
            print_r("Checkbox: ".$value."<br>");
        } else if($i != 1){
            print_r("Checkbox: ".$value.", ");
        $i++;
        } else {
            print_r($value."<br>");

        }
    }
}
print_r("Drop selection: ".$_REQUEST["myselect"]."<br>");
print_r("Text box: ".$_REQUEST["mytextarea"]."<br>");

$UploadDir = 'files/';
$fichero_subido = $UploadDir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $fichero_subido)) {
    print_r("<br><img width='200' src=\"".$fichero_subido."\">");
} else {
    print_r("<br>Not uploaded or error with the uploaded file");
}
?>