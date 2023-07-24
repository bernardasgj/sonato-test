<!-- notification message -->
<?php if (isset($_SESSION['success'])) : ?>
    <div class="notification success">
        <i class="fa fa-check-circle-o" aria-hidden="true"></i>
        <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
        ?>
    </div>
<?php endif ?>

<?php if (isset($_SESSION['error'])) : ?>
    <div class="notification error">
        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
        <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
        ?>
    </div>
<?php endif ?>

<?php if (isset($_SESSION['info'])) : ?>
    <div class="notification info">
        <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
        <?php 
            echo $_SESSION['info']; 
            unset($_SESSION['info']);
        ?>
    </div>
<?php endif ?>
