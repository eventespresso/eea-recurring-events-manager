<?php
/**
 * @var EE_Datetime $datetime
 * @var string      $description
 * @var string      $name
 * @var string      $time_format
 */
?>
<div class="ee-rem-date__wrapper">
    <div class="ee-rem-date__stripe"></div>
    <div class="ee-rem-date__date">
        <div class="ee-rem-date__date--day"><?php echo $datetime->start_date('d'); ?></div>
        <div class="ee-rem-date__date--month"><?php echo $datetime->start_date('M'); ?></div>
    </div>
    <div class="ee-rem-date__details">
    <?php if (! empty($name)) { ?>
        <div class="ee-rem-date__details--name"><strong><?php echo $name; ?></strong></div>
    <?php } ?>
    <?php if (! empty($description)) { ?>
        <div class="ee-rem-date__details--description"><?php echo $description; ?></div>
    <?php } ?>
    </div>
    <div class="ee-rem-date__time">
        <div class="ee-rem-date__time--start"><?php echo $datetime->start_time($time_format); ?></div>
        <div class="ee-rem-date__time--sep">-</div>
        <div class="ee-rem-date__time--end"><?php echo $datetime->end_time($time_format); ?></div>
    </div>
</div>
