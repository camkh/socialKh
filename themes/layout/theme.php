<?php $user=$this->
	session->userdata('email'); $user_type = $this->session->userdata('provider'); $log_id = $this->session->userdata('user_id'); ?>
	<!DOCTYPE html>
	<!-- This is a minified version of the ThemeForest-theme "Melon - Flat & Responsive Admin Template". Author: Simon 'Stammi' Stamm <http://themeforest.net/user/Stammi?ref=stammi theme: http://envato.stammtec.de/themeforest/melon/>
	Note: If you buy my template on ThemeForest, you will receive the non-minified and commented/ documentated version!
	This is a minified version to prevent stealing.
	-->
	<html lang="en">
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
			<title>
				<?php echo $title; ?>
			</title>
			<?php if ($user) { ?>
				<link href="<?php echo base_url(); ?>themes/layout/blueone/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
				<!--[if lt IE 9]>
					<link rel="stylesheet" type="text/css" href="./plugins/jquery-ui/jquery.ui.1.10.2.ie.css" />
				<![endif]-->
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/main.css" rel="stylesheet" type="text/css" />
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/plugins.css" rel="stylesheet" type="text/css" />
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/responsive.css" rel="stylesheet" type="text/css" />
				<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/icons.css" rel="stylesheet" type="text/css" />
				<link rel="stylesheet" href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/fontawesome/font-awesome.min.css" />
				<!--[if IE 7]>
					<link rel="stylesheet" href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/fontawesome/font-awesome-ie7.min.css" />
				<![endif]-->
				<!--[if IE 8]>
					<link href="<?php echo base_url(); ?>themes/layout/blueone/assets/css/ie8.css" rel="stylesheet" type="text/css" />
				<![endif]-->
				<?php } ?>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<?php if (!empty($css)) { foreach ($css as $key=>
						$cssscript) { ?>
						<link rel="stylesheet" href="<?php echo base_url(); ?><?php echo $cssscript; ?>.css" />
						<?php } } ?>
							<?php if (isset($includes)) { echo $includes; } ?>
		</head>
		<body class="<?php echo (!empty($bodyClass) ? $bodyClass : '') ?>">
			<?php if ($user) { include( 'blueone/header.php'); include( 'blueone/contentLogin.php'); } else { echo $content; } ?>
				<!-- basic scripts -->
				<?php if ($user) { ?>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/jquery.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/bootstrap/js/bootstrap.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/lodash.compat.min.js">
					</script>
					<!--[if lt IE 9]>
						<script src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/html5shiv.js">
						</script>
					<![endif]-->
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/touchpunch/jquery.ui.touch-punch.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/event.swipe/jquery.event.move.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/event.swipe/jquery.event.swipe.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/libs/breakpoints.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/respond/respond.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/cookie/jquery.cookie.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/slimscroll/jquery.slimscroll.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/sparkline/jquery.sparkline.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/typeahead/typeahead.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/tagsinput/jquery.tagsinput.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/select2/select2.min.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/app.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/plugins.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/plugins.form-components.js">
					</script>
					<script>
						$(document).ready(function() {

							App.init();

							Plugins.init();

							FormComponents.init()

						});
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/custom.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/assets/js/demo/form_components.js">
					</script>
					<script type="text/javascript" src="<?php echo base_url(); ?>themes/layout/blueone/plugins/noty/packaged/jquery.noty.packaged.min.js">
					</script>
					<script>
						$(document).ready(function() {

							$(".btn-notification").click(function() {

								var b = $(this);

								noty({

									text: b.data("text"),

									type: b.data("class"),

									layout: b.data("layout"),

									timeout: 2000,
									modal: b.data("modal"),

									buttons: (b.data("type") != "confirm") ? false : [{
										addClass: "btn btn-primary",
										text: "Ok",
										onClick: function(c) {

											c.close();

											window.location = "<?php echo base_url(); ?>" + b.data("action");

										}
									},
									{
										addClass: "btn btn-danger",
										text: "Cancel",
										onClick: function(c) {

											c.close();

											noty({
												force: true,
												text: 'You clicked "Cancel" button',
												type: "error",
												layout: b.data("layout")
											});

											setTimeout(function() {

												$.noty.closeAll();

											}, 4000);

										}

									}]
								});

								return false

							});

						});
					</script>
					<?php } if (!empty($js)) { foreach ($js as $key=>
						$jsScript) { ?>
						<script src="<?php echo base_url(); ?><?php echo $jsScript; ?>">
						</script>
						<?php } } ?>
							<script>
								<?php
								if (!empty($addJsScript)) {
									foreach($addJsScript as $key => $AddJs) {
										echo $AddJs;
									}
								}
								?>

								function generate(type) {

									var n = noty({

										text: type,

										type: type,

										dismissQueue: false,

										layout: 'top',

										theme: 'defaultTheme'

									});

									console.log(type + ' - ' + n.options.id);

									return n;

								}
							</script>
		</body>
	
	</html>