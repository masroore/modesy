<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "bank_transfer"): ?>
    <?php echo form_open('promote_controller/bank_transfer_payment_post'); ?>
    <input type="hidden" name="payment_id" value="<?php echo $transaction_number; ?>">
    <div id="payment-button-container" class=paypal-button-cnt">
        <div class="bank-account-container">
            <?php echo $payment_settings->bank_transfer_accounts; ?>
        </div>

        <p class="p-transaction-number"><span><?php echo trans("transaction_number"); ?>:&nbsp;<?php echo $transaction_number; ?></span></p>

        <p class="p-complete-payment"><?php echo trans("msg_promote_bank_transfer_text"); ?></p>
        <button type="submit" name="submit" value="update" class="btn btn-lg btn-custom float-right"><?php echo trans("place_order") ?></button>
    </div>
    <?php echo form_close(); ?>
<?php endif; ?>