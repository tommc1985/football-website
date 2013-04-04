<h2>Player Profile - <?php echo Player_helper::fullName($player); ?></h2>

<dl>
  <dt>Full Name:</dt>
  <dd><?php echo Player_helper::fullNameReverse($player); ?></dd>
  <dt>Date of Birth:</dt>
  <dd><?php echo Utility_helper::shortDate($player->dob); ?></dd>
  <dt>Nationality:</dt>
  <dd><?php echo $player->nationality; ?></dd>
  <dt>Gender:</dt>
  <dd><?php echo $player->gender; ?></dd>
  <dt>Position:</dt>
  <dd>?</dd>
  <dt>Debut:</dt>
  <dd><?php print_r($player->debut['overall']); ?></dd>
  <dt>First Goal:</dt>
  <dd><?php print_r($player->firstGoal['overall']); ?></dd>
</dl>

<div id="profile">
<?php echo $player->profile; ?>
</div>

<?php var_dump($player->accumulatedStatistics); ?>