    <?php foreach($years as $key => $seasons){ ?>
      <h3 class="season-title"><?php echo esc_attr($key);?></h3>
      <table class="season-table">
        <thead>
          <tr>
            <th><?php echo __('Rental Period','turisbook-booking-system');?></th>
            <th class="text-center"><?php echo __('Price Per Week','turisbook-booking-system');?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($seasons as $season){?>
            <tr>
              <td class="text-center"><?= date("d M",strtotime(esc_attr($season->start_date))) ?> - <?php echo date("d M, Y",strtotime(esc_attr($season->end_date))) ?></td>
              <td class="text-center">€ <?php echo esc_attr( $season->week_price );?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
