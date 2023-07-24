<!-- poke table component -->
<!-- @var array|Poke[] $pokes -->
<?php if($pokes): ?>
<div class="table-container">
    <div class="table-row">
        <div class="table-cell">Data</div>
        <div class="table-cell">Siuntejas</div>
        <div class="table-cell"></div>
        <div class="table-cell">Gavejas</div>
        <div class="table-cell"></div>
    </div>
    <?php foreach ($pokes as $poke) : ?>
        <div class="table-row">
            <div class="table-cell"><?php echo $poke->getPokedAt()->format('Y-m-d'); ?></div>
            <div class="table-cell"><?php echo $poke->getPokedByUser()->getFirstName() . ' ' . $poke->getPokedByUser()->getLastName(); ?></div>
            <div class="table-cell"><i class="fa fa-angle-right"></i></div>
            <div class="table-cell"><?php echo $poke->getPokedUser()->getFirstName() . ' ' . $poke->getPokedUser()->getLastName(); ?></div>
            <div class="table-cell"></div>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div class="empty-text">POKE NĖRA</div>
<?php endif ?>

