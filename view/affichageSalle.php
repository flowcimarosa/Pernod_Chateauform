
<title>Interface de gestion ChateauForm CNIT</title>

<!-- Javascript -->
<script type="text/javascript" src="/pernod/js/jquery-3.2.1.min.map"></script>
<script type="text/javascript" src="/pernod/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/pernod/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="/pernod/js/bootstrap.js"></script>
<script type="text/javascript" src="/pernod/js/bootstrap.min.js"></script>
<script src="js/Js.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/pernod/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css">
<link rel="stylesheet" href="/pernod/Css/bootstrap.min.css.map">
<link rel="stylesheet" href="/pernod/Css/bootstrap-theme.min.css.map">
<link rel="stylesheet" href="/pernod/Css/bootstrap-theme.min.css">
<link rel="stylesheet" href="/pernod/Css/bootstrap.min.css">
<link rel="stylesheet" href="/pernod/Css/style.css">
<link rel="stylesheet" href="/pernod/Css/stylegrid.css">

<script type="text/javascript">
    function addRow() {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        if(rowCount < 4){                            // limit the user from creating fields more than your limits
            var row = table.insertRow(rowCount);
            var colCount = table.rows[0].cells.length;
            for(var i=0; i <colCount; i++) {
                var newcell = row.insertCell(i);
                newcell.innerHTML = table.rows[0].cells[i].innerHTML;
            }
        }else{
            alert("Le maximum de créneaux par séminaire est de 4");

        }

        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
            if(null != chkbox && true == chkbox.checked) {
                if(rowCount <= 1) {               // limit the user from removing all the fields
                    alert("Il est impossible de supprimer davantage de lignes");
                    break;
                }
                table.deleteRow(i);
                rowCount--;
                i--;
            }
        }
    }

    function deleteRow() {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;

        for(var i=0; i<rowCount; i++) {
            if(rowCount <= 1) {               // limit the user from removing all the fields
                break;
            } else {
                table.deleteRow(rowCount-1);
            }
            break;
        }

        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++) {
            var row = table.rows[i];
            var chkbox = row.cells[0].childNodes[0];
            if(null != chkbox && true == chkbox.checked) {
                if(rowCount <= 1) {               // limit the user from removing all the fields
                    alert("Il est impossible de supprimer davantage de lignes");
                    break;
                }
                table.deleteRow(i);
                rowCount--;
                i--;
            }
        }
    }


</script>
<div style="font-family: 'Ubuntu', Helvetica, Arial, sans-serif;
	font-size: 70px;
	line-height: 65px;">
<center>Plénière</center>
</div>

</br>
</br>
</br>
</br>
</br>
<!-- <?php
foreach($seminaireToday as $seminaire) :

$clientSeminaire = $unController->getInfoClient($seminaire['idSeminaire']);

foreach($clientSeminaire as $client): ?>

<div class="row" style="
clear: both; padding-bottom: 50px;
font-family: 'Vollkorn', Georgia, Times, serif;
font-size: 16px;
line-height: 25px;">

<img src="<?php echo $client['logo'] ;?>" width="110px"/>

<?php endforeach; ?>


    <div class="col-md-6" style="margin: 20px;">
  	<p>
  	<?php echo $seminaire['nomSeminaire']; ?></br>
  	</p>
  	</div>

	</div>

<?php endforeach; ?>

 -->
 <center>
<ul class="rig columns-0">
	<li>
		<img src="Logo/CELGENE.png" height="" width="30%"  />
		<h3 style="font-size: 40px";>Séminaire ChateauForm</h3>
		<h3 style="font-size: 32px";>18h00 - 02h00</h3>
	</li>   
</ul>

</center>

</br>
</br>
</br>
<center><img src="Logo/logo-pernod-ricard.png"  height="" width="15%" /></center>