<div class="subheader">
  <?php Section::inject('page_title', "$project->title") ?>
  <div class="subheader-secondline"><?php echo $project->agency; ?></div>
</div><!-- subheader -->

<div class="container inner-container inner-container-show-project">
	<p>
		Redirecting to 
		<a class="outbound" href="<?php echo $project->external_url; ?>"><?php echo $project->external_url; ?></a> 
		...
	</p>
</div>
<script>
	window.location.href = "<?php echo $project->external_url; ?>";
</script>