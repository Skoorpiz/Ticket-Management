<?php
include_once 'includes/bdd.php';
$page = "doublon";
include_once 'includes/header.php';
$req = "SELECT * FROM `tag` ORDER BY `tag`.`name` ASC";
$res = $pdo->query($req);
$tag = $res->fetchAll();
?>
<style> 
#myInput {
  background-image: url('/css/searchicon.png'); /* Add a search icon to input */
  background-position: 10px 12px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 100%; /* Full-width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 40px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}

#myTable {
  border-collapse: collapse; /* Collapse borders */
  width: 100%; /* Full-width */
  border: 1px solid #ddd; /* Add a grey border */
  font-size: 18px; /* Increase font-size */
}

#myTable th, #myTable td {
  text-align: left; /* Left-align text */
  padding: 12px; /* Add padding */
}

#myTable tr {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  /* Add a grey background color to the table header and on hover */
  background-color: #f1f1f1;
}
</style>
<p> Edition de regroupement</p>
<input class="w-25" type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
<table  id="myTable" class="table table-bordered w-25 ">
    <thead>
        <tr>
            <th >Nom</th>
            <th width="1px">Actions</th>
        </tr>

    </thead>
    <tbody>
        <?php for ($i = 0; $i < count($tag); $i++) { ?>
            <tr>
                <td><?php echo $tag[$i]['name'] ?></td>
                <td>
                    <a href="script/traitementDeleteDoublon.php?id=<?php echo $tag[$i]['id_tag'] ?>"><i class="fas fa-trash"></i></a>
                    &nbsp;
                    <a href="updateDoublon.php?id=<?php echo $tag[$i]['id_tag'] ?>"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
<?php
include_once 'includes/footer.php';
