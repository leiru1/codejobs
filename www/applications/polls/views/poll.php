<?php
	if (isset($special)) {
		$class = "polls-special-width";
	?>
	<div id="poll-container">
	<?php
	} else {
		$class = "polls-width";
	}
	?>
		<div class="polls">
	<?php

	if (isset($poll["answers"])) {
		if (!COOKIE("ZanPoll") and !$results) {
			$URL = path("polls/". $poll["question"]["ID_Poll"] ."/". slug($poll["question"]["Title"]));
			?>
				<form id="polls" method="post" action="<?php echo path("polls/vote"); ?>">			
					<p class="poll-center <?php echo $class; ?>">
						<h3 class="<?php echo $class; ?>"><a href="<?php echo $URL; ?>"><?php echo $poll["question"]["Title"]; ?></a></h3>
					</p>
							
					<?php 
						$i = 1; 
						
						foreach ($poll["answers"] as $answer) {
							echo '	<label for="answer_'. $i .'" class="poll-total">
										<input id="answer_'. $i .'" name="answer" type="radio" value="'. $answer["ID_Answer"] .'"/> '. $answer["Answer"] .'<br />
									</label>';
							$i++;
						}
					?>
					
					<input name="ID_Poll" type="hidden" value="<?php echo $poll["question"]["ID_Poll"]; ?>" /><br />
					<input name="URL" type="hidden" value="<?php echo $URL; ?>" />			
				  
					<div class="poll-send-vote">
						<input id="send-vote" type="button" value="<?php echo __("Vote");?>" />
						<input id="results" type="button" value="<?php echo __("Results");?>" />
						<div id="warningGradientOuterBarG">
							<div id="warningGradientFrontBarG" class="warningGradientAnimationG">
								<div class="warningGradientBarLineG">
								</div>
								<div class="warningGradientBarLineG">
								</div>
								<div class="warningGradientBarLineG">
								</div>
								<div class="warningGradientBarLineG">
								</div>
								<div class="warningGradientBarLineG">
								</div>
								<div class="warningGradientBarLineG">
								</div>
							</div>
						</div>
					</div>
				</form>
			<?php
		} else {
			if (isset($poll)) {
				$total = 0;
				$URL = path("polls/". $poll["question"]["ID_Poll"] ."/". slug($poll["question"]["Title"]));

				if (GET("answer")) {
					$answer = (int) GET("answer");
				} else {
					$answer = false;
				}

				foreach ($poll["answers"] as $answers) {
					$total = (int) ($total + $answers["Votes"]);
				}
				
				?>
					<p class="section">					
						<p class="poll-center <?php echo $class; ?>">
							<h3 class="<?php echo $class; ?>"><a href="<?php echo $URL; ?>"><?php echo $poll["question"]["Title"]; ?></a></h3>
						</p>
					
						<?php 
							$i = 0;
							$percentage = 0;
							
							foreach ($poll["answers"] as $answers) {
								if ((int) $answers["Votes"] > 0) {								
									$percentage = ($answers["Votes"] * 100) / $total;
									
									if ($percentage >= 10) {
										$percentage = substr($percentage, 0, 5);
									} else {
										$percentage = substr($percentage, 0, 4);
									}
								}			

								if ($percentage == 0) {
									$color = "transparent";
								} else {
									$color = "#3478E3";
								}

								$style = "width: ". intval($percentage) ."%; background-color: ". $color .";";
						?>
								
								<span class="poll-answer"><?php echo $answers["Answer"]; ?> (<?php echo $percentage; ?>%)</span> <br />
								
								<div class="poll-graphic">
									<div style="<?php echo $style; ?>"<?php
										if ((int) $answers["ID_Answer"] === $answer) {
											echo ' class="answer-chosen"';
										}
									?>>&nbsp;</div>
								</div>
								
						<?php
								$i++;
								
								$percentage = 0;
							}
							
							$show = ($total === 1) ? '1 ' . __("_vote") : $total .' '. __("votes");
						?>
						
						<br />
						<span class="poll-total"><strong><?php echo __("Total");?>:</strong> <?php echo $show; ?></span>
					</p>
					<?php

				if ($already) {
					?>
					<script>alert("<?php echo __("You have previously voted on this poll"); ?>");</script>
					<?php
				}
			}
		}
	}
?>
</div>
<?php
	if (isset($special)) {
?>
</div>
<?php
	}
?>
<br />

<?php 
	if (isset($special)) {
		?>
		<a href="https://twitter.com/codejobs" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large"><?php echo __("Follow"); ?> @codejobs</a>
    	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if (!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		
		<?php
			echo display('<p>'. getAd("728px") .'</p>', 4);
		?>

		<div class="fb-comments" data-href="<?php echo $URL; ?>" data-num-posts="2" data-width="750"></div>
	<?php
	}
?>

<script>
var empty_message   = "<?php echo __("You must choose an answer"); ?>",
	sending_message = "<?php echo __("Voting"); ?>",
	poll_selector   = "<?php echo isset($special) ? "#poll-container" : "section.polls"; ?>";
</script>