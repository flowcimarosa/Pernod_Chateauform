<h1>Choisissez une salle</h1>

<?php foreach($salles as $salle) { ?>
  <h2 class="salleColor">
    <?php echo $salle->libelle; ?>
    <a class="colorHref" href='?controller=seminaires&action=show&id=<?php echo $salle->idSalle; ?>'>Consulter</a>
  </h2>

<?php } ?>
