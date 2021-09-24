<?php
/**
 * Шаблон письма.
 *
 * @global $args
 * @package art-feedback-button
 */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=400"/>
	<title>Первое письмо</title>
	<meta name="description" content="Первое письмо"/>
	<style type="text/css">
		.ExternalClass {
			width: 100%;
		}

		img {
			border: 0 none;
			height: auto;
			line-height: 100%;
			outline: none;
			text-decoration: none;
			-ms-interpolation-mode: bicubic;
		}

		a img {
			border: 0 none;
		}

		#outlook a {
			padding: 0;
		}

		#allrecords {
			height: 100% !important;
			margin: 0;
			padding: 0;
			width: 100% !important;
			-webkit-font-smoothing: antialiased;
			line-height: 1.45;
		}

		#allrecords td {
			margin: 0;
			padding: 0;
		}

		#allrecords ul {
			-webkit-padding-start: 30px;
		}

		@media only screen and (max-width: 600px) {
			.r {
				width: 100% !important;
				min-width: 400px !important;
			}
		}

		@media only screen and (max-width: 480px) {
			.t-emailBlock {
				display: block !important;
				padding-left: 0 !important;
				padding-right: 0 !important;
				width: 100% !important;
			}

			.t-emailBlockPadding {
				padding-top: 15px !important;
			}

			.t-emailBlockPadding30 {
				padding-top: 30px !important;
			}

			.t-emailAlignLeft {
				text-align: left !important;
				margin-left: 0 !important;
			}

			.t-emailAlignCenter {
				text-align: center !important;
				margin-left: auto !important;
				margin-right: auto !important;
			}
		}</style>
</head>
<body
	cellpadding="0" cellspacing="0"
	style="padding: 0; margin: 0; border: 0; width:100%; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color: #efefef;">
<!--allrecords-->
<table
	id="allrecords"
	cellpadding="0" cellspacing="0"
	style="width:100%; border-collapse:collapse; border-spacing:0; padding:0; margin:0; border:0;">
	<tr>
		<td style="background-color: #efefef; "><!--record_mail-->
			<table
				id="rec149942664" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
				cellpadding="0" cellspacing="0" data-record-type="329">
				<tr>
					<td style="padding-left:15px; padding-right:15px; background-color:#ffffff;">
						<table
							id="recin149942664" class="r"
							style="margin: 0 auto;background-color:#e3e3e3;border-spacing: 0;width:600px;"
							align="center">
							<tr>
								<td style="padding-top:15px;padding-bottom:15px;padding-left:30px;padding-right:30px;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
										<tr>
											<td style="text-align: center; padding: 0 0 0;">
												<div
													style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#44495f;font-size:24px;line-height:1.45;font-weight:bold;">
													Заявка на обратный звонок
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><!--/record--><!--record_mail-->
			<table
				id="rec150776148" style="width:100%; border-collapse:collapse; border-spacing:0; margin:0; border:0;"
				cellpadding="0" cellspacing="0" data-record-type="329">
				<tr>
					<td style="padding-left:15px; padding-right:15px;  background-color:#ffffff;">
						<table
							id="recin150776148" class="r"
							style="margin: 0 auto;background-color:#e3e3e3;border-spacing: 0;width:600px;"
							align="center">
							<tr>
								<td style="padding-top:0px;padding-bottom:15px;padding-left:30px;padding-right:30px;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;">
										<tr>
											<td style="text-align: left; padding: 20px 0;">
												<div
													style="margin: 0 auto; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#44495f;font-size:20px;line-height:1.45;">Вам пришла заявка на обратный звонок</div>

											</td>
										</tr>
										<tr>
											<td>
												<div
													style="margin: 0 auto; padding: 20px 0; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; color:#44495f;font-size:16px;line-height:1.2;">
													<?php foreach ( $args['fields'] as $field => $value ): ?>
														<strong><?php echo esc_html( $field ); ?>:</strong>
														<strong><em><?php echo esc_html( $value ); ?></em></strong>
														<br/>
													<?php endforeach; ?>
												</div>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table><!--/record--><!--record_mail-->

		</td>
	</tr>
</table><!--/allrecords--></body>
</html>