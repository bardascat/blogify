<? $url = 'http://localhost/ckeditor/_samples/browser/java.jpg'; $error="Eroare";

// Required: anonymous function reference number as explained above.
$funcNum = $_GET['CKEditorFuncNum'] ;
// Optional: instance name (might be used to load a specific configuration file or anything else).
$CKEditor = $_GET['CKEditor'] ;
// Optional: might be used to provide localized messages.
$langCode = $_GET['langCode'] ;
 
// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).

// Usually you will only assign something here if the file could not be uploaded.
$message = '';
 
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
?>