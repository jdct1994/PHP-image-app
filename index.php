<?php 
//load the configuration file - this is where the database connects ($db)
require('config.php');
require_once('includes/functions.php');
//get the doctype, head, header and open wrapper div
require('includes/header.php');
?>


	<main class="content">
		<?php
		//get up to 20 published posts in reverse-chronological order
		$sql = "SELECT posts.*, users.username, users.profile_pic, users.user_id, categories.* 
				FROM posts, users, categories
				WHERE posts.is_published = 1 
				AND users.user_id = posts.user_id
				AND categories.category_id = posts.category_id
				ORDER BY posts.date DESC
				LIMIT 20";

		//run it with query()
		//$db is an object and the arrow is how you access properties of an object
		$result = $db->query($sql);

		//check for a bad result
		if( ! $result AND DEBUG_MODE ){
			echo $db->error;
		}


		//check to see if we found any posts (rows)
		if( $result->num_rows >= 1 ){
		//loop through each post
		while( $row = $result->fetch_assoc() ){
			// echo '<pre>';
			// print_r($row);
			// echo '</pre>';
		?>
		<article>
			<h3>
				<a href="profile.php?user_id=<?php echo $row['user_id']; ?>">
				<img src="<?php echo $row['profile_pic'];?>" width="50" height="50" class="profile-pic">
				<?php echo $row['username'];?>
				</a>
			</h3>
			<a href="single.php?post_id=<?php echo $row['post_id'];?>">
					<?php display_image( $row['image'] ); ?>
			</a>

			<h2>
				<a href="single.php?post_id=<?php echo $row['post_id'];?>">
				<?php echo $row['title'];?>
				</a>
			</h2>
			<p><?php echo $row['body'];?></p>
			<div class="date"><?php echo convert_date($row['date']);?></div>
			<div class="category"><?php echo $row['name'];?></div>
			<div class="comments"><?php echo count_comments( $row['post_id'] );?></div>
		</article>
		<?php 
			} //end while loop
			$result->free();
			} else {
			echo '<h2 class="no_posts"> Sorry, no posts found. </h2>';
			} //end if there are rows to show 
		?>
	</main>

	<?php include('includes/sidebar.php'); ?>
	<?php require('includes/footer.php'); ?>




