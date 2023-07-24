<!-- header component -->
<div class="component-header" id="header">
    <a class="title" href="/"><span>BAKSNOTOJAS 2000</span></a>
    <div class="header-icons">
        <i class="fa fa-hand-o-right active" data-popup-trigger="pokesPopup"></i>
        <!-- Kazkodel dizainas indikavo padaryti taip... -->
        <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="update_account"><i class="fa fa-user-circle active"></i></a>
            <a href="logout"><i class="fa fa-sign-out active" aria-hidden="true"></i></a>
        <?php else: ?>            
            <i class="fa fa-user-circle"></i>
            <i class="fa fa-sign-out" aria-hidden="true"></i>
        <?php endif; ?>
    </div>
    
    <div class="popup-content poke-list" data-pokes-popup-content data-popup-content="pokesPopup">
        <div class="container"></div>
    </div>
</div>
