<?php
if (!defined('ABSPATH')) {
	exit;
}
$yellow = AutocerfaMisc::color1();
$blue   = AutocerfaMisc::color2();
$black  = AutocerfaMisc::color3();
//$badge_bg_color  = AutocerfaMisc::color4();
//$badge_txt_color  = AutocerfaMisc::color5();

$selected_single_page = (int) get_option('autocerfa_single_page');

?>
<style>
	.autocerfa-car-item:hover {
		border-bottom: 3px solid <?= $blue ?>;
	}

	.autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content span {
		color: <?= $blue ?>;
	}

	.autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content p a {
		color: <?= $yellow ?>;
	}

	.autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover {
		background-color: <?= $yellow ?>
	}

	.autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover i {
		color: <?= $black ?>
	}

	.autocerfa-on-listing .autocerfa-car-item .autocerfa-down-content ul li .autocerfa-item:hover p {
		color: <?= $black ?>
	}

	.pagination-content ul li a {
		color: <?= $black ?>;
		background-color: <?= $yellow ?>;
	}

	.pagination-content ul li a:hover {
		color: #fff;
		background-color: <?= $blue ?>
	}

	.pagination-content ul li.active a {
		color: #fff;
		background-color: <?= $blue ?>
	}

	.autocerfa-car-badge {
		background-color: <?= $badge_bg_color ?>;
		color: <?= $badge_txt_color ?>;
	}
</style>
<div class="bootstrap-wrapper autocerfa_main_wrapper">
	<div class="container autocerfa_container">
		<div class="autocerfa-on-listing wow fadeIn" data-wow-delay="0.5s" data-wow-duration="1s">
			<div class="autocerfa-recent-car-content">
				<div class="row">
					<div class="col-md-10 offset-md-1">
						<div class="row">
							<?php
							if (!empty($leads)) :
								foreach ($leads as $lead) :
									$images = $lead->images;
                                    $raw_images = $lead->raw_images;

                                    $first_image = empty($images) ? [] : reset(array_filter($images));
                                    $raw_first_image = empty($raw_images) ? '' : $raw_images[0];
                                    $image_url = empty($first_image) ? $raw_first_image : $first_image['thumbnails']['375-345']['url'];

									$url   = AutocerfaMisc::url($lead);

									$title = apply_filters(
										'autocerfa_car_list_title',
										sprintf('<h4>%s</h4>', esc_attr($lead->title)),
										$lead
									);
									$badge_label = '';
									if (!empty($lead->badge_id)) {
										$badge = (new AutocerfaBadge())->get_by_id($lead->badge_id);
										if (!empty($badge)) {
											$badge_label = sprintf(
												'<div class="autocerfa-car-badge autocerfa-car-badge-grid" style="background: %s; color: %s">%s</div>',
												$badge->background_color,
												$badge->text_color,
												$badge->label
											);
										}
									}
							?>
									<div class="col-md-12">
										<div class="autocerfa-car-item">
											<div class="row">
												<div class="col-md-5">
													<div class="autocerfa-thumb-content">
														<?= $badge_label ?>
														<div class="autocerfa-thumb-inner">
															<a href="<?= esc_url($url) ?>">
																<img src="<?= esc_attr($image_url) ?>" alt="<?= $title ?>">
															</a>
														</div>
													</div>
												</div>
												<div class="col-md-7">
													<div class="autocerfa-down-content">
														<a href="<?= esc_url($url) ?>"><?= $title ?></a>
														<span><?= esc_attr($lead->price) ?> &euro;</span>
														<div class="line-dec"></div>
														<p><?php
															$sinlge_page = sanitize_title_with_dashes(get_option('single_page_slug'));
															echo autocerfa_read_more(esc_attr($lead->description), $url); ?></p>

														<ul class="autocerfa-car-info">
															<?php if (!empty($lead->reg_date)) : ?>
																<li>
																	<div class="autocerfa-item"><i class="fa-regular fa-calendar-days"></i>
																		<p><?= esc_attr(explode(" ", $lead->reg_date)[2]) ?></p>
																	</div>
																</li>
															<?php endif ?>
															<?php if (!empty($lead->gear_box)) : ?>
																<li>
																	<div class="autocerfa-item"><i class="fa-solid fa-gauge"></i>
																		<p><?= esc_attr($lead->gear_box) ?> </p>
																	</div>
																</li>
															<?php endif; ?>
															<?php if (!empty($lead->milage)) : ?>
																<li>
																	<div class="autocerfa-item"><i class="fa-sharp fa-solid fa-road"></i>
																		<p><?= esc_attr($lead->milage) . 'km' ?></p>
																	</div>
																</li>
															<?php endif; ?>
															<?php if (!empty($lead->energy)) : ?>
																<li>
																	<div class="autocerfa-item"><i class="fa-solid fa-gas-pump"></i>
																		<p><?= esc_attr($lead->energy) ?></p>
																	</div>
																</li>
															<?php endif; ?>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach;
							else :
								?>
								<div class="col-md-12">
									<p class='no-result-message'><?php _e('Sorry, we don’t have any cars according your criteria, try something else', 'autocerfa-connector') ?></p>
								</div>
							<?php endif ?>

							<div class="col-md-12">
								<div class="page-numbers">
									<div class="pagination-content">

										<?= (new AutocerfaPaginator($totalStock, $rowPerPage, $currentPage, $urlPattern))->toHtml()  ?>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>