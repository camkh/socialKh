<?php if (isset($_SESSION['valid'])) { ?>
    <script type="text/javascript">
        idleTime = 0;
        $(document).ready(function() {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 10000); // 1 minute

            //Zero the idle timer on mouse movement.
            $(this).mousemove(function(e) {
                idleTime = 0;
            });
            $(this).keypress(function(e) {
                idleTime = 0;
            });
        });

        function timerIncrement() {
            idleTime = idleTime + 1;
            if (idleTime > 1000) {
                //window.location.reload();
                window.location = "/post/logout.php";
            }
        }
    </script> 
<?php } ?>
<header class="header navbar navbar-fixed-top" role="banner">        
    <div class="container">
        <ul class="nav navbar-nav">
            <li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
        </ul>
        <a class="navbar-brand" href="<?php echo base_url(); ?>"> <img src="<?php echo base_url(); ?>themes/layout/blueone/assets/img/logo.png" alt="logo" style="max-height: 40px;" /> <strong>AD</strong>MIN </a> <a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation"> <i class="icon-reorder"></i> </a>
        <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
            <?php if (!empty($menuPermission)) : ?>
                <?php foreach ($menuPermission as $menu_value):
                    $showonly = $menu_value[Tbl_title::value];
                    if($showonly =='post/getfromb' || $showonly =='post/movies' || $showonly =='music/xml' || $showonly =='music/add' || $showonly =='post/vdokh'):
                    ?>
                    <li><a href="<?php echo base_url(); ?><?php echo $menu_value[Tbl_title::value]; ?>"> <?php echo $menu_value[Tbl_title::title]; ?> </a></li>
                <?php 
                    endif;
                endforeach;
            endif;
            ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-male"></i> <span class="username">
                         <?php
                        $user = $this->session->userdata('email');
                        echo @$user;?></span> <i class="icon-caret-down small"></i> </a>
                <ul class="dropdown-menu"> 
                    <li><a href="<?php echo base_url(); ?>home/logout"><i class="icon-key"></i> Log Out</a></li>
                </ul>
            </li>
        </ul>
    </div>
</header>