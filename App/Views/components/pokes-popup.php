<!-- pokes popup component -->
<!-- @var array|Poke[] $pokes -->
<div class="container">
    <?php if ($pokes) : ?>
        <?php foreach ($pokes as $poke) : ?>
            <div class="item">
                Poke nuo 
                <strong><?php echo $poke->getPokedByUser()->getFirstName() . ' ' . $poke->getPokedByUser()->getLastName(); ?></strong>
            </div>
        <?php endforeach; ?>
    <a href="pokes" class="left-aligned-link"> VISI POKE <i class="fa fa-angle-right"></i></a>
    <?php else : ?>
        <?php if (Session::isLoggedIn()) : ?>
            <div class="empty-poke-message">POKE NETURI</div>
            <a href="pokes" class="left-aligned-link"> VISI POKE <i class="fa fa-angle-right"></i></a>
        <?php else : ?>
            <div class="empty-poke-message">NESI PRISIJUNGES</div>
            <a href="pokes" class="left-aligned-link"> VISI POKE <i class="fa fa-angle-right"></i></a>
        <?php endif ?>
    <?php endif ?>
</div>