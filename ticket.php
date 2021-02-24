<?php
include_once 'includes/bdd.php';
include_once 'includes/header.php'
?>
   <form action="script/traitementUploadTicket.php" method="post" enctype="multipart/form-data">
        <h2>Upload Fichier</h2>
        <label for="fileUpload">Fichier:</label>
        <input type="file" name="file" id="fileUpload">
        <p><strong>Note:</strong> Seul le format CSV est autorisé</p>
        <input type="submit" name="submit" value="Upload">
    </form>
<?php
include_once 'includes/footer.php';