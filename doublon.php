<?php
include_once 'includes/bdd.php';
$page = "doublon";
include_once 'includes/header.php';
$req = "SELECT * FROM `customer` WHERE id_tag IS NULL ORDER BY `customer`.`name` ASC";
$res = $pdo->query($req);
$customerDisplay = $res->fetchAll();
?>
<p> Ajout de regroupement</p>
<style> 
.taille{
    height: 600px;
}
</style>
<form method="POST" action="script/traitementDoublon.php">
    <div class="col-3">
        <select class="taille"  id="multipleSelect" multiple name="customer[]" >
            <?php
            for ($i = 0; $i < count($customerDisplay); $i++) {
            ?>
                <option value="<?php echo $customerDisplay[$i]['id_customer'] ?>"><?php echo $customerDisplay[$i]['name'] ?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <br>
    <div class="col-2">
        <p>&nbsp;Libelle de regroupement</p>
        <input class="form-control" name="tag" type="text">
    </div>
    <br><br>
    <button class="btn btn-primary" type="submit">Valider</button>
</form>
<script> 
function test() {
  var sel = document.getElementById("multipleSelect");
  var opts = sel.options;
  for(var i=0,l=opts.length;i<l;i++) {
    opts[i].onmousedown = save_selected;
    opts[i].onclick = (function(option,index) {
      var bool = false;
      return function() {
        option.selected = bool = !bool;
        nb += bool ? 1 : -1;
        restore_selected(index);
      };
    })(opts[i],i);
  }
  var save, nb = 0;
  function save_selected() {
    save = [];
    for(var i=0,l=opts.length;i<l;i++) {
      save.push(opts[i].selected);
    }
    nb = save.filter(function(el) { return el; }).length;
  }
  function restore_selected(index) {
    for(var i=0,l=opts.length;i<l;i++) {
      if(i!=index) {
        opts[i].selected = save[i];
      }
    }
  }
}
window.onload=test;


</script>

<?php
include_once 'includes/footer.php';
