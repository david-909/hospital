<?php
$deletesvg = file_get_contents("assets/img/deletebtn.svg");
?>
<div class="modal" id="modalTherapy" style="display:none;z-index:9999">
    <div class="modal-header" id="modalTherapy-header">
        <p>PREGLED TERAPIJE</p>
        <button class="close-button crudbtns" id='exitView'><?php echo $deletesvg ?></button>
    </div>
    <div class="modal-body scroll" id="modalTherapy-body">
    </div>
</div>