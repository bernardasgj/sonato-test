<!-- user table component -->
<!-- @var array|User[] $users -->
<?php if($users): ?>
<div class="table-container" data-user-table>
    <div class="table-row">
        <div class="table-cell">Vardas</div>
        <div class="table-cell">Pavarde</div>
        <div class="table-cell">El. pastas</div>
        <div class="table-cell">Poke count</div>
        <div class="table-cell"></div>
    </div>
    <?php foreach ($users as $user) : ?>
        <div class="table-row">
            <div class="table-cell"><?php echo $user->getFirstName(); ?></div>
            <div class="table-cell"><?php echo $user->getLastName(); ?></div>
            <div class="table-cell"><?php echo $user->getEmail(); ?></div>
            <div class="table-cell" id="poke-count-<?php echo $user->getId(); ?>"><?php echo count($user->getPokes())?></div>
            <div class="table-cell">
                <?php if ($_SESSION['user_id'] != $user->getId()): ?>
                    <button class="btn poke-button" data-user-id="<?php echo $user->getId(); ?>">Poke</button>
                <?php else: ?>
                    Nesipoke'ink savęs :)
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div class="empty-text">VARTOTOJŲ NERASTA</div>
<?php endif ?>