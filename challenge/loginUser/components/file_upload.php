<?php 
    function file_upload($picture){
    //creating new class
    $result = new stdClass();
    //setting new class' fileName to avatar.png
    $result->fileName = 'avatar.png';
    //... lolwut
    $result->error = 1;
    //splitting aspects of the picture into different variables
    $fileName = $picture["name"];
    $fileType = $picture["type"];
    $fileTmpName = $picture["tmp_name"];
    $fileError = $picture["error"];
    $fileSize = $picture["size"];
    //lowercasing the file extension
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $filesAllowed = ["png", "jpg", "jpeg", "jfif"];
    if ($fileError == 4){
        $result->ErrorMessage = "No picture was chosen. It can be done later";
        return $result; //return the class
    } else {
        if (in_array($fileExtension, $filesAllowed)){
            if ($fileError === 0){ //lol wut
                if ($fileSize < 500000){ //if file below certain size
                    $fileNewName = uniqid('') . "." . $fileExtension; //assign the file a uniqid
                    $destination = "pictures/$fileNewName"; //send it to the pcitures folder
                    if (move_uploaded_file($fileTmpName, $destination)){ //checks that the file was uploaded with PHP POST method
                        $result->error = 0;
                        $result->fileName = $fileNewName; //asign it the generated fileName
                        return $result; //return the class with new name
                    } else {
                        $result->ErrorMesage ="There was an error uploading this file";
                        return $result;
                        }
                    } else {
                        $result->ErrorMessage = "This picture exceeds the upload limit. Please choose a smaller one.";
                        return $result;
                        }
                    } else {
                        $result->ErrorMessage = "There was an error uploading $fileError code.";
                        return $result;
                        }
                    } else {
                        $result->ErrorMessage = "This file type cant be uploaded.";
                        return $result;
                    }
                }
            }
?>