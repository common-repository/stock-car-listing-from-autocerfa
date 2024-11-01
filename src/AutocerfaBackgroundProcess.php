<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AutocerfaBackgroundProcess extends WP_Background_Process
{
    /**
     * @var string
     */
    protected $action = 'autocerfa_bg_process';

    /**
     * Task
     *
     * Override this method to perform any actions required on each
     * queue item. Return the modified item for further processing
     * in the next pass through. Or, return false to remove the
     * item from the queue.
     *
     * @param mixed $item Queue item to iterate over
     *
     * @return mixed
     */
    protected function task( $item ) {
        // Actions to perform
        (new AutocerfaStockProcess())->save($item);
        return false;
    }

    /**
     * Complete
     *
     * Override if applicable, but ensure that the below actions are
     * performed, or, call parent::complete().
     */
    protected function complete() {
        parent::complete();
        // error_log('called', 3, ABSPATH . 'test.log');
        // Show notice to user or perform some other arbitrary task...
        (new AutocerfaStockProcess())->deleteOldLead();
        (new AutocerfaStockProcess())->saveMinMaxPrice();
    }
}